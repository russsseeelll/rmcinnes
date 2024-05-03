<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Mail\AutoReplyMail;

class ContactController extends Controller
{
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
            'email' => 'required|email|max:255',
            'message' => 'required|string|min:20',
        ]);

        Mail::to('russell@rmcinnes.co.uk')->send(new ContactMail($validatedData));

        $autoReplyDetails = [
            'name' => $validatedData['name'],
            'message' => 'Thank you for contacting us. We will get back to you soon.'
        ];
        Mail::to($validatedData['email'])->send(new AutoReplyMail($autoReplyDetails));

        return back()->with('message_sent', 'Your message has been sent successfully!');
    }
}
