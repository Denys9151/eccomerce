<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\FooterSocialDataTable;
use App\Http\Controllers\Controller;
use App\Models\FooterSocial;
use Illuminate\Http\Request;

class FooterSocialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FooterSocialDataTable $dataTable)
    {
        return $dataTable->render('admin.footer.footer-socials.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.footer.footer-socials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'icon' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string'],
            'status' => ['required'],
        ]);

        $footerSocial = new FooterSocial();
        $footerSocial->icon = $request->icon;
        $footerSocial->name = $request->name;
        $footerSocial->url = $request->url;
        $footerSocial->status = $request->status;
        $footerSocial->save();

        toastr('Created Successfully!', 'success', 'Success');

        return redirect()->route('admin.footer-socials.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $footer = FooterSocial::findOrFail($id);
        return view('admin.footer.footer-socials.edit', compact('footer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'icon' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string'],
            'status' => ['required'],
        ]);

        $footerSocial = FooterSocial::findOrFail($id);
        $footerSocial->icon = $request->icon;
        $footerSocial->name = $request->name;
        $footerSocial->url = $request->url;
        $footerSocial->status = $request->status;
        $footerSocial->save();

        toastr('Updated Successfully!', 'success', 'Success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $footer = FooterSocial::findOrFail($id);
        $footer->delete();

        return response(['status' => 'success', 'message' => 'Deleted Successfully']);
    }

    public function changeStatus(Request $request)
    {
        $footer = FooterSocial::findOrFail($request->id);
        $footer->status = $request->status == 'true' ? 1 : 0;
        $footer->save();

        return response(['message' => 'Status has been updated!']);
    }
}
