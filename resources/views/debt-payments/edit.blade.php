@extends('layouts.app')

@section('title', 'Edit Payment - ' . $debtPayment->debt->name)

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Edit Payment
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Modify payment for: <span class="font-semibold">{{ $debtPayment->debt->name }}</span>
                    </p>
                </div>
                <a href="{{ route('debt-payments.index', $debtPayment->debt) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Payments
                </a>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Payment Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Original Amount</h4>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        ${{ number_format($debtPayment->amount, 2) }}
                    </p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Date</h4>
                    <p class="mt-1 text-lg font-medium text-gray-900 dark:text-white">
                        {{ $debtPayment->payment_date->format('M j, Y') }}
                    </p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Debt Remaining</h4>
                    <p class="mt-1 text-lg font-medium text-red-600">
                        ${{ number_format($debtPayment->debt->remainingAmount, 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Edit Payment Details</h2>
            </div>
            
            <form action="{{ route('debt-payments.update', $debtPayment) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Amount -->
                <div class="mb-6">
                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Payment Amount <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">{{ Auth::user()->preferred_currency_symbol }}</span>
                        </div>
                        <input type="number" 
                               name="amount" 
                               id="amount" 
                               step="0.01" 
                               min="0.01" 
                               value="{{ old('amount', $debtPayment->amount) }}"
                               class="block w-full pl-7 pr-12 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                               placeholder="0.00"
                               required>
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Date -->
                <div class="mb-6">
                    <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Payment Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="payment_date" 
                           id="payment_date" 
                           value="{{ old('payment_date', $debtPayment->payment_date->format('Y-m-d')) }}"
                           max="{{ now()->format('Y-m-d') }}"
                           class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                           required>
                    @error('payment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="mb-8">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Notes (Optional)
                    </label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="3"
                              class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                              placeholder="Add any notes about this payment...">{{ old('notes', $debtPayment->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('debt-payments.index', $debtPayment->debt) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
