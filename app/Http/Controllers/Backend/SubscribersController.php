<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\NewsLetterSubscriberDataTable;
use App\Http\Controllers\Controller;
use App\Mail\Newsletter;
use App\Models\NewsLetterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubscribersController extends Controller
{
    public function index(NewsLetterSubscriberDataTable $dataTable)
    {
        return $dataTable->render('admin.subscribers.index');
    }

    public function sendMail(Request $request)
    {
        $request->validate([
            'subject' => ['required', 'string'],
            'message' => ['required', 'string'],
        ]);

        $emails = NewsLetterSubscriber::where('is_verified', 1)->pluck('email')->toArray();

        Mail::to($emails)->send(new Newsletter($request->subject, $request->message));

        toastr('Mail has been sent!', 'success', 'Success');

        return redirect()->back();
    }

    public function destroy(string $id)
    {
        $subscriber = NewsLetterSubscriber::findOrFail($id);
        $subscriber->delete();

        return response(['status' => 'success', 'message' => 'Subscriber deleted successfully.']);
    }
}
