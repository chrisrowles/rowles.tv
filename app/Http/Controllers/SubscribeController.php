<?php

namespace Rowles\Http\Controllers;

use Auth;
use Rowles\Models\User;

class SubscribeController extends Controller
{
    public function index()
    {
        if (Auth::user()->subscribed() && Auth::user()->role !== User::ADMINISTRATOR) {
            return redirect()->route('video.index');
        }

        return view('subscribe');
    }
}
