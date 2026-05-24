<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;

// =============================================
// PUBLIC ROUTES
// =============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil', [HomeController::class, 'profil'])->name('profil');
Route::get('/katalog', [HomeController::class, 'katalog'])->name('katalog');
Route::get('/bantuan', [HomeController::class, 'bantuan'])->name('bantuan');
Route::get('/contact', [HomeController::class, 'kontak'])->name('kontak');

Route::get('/event/{id}', [EventController::class, 'show'])->name('events.show');
Route::get('/checkout/{id}', [EventController::class, 'checkout'])->name('checkout');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

// =============================================
// ADMIN AREA ROUTES
// =============================================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

    // Route::resource otomatis generate 7 route CRUD untuk events
    Route::resource('events', AdminEventController::class);

    // Route::resource otomatis generate 7 route CRUD untuk categories
    Route::resource('categories', CategoryController::class);

    // Route::resource otomatis generate 7 route CRUD untuk partners
    Route::resource('partners', PartnerController::class);
});
