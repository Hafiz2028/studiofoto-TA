<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\ServiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(FrontEndController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/search', 'searchPage')->name('search-page');
});


Route::view('/example-page', 'example-page');
Route::view('/example-auth', 'example-auth');
Route::view('/example-frontend', 'example-frontend');
