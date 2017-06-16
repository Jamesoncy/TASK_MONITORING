<?php

namespace App\Http\Controllers;

use Redirect;
use App\User;
use Illuminate\Support\Facades\Auth;
use Request;
class Login extends Controller
{
    public function index(){
    	return view('login2');
    }

    public function auth(Request $request){
    	$user = User::where('email', '=', Request::get('email'))->first();
        if($user) {
            if($user->password == md5(Request::get('password'))) { // If their password is still MD5
                 Auth::loginUsingId($user->id);
                 return redirect()->intended('project/ongoing-projects');
            } else return redirect()->intended('/');
        } else return redirect()->intended('/');

    }

    public function logout(){
    	Auth::logout();
        return redirect()->intended('/');
    }
}
