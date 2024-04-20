<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        // Send the email to the admin
        Mail::to('russell@rmcinnes.co.uk')->send(new \App\Mail\ContactMail($validatedData));

        // Send the auto-reply to the user
        $autoReplyDetails = [
            'name' => $validatedData['name'],
            'message' => 'Thank you for contacting us. We will get back to you soon.'
        ];
        Mail::to($validatedData['email'])->send(new \App\Mail\AutoReplyMail($autoReplyDetails));

        return back()->with('message_sent', 'Your message has been sent successfully!');
    }
}
