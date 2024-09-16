<?php

namespace App\Http\Controllers\ConsAuth;

use App\Http\Controllers\Controller;
use App\Models\Constructor;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredConstructorController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('constructor-auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Constructor::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $constructor = Constructor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($constructor));

        Auth::guard('constructor')->login($constructor);

        return redirect(route('constructor-dashboard', absolute: false));
    }
}
