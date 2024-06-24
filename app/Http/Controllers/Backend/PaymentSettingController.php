<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CodSetting;
use App\Models\GeneralSetting;
use App\Models\PaypalSetting;
use App\Models\RazorpaySetting;
use App\Models\StripeSetting;
use Illuminate\Http\Request;

class PaymentSettingController extends Controller
{
    function index()
    {
        $paypalSetting = PaypalSetting::first();
        $stripeSetting = StripeSetting::first();
        $razorpaySetting = RazorpaySetting::first();
        $codSetting = CodSetting::first();
        return view('admin.payment-settings.index', compact('paypalSetting', 'stripeSetting', 'razorpaySetting', 'codSetting'));
    }
}
