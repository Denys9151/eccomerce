<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\EmailConfiguration;
use App\Models\GeneralSetting;
use App\Models\LogoSetting;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use ImageUploadTrait;

    public function index()
    {
        $generalSettings = GeneralSetting::first();
        $emailSettings = EmailConfiguration::first();
        $logoSetting = LogoSetting::first();
        return view('admin.setting.index', compact('generalSettings', 'emailSettings', 'logoSetting'));
    }

    public function generalSettingUpdate(Request $request)
    {
        $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'layout' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:255'],
            'contact_address' => ['required', 'string', 'max:255'],
            'map' => ['required'],
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
                'contact_phone' => $request->contact_phone,
                'contact_address' => $request->contact_address,
                'map' => $request->map,
                'currency_name' => $request->currency_name,
                'currency_icon' => $request->currency_icon,
                'time_zone' => $request->time_zone,
            ],
        );

        toastr('Updated Successfully', 'success', 'Success');

        return redirect()->back();
    }

    public function emailConfigSettingUpdate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'host' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'port' => ['required', 'string', 'max:255'],
            'encryption' => ['required', 'string', 'max:255'],
        ]);

        EmailConfiguration::updateOrCreate(
            ['id' => 2],
            [
                'email' => $request->email,
                'host' => $request->host,
                'username' => $request->username,
                'password' => $request->password,
                'port' => $request->port,
                'encryption' => $request->encryption,
            ]
        );

        toastr()->success('Updated Successfully', 'Success');

        return redirect()->back();
    }

    public function logoSettingUpdate(Request $request)
    {
        $request->validate([
            'logo' => ['image', 'max:2048'],
            'favicon' => ['image', 'max:2048'],
        ]);

        $logoPath = $this->updateImage($request, 'logo', 'uploads', $request->old_log);
        $favicon = $this->updateImage($request, 'favicon', 'uploads', $request->old_favicon);

        LogoSetting::updateOrCreate(
            ['id' => 1],
            [
                'logo' => !empty($logoPath) ? $logoPath : $request->old_logo,
                'favicon' => !empty($favicon) ? $favicon : $request->old_favicon,
            ]
        );
        toastr('Updated Successfully', 'success', 'Success');
        return redirect()->back();
    }
}
