<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        $transactions = $user->transactions()
            ->with(['account', 'category', 'transferAccount'])
            ->orderBy('occurred_on', 'desc')
            ->paginate(20);
            
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = Auth::user();
        
        $accounts = $user->accounts()->active()->get();
        $categories = $user->categories()->active()->get();
        
        return view('transactions.create', compact('accounts', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => 'required|in:income,expense,transfer',
            'amount' => 'required|numeric|min:0.01',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'nullable|exists:categories,id',
            'transfer_account_id' => 'nullable|exists:accounts,id',
            'occurred_on' => 'required|date',
            'payee' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:1000',
            'receipt' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        
        // Validate transfer logic
        if ($request->type === 'transfer') {
            if (empty($request->transfer_account_id)) {
                return back()->withErrors(['transfer_account_id' => 'Transfer account is required for transfer transactions.']);
            }
            if ($request->account_id === $request->transfer_account_id) {
                return back()->withErrors(['transfer_account_id' => 'Source and destination accounts cannot be the same for transfers.']);
            }
        } else {
            if (empty($request->category_id)) {
                return back()->withErrors(['category_id' => 'Category is required for income and expense transactions.']);
            }
        }

        // Handle receipt upload
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        // Handle currency conversion for transfers
        if ($request->type === 'transfer' && $request->transfer_account_id) {
            $sourceAccount = $user->accounts()->find($request->account_id);
            $destAccount = $user->accounts()->find($request->transfer_account_id);
            
            // Check if currencies are different
            if ($sourceAccount->currency !== $destAccount->currency) {
                // Create two separate transactions for cross-currency transfers
                
                // 1. Expense transaction in source currency
                $expenseTransaction = $user->transactions()->create([
                    'type' => 'expense',
                    'amount' => $request->amount,
                    'account_id' => $request->account_id,
                    'category_id' => $this->getTransferCategoryId($user, 'Transfer Out'),
                    'occurred_on' => $request->occurred_on,
                    'payee' => $request->payee ?: "Transfer to {$destAccount->name}",
                    'note' => $request->note ?: "Transfer to {$destAccount->name} ({$destAccount->currency})",
                    'receipt_path' => $receiptPath,
                ]);
                
                // 2. Income transaction in destination currency
                $convertedAmount = $this->convertCurrency($request->amount, $sourceAccount->currency, $destAccount->currency);
                
                $incomeTransaction = $user->transactions()->create([
                    'type' => 'income',
                    'amount' => $convertedAmount,
                    'account_id' => $request->transfer_account_id,
                    'category_id' => $this->getTransferCategoryId($user, 'Transfer In'),
                    'occurred_on' => $request->occurred_on,
                    'payee' => $request->payee ?: "Transfer from {$sourceAccount->name}",
                    'note' => $request->note ?: "Transfer from {$sourceAccount->name} ({$sourceAccount->currency}) - Converted from {$sourceAccount->currency} {$request->amount}",
                    'receipt_path' => $receiptPath,
                ]);
                
                $transaction = $expenseTransaction; // Use expense transaction as the main one
            } else {
                // Same currency transfer - create single transaction
                $transaction = $user->transactions()->create([
                    'type' => $request->type,
                    'amount' => $request->amount,
                    'account_id' => $request->account_id,
                    'category_id' => $request->category_id,
                    'transfer_account_id' => $request->transfer_account_id,
                    'occurred_on' => $request->occurred_on,
                    'payee' => $request->payee,
                    'note' => $request->note,
                    'receipt_path' => $receiptPath,
                ]);
            }
        } else {
            // Regular income/expense transaction
            $transaction = $user->transactions()->create([
                'type' => $request->type,
                'amount' => $request->amount,
                'account_id' => $request->account_id,
                'category_id' => $request->category_id,
                'transfer_account_id' => $request->transfer_account_id,
                'occurred_on' => $request->occurred_on,
                'payee' => $request->payee,
                'note' => $request->note,
                'receipt_path' => $receiptPath,
            ]);
        }

        // Update account balances
        $account = $user->accounts()->find($request->account_id);
        $account->updateBalance();

        if ($request->transfer_account_id) {
            $transferAccount = $user->accounts()->find($request->transfer_account_id);
            $transferAccount->updateBalance();
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction): View
    {
        $this->authorize('view', $transaction);
        
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction): View
    {
        $this->authorize('update', $transaction);
        
        $user = Auth::user();
        $accounts = $user->accounts()->active()->get();
        $categories = $user->categories()->active()->get();
        
        return view('transactions.edit', compact('transaction', 'accounts', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->authorize('update', $transaction);
        
        $request->validate([
            'type' => 'required|in:income,expense,transfer',
            'amount' => 'required|numeric|min:0.01',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'nullable|exists:categories,id',
            'transfer_account_id' => 'nullable|exists:accounts,id',
            'occurred_on' => 'required|date',
            'payee' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:1000',
            'receipt' => 'nullable|image|max:2048',
        ]);

        // Validate transfer logic
        if ($request->type === 'transfer') {
            if (empty($request->transfer_account_id)) {
                return back()->withErrors(['transfer_account_id' => 'Transfer account is required for transfer transactions.']);
            }
            if ($request->account_id === $request->transfer_account_id) {
                return back()->withErrors(['transfer_account_id' => 'Source and destination accounts cannot be the same for transfers.']);
            }
        } else {
            if (empty($request->category_id)) {
                return back()->withErrors(['category_id' => 'Category is required for income and expense transactions.']);
            }
        }

        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($transaction->receipt_path) {
                Storage::disk('public')->delete($transaction->receipt_path);
            }
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        } else {
            $receiptPath = $transaction->receipt_path;
        }

        $oldAccountId = $transaction->account_id;
        $oldTransferAccountId = $transaction->transfer_account_id;

        $transaction->update([
            'type' => $request->type,
            'amount' => $request->amount,
            'account_id' => $request->account_id,
            'category_id' => $request->category_id,
            'transfer_account_id' => $request->transfer_account_id,
            'occurred_on' => $request->occurred_on,
            'payee' => $request->payee,
            'note' => $request->note,
            'receipt_path' => $receiptPath,
        ]);

        // Update account balances for affected accounts
        $accountsToUpdate = collect([$oldAccountId, $oldTransferAccountId, $request->account_id, $request->transfer_account_id])
            ->filter()
            ->unique();
            
        foreach ($accountsToUpdate as $accountId) {
            $account = Auth::user()->accounts()->find($accountId);
            if ($account) {
                $account->updateBalance();
            }
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        $this->authorize('delete', $transaction);
        
        $accountId = $transaction->account_id;
        $transferAccountId = $transaction->transfer_account_id;
        
        // Delete receipt if exists
        if ($transaction->receipt_path) {
            Storage::disk('public')->delete($transaction->receipt_path);
        }
        
        $transaction->delete();
        
        // Update account balances
        $user = Auth::user();
        $accountsToUpdate = collect([$accountId, $transferAccountId])->filter();
        
        foreach ($accountsToUpdate as $accountId) {
            $account = $user->accounts()->find($accountId);
            if ($account) {
                $account->updateBalance();
            }
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }

    /**
     * Convert currency amount using exchange rates.
     */
    private function convertCurrency(float $amount, string $fromCurrency, string $toCurrency): float
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $exchangeRateService = new \App\Services\ExchangeRateService();
        $convertedAmount = $exchangeRateService->convert($amount, $fromCurrency, $toCurrency);
        
        return $convertedAmount ?? $amount; // Fallback to original amount if conversion fails
    }

    /**
     * Get or create transfer category.
     */
    private function getTransferCategoryId($user, string $categoryName): int
    {
        $category = $user->categories()->where('name', $categoryName)->first();
        
        if (!$category) {
            $category = $user->categories()->create([
                'name' => $categoryName,
                'type' => $categoryName === 'Transfer In' ? 'income' : 'expense',
                'color' => '#6B7280',
            ]);
        }
        
        return $category->id;
    }
}
