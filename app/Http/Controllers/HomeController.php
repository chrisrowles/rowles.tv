<?php

namespace Rowles\Http\Controllers;

use Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (!Auth::user()->subscribed()) {
            return view('index');
        }

        return redirect()->route('video.index');
    }
}
