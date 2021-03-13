<?php

namespace Rowles\Http\Controllers;

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
        try {
            $request->user()->newSubscription('default', 'price_1ISjyxFnOG8jwOnxXs5u7cHn')
                ->create($request->get('payment_method'), [
                    'email' => $request->user()->email
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
     * @param Request $request
     * @return RedirectResponse
     */
    public function confirm(Request $request): RedirectResponse
    {
        $request->user()->subscription()->syncStripeStatus();

        return redirect()->route('video.index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function portal(Request $request): RedirectResponse
    {
        return $request->user()->redirectToBillingPortal(route('video.index'));
    }
}
