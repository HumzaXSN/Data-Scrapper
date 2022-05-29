<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\ScraperJobController;
use App\Http\Controllers\GoogleBusinessController;
use App\Http\Controllers\ScraperCriteriaController;

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

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('contacts', ContactController::class);

    Route::resource('lists', ListController::class);

    Route::resource('google-businesses', GoogleBusinessController::class)->only([
        'index', 'show', 'destroy'
    ]);

    Route::get('/show-businesses', [GoogleBusinessController::class, 'index'])
        ->name('google-businesses.showBusinesses');

    Route::resource('scraper-criterias', ScraperCriteriaController::class);

    Route::get('/run-scraper', [ScraperCriteriaController::class, 'runScraper'])
        ->name('scraper-criterias.runScraper');

    Route::get('/stop-scraper', [ScraperCriteriaController::class, 'stopScraper'])
        ->name('scraper-criterias.stopScraper');

    Route::get('/start-scraper', [ScraperCriteriaController::class, 'startScraper'])
        ->name('scraper-criterias.startScraper');

    Route::post('/bulk-update-record', [ContactController::class, 'bulkupdate'])
        ->name('bulk-update');

    Route::post('/add-contact', [ContactController::class, 'addContact'])
        ->name('add-contact');

    Route::post('/update-provisional-page', [ContactController::class, 'provisionalPage'])
        ->name('update-contacts-page');

    Route::get('/scraper-jobs', [ScraperJobController::class, 'index'])->name('scraper-jobs.index');

    Route::get('/show-jobs', [ScraperJobController::class, 'index'])->name('scraper-jobs-show.index');

    Route::get('/debug-sentry', function () {
        throw new Exception('My first Sentry error!');
    });
});

Route::get('/shift-to-mbl/{unsubLink}', [ContactController::class, 'shiftToMBL'])->name('contacts.mbl');

require __DIR__ . '/auth.php';
