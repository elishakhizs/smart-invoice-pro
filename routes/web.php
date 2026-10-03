<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanySettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Client Management
    Route::resource('clients', ClientController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])
    ->name('invoices.pdf');
    Route::get('/invoice-dashboard', [InvoiceController::class, 'dashboard'])
    ->name('invoices.dashboard');
    Route::patch('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])
    ->name('invoices.markPaid');
    Route::get('/company/settings', [CompanySettingsController::class, 'edit'])
    ->name('company.settings');
    Route::patch('/company/settings', [CompanySettingsController::class, 'update'])
    ->name('company.settings.update');
    Route::get('/reports', [ReportsController::class, 'index'])
    ->middleware(['auth'])
    ->name('reports.index');
    Route::post('/invoices/{invoice}/payments', [PaymentController::class, 'store'])
    ->middleware(['auth'])
    ->name('payments.store');
    Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])
    ->middleware(['auth'])
    ->name('payments.edit');

    Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'sendInvoice'])
    ->middleware(['auth'])
    ->name('invoices.send');

    Route::patch('/payments/{payment}', [PaymentController::class, 'update'])
    ->middleware(['auth'])
    ->name('payments.update');

    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('payments.destroy');

    
});

require __DIR__.'/auth.php';