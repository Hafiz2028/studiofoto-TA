<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ServiceVenueController;

Route::prefix('admin')->name('admin.')->group(function(){

    Route::middleware(['guest:admin','PreventBackHistory'])->group(function(){
    //route saat admin tidak autentikasi
        Route::view('/login', 'back.pages.admin.auth.login')->name('login');
        Route::post('/login_handler', [AdminController::class, 'loginHandler'])->name('login_handler');
        Route::view('/forgot-password', 'back.pages.admin.auth.forgot-password')->name('forgot-password');
        Route::post('/send-password-reset-link',[AdminController::class, 'sendPasswordResetLink'])->name('send-password-reset-link');
        Route::get('/password/reset/{token}',[AdminController::class,'resetPassword'])->name('reset-password');
        Route::post('/reset-password-handler',[AdminController::class, 'resetPasswordHandler'])->name('reset-password-handler');
    });
    Route::middleware(['auth:admin','PreventBackHistory'])->group(function(){
    //route saat admin autentikasi
        Route::view('/home', 'back.pages.admin.home')->name('home');
        Route::post('/logout_handler', [AdminController::class, 'logoutHandler'])->name('logout_handler');
        Route::get('/profile',[AdminController::class, 'profileView'])->name('profile');
        Route::post('/change-profile-picture',[AdminController::class, 'changeProfilePicture'])->name('change-profile-picture');
        Route::view('/settings','back.pages.settings')->name('settings');
        //hanya view belum crud
        //menu Users
        Route::prefix('user')->name('user.')->group(function(){
            Route::controller(AdminController::class)->group(function(){
                Route::get('/','adminList')->name('adminList');
                Route::get('/add-admin','addAdmin')->name('add-admin');
                Route::post('/store-admin','storeAdmin')->name('store-admin');
                Route::get('/edit-admin','editAdmin')->name('edit-admin');
                // Route::post('update-admin','updateService')->name('update-admin');
            });
            // Route::view('/admin-users','back.pages.admin.manage-users.admin.user-admin')->name('admin-users');
            Route::view('/cust-users','back.pages.admin.manage-users.customer.user-customer')->name('cust-users');
            Route::view('/owner-users','back.pages.admin.manage-users.owner.user-owner')->name('owner-users');
        });
        //menu Venue
        Route::view('/venue-need-approval','back.pages.admin.manage-venue.need-approval.need-approval-table')->name('venue-need-approval');
        Route::view('/venue-approved','back.pages.admin.manage-venue.approved.approved-table')->name('venue-approved');
        Route::view('/venue-rejected','back.pages.admin.manage-venue.rejected.rejected-table')->name('venue-rejected');

        //menu Venue's Service
        Route::prefix('service')->name('service.')->group(function(){
            Route::controller(ServiceVenueController::class)->group(function(){
                Route::get('/','venueServiceList')->name('venueServiceList');
                Route::get('/add-service','addService')->name('add-service');
                Route::post('/store-service','storeService')->name('store-service');
                Route::get('/edit-service','editService')->name('edit-service');
                Route::post('/update-service','updateService')->name('update-service');
                Route::delete('/delete-service','deleteService')->name('delete-service');
            });
        });

    });

});
