<?php

namespace Rowles\Http\Controllers;

use Illuminate\Http\Request;
use Rowles\PaymentProviderInterface;
use Rowles\Models\SubscriptionPackage;

class AccountController extends Controller
{
    private PaymentProviderInterface $paymentProvider;

    public function __construct(PaymentProviderInterface $provider)
    {
        $this->paymentProvider = $provider;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $plan = SubscriptionPackage::wherePrice($user->subscription()->stripe_plan)->first();

        if (!$plan) {
            $request->session()->flash('error', 'We were unable to retrieve your billing plan at this time, please try again later or check the billing portal for more information.');

            return redirect()->back(404);
        }

        return view('account', compact('user', 'plan'));
    }
}
