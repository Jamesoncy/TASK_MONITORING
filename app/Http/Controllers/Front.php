<?php

namespace App\Http\Controllers;

use Request;
use Redirect;
use App\User;
use Illuminate\Support\Facades\Auth;
class Front extends Controller
{
     public function index() {
        return 'index page';
    }

    public function products() {
        return 'products page';
    }

    public function product_details($id) {
        return 'product details page';
    }

    public function product_categories() {
        return 'product categories page';
    }

    public function product_brands() {
        return 'product brands page';
    }

    public function blog() {
        return 'blog page';
    }

    public function blog_post($id) {
        return "Welcome {$id}s!";
    }

    public function contact_us() {
        return 'contact us page';
    }

  
    public function cart() {
        return 'cart page';
    }

    public function checkout() {
        $user = Auth::user();
        print_r($user);
    }

    public function search($query) {
        return "$query search page";
    }

    public function login(){
        return view('login');
    }

    public function authenticate(){
        if (Auth::attempt(['email' => Request::get('email'), 'password' => Request::get('password') ]  )) {
            return redirect()->intended('checkout');
        } else {
            return view('login');
        }
     }



    public function logout(){
        Auth::logout();
        return redirect()->intended('auth/login');
    }

    public function register(){
        if (Request::isMethod('post')) {
        User::create([
                    'name' => Request::get('name'),
                    'email' => Request::get('email'),
                    'password' => bcrypt(Request::get('password')),
        ]);
    } 
    
    return Redirect::away('login');
    }

    

    
}
