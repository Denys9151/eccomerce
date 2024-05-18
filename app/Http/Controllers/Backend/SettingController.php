<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $generalSettings = GeneralSetting::first();
        return view('admin.setting.index', compact('generalSettings'));
    }

    public function generalSettingUpdate(Request $request)
    {
        $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'layout' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'string', 'max:255'],
            'currency_name' => ['required', 'string', 'max:255'],
            'currency_icon' => ['required', 'string', 'max:255'],
            'time_zone' => ['required', 'string', 'max:255'],
        ]);

        GeneralSetting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => $request->site_name,
                'layout' => $request->layout,
                'contact_email' => $request->contact_email,
                'currency_name' => $request->currency_name,
                'currency_icon' => $request->currency_icon,
                'time_zone' => $request->time_zone,
            ],
        );

        toastr('Updated Successfully', 'success', 'Success');

        return redirect()->back();
    }
}
