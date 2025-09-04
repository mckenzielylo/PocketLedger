<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        try {
            $user = Auth::user();
            
            // Check if user exists
            if (!$user) {
                \Log::error('No authenticated user found');
                return redirect()->route('login')->with('error', 'Please log in to access accounts.');
            }
            
            // Try to get accounts with error handling
            try {
                $accounts = $user->accounts()
                    ->withCount(['transactions', 'transferTransactions'])
                    ->orderBy('is_archived')
                    ->orderBy('name')
                    ->get();
            } catch (\Exception $e) {
                \Log::error('Database query error in accounts: ' . $e->getMessage());
                // Fallback to simple query without withCount
                $accounts = $user->accounts()
                    ->orderBy('is_archived')
                    ->orderBy('name')
                    ->get();
            }
                
            return view('accounts.index', compact('accounts'));
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Accounts page error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a simple error view or redirect
            return redirect()->route('dashboard')->with('error', 'Unable to load accounts. Please try again later.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        try {
            return view('accounts.create');
        } catch (\Exception $e) {
            \Log::error('Accounts create page error: ' . $e->getMessage());
            return redirect()->route('accounts.index')->with('error', 'Unable to load create account page. Please try again later.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cash,bank,e-wallet,credit-card',
            'currency' => 'required|string|max:3',
            'starting_balance' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        
        $account = $user->accounts()->create([
            'name' => $request->name,
            'type' => $request->type,
            'currency' => strtoupper($request->currency),
            'starting_balance' => $request->starting_balance,
            'current_balance' => $request->starting_balance,
            'note' => $request->note,
            'is_archived' => false,
        ]);

        return redirect()->route('accounts.index')
            ->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account): View
    {
        Gate::authorize('view', $account);
        
        $account->load(['transactions' => function ($query) {
            $query->orderBy('occurred_on', 'desc')->limit(20);
        }, 'transferTransactions' => function ($query) {
            $query->orderBy('occurred_on', 'desc')->limit(20);
        }]);
        
        return view('accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account): View
    {
        Gate::authorize('update', $account);
        
        return view('accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account): RedirectResponse
    {
        Gate::authorize('update', $account);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cash,bank,e-wallet,credit-card',
            'currency' => 'required|string|max:3',
            'note' => 'nullable|string|max:1000',
        ]);

        $account->update([
            'name' => $request->name,
            'type' => $request->type,
            'currency' => strtoupper($request->currency),
            'note' => $request->note,
        ]);

        return redirect()->route('accounts.index')
            ->with('success', 'Account updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account): RedirectResponse
    {
        Gate::authorize('delete', $account);
        
        // Check if account has transactions
        if ($account->transactions()->exists() || $account->transferTransactions()->exists()) {
            return back()->withErrors(['delete' => 'Cannot delete account with existing transactions. Archive it instead.']);
        }
        
        $account->delete();

        return redirect()->route('accounts.index')
            ->with('success', 'Account deleted successfully.');
    }

    /**
     * Toggle archive status of the account.
     */
    public function toggleArchive(Account $account): RedirectResponse
    {
        Gate::authorize('update', $account);
        
        $account->update([
            'is_archived' => !$account->is_archived
        ]);

        $status = $account->is_archived ? 'archived' : 'activated';
        return redirect()->route('accounts.index')
            ->with('success', "Account {$status} successfully.");
    }

    /**
     * Set account as default.
     */
    public function setDefault(Account $account): RedirectResponse
    {
        Gate::authorize('update', $account);
        
        $user = Auth::user();
        
        // Update user's default account
        $user->update([
            'settings' => array_merge($user->settings ?? [], [
                'default_account_id' => $account->id
            ])
        ]);

        return redirect()->route('accounts.index')
            ->with('success', 'Default account updated successfully.');
    }
}
