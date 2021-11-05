<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ScraperJobController;
use App\Http\Controllers\GoogleBusinessController;

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
    return view('auth.login');
});

Route::middleware(['auth','verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('contacts', ContactController::class);

    Route::resource('google-businesses', GoogleBusinessController::class)->only([
        'index', 'show', 'destroy'
    ]);

    Route::get('/scraper-jobs', [ScraperJobController::class, 'index'])->name('scraper-jobs.index');

    // Route::get('/import', [ContactController::class, 'index'])->name('import');
    // Route::post('/import', [ContactController::class, 'import'])->name('import');

});
require __DIR__.'/auth.php';
