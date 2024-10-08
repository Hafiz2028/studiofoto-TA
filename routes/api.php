<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransactionController;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



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
Route::get('/services-and-events/{venueId}', [BookingController::class, 'getServicesAndEvents']);
Route::get('/packages/{serviceEventId}', [BookingController::class, 'getPackageAndDetails']);
Route::get('/package-details/{packageId}', [BookingController::class, 'getPackageDetails']);


//not used
Route::get('/services/{venueId}/{serviceTypeId}', [BookingController::class, 'getServicesByTypeAndVenue']);
//not used

Route::get('/venue-suggestions', function (Request $request) {
    $query = $request->input('query', '');
    $venues = Venue::where('name', 'like', "%{$query}%")->pluck('name');
    return response()->json($venues);
});
Route::get('/print-photo-details/{packageId}', [BookingController::class, 'getPrintPhotoDetails']);
Route::get('/get-book-dates', [BookingController::class, 'getBookDates'])->name('getBookDates');

Route::get('/rent-events/{ownerId}', [OwnerController::class, 'getRentEvents']);

Route::get('/cust/services/{serviceTypeId}', [FrontEndController::class, 'getServicesByTypeAndVenue']);
Route::get('/cust/packages/{serviceEventId}', [FrontEndController::class, 'getPackages']);
Route::get('/cust/package-details/{packageId}', [FrontEndController::class, 'getPackageDetails']);
Route::get('/cust/print-photo-details/{packageId}', [FrontEndController::class, 'getPrintPhotoDetails']);
Route::get('/cust/get-book-dates', [FrontEndController::class, 'getBookDates']);
// katalog
Route::get('/price-catalog/{venue}', [FrontEndController::class, 'getPriceCatalog']);
