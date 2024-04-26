<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistrictController;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/districts', [DistrictController::class, 'getDistricts'])->name('districts');
Route::post('/submit', [DistrictController::class, 'submit'])->name('submit');

Route::view('/example-page', 'example-page');
Route::view('/example-auth', 'example-auth');
Route::get('/error-page', function () {
    return view('error.page');
})->name('error.page');
