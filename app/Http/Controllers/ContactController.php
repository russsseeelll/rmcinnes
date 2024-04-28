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
        // Validate form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
            'email' => 'required|email|max:255',
            'message' => 'required|string|min:20',
        ]);

        // Attempt to send the email to the admin
        Mail::to('russell@rmcinnes.co.uk')->send(new ContactMail($validatedData));

        // Send the auto-reply to the user
        $autoReplyDetails = [
            'name' => $validatedData['name'],
            'message' => 'Thank you for contacting us. We will get back to you soon.'
        ];
        Mail::to($validatedData['email'])->send(new AutoReplyMail($autoReplyDetails));

        // Redirect back to the form section with a success message
        return back()->with('message_sent', 'Your message has been sent successfully!');
    }
}
