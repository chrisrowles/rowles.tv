<?php

namespace Rowles\Http\Controllers;


use Auth;
use Cache;
use Illuminate\Http\Request;
use Rowles\PaymentProviderInterface;
use Stripe\Exception\ApiErrorException;
use Stripe\Service\PlanService;

class AccountController extends Controller
{
    private PaymentProviderInterface $paymentProvider;

    public function __construct(PaymentProviderInterface $provider)
    {
        $this->paymentProvider = $provider;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        try {
            if (Cache::offsetExists($user->email.':subscription_plan')) {
                // TODO add event to invalidate this cache on login and if user updates their subscription.
                //    Subscription management is handled through the Stripe billing portal, so
                //    it should just be a case of adding a dispatch event to remove the cache entry
                //    whenever users are redirected back from the portal.
                $plan = Cache::get($user->email.':subscription_plan');
            } else {
                $plan = $this->paymentProvider->client->prices->retrieve($user->subscription()->stripe_plan);
                Cache::put($user->email.':subscription_plan', $plan);
            }
        } catch (ApiErrorException $e) {
            $request->session()->flash('error', 'We were unable to retrieve your billing plan at this time, please try again later or check the billing portal for more information.');
        }

        return view('account', compact('user', 'plan'));
    }
}
