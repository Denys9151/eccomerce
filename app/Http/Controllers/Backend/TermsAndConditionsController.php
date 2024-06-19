<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TermsAndCondition;
use Illuminate\Http\Request;

class TermsAndConditionsController extends Controller
{
    public function index()
    {
        $content = TermsAndCondition::first();
        return view('admin.terms.index', compact('content'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'content' => ['required', 'string'],
        ]);

        TermsAndCondition::updateOrCreate(
            ['id' => 1],
            [
                'content' => $request->content,
            ]
        );

        toastr('Updated Successfully', 'success', 'Success');
        return redirect()->back();
    }
}
