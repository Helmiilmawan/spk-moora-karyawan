<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlternativeController;
use App\Http\Controllers\CriterionController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\MooraController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProcessHistoryController;
/*
|--------------------------------------------------------------------------
| Redirect Root
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Alternatives
|--------------------------------------------------------------------------
*/
Route::resource('alternatives', AlternativeController::class);

/*
|--------------------------------------------------------------------------
| Criteria & Sub Criteria
|--------------------------------------------------------------------------
*/
Route::resource('criteria', CriterionController::class);

/*
|--------------------------------------------------------------------------
| Ratings (Input Nilai Alternatif)
|--------------------------------------------------------------------------
| index  -> form input nilai
| store  -> simpan nilai
*/
Route::get('/ratings', [RatingController::class, 'index'])
    ->name('ratings.index');

Route::post('/ratings', [RatingController::class, 'store'])
    ->name('ratings.store');

/*
|--------------------------------------------------------------------------
| MOORA
|--------------------------------------------------------------------------
*/
Route::get('/moora/process', [MooraController::class, 'process'])
    ->name('moora.process');

Route::get('/moora/results', [MooraController::class, 'showStored'])
    ->name('moora.results');

Route::get('/moora/export/pdf', [MooraController::class, 'exportPDF'])
    ->name('moora.export.pdf');
    
Route::get('/moora/process', [MooraController::class, 'process'])->name('moora.process');
Route::post('/moora/store-history', [MooraController::class, 'storeHistory'])->name('moora.storeHistory');

Route::get('/history', [ProcessHistoryController::class, 'index'])->name('history.index');
Route::get('/history/{id}', [ProcessHistoryController::class, 'show'])->name('history.show');
// Tambahkan baris ini di bawah route history lainnya
Route::delete('/history/{id}', [ProcessHistoryController::class, 'destroy'])->name('history.destroy');
/*
|--------------------------------------------------------------------------
| Team
|--------------------------------------------------------------------------
*/
Route::get('/team', [TeamController::class, 'index'])
    ->name('team.index');

    Route::get(
    '/history',
    [ProcessHistoryController::class,'index']
)->name('history.index');

Route::get(
    '/history/{id}',
    [ProcessHistoryController::class,'show']
)->name('history.show');

