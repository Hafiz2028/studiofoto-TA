<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\Admin\ServiceVenueController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\CustomerController;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware(['guest:admin', 'PreventBackHistory'])->group(function () {
        //route saat admin tidak autentikasi
        Route::view('/login', 'back.pages.admin.auth.login')->name('login');
        Route::post('/login_handler', [AdminController::class, 'loginHandler'])->name('login_handler');
        Route::view('/forgot-password', 'back.pages.admin.auth.forgot-password')->name('forgot-password');
        Route::post('/send-password-reset-link', [AdminController::class, 'sendPasswordResetLink'])->name('send-password-reset-link');
        Route::get('/password/reset/{token}', [AdminController::class, 'resetPassword'])->name('reset-password');
        Route::post('/reset-password-handler', [AdminController::class, 'resetPasswordHandler'])->name('reset-password-handler');
    });
    Route::middleware(['auth:admin', 'PreventBackHistory'])->group(function () {
        //route saat admin autentikasi
        Route::view('/home', 'back.pages.admin.home')->name('home');
        Route::post('/logout_handler', [AdminController::class, 'logoutHandler'])->name('logout_handler');
        Route::get('/profile', [AdminController::class, 'profileView'])->name('profile');
        Route::post('/change-profile-picture', [AdminController::class, 'changeProfilePicture'])->name('change-profile-picture');
        // Route::view('/settings','back.pages.settings')->name('settings');
        //menu Users
        Route::prefix('user')->name('user.')->group(function () {
            //crud user admin
            Route::controller(AdminController::class)->group(function () {
                Route::get('/', 'adminList')->name('adminList');
                Route::get('/add-admin', 'addAdmin')->name('add-admin');
                Route::post('/store-admin', 'storeAdmin')->name('store-admin');
                Route::get('/edit-admin', 'editAdmin')->name('edit-admin');
                Route::post('/update-admin', 'updateAdmin')->name('update-admin');
                Route::delete('/delete-admin', 'deleteAdmin')->name('delete-admin');
            });
            //crud user owner
            Route::resource('owner', OwnerController::class);
            // crud user customer
            Route::resource('customer', CustomerController::class);
        });
        //menu Venue
        Route::prefix('venue')->name('venue.')->group(function () {
            Route::get('/need-approval', [VenueController::class, 'needApproval'])->name('need-approval');
            Route::get('/approved', [VenueController::class, 'approved'])->name('approved');
            Route::get('/rejected', [VenueController::class, 'rejected'])->name('rejected');
            Route::patch('/approve/{id}', [VenueController::class, 'approveVenue'])->name('approve-venue');
            Route::patch('/reject/{id}', [VenueController::class, 'rejectVenue'])->name('reject-venue');
            Route::get('/detail/{id}',[VenueController::class, 'detailVenue'])->name('detail-venue');
        });

        //menu Venue's Service
        Route::prefix('service')->name('service.')->group(function () {
            Route::controller(ServiceVenueController::class)->group(function () {
                Route::get('/', 'venueServiceList')->name('venueServiceList');
                Route::get('/add-service', 'addService')->name('add-service');
                Route::post('/store-service', 'storeService')->name('store-service');
                Route::get('/edit-service', 'editService')->name('edit-service');
                Route::post('/update-service', 'updateService')->name('update-service');
                Route::delete('/delete-service', 'deleteService')->name('delete-service');
            });
        });
    });
});
