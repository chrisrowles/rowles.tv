<?php

namespace Rowles\Http\Controllers\Membership;

use Rowles\Models\Video;
use Illuminate\Http\Request;
use Rowles\Models\SubscriptionPackage;
use Rowles\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $packages = SubscriptionPackage::all();

        if (!$packages) {
            abort(404);
        }

        return view('membership.packages', compact('packages'));
    }
}
