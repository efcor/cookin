<?php

namespace App\Http\Controllers;

use App\Models\Username;
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
            $username = $this->getUsername();
            session()->put('username', $username);
            session()->put('is_logged_in', true);
        }

        return redirect('/');
    }

    public function getUsername()
    {
        $animal_names = [
            'aardvark', 'albatross', 'antelope', 'armadillo', 'baboon',
            'badger', 'barracuda', 'beaver', 'bison', 'boa',
            'butterfly', 'camel', 'caribou', 'cardinal', 'catfish',
            'chameleon', 'cheetah', 'chipmunk', 'coyote', 'crab',
            'crane', 'crow', 'deer', 'dolphin', 'dragonfly',
            'eagle', 'elephant', 'ferret', 'flamingo', 'fox',
            'gecko', 'gorilla', 'hedgehog', 'heron', 'hyena',
            'jaguar', 'jellyfish', 'koala', 'leopard', 'lion',
            'lizard', 'moose', 'mouse', 'octopus', 'opossum',
            'panda', 'pelican', 'penguin', 'raccoon', 'rabbit',
            'raven', 'scorpion', 'seahorse', 'shark', 'sloth',
            'snake', 'sparrow', 'squirrel', 'tiger', 'vulture',
            'wallaby', 'wombat', 'zebra'
        ];

        $key = array_rand($animal_names);

        $username = $animal_names[$key];

        $attempts = 1;
        while ($record = Username::where('username', $username)->first()) {
            if ($attempts > 100) {
                die('Error: no usernames available.');
            }

            $attempts++;
        }

        Username::create(['username' => $username]);

        return $username;
    }
}
