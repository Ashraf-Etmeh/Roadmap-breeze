<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Roadmap;
use App\Http\Middleware\ConstructorAuth;

//for constructor:
use App\Http\Controllers\ConsAuth\RegisteredConstructorController;
use App\Http\Controllers\ConsAuth\AuthenticatedSessionController as ConstructorSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController as  ConstructorConfirmableController;
use App\Http\Controllers\ConsAuth\EmailVerificationNotificationController as ConstructorNotificationController;
use App\Http\Controllers\ConsAuth\EmailVerificationPromptController as ConstructorPromptController;
use App\Http\Controllers\ConsAuth\NewPasswordController as ConstructorNewPasswordController;
use App\Http\Controllers\ConsAuth\PasswordController as  ConstructorPasswordController;
use App\Http\Controllers\ConsAuth\PasswordResetLinkController as ConstructorResetController;
use App\Http\Controllers\ConsAuth\VerifyEmailController as ConstructorVerifyController;
use App\Http\Controllers\ConstructorProfileController;

Route::get('/', function () {
    return view('welcome',['roadmaps' => Roadmap::all()]);
});

Route::get('/dashboard', function () {
    return view('dashboard',['roadmaps' => Roadmap::all()]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware([ConstructorAuth::class])->group(function () {
    Route::get('/constructor-dashboard', function () {
        return view('constructor-dashboard');
    })->name('constructor-dashboard');
});

Route::view('/roadmap', 'roadmap');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
//constructor profile:
Route::middleware([ConstructorAuth::class])->prefix('constructor')->group(function () {
    Route::get('/profile-edit', [ConstructorProfileController::class, 'edit'])->name('constructor.profile.edit');
    Route::patch('/profile-update', [ConstructorProfileController::class, 'update'])->name('constructor.profile.update');
    Route::delete('/profile-destroy', [ConstructorProfileController::class, 'destroy'])->name('constructor.profile.destroy');
});
//constructor reset password:
// Route::prefix('constructor')->group(function () {
//     Route::post('password/email', [PasswordResetLinkController::class, 'store'])->name('constructor.password.email');
// });

// constructor auth:
Route::middleware('guest')->prefix('constructor')->group(function () {
    Route::get('register', [RegisteredConstructorController::class, 'create'])
                ->name('constructor.register');

    Route::post('register', [RegisteredConstructorController::class, 'store']);

    Route::get('login', [ConstructorSessionController::class, 'create'])
                ->name('constructor.login');

    Route::post('login', [ConstructorSessionController::class, 'store']);

    Route::get('forgot-password', [ConstructorResetController::class, 'create'])
                ->name('constructor.password.request');

    Route::post('forgot-password', [ConstructorResetController::class, 'store'])
                ->name('constructor.password.email');

    Route::get('reset-password/{token}', [ConstructorNewPasswordController::class, 'create'])
                ->name('constructor.password.reset');

    Route::post('reset-password', [ConstructorNewPasswordController::class, 'store'])
                ->name('constructor.password.store');
});

Route::middleware([ConstructorAuth::class])->prefix('constructor')->group(function () {
    Route::get('verify-email', ConstructorPromptController::class)
                ->name('constructor.verification.notice');

    Route::get('verify-email/{id}/{hash}', ConstructorVerifyController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('constructor.verification.verify');

    Route::post('email/verification-notification', [ConstructorNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('constructor.verification.send');

    Route::get('confirm-password', [ConstructorConfirmableController::class, 'show'])
                ->name('constructor.password.confirm');

    Route::post('confirm-password', [ConstructorConfirmableController::class, 'store']);

    Route::put('password', [ConstructorPasswordController::class, 'update'])->name('constructor.password.update');

    Route::post('logout', [ConstructorSessionController::class, 'destroy'])
                ->name('constructor.logout');
});
//Ashraf
require __DIR__.'/auth.php';
