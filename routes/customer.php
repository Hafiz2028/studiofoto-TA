<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FrontEndController;

Route::prefix('customer')->name('customer.')->group(function () {
    Route::middleware(['guest:customer', 'PreventBackHistory'])->group(function () {
        Route::controller(CustomerController::class)->group(function () {
            Route::get('/login', 'login')->name('login');
            Route::post('/login_handler', 'loginHandler')->name('login-handler');
            Route::get('/register', 'register')->name('register');
            Route::post('/create', 'createCustomer')->name('create');
            Route::get('/account/verify/{token}', 'verifyAccount')->name('verify');
            Route::get('/register-success', 'registerSuccess')->name('register-success');
            Route::get('/forgot-password', 'forgotPassword')->name('forgot-password');
            Route::post('/send-password-reset-link', 'sendPasswordResetLink')->name('send-password-reset-link');
            Route::get('/password/reset/{token}', 'showResetForm')->name('reset-password');
            Route::post('/reset-password-handler', 'resetPasswordHandler')->name('reset-password-handler');
        });
    });
    Route::middleware(['auth:customer', 'PreventBackHistory'])->group(function () {
        Route::controller(CustomerController::class)->group(function () {
            Route::post('/logout', 'logoutHandler')->name('logout');
            Route::get('/profile', 'profileView')->name('profile');
            Route::post('/change-profile-picture', 'changeProfilePicture')->name('change-profile-picture');
            Route::post('/change-ktp-image', 'changeKtpImage')->name('change-ktp-image');
        });
        Route::controller(FrontEndController::class)->group(function () {
            Route::get('/', 'home')->name('home');
            Route::get('/detail/{id}', 'detailVenue')->name('detail-venue');
        });
    });
});
