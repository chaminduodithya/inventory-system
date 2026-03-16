<?php

use App\Livewire\Dashboard;
use App\Livewire\StockAdd;
use App\Livewire\StockList;
use App\Livewire\DealerAdd;
use App\Livewire\DealerList;
use App\Http\Controllers\ProfileController;
use App\Livewire\InvoiceAdd;
use App\Livewire\InvoiceList;
use App\Livewire\InvoiceDetail;
use App\Livewire\SummaryReport;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Stocks
    Route::get('/stocks', StockList::class)->name('stocks.list');
    Route::get('/stocks/add', StockAdd::class)->name('stocks.add');
    Route::get('/stocks/{id}/edit', StockAdd::class)->name('stocks.edit');

    // Dealers
    Route::get('/dealers', DealerList::class)->name('dealers.list');
    Route::get('/dealers/add', DealerAdd::class)->name('dealers.add');
    Route::get('/dealers/{id}/edit', DealerAdd::class)->name('dealers.edit');

    // Invoices
    Route::get('/invoices', InvoiceList::class)->name('invoices.list');
    Route::get('/invoices/add', InvoiceAdd::class)->name('invoices.add');
    Route::get('/invoices/{id}/edit', InvoiceAdd::class)->name('invoices.edit');
    Route::get('/invoices/{invoice}/detail', InvoiceDetail::class)->name('invoice.detail');

    Route::get('/summary', SummaryReport::class)->name('summary');
});

require __DIR__.'/auth.php';
