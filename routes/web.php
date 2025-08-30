<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\BudgetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    
    // Transactions
    Route::resource('transactions', TransactionController::class);
    
    // Accounts
    Route::resource('accounts', AccountController::class);
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Debts
    Route::resource('debts', DebtController::class);
    Route::post('/debts/{debt}/payments', [DebtController::class, 'recordPayment'])->name('debts.payments.store');
    
    // Assets
    Route::resource('assets', AssetController::class);
    
    // Budgets
    Route::resource('budgets', BudgetController::class);
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
