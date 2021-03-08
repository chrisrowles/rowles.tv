<?php

namespace Rowles\Http\Controllers;

use Auth;

class SubscribeController extends Controller
{
    public function index()
    {
        if (Auth::user()->subscribed()) {
            return redirect()->route('video.index');
        }

        return view('subscribe');
    }
}
