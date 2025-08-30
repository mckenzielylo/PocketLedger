<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDebtPaymentRequest;
use App\Http\Requests\UpdateDebtPaymentRequest;
use App\Models\Debt;
use App\Models\DebtPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DebtPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        /** @var \App\Models\Debt $debt */
        $debt = $request->route('debt');
        Gate::authorize('view', $debt);

        $payments = $debt->payments()
            ->orderBy('payment_date', 'desc')
            ->paginate(10);

        return view('debt-payments.index', compact('debt', 'payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        /** @var \App\Models\Debt $debt */
        $debt = $request->route('debt');
        Gate::authorize('update', $debt);

        return view('debt-payments.create', compact('debt'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDebtPaymentRequest $request): RedirectResponse
    {
        /** @var \App\Models\Debt $debt */
        $debt = $request->route('debt');
        Gate::authorize('update', $debt);

        $payment = new DebtPayment($request->validated());
        $payment->user_id = auth()->id();
        $payment->debt_id = $debt->id;
        $payment->save();

        // Update debt status if fully paid
        $totalPaid = $debt->payments()->sum('amount');
        if ($totalPaid >= $debt->amount) {
            $debt->update(['is_paid' => true]);
        }

        return redirect()
            ->route('debts.show', $debt)
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DebtPayment $debtPayment): View
    {
        Gate::authorize('view', $debtPayment->debt);

        return view('debt-payments.show', compact('debtPayment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DebtPayment $debtPayment): View
    {
        Gate::authorize('update', $debtPayment->debt);

        return view('debt-payments.edit', compact('debtPayment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDebtPaymentRequest $request, DebtPayment $debtPayment): RedirectResponse
    {
        Gate::authorize('update', $debtPayment->debt);

        $debtPayment->update($request->validated());

        // Recalculate debt status
        /** @var \App\Models\Debt $debt */
        $debt = $debtPayment->debt;
        $totalPaid = $debt->payments()->sum('amount');
        $debt->update(['is_paid' => $totalPaid >= $debt->amount]);

        return redirect()
            ->route('debts.show', $debt)
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DebtPayment $debtPayment): RedirectResponse
    {
        Gate::authorize('update', $debtPayment->debt);

        /** @var \App\Models\Debt $debt */
        $debt = $debtPayment->debt;
        $debtPayment->delete();

        // Recalculate debt status
        $totalPaid = $debt->payments()->sum('amount');
        $debt->update(['is_paid' => $totalPaid >= $debt->amount]);

        return redirect()
            ->route('debts.show', $debt)
            ->with('success', 'Payment deleted successfully.');
    }
}
