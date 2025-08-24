<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Session;

class loginController extends Controller
{

    public function login()
    {  
        return view('login');
    }

    public function sign_in(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], 
        [
            'email.required' => 'Please enter your email.',
            'password.required' => 'Please enter your password.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

             return redirect('products/index');
        }
        else
        {
             return redirect('login')->with('error', 'Invalid Credentials!');
        }
    }

    public function logout(Request $request){
        
        if (Auth::check()) {
        
            Auth::logout(); // Log the user out
            $request->session()->invalidate(); 
            $request->session()->regenerateToken();

            return redirect('login');
        }

    }
}
