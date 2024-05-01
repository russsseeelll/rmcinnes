<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
       // This method returns the view for user login form
       public function login(){
        return view('login');
    }

    public function authenticate(Request $request)
    {
        // This line validates the incoming request
        $formFields = $request->validate([
            'name' => ['required', 'string'],
            'password' => 'required'
        ]);

        // Retrieve the user by the provided username
        $user = User::where('name', $formFields['name'])->first();

        // If the user was found and the password matches
        if ($user && Hash::check($formFields['password'], $user->password)) {
            Auth::login($user);  // Manually log in the user
            $request->session()->regenerate();  // Regenerate the session

            // Then redirect the user to the home page with a success message
            return redirect('/')->with('message', 'You have successfully logged in');
        }

        // If authentication fails, redirect back with an error message
        return back()->withErrors(['name'=> 'Invalid Credentials'])->withInput(['name']);

    }

    public function logout(Request $request){
        auth()->logout();  // Logging out the user
        $request->session()->invalidate();  // Invalidating the session data
        $request->session()->regenerateToken();  // Regenerating CSRF token

        // Redirecting the user to the home page with a success message
        return redirect('/')->with('message', 'You have successfully logged out');
    }


}
