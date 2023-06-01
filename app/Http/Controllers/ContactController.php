<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        Mail::to('admin@rmcinnes.co.uk')->send(new \App\Mail\ContactMail($validatedData));

        return back()->with('message_sent', 'Your message has been sent successfully!');
    }
}
