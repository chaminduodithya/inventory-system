<?php

use App\Livewire\StockManagement;
use App\Livewire\DealerManagement;
use App\Http\Controllers\ProfileController;
use App\Livewire\InvoiceManagement;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/stock', StockManagement::class)->name('stocks');
    Route::get('/dealers', DealerManagement::class)->name('dealers');
    Route::get('/invoices', InvoiceManagement::class)->name('invoices');
});

require __DIR__.'/auth.php';
