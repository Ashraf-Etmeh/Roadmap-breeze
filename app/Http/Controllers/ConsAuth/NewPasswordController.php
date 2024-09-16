<?php

namespace App\Http\Controllers\ConsAuth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log; // Import the Log facade

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('constructor-auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Log the constructor lookup result
        $constructor = \App\Models\Constructor::where('email', $request->email)->first();

        if (!$constructor) {
            return back()->withErrors(['email' => 'We can\'t find a constructor with that email address.']);
        }


        // Here we will attempt to reset the constructor's password. If it is successful we
        // will update the password on an actual constructor model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::broker('constructors')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($constructor, $password) {
                $constructor->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($constructor));
            }
        );

        Log::info('Password Reset Status:', ['status' => $status]);

        // If the password was successfully reset, we will redirect the constructor back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('constructor.login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
