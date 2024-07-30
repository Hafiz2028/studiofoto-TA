<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransactionController;



Route::prefix('owner')->name('owner.')->group(function () {
    Route::middleware(['guest:owner', 'PreventBackHistory'])->group(function () {
        Route::controller(OwnerController::class)->group(function () {
            Route::get('/login', 'login')->name('login');
            Route::post('/login_handler', 'loginHandler')->name('login-handler');
            Route::get('/register', 'register')->name('register');
            Route::post('/create', 'createOwner')->name('create');
            Route::get('/account/verify/{token}', 'verifyAccount')->name('verify');
            Route::get('/register-success', 'registerSuccess')->name('register-success');
            Route::get('/forgot-password', 'forgotPassword')->name('forgot-password');
            Route::post('/send-password-reset-link', 'sendPasswordResetLink')->name('send-password-reset-link');
            Route::get('/password/reset/{token}', 'showResetForm')->name('reset-password');
            Route::post('/reset-password-handler', 'resetPasswordHandler')->name('reset-password-handler');
        });
    });
    Route::middleware(['auth:owner', 'PreventBackHistory'])->group(function () {
        //route saat admin autentikasi
        Route::controller(OwnerController::class)->group(function () {
            Route::get('/', 'home')->name('home');
            Route::post('/logout', 'logoutHandler')->name('logout');
            Route::get('/profile', 'profileView')->name('profile');
            Route::post('/change-profile-picture', 'changeProfilePicture')->name('change-profile-picture');
            Route::post('/change-ktp-image', 'changeKtpImage')->name('change-ktp-image');
            Route::post('/change-logo-image', 'changeLogoImage')->name('change-logo-image');
        });
        //sidebar route
        //menu venue's manage
        Route::resource('venue', VenueController::class);
        Route::get('/venue/districts', [VenueController::class, 'getDistricts'])->name('districts');
        Route::resource('venue.services', ServiceController::class);
        Route::resource('venue.services.packages', PackageController::class);
        Route::get('/venue/{venue}/services/{service}/packages/{package}', [PackageController::class, 'showDetail'])->name('venue.services.packages.showDetail');

        Route::resource('booking', BookingController::class);
        Route::get('/booking/{booking}/show-payment', [BookingController::class, 'showPayment'])->name('booking.show-payment');
        Route::post('/booking/{booking}/payment', [BookingController::class, 'rentPayment'])->name('booking.payment');
        Route::get('/booking/{booking}/show-payment-lunas', [BookingController::class, 'showPaymentLunas'])->name('booking.show-payment-lunas');
        Route::post('/booking/{booking}/payment-lunas', [BookingController::class, 'rentPaymentLunas'])->name('booking.payment-lunas');

        Route::post('/booking/update-status', [BookingController::class, 'updateStatus'])->name('booking.update-status');
        Route::post('/booking/{booking}/update-status-mulai-foto', [BookingController::class, 'updateStatusMulaiFoto'])->name('booking.update-status-mulai-foto');
        Route::patch('/booking/approve/{id}', [BookingController::class, 'approveRent'])->name('booking.approve-rent');
        Route::patch('/booking/reject/{id}', [BookingController::class, 'rejectRent'])->name('booking.reject-rent');
        Route::patch('/booking/batal/{id}', [BookingController::class, 'batalRent'])->name('booking.batal-rent');
        Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
        Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction.index');
    });
});
