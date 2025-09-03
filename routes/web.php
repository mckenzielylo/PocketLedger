<?php

// Include health check routes
require __DIR__.'/health.php';

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\DebtPaymentController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\BudgetController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/test', function() { return view('dashboard.test'); })->name('dashboard.test');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    
    // Transactions
    Route::resource('transactions', TransactionController::class);
    
    // Accounts
    Route::resource('accounts', AccountController::class);
    Route::post('/accounts/{account}/archive', [AccountController::class, 'toggleArchive'])->name('accounts.archive');
    Route::post('/accounts/{account}/default', [AccountController::class, 'setDefault'])->name('accounts.default');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    Route::patch('/categories/{category}/archive', [CategoryController::class, 'toggleArchive'])->name('categories.archive');
    Route::get('/categories/type/{type}', [CategoryController::class, 'getByType'])->name('categories.by-type');
    
    // Debts
    Route::resource('debts', DebtController::class);
    Route::post('/debts/{debt}/payments', [DebtController::class, 'recordPayment'])->name('debts.payments.store');
    Route::patch('/debts/{debt}/mark-paid', [DebtController::class, 'markAsPaid'])->name('debts.mark-paid');
    Route::patch('/debts/{debt}/mark-unpaid', [DebtController::class, 'markAsUnpaid'])->name('debts.mark-unpaid');
    Route::get('/debts/stats', [DebtController::class, 'getStats'])->name('debts.stats');
    
    // Debt Payments
    Route::resource('debt-payments', DebtPaymentController::class)->except(['index', 'create']);
    Route::get('/debts/{debt}/payments', [DebtPaymentController::class, 'index'])->name('debt-payments.index');
    Route::get('/debts/{debt}/payments/create', [DebtPaymentController::class, 'create'])->name('debt-payments.create');
    
    // Assets
    Route::resource('assets', AssetController::class);
    
    // Budgets
    Route::resource('budgets', BudgetController::class);
    Route::get('/budgets/categories', [BudgetController::class, 'categories'])->name('budgets.categories');
    Route::post('/budgets/copy-from-previous', [BudgetController::class, 'copyFromPrevious'])->name('budgets.copy-from-previous');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/debug', function() { return view('profile.partials.profile-overview', ['user' => \Illuminate\Support\Facades\Auth::user(), 'stats' => ['accounts' => 2, 'transactions' => 4, 'categories' => 6, 'budgets' => 1, 'debts' => 1, 'assets' => 2]]); })->name('profile.debug');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // User theme update
    Route::post('/user/theme', function (Request $request) {
        $request->validate(['theme' => 'required|in:light,dark,auto']);
        auth()->user()->setSetting('theme', $request->theme);
        return response()->json(['success' => true]);
    })->name('user.theme.update');
});

require __DIR__.'/auth.php';
