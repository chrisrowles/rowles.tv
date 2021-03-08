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
    public function index(Request $request) : RedirectResponse
    {
        $user = Auth::user();

        try {
            $user->newSubscription('default', 'price_1ISjyxFnOG8jwOnxXs5u7cHn')
                ->create($request->get('payment_method'), [
                    'email' => $user->email
                ]);
        } catch (IncompletePayment $e) {
            if ($e->payment->requiresAction()) {
                return redirect()->back()->with(['payment_intent' => $e->payment->clientSecret()]);
            }

            return redirect()->back()->with(['error_message' => $e->getMessage()]);
        }

        return redirect()->back();
    }
}
