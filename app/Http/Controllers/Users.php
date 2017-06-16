<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Users extends Controller
{
    public function index()
    {
        echo "hello";
    }

    public function show($name){
    	return view('hello',array('name' => $name));
    }
}
