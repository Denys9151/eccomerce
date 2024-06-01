<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PaypalSetting;
use App\Models\Product;
use App\Models\StripeSetting;
use App\Models\Transaction;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

// Import the class namespaces first, before using it directly
use Razorpay\Api\Api;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Stripe\Charge;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function index()
    {
        if (!Session::has('address')) {
            return redirect()->route('user.checkout');
        }
        return view('frontend.pages.payment');
    }

    public function paymentSuccess()
    {
        return view('frontend.pages.payment-success');
    }

    public function storeOrder($paymentMethod, $paymentStatus, $transactionId, $paidAmount, $paidCurrencyName)
    {
        $settings = GeneralSetting::first();

        $order = new Order();

        $order->invoice_id = rand(1, 999999);
        $order->user_id = Auth::user()->id;
        $order->sub_total = getMainCartTotal();
        $order->amount = getFinalPayableAmount();
        $order->currency_name = $settings->currency_name;
        $order->currency_icon = $settings->currency_icon;
        $order->product_qty = Cart::content()->count();
        $order->payment_method = $paymentMethod;
        $order->payment_status = $paymentStatus;
        $order->order_address = json_encode(Session::get('address'));
        $order->shipping_method = json_encode(Session::get('shipping_method'));
        $order->coupon = json_encode(Session::get('coupon'));
        $order->order_status = 0;
        $order->save();

        // store order products

        foreach (Cart::content() as $item) {
            $product = Product::find($item->id);
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $product->id;
            $orderProduct->vendor_id = $product->vendor_id;
            $orderProduct->product_name = $product->name;
            $orderProduct->variants = json_encode($item->options->variants);
            $orderProduct->variant_total = $item->options->variants_total;
            $orderProduct->unit_price = $item->price;
            $orderProduct->qty = $item->qty;
            $orderProduct->save();
        }

        // store transactions details
        $transactions = new Transaction();
        $transactions->order_id = $order->id;
        $transactions->transaction_id = $transactionId;
        $transactions->payment_method = $paymentMethod;
        $transactions->amount = getFinalPayableAmount();
        $transactions->amount_real_currency = $paidAmount;
        $transactions->amount_real_currency_name = $paidCurrencyName;
        $transactions->save();
    }

    public function clearSession()
    {
        Cart::destroy();
        Session::forget('address');
        Session::forget('shipping_method');
        Session::forget('coupon');
    }

    public function paypalConfig()
    {
        $paypalSetting = PaypalSetting::first();
        $config = [
            'mode' => $paypalSetting->mode === 1 ? 'live' : 'sandbox',
            // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
            'sandbox' => [
                'client_id' => $paypalSetting->client_id,
                'client_secret' => $paypalSetting->secret_key,
                'app_id' => '',
            ],
            'live' => [
                'client_id' => $paypalSetting->client_id,
                'client_secret' => $paypalSetting->secret_key,
                'app_id' => '',
            ],

            'payment_action' => 'Sale',
            // Can only be 'Sale', 'Authorization' or 'Order'
            'currency' => $paypalSetting->currency_name,
            'notify_url' => '',
            // Change this accordingly for your application.
            'locale' => 'en_US',
            // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
            'validate_ssl' => true,
            // Validate SSL when creating api client.
        ];

        return $config;
    }

    /** Paypal redirect */
    public function payWithPaypal()
    {
        $config = $this->paypalConfig();
        $paypalSetting = PaypalSetting::first();

        $provider = new PayPalClient($config);
        $provider->getAccessToken();

        // calculate payable amount depending on currency rate
        $total = getFinalPayableAmount();
        $payableAmount = round($total * $paypalSetting->currency_rate, 2);

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('user.paypal.success'),
                "cancel_url" => route('user.paypal.cancel'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => $config['currency'],
                        "value" => $payableAmount
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] == 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        } else {
            return redirect()->route('user.paypal.cancel');
        }
    }

    public function paypalSuccess(Request $request)
    {
        $config = $this->paypalConfig();
        $provider = new PayPalClient($config);
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->token);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $paypalSetting = PaypalSetting::first();
            $total = getFinalPayableAmount();
            $paidAmount = round($total * $paypalSetting->currency_rate, 2);

            $this->storeOrder('paypal', 1, $response['id'],$paidAmount, $paypalSetting->currency_name);

            // clear session
            $this->clearSession();

            return redirect()->route('user.payment.success');
        }

        return redirect()->route('user.paypal.cancel');
    }

    public function paypalCancel()
    {
        toastr('Something went wrong try again later!', 'error', 'Error');
        return redirect()->route('user.payment');
    }

    public function payWithStripe(Request $request)
    {
        $stripeSetting = StripeSetting::first();
        $total = getFinalPayableAmount();
        $payableAmount = round($total * $stripeSetting->currency_rate, 2);

        Stripe::setApiKey($stripeSetting->secret_key);
        $response = Charge::create([
            'amount' => $payableAmount * 100,
            'currency' => $stripeSetting->currency_name,
            'source' => $request->stripe_token,
            'description' => 'Product purchase!',
        ]);

        if ($response->status == 'succeeded') {
            $this->storeOrder('stripe', 1, $response->id, $payableAmount, $stripeSetting->currency_name);

            // clear session
            $this->clearSession();

            return redirect()->route('user.payment.success');
        } else{
            toastr('Something went wrong try again later!', 'error', 'Error');
            return redirect()->route('user.payment');
        }
    }

    /** Razorpay payment */
    public function payWithRazorPay(Request $request)
    {
        $razorPaySetting = RazorpaySetting::first();
        $api = new Api($razorPaySetting->razorpay_key, $razorPaySetting->razorpay_secret_key);

        // amount calculation
        $total = getFinalPayableAmount();
        $payableAmount = round($total * $razorPaySetting->currency_rate, 2);
        $payableAmountInPaisa = $payableAmount * 100;

        if($request->has('razorpay_payment_id') && $request->filled('razorpay_payment_id')){
            try{
                $response = $api->payment->fetch($request->razorpay_payment_id)
                    ->capture(['amount' => $payableAmountInPaisa]);
            }catch(\Exception $e){
                toastr($e->getMessage(), 'error', 'Error');
                return redirect()->back();
            }


            if($response['status'] == 'captured'){
                $this->storeOrder('razorpay', 1, $response['id'], $payableAmount, $razorPaySetting->currency_name);
                // clear session
                $this->clearSession();

                return redirect()->route('user.payment.success');
            }

        }
    }
}
