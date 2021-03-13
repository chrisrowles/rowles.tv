<?php

namespace Rowles\Http\Controllers\Admin;

use DB;
use Rowles\Models\User;
use Stripe\StripeClient;
use Rowles\PaymentProviderInterface;
use Illuminate\Http\RedirectResponse;
use Rowles\Models\SubscriptionPackage;
use Rowles\Http\Controllers\Controller;
use Stripe\Exception\ApiErrorException;
use Rowles\Http\Requests\UpdateSubscriptionPackageRequest;

class SubscriptionController extends Controller
{
    /** @var StripeClient  */
    protected StripeClient $paymentProvider;

    /**
     * SubscriptionController constructor.
     *
     * @param PaymentProviderInterface $paymentProvider
     */
    public function __construct(PaymentProviderInterface $paymentProvider)
    {
        $this->paymentProvider = $paymentProvider->get();
    }

    public function index()
    {
        $plan = SubscriptionPackage::first();
        $subscribers = [];

        $users = User::all();
        foreach($users as $user) {
            $userSubscriptions = $user->subscriptions()->get();
            if ($userSubscriptions->count() > 0) {
                foreach($userSubscriptions as $subscription) {
                    if ($subscription->active() && $subscription->stripe_plan === $plan->price) {
                        $subscribers[] = $user;
                    }
                }
            }
        }

        return view('admin.subscription-management', compact('plan', 'subscribers'));
    }

    public function update(SubscriptionPackage $plan, UpdateSubscriptionPackageRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();

        $plan->update([
            'nickname' => $validated['nickname'],
            'description' => $validated['description']
        ]);

        try {
            $price = $this->paymentProvider->prices->retrieve($validated['price']);
            $this->paymentProvider->products->update($price->product, [
                'name' => $validated['nickname'],
                'description' => $validated['description']
            ]);

            $request->session()->flash('success', 'Subscription successfully updated.');
        } catch (ApiErrorException $e) {
            DB::rollBack();
            $request->session()->flash('error', $e->getMessage());
        }

        DB::commit();

        return redirect()->back();
    }
}
