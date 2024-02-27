<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerController;

Route::prefix('owner')->name('owner.')->group(function(){
    Route::middleware(['guest:owner', 'PreventBackHistory'])->group(function () {

    //route saat owner tdk autentikasi
        Route::view('/login','back.pages.owner.auth.login')->name('login');

    });
});

