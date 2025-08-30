@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow">
        <div class="px-4 py-6 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $debt->name }}</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ ucfirst($debt->type) }} • {{ $debt->account->name }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('debts.edit', $debt) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('debts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Debts
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="px-4 py-6 sm:px-6">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <p class="text-sm text-green-600 dark:text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Debt Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Debt Overview -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Debt Overview</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Original Amount</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ Auth::user()->preferred_currency_symbol }} {{ number_format($debt->amount, 0, ',', '.') }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Paid</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ Auth::user()->preferred_currency_symbol }} {{ number_format($totalPaid, 0, ',', '.') }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Remaining</p>
                            <p class="text-2xl font-bold 
                                @if($remainingAmount <= 0) text-green-600 dark:text-green-400
                                @else text-yellow-600 dark:text-yellow-400 @endif">
                                {{ Auth::user()->preferred_currency_symbol }} {{ number_format($remainingAmount, 0, ',', '.') }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                            <div class="mt-1">
                                @if($debt->is_paid)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Paid
                                    </span>
                                @elseif($debt->due_date && $debt->due_date->isPast())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Overdue
                                    </span>
                                @elseif($debt->due_date && $debt->due_date->diffInDays(now()) <= 7)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        Due Soon
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        Active
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Payment Progress -->
                    @if($debt->amount > 0)
                        <div class="mt-6">
                            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <span>Payment Progress</span>
                                <span class="font-medium">{{ number_format($paymentProgress, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                <div class="h-3 rounded-full transition-all duration-300 
                                    @if($paymentProgress >= 100) bg-green-500
                                    @elseif($paymentProgress >= 50) bg-yellow-500
                                    @else bg-blue-500 @endif"
                                     style="width: {{ $paymentProgress }}%">
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Additional Details -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($debt->interest_rate && $debt->interest_rate > 0)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Interest Rate</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $debt->interest_rate }}% per year</p>
                                @if($totalInterest > 0)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Accumulated: {{ Auth::user()->preferred_currency_symbol }} {{ number_format($totalInterest, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        @endif
                        
                        @if($debt->due_date)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Due Date</p>
                                <p class="text-lg font-semibold 
                                    @if($debt->due_date->isPast()) text-red-600 dark:text-red-400
                                    @elseif($debt->due_date->diffInDays(now()) <= 7) text-yellow-600 dark:text-yellow-400
                                    @else text-gray-900 dark:text-white @endif">
                                    {{ $debt->due_date->format('F d, Y') }}
                                </p>
                                @if($debt->due_date->isPast())
                                    <p class="text-sm text-red-500 dark:text-red-400">
                                        {{ $debt->due_date->diffForHumans() }} overdue
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Due {{ $debt->due_date->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if($debt->description)
                        <div class="mt-6">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Description</p>
                            <p class="text-gray-900 dark:text-white">{{ $debt->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Payment History -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Payment History</h2>
                        <div class="flex items-center space-x-3">
                            @if($debt->payments->count() > 0)
                                <a href="{{ route('debt-payments.index', $debt) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    View All
                                </a>
                            @endif
                            @if(!$debt->is_paid)
                                <button onclick="document.getElementById('payment-modal').classList.remove('hidden')" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Record Payment
                                </button>
                            @endif
                        </div>
                    </div>

                    @if($debt->payments->count() > 0)
                        <div class="space-y-3">
                            @foreach($debt->payments as $payment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ Auth::user()->preferred_currency_symbol }} {{ number_format($payment->amount, 0, ',', '.') }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $payment->payment_date->format('M d, Y') }}
                                            @if($payment->notes) • {{ $payment->notes }} @endif
                                        </p>
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $payment->payment_date->diffForHumans() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No payments yet</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Record your first payment to start tracking progress.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        @if(!$debt->is_paid)
                            <form action="{{ route('debts.mark-paid', $debt) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Mark as Paid
                                </button>
                            </form>
                        @else
                            <form action="{{ route('debts.mark-unpaid', $debt) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Mark as Unpaid
                                </button>
                            </form>
                        @endif

                        @if(!$debt->payments()->exists())
                            <form action="{{ route('debts.destroy', $debt) }}" method="POST" class="w-full" onsubmit="return confirm('Are you sure you want to delete this debt?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete Debt
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Debt Info -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Debt Information</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Type</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($debt->type) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Account</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $debt->account->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Created</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $debt->created_at->format('M d, Y') }}</dd>
                        </div>
                        @if($debt->interest_rate && $debt->interest_rate > 0)
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Interest Rate</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $debt->interest_rate }}% annually</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="payment-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Record Payment</h3>
            <form action="{{ route('debts.payments.store', $debt) }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="payment_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Amount</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">{{ Auth::user()->preferred_currency_symbol }}</span>
                        </div>
                        <input type="number" name="amount" id="payment_amount" step="0.01" min="0.01" max="{{ $remainingAmount }}" required
                            class="block w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="0.00">
                    </div>
                </div>

                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Date</label>
                    <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" required
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <div>
                    <label for="payment_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes (Optional)</label>
                    <textarea name="notes" id="payment_notes" rows="2"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        placeholder="Payment notes..."></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="document.getElementById('payment-modal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">Cancel</button>
                    <button type="submit" class="px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

