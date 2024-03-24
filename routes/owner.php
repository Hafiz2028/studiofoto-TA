<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\BookingController;


Route::prefix('owner')->name('owner.')->group(function(){
    Route::middleware(['guest:owner', 'PreventBackHistory'])->group(function () {

    //route saat owner tdk autentikasi
        Route::view('/login','back.pages.owner.auth.login')->name('login');
        Route::post('/login_handler', [OwnerController::class, 'loginHandler'])->name('login_handler');
        Route::view('/forgot-password', 'back.pages.owner.auth.forgot-password')->name('forgot-password');
        Route::post('/send-password-reset-link',[OwnerController::class, 'sendPasswordResetLink'])->name('send-password-reset-link');
        Route::get('/password/reset/{token}',[OwnerController::class,'resetPassword'])->name('reset-password');
        Route::post('/reset-password-handler',[OwnerController::class, 'resetPasswordHandler'])->name('reset-password-handler');

    });
    Route::middleware(['auth:owner','PreventBackHistory'])->group(function(){
        //route saat admin autentikasi
        Route::view('/home', 'back.pages.owner.home')->name('home');
        Route::post('/logout_handler', [OwnerController::class, 'logoutHandler'])->name('logout_handler');
        Route::get('/profile',[OwnerController::class, 'profileView'])->name('profile');
        Route::post('/change-profile-picture',[OwnerController::class, 'changeProfilePicture'])->name('change-profile-picture');

        //sidebar route
        //menu venue's manage
        Route::resource('venue', VenueController::class);
        Route::resource('booking', BookingController::class);



    });

});

