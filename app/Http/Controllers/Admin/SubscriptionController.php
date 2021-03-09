<?php

namespace Rowles\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Rowles\Http\Controllers\Controller;
use Rowles\Http\Requests\UpdateSubscriptionPackageRequest;
use Rowles\Models\SubscriptionPackage;
use Rowles\Models\User;
use Rowles\PaymentProviderInterface;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class SubscriptionController extends Controller
{
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
        $packages = SubscriptionPackage::first();
        $subscribers = [];

        $users = User::all();
        foreach($users as $user) {
            $userSubscriptions = $user->subscriptions()->get();
            if ($userSubscriptions->count() > 0) {
                foreach($userSubscriptions as $subscription) {
                    if ($subscription->active() && $subscription->stripe_plan === $packages->price) {
                        $subscribers[] = $user;
                    }
                }
            }
        }

        return view('admin.subscription.index', compact('packages', 'subscribers'));
    }

    public function manage(SubscriptionPackage $package)
    {
        return view('admin.subscription.manage', compact('package'));
    }

    public function update(SubscriptionPackage $package, UpdateSubscriptionPackageRequest $request)
    {
        DB::beginTransaction();

        $validated = $request->validated();

        $package->update([
            'name' => $validated['name'],
            'description' => $validated['description']
        ]);

        try {
            $price = $this->paymentProvider->prices->retrieve($validated['price_id']);
            $this->paymentProvider->products->update($price->product, [
                'name' => $validated['name'],
                'description' => $validated['description']
            ]);
        } catch (ApiErrorException $e) {
            DB::rollBack();
            $request->session()->flash('error', $e->getMessage());
        }

        DB::commit();

        $request->session()->flash('success', 'Subscription successfully updated.');

        return redirect()->back();
    }
}
