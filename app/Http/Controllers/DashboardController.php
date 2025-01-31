<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin_login(Request $request) {

        if($request->isMethod('post')) {

            $validated = $request->validate([
                'email'     => 'required',
                'password'  => 'required',

            ], [

                'email.required'    => 'Please Write Your Email',
                'password.required' => 'Please Write Your Password'
            ]);


            $email      = $request->email;
            $password   = $request->password;

            $user = User::where('email', '=', $email)->first();//هل الايميل يطابق ايميل الادمن

            if(isset($user) && $user->hasRole('admin')) {

                if (Auth::attempt(['email' => $email, 'password' => $password])) {

                    $request->session()->regenerate();
         
                    return redirect()->intended('dashboard');

                } else {

                    return redirect()->back()->with('msg', 'Password Not Correct');

                }

            } else {

                return redirect()->back()->with('msg', 'Can\'t Access To This Page');
            }
            

        } else {

            return redirect()->route('login');
        }

    } 
}
