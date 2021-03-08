<?php

namespace Rowles\Http\Controllers\Auth;

use DB;
use Rowles\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Rowles\Http\Controllers\Controller;
use Rowles\Providers\RouteServiceProvider;
use Laravel\Cashier\Exceptions\IncompletePayment;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return mixed
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        DB::beginTransaction();

        event(new Registered($user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])));

        try {
            $user->newSubscription('default', 'price_1ISjyxFnOG8jwOnxXs5u7cHn')
                ->create($request->payment_method, ['email' => $user->email]);
        } catch (IncompletePayment $exception ){
            DB::rollback();
            return redirect()->back()->with(['error_message' => $exception->getMessage()]);
        }

        DB::commit();

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
