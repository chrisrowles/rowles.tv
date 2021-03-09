<?php

namespace Rowles\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Laravel\Cashier\Exceptions\IncompletePayment;

class BillingController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function post(Request $request) : RedirectResponse
    {
        $user = Auth::user();

        try {
            $user->newSubscription('default', 'price_1ISjyxFnOG8jwOnxXs5u7cHn')
                ->create($request->get('payment_method'), [
                    'email' => $user->email
                ]);
        } catch (IncompletePayment $e) {
            return redirect()->route(
                'cashier.payment',
                [$e->payment->id, 'redirect' => route('billing.confirm')]
            );
        }

        return redirect()->back();
    }

    /**
     * @return RedirectResponse
     */
    public function confirm(): RedirectResponse
    {
        Auth::user()->subscription()->syncStripeStatus();

        return redirect()->route('video.index');
    }

    /**
     * @return RedirectResponse
     */
    public function portal(): RedirectResponse
    {
        return Auth::user()->redirectToBillingPortal(route('video.index'));
    }
}
