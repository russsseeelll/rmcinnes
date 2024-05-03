<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function login(){
        return view('login');
    }

    public function authenticate(Request $request)
    {

        $formFields = $request->validate([
            'name' => ['required', 'string'],
            'password' => 'required'
        ]);

        $user = User::where('name', $formFields['name'])->first();

        if ($user && Hash::check($formFields['password'], $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect('/')->with('message', 'You have successfully logged in');
        }

        return back()->withErrors(['name'=> 'Invalid Credentials'])->withInput(['name']);

    }

    public function logout(Request $request){
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have successfully logged out');
    }

}
