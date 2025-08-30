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
        $user = Auth::user();
        
        $accounts = $user->accounts()
            ->withCount(['transactions', 'transferTransactions'])
            ->orderBy('is_archived')
            ->orderBy('name')
            ->get();
            
        return view('accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cash,bank,e-wallet',
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
            'type' => 'required|in:cash,bank,e-wallet',
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
