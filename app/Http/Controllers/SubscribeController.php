<?php

namespace Rowles\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->subscribed()) {
            return redirect()->route('video.index');
        }

        return view('subscribe');
    }
}
