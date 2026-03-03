<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\SettingsController;

Route::redirect('/', '/dashboard')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customers
    Route::resource('customers', CustomerController::class);

    // Billing
    Route::get('billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('billing/create', [BillingController::class, 'create'])->name('billing.create');
    Route::post('billing', [BillingController::class, 'store'])->name('billing.store');
    Route::get('billing/{bill}', [BillingController::class, 'show'])->name('billing.show');
    Route::patch('billing/{bill}/mark-paid', [BillingController::class, 'markAsPaid'])->name('billing.mark-paid');
    Route::delete('billing/{bill}', [BillingController::class, 'destroy'])->name('billing.destroy');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

    // API Routes for AJAX
    Route::get('api/customers/{customer}/readings', [BillingController::class, 'getCustomerReadings']);
});

require __DIR__.'/settings.php';
