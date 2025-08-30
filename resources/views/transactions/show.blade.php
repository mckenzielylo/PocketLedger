@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('transactions.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Transaction Details</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">View transaction information</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('transactions.edit', $transaction) }}" 
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Edit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Details -->
    <div class="px-4 py-6 sm:px-6">
        <div class="space-y-6">
            <!-- Transaction Header -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Transaction Type Icon -->
                        <div class="flex-shrink-0">
                            @if($transaction->type === 'income')
                                <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                    </svg>
                                </div>
                            @elseif($transaction->type === 'expense')
                                <div class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Transaction Info -->
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $transaction->payee ?: ($transaction->category ? $transaction->category->name : 'Transaction') }}
                            </h2>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->type === 'income' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : ($transaction->type === 'expense' ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' : 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200') }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                                @if($transaction->type === 'transfer')
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Transfer</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Amount -->
                    <div class="text-right">
                        <p class="text-3xl font-bold {{ $transaction->type === 'income' ? 'text-green-600 dark:text-green-400' : ($transaction->type === 'expense' ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400') }}">
                            {{ $transaction->type === 'expense' ? '-' : '' }}{{ number_format($transaction->amount, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->account->currency }}</p>
                    </div>
                </div>
            </div>

            <!-- Transaction Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Transaction Details</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->occurred_on->format('F j, Y') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Account</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->account->name }}</dd>
                    </div>
                    
                    @if($transaction->category && $transaction->type !== 'transfer')
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->category->name }}</dd>
                        </div>
                    @endif
                    
                    @if($transaction->transferAccount)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Transfer To</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->transferAccount->name }}</dd>
                        </div>
                    @endif
                    
                    @if($transaction->payee)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payee/Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->payee }}</dd>
                        </div>
                    @endif
                    
                    @if($transaction->note)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Note</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->note }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Receipt -->
            @if($transaction->receipt_path)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Receipt</h3>
                    <div class="flex items-center justify-center p-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Receipt uploaded</p>
                            <a href="{{ Storage::url($transaction->receipt_path) }}" 
                               target="_blank"
                               class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-primary-700 dark:text-primary-400 bg-primary-100 dark:bg-primary-900 hover:bg-primary-200 dark:hover:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                View Receipt
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Created:</span>
                        <span class="text-sm text-gray-900 dark:text-white">{{ $transaction->created_at->format('M j, Y \a\t g:i A') }}</span>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('transactions.edit', $transaction) }}" 
                           class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Edit Transaction
                        </a>
                        
                        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    onclick="return confirm('Are you sure you want to delete this transaction? This action cannot be undone.')">
                                Delete Transaction
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
