<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SessionsController extends Controller
{
    public function create()
    {
        return view('sessions.create');
    }

    public function store()
    {
        //validate request
        $attributes = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        //authenticate and login user based on credentials provided
        if (auth()->attempt($attributes)) {
            //redirect with a success flash message
            return redirect('/')->with('success', 'Welcome Back!');
        }

        throw ValidationException::withMessages([
            'email' => 'Your provided credentials could not be verified'
        ]);
    }
    public function destroy()
    {
        auth()->logout();

        return redirect('/')->with('success', 'Goodbye!');
    }
}
