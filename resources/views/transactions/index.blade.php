@extends('layouts.app', ['currentPage' => 'transactions'])

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Transactions</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your income, expenses, and transfers</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" 
                               placeholder="Search transactions..." 
                               class="w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Filter Button -->
                    <button class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction List -->
    <div class="px-4 py-6 sm:px-6">
        @if($transactions->count() > 0)
            <div class="space-y-3">
                @foreach($transactions as $transaction)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <!-- Transaction Type Icon -->
                                <div class="flex-shrink-0">
                                    @if($transaction->type === 'income')
                                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                            </svg>
                                        </div>
                                    @elseif($transaction->type === 'expense')
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                                            @if($transaction->account->type === 'credit-card') bg-red-100 dark:bg-red-900
                                            @elseif($transaction->account->type === 'bank') bg-blue-100 dark:bg-blue-900
                                            @elseif($transaction->account->type === 'cash') bg-yellow-100 dark:bg-yellow-900
                                            @elseif($transaction->account->type === 'e-wallet') bg-purple-100 dark:bg-purple-900
                                            @else bg-red-100 dark:bg-red-900 @endif">
                                            @if($transaction->account->type === 'credit-card')
                                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                            @elseif($transaction->account->type === 'bank')
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                            @elseif($transaction->account->type === 'cash')
                                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            @elseif($transaction->account->type === 'e-wallet')
                                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Transaction Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $transaction->payee ?: ($transaction->category ? $transaction->category->name : 'Transaction') }}
                                        </p>
                                        @if($transaction->type === 'transfer')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                                Transfer
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span>{{ $transaction->account->name }}</span>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium 
                                            @if($transaction->account->type === 'bank') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                            @elseif($transaction->account->type === 'cash') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                            @elseif($transaction->account->type === 'e-wallet') bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200
                                            @elseif($transaction->account->type === 'credit-card') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                            @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 @endif">
                                            @if($transaction->account->type === 'credit-card') Credit Card
                                            @else {{ ucfirst($transaction->account->type) }}
                                            @endif
                                        </span>
                                        @if($transaction->transferAccount)
                                            <span>→</span>
                                            <span>{{ $transaction->transferAccount->name }}</span>
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium 
                                                @if($transaction->transferAccount->type === 'bank') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                                @elseif($transaction->transferAccount->type === 'cash') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                                @elseif($transaction->transferAccount->type === 'e-wallet') bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200
                                                @elseif($transaction->transferAccount->type === 'credit-card') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                                @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 @endif">
                                                @if($transaction->transferAccount->type === 'credit-card') Credit Card
                                                @else {{ ucfirst($transaction->transferAccount->type) }}
                                                @endif
                                            </span>
                                        @endif
                                        @if($transaction->category && $transaction->type !== 'transfer')
                                            <span>•</span>
                                            <span>{{ $transaction->category->name }}</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ $transaction->occurred_on->format('M j, Y') }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Amount -->
                            <div class="flex-shrink-0 text-right min-w-0">
                                <p class="text-lg font-semibold {{ $transaction->type === 'income' ? 'text-green-600 dark:text-green-400' : ($transaction->type === 'expense' ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400') }} break-words">
                                    {{ $transaction->type === 'expense' ? '-' : '' }}{{ $transaction->account->currency_symbol }} {{ number_format($transaction->amount, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 break-words">{{ $transaction->account->currency }}</p>
                            </div>
                        </div>
                        
                        @if($transaction->note)
                            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $transaction->note }}</p>
                            </div>
                        @endif
                        
                        <!-- Actions -->
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                @if($transaction->receipt_path)
                                    <a href="{{ Storage::url($transaction->receipt_path) }}" 
                                       target="_blank"
                                       class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 inline-flex items-center">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View Receipt
                                    </a>
                                @endif
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('transactions.edit', $transaction) }}" 
                                   class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                    Edit
                                </a>
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200"
                                            onclick="return confirm('Are you sure you want to delete this transaction?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No transactions</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first transaction.</p>
                <div class="mt-6">
                    <a href="{{ route('transactions.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Transaction
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Floating Action Button -->
<div class="fixed bottom-20 right-4 z-50">
    <a href="{{ route('transactions.create') }}" 
       class="w-14 h-14 bg-primary-600 hover:bg-primary-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors duration-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
    </a>
</div>
@endsection
