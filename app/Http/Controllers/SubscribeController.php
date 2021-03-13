<?php

namespace Rowles\Http\Controllers;

use Rowles\Models\User;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->subscribed() && $request->user()->role !== User::ADMINISTRATOR) {
            return redirect()->route('video.index');
        }

        return view('subscribe');
    }
}
