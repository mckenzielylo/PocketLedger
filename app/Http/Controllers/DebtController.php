<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Account;
use App\Models\DebtPayment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        $debts = $user->debts()
            ->with(['account', 'payments'])
            ->orderBy('due_date')
            ->get();
            
        // Calculate debt statistics
        $totalDebt = $debts->sum('amount');
        $totalPaid = $debts->sum(function ($debt) {
            return $debt->payments->sum('amount');
        });
        $totalRemaining = $totalDebt - $totalPaid;
        $overdueDebts = $debts->filter(function ($debt) {
            return $debt->due_date && $debt->due_date->isPast() && $debt->payments->sum('amount') < $debt->amount;
        });
        
        return view('debts.index', compact('debts', 'totalDebt', 'totalPaid', 'totalRemaining', 'overdueDebts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = Auth::user();
        $accounts = $user->accounts()
            ->where('is_archived', false)
            ->orderBy('name')
            ->get();
            
        return view('debts.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:borrowed,lent',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'due_date' => 'nullable|date|after:today',
            'description' => 'nullable|string|max:500',
            'is_paid' => 'boolean',
        ]);

        $user = Auth::user();
        
        // Verify account belongs to user
        $account = $user->accounts()->findOrFail($request->account_id);
        
        $debt = $user->debts()->create([
            'name' => $request->name,
            'amount' => $request->amount,
            'account_id' => $request->account_id,
            'type' => $request->type,
            'interest_rate' => $request->interest_rate,
            'due_date' => $request->due_date,
            'description' => $request->description,
            'is_paid' => $request->boolean('is_paid'),
        ]);

        // If marked as paid, create initial payment
        if ($request->boolean('is_paid')) {
            $debt->payments()->create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'payment_date' => now(),
                'notes' => 'Initial payment',
            ]);
        }

        return redirect()->route('debts.index')
            ->with('success', 'Debt created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Debt $debt): View
    {
        Gate::authorize('view', $debt);
        
        $debt->load(['account', 'payments' => function ($query) {
            $query->orderBy('payment_date', 'desc');
        }]);
        
        // Calculate debt statistics
        $totalPaid = $debt->payments->sum('amount');
        $remainingAmount = $debt->amount - $totalPaid;
        $paymentProgress = $debt->amount > 0 ? ($totalPaid / $debt->amount) * 100 : 0;
        
        // Calculate interest if applicable
        $totalInterest = 0;
        if ($debt->interest_rate && $debt->interest_rate > 0) {
            $daysOutstanding = $debt->created_at->diffInDays(now());
            $totalInterest = ($debt->amount * $debt->interest_rate / 100) * ($daysOutstanding / 365);
        }
        
        return view('debts.show', compact('debt', 'totalPaid', 'remainingAmount', 'paymentProgress', 'totalInterest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Debt $debt): View
    {
        Gate::authorize('update', $debt);
        
        $user = Auth::user();
        $accounts = $user->accounts()
            ->where('is_archived', false)
            ->orderBy('name')
            ->get();
            
        return view('debts.edit', compact('debt', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Debt $debt): RedirectResponse
    {
        Gate::authorize('update', $debt);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:borrowed,lent',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string|max:500',
        ]);

        $debt->update([
            'name' => $request->name,
            'amount' => $request->amount,
            'account_id' => $request->account_id,
            'type' => $request->type,
            'interest_rate' => $request->interest_rate,
            'due_date' => $request->due_date,
            'description' => $request->description,
        ]);

        return redirect()->route('debts.show', $debt)
            ->with('success', 'Debt updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Debt $debt): RedirectResponse
    {
        Gate::authorize('delete', $debt);
        
        // Check if debt has payments
        if ($debt->payments()->exists()) {
            return back()->withErrors(['delete' => 'Cannot delete debt with existing payments.']);
        }
        
        $debt->delete();

        return redirect()->route('debts.index')
            ->with('success', 'Debt deleted successfully.');
    }

    /**
     * Record a payment for the debt.
     */
    public function recordPayment(Request $request, Debt $debt): RedirectResponse
    {
        Gate::authorize('update', $debt);
        
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $payment = $debt->payments()->create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'notes' => $request->notes,
        ]);

        // Check if debt is now fully paid
        $totalPaid = $debt->payments->sum('amount');
        if ($totalPaid >= $debt->amount) {
            $debt->update(['is_paid' => true]);
        }

        return redirect()->route('debts.show', $debt)
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Mark debt as paid.
     */
    public function markAsPaid(Debt $debt): RedirectResponse
    {
        Gate::authorize('update', $debt);
        
        $debt->update(['is_paid' => true]);
        
        return redirect()->route('debts.show', $debt)
            ->with('success', 'Debt marked as paid.');
    }

    /**
     * Mark debt as unpaid.
     */
    public function markAsUnpaid(Debt $debt): RedirectResponse
    {
        Gate::authorize('update', $debt);
        
        $debt->update(['is_paid' => false]);
        
        return redirect()->route('debts.show', $debt)
            ->with('success', 'Debt marked as unpaid.');
    }

    /**
     * Get debt statistics for dashboard.
     */
    public function getStats(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        
        $debts = $user->debts()->with('payments')->get();
        
        $stats = [
            'total_debts' => $debts->count(),
            'total_amount' => $debts->sum('amount'),
            'total_paid' => $debts->sum(function ($debt) {
                return $debt->payments->sum('amount');
            }),
            'overdue_count' => $debts->filter(function ($debt) {
                return $debt->due_date && $debt->due_date->isPast() && !$debt->is_paid;
            })->count(),
            'recent_payments' => $user->debtPayments()
                ->with('debt')
                ->latest('payment_date')
                ->limit(5)
                ->get()
        ];
        
        return response()->json($stats);
    }
}
