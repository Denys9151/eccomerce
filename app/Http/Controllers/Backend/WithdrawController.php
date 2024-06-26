<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\WithdrawRequestDataTable;
use App\Http\Controllers\Controller;
use App\Models\WithdrawRequest;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function index(WithdrawRequestDataTable $dataTable)
    {
        return $dataTable->render('admin.withdraw.index');
    }

    public function show(string $id)
    {
        $request = WithdrawRequest::findOrFail($id);
        return view('admin.withdraw.show', compact('request'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => ['required', 'in:pending,paid,declined']
        ]);

        $withdraw = WithdrawRequest::findOrFail($id);
        $withdraw->status = $request->status;
        $withdraw->save();

        toastr('Updated Successfully', 'success', 'Successfully');
        return redirect()->route('admin.withdraw.index');
    }
}
