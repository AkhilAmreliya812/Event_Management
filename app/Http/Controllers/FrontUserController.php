<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontUserController extends Controller
{
    public function home() {
        return view('user.home');
    }

    public function about() {
        return view('user.about');
    }

    public function contact() {
        return view('user.contact');
    }
}
