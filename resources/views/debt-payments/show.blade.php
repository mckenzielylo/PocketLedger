@extends('layouts.app')

@section('title', 'Payment Details - ' . $debtPayment->debt->name)

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Payment Details
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Payment for: <span class="font-semibold">{{ $debtPayment->debt->name }}</span>
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('debt-payments.index', $debtPayment->debt) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Payments
                    </a>
                    <a href="{{ route('debt-payments.edit', $debtPayment) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Payment
                    </a>
                </div>
            </div>
        </div>

        <!-- Payment Details Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Payment Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Payment Information</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount</dt>
                            <dd class="mt-1 text-3xl font-bold text-green-600">
                                ${{ number_format($debtPayment->amount, 2) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Date</dt>
                            <dd class="mt-1 text-lg text-gray-900 dark:text-white">
                                {{ $debtPayment->payment_date->format('l, F j, Y') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Recorded On</dt>
                            <dd class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ $debtPayment->created_at->format('M j, Y \a\t g:i A') }}
                            </dd>
                        </div>
                        @if($debtPayment->notes)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">
                                    {{ $debtPayment->notes }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <!-- Debt Context -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Debt Context</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Debt Name</dt>
                            <dd class="mt-1 text-lg text-gray-900 dark:text-white">
                                {{ $debtPayment->debt->name }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Debt Amount</dt>
                            <dd class="mt-1 text-lg text-gray-900 dark:text-white">
                                ${{ number_format($debtPayment->debt->amount, 2) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Paid</dt>
                            <dd class="mt-1 text-lg text-green-600">
                                ${{ number_format($debtPayment->debt->totalPaid, 2) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Remaining Balance</dt>
                            <dd class="mt-1 text-lg text-red-600">
                                ${{ number_format($debtPayment->debt->remainingAmount, 2) }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Progress Visualization -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Payment Progress</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <span>Payment Progress</span>
                        <span>{{ $debtPayment->debt->paymentProgress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div class="bg-primary-600 h-3 rounded-full transition-all duration-300" 
                             style="width: {{ $debtPayment->debt->paymentProgress }}%"></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            ${{ number_format($debtPayment->debt->amount, 2) }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Original Debt</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                        <div class="text-2xl font-bold text-green-600">
                            ${{ number_format($debtPayment->debt->totalPaid, 2) }}
                        </div>
                        <div class="text-sm text-green-600 dark:text-green-400">Total Paid</div>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                        <div class="text-2xl font-bold text-red-600">
                            ${{ number_format($debtPayment->debt->remainingAmount, 2) }}
                        </div>
                        <div class="text-sm text-red-600 dark:text-red-400">Remaining</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Actions</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('debt-payments.edit', $debtPayment) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Payment
                </a>
                
                <form action="{{ route('debt-payments.destroy', $debtPayment) }}" method="POST" class="inline" 
                      onsubmit="return confirm('Are you sure you want to delete this payment? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Payment
                    </button>
                </form>
                
                <a href="{{ route('debts.show', $debtPayment->debt) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    View Debt Details
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
