<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        return view('login');
    }

    public function doLogin(Request $request)
    {
        $actual = env('AUTH_PW');

        if (is_null($actual)) {
            die('App misconfiguration.');
        }

        if ($request->input('access_code') === $actual) {
            session()->put('is_logged_in', true);
        }

        return redirect('/');
    }
}
