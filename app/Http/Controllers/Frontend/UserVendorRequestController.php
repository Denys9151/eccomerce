<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorCondition;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserVendorRequestController extends Controller
{
    use ImageUploadTrait;

    public function index()
    {
        $content = VendorCondition::first();
        return view('frontend.dashboard.vendor-request.index', compact('content'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'shop_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'shop_name' => ['required', 'string', 'max:255'],
            'shop_email' => ['required', 'string', 'email', 'max:255'],
            'shop_phone' => ['required', 'string', 'max:255'],
            'shop_address' => ['required', 'string'],
            'about' => ['required', 'string'],
        ]);

        if (Auth::user()->role === 'vendor'){
            return redirect()->back();
        }

        $imagePath = $this->uploadImage($request, 'shop_image', 'uploads');

        $vendor = new Vendor();

        $vendor->banner = $imagePath;
        $vendor->phone = $request->shop_phone;
        $vendor->email = $request->shop_email;
        $vendor->address = $request->shop_address;
        $vendor->description = $request->about;
        $vendor->shop_name = $request->shop_name;
        $vendor->user_id = Auth::id();
        $vendor->status = 0;
        $vendor->save();

        toastr('Submitted successfully pleas wait for approve!', 'success', 'Success');

        return redirect()->back();
    }
}
