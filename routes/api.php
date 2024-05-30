<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('/venues/{ownerId}', [BookingController::class, 'getVenues']);
Route::get('/services/{venueId}', [BookingController::class, 'getServices']);
Route::get('/services/{venueId}/{serviceTypeId}', [BookingController::class, 'getServicesByTypeAndVenue']);
Route::get('/packages/{serviceEventId}', [BookingController::class, 'getPackages']);
Route::get('/package-details/{packageId}', [BookingController::class, 'getPackageDetails']);
Route::get('/print-photo-details/{packageId}', [BookingController::class, 'getPrintPhotoDetails']);
Route::post('/get-book-dates', [BookingController::class, 'getBookDates'])->name('getBookDates');
