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

    Route::post('/bulk-update-record', [ContactController::class, 'bulkupdate'])
                ->name('bulk-update');

    Route::post('/add-contact', [ContactController::class, 'addContact'])
                ->name('add-contact');

    Route::post('/delete-selected-contacts',[ContactController::class,'deleteSelectedContacts'])
                ->name('delete.selected.contacts');

    Route::post('/update-provisional-page',[ContactController::class,'provisionalPage'])
                ->name('update-contacts-page');

    Route::get('/scraper-jobs', [ScraperJobController::class, 'index'])->name('scraper-jobs.index');

    Route::get('/contact-proviosonal', [ContactController::class, 'addProvisionalContact'])->name('contactProviosonal');

    Route::get('/add-provisional-contact', [ContactController::class, 'storeProvisionalContactdata'])->name('addProvisionalContactData');

    // Route::get('/import', [ContactController::class, 'index'])->name('import');
    // Route::post('/import', [ContactController::class, 'import'])->name('import');

});
require __DIR__.'/auth.php';
