<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\TermsAndConditionsController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckOutController;
use App\Http\Controllers\Frontend\FlashSaleController;
use App\Http\Controllers\Frontend\FrontendProductController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsLetterController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\ProductTrackController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\UserAddressController;
use App\Http\Controllers\Frontend\UserDashboardController;
use App\Http\Controllers\Frontend\UserOrderController;
use App\Http\Controllers\Frontend\UserProfileController;
use App\Http\Controllers\Frontend\UserVendorRequestController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');

Route::get('flash-sale', [FlashSaleController::class, 'index'])->name('flash-sale');

/** Product route */
Route::get('/products', [FrontendProductController::class, 'productsIndex'])->name('products.index');
Route::get('/product-detail/{slug}', [FrontendProductController::class, 'showProduct'])->name('product-detail');
Route::get('/change-product-list-view', [FrontendProductController::class, 'changeListView'])->name('change-product-list-view');

/** Add to cart routes */
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
Route::get('/cart-details', [CartController::class, 'cartDetails'])->name('cart-details');
Route::post('/cart/update-quantity', [CartController::class, 'updateProductQty'])->name('cart.update-quantity');
Route::get('/clear-cart', [CartController::class, 'clearCart'])->name('clear-cart');
Route::get('/cart/remove-product/{rowId}', [CartController::class, 'removeProduct'])->name('cart.remove-product');
Route::get('/cart-count', [CartController::class, 'getCartCount'])->name('cart-count');
Route::get('/cart-products', [CartController::class, 'getCartProducts'])->name('cart-products');
Route::post('/cart/remove-sidebar-product', [CartController::class, 'removeSidebarProduct'])->name('cart.remove-sidebar-product');
Route::get('/cart/sidebar-product-total', [CartController::class, 'cartTotal'])->name('cart.sidebar-product-total');

Route::get('/apply-coupon', [CartController::class, 'applyCoupon'])->name('apply-coupon');
Route::get('/coupon-calculation', [CartController::class, 'couponCalculation'])->name('coupon-calculation');

/** News Letter routes */
Route::post('/news-letter-request', [NewsLetterController::class, 'newsLetterRequest'])->name('news-letter-request');
Route::get('/news-letter-verify/{token}', [NewsLetterController::class, 'newsLetterEmailVerify'])->name('news-letter-verify');

/** Vendor page routes */
Route::get('/vendor', [HomeController::class, 'vendorPage'])->name('vendor.index');
Route::get('/vendor-product/{id}', [HomeController::class, 'vendorProductsPage'])->name('vendor.products');

/** About page route */
Route::get('/about', [PageController::class, 'about'])->name('about');

/** Terms and conditions page route */
Route::get('/terms-and-conditions', [PageController::class, 'termsAndCondition'])->name('terms-and-conditions');

/** Contact routes */
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'handleContactForm'])->name('handle-contact-form');

/** Product track routes */
Route::get('/product-tracking', [ProductTrackController::class, 'index'])->name('product-tracking.index');

/** Blog routes */
Route::get('/blog-details/{slug}', [\App\Http\Controllers\Frontend\BlogController::class, 'blogDetails'])->name('blog-details');
Route::get('/blog', [\App\Http\Controllers\Frontend\BlogController::class, 'blog'])->name('blog');


Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [UserProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile', [UserProfileController::class, 'updatePassword'])->name('profile.update.password');

    /** User Address route */
    Route::resource('/address', UserAddressController::class);

    /** Order routes */
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/show/{id}', [UserOrderController::class, 'show'])->name('orders.show');

    /** Wishlist routes */
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::get('/wishlist/add-product', [WishlistController::class, 'addToWishlist'])->name('wishlist.store');
    Route::get('/wishlist/remove-product/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    Route::get('/reviews', [ReviewController::class, 'index'])->name('review.index');

    /** Vendor request routes */
    Route::get('/vendor-request', [UserVendorRequestController::class, 'index'])->name('vendor-request.index');
    Route::post('/vendor-request', [UserVendorRequestController::class, 'create'])->name('vendor-request.create');

    /** Product review routes */
    Route::post('/review', [ReviewController::class, 'create'])->name('review.create');

    /** Blog comment routes */
    Route::post('/blog-comment', [\App\Http\Controllers\Frontend\BlogController::class, 'comment'])->name('blog-comment');

    /** Checkout routes */
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/address-create', [CheckoutController::class, 'createAddress'])->name('checkout.address.create');
    Route::post('/checkout/form-submit', [CheckoutController::class, 'checkOutForm'])->name('checkout.form-submit');

    /** Payment Routes */
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
    Route::get('/payment-success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');

    /** Paypal Routes */
    Route::get('/paypal/payment', [PaymentController::class, 'payWithPaypal'])->name('paypal.payment');
    Route::get('/paypal/success', [PaymentController::class, 'paypalSuccess'])->name('paypal.success');
    Route::get('/paypal/cancel', [PaymentController::class, 'paypalCancel'])->name('paypal.cancel');

    /** Stripe routes */
    Route::post('/stripe/payment', [PaymentController::class, 'payWithStripe'])->name('stripe.payment');
    Route::get('/stripe/success', [PaymentController::class, 'stripeSuccess'])->name('stripe.success');
    Route::get('/stripe/cancel', [PaymentController::class, 'stripeCancel'])->name('stripe.cancel');

    /** Razorpay routes */
    Route::post('/razorpay/payment', [PaymentController::class, 'payWithRazorPay'])->name('razorpay.payment');

    /** COD routes */
    Route::get('/cod/payment', [PaymentController::class, 'payWithCod'])->name('cod.payment');
});

