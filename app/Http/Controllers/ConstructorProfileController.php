<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ConstructorProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ConstructorProfileController extends Controller
{
     /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // dd('heoll ');
        $constructor = Auth::guard('constructor')->user();
        return view('constructorProfile.edit',
        ['constructor' => $constructor]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ConstructorProfileUpdateRequest $request): RedirectResponse
    {
        $request->user('constructor')->fill($request->validated());

        if ($request->user('constructor')->isDirty('email')) {
            $request->user('constructor')->email_verified_at = null;
        }

        $request->user('constructor')->save();

        return Redirect::route('constructor.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password:constructor'],
        ]);

        $user = $request->user('constructor');

        Auth::guard('constructor')->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

