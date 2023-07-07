<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function loginForm(){
        // dd(22);
        return view('login.form');
    }

    public function loginSubmit(Request $request){
        // dd($request->all());
        $credentials = $request->validate([
            'email'=>"required|email",
            'password'=> 'required'
        ]);
        
        if ($auth = Auth::attempt($credentials)) {
            // dd($auth);
            return redirect()->route('products.index');
        }
        else {
            return redirect()->route('login.submit')->withErrors([
                'email'=> 'Email or password not correct'
            ]);
        }
    }

    public function logoutSubmit(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login.submit');
    }
}

 

