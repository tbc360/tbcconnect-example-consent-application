<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');

/**
 * API Consent
 */
Route::group([
    'prefix'     => 'consent',
    'as'         => 'consent.',
    'middleware' => 'auth'
], function () {
    Route::post('check', [ConsentController::class, 'checkConsent'])->name('check');
    Route::post('agree', [ConsentController::class, 'agree'])->name('agree');
    Route::post('status', [ConsentController::class, 'checkCustomerStatus'])->name('status');
    Route::post('terms_and_conditions', [ConsentController::class, 'getTermsAndConditions'])->name('status');
});

Route::get('consent/terms_and_conditions', [ConsentController::class, 'index'])->name('consent.page');

