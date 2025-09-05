@extends('layouts.app', ['currentPage' => 'transactions'])

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    <!-- Header -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-sm border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 z-40">
        <div class="px-4 py-6 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="p-2 bg-primary-100 dark:bg-primary-900 rounded-xl">
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Transactions</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Track your financial activity</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Search -->
                    <div class="relative group">
                        <input type="text" 
                               placeholder="Search transactions..." 
                               class="w-64 pl-10 pr-4 py-3 border-0 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:bg-white dark:focus:bg-gray-600 transition-all duration-200 shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Filter Button -->
                    <button class="p-3 text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-xl transition-all duration-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction List -->
    <div class="px-4 py-8 sm:px-6">
        @if($transactions->count() > 0)
            <div class="space-y-4">
                @foreach($transactions as $transaction)
                    <div class="group bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 p-6 hover:shadow-md hover:bg-white dark:hover:bg-gray-800 transition-all duration-300 hover:scale-[1.02]">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <!-- Transaction Type Icon -->
                                <div class="flex-shrink-0 relative">
                                    @if($transaction->type === 'income')
                                        <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900 dark:to-green-800 rounded-2xl flex items-center justify-center shadow-sm">
                                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                            </svg>
                                        </div>
                                    @elseif($transaction->type === 'expense')
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-sm
                                            @if($transaction->account->type === 'credit-card') bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900 dark:to-red-800
                                            @elseif($transaction->account->type === 'bank') bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800
                                            @elseif($transaction->account->type === 'cash') bg-gradient-to-br from-yellow-100 to-yellow-200 dark:from-yellow-900 dark:to-yellow-800
                                            @elseif($transaction->account->type === 'e-wallet') bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900 dark:to-purple-800
                                            @else bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900 dark:to-red-800 @endif">
                                            @if($transaction->account->type === 'credit-card')
                                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                            @elseif($transaction->account->type === 'bank')
                                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                            @elseif($transaction->account->type === 'cash')
                                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            @elseif($transaction->account->type === 'e-wallet')
                                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    @else
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800 rounded-2xl flex items-center justify-center shadow-sm">
                                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Transaction Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-3 mb-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                            {{ $transaction->payee ?: ($transaction->category ? $transaction->category->name : 'Transaction') }}
                                        </h3>
                                        @if($transaction->type === 'transfer')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800 text-blue-800 dark:text-blue-200 shadow-sm">
                                                Transfer
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-3 text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-medium">{{ $transaction->account->name }}</span>
                                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium 
                                                @if($transaction->account->type === 'bank') bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300
                                                @elseif($transaction->account->type === 'cash') bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300
                                                @elseif($transaction->account->type === 'e-wallet') bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300
                                                @elseif($transaction->account->type === 'credit-card') bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300
                                                @else bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 @endif">
                                                @if($transaction->account->type === 'credit-card') Credit Card
                                                @else {{ ucfirst($transaction->account->type) }}
                                                @endif
                                            </span>
                                        </div>
                                        @if($transaction->transferAccount)
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                </svg>
                                                <span class="font-medium">{{ $transaction->transferAccount->name }}</span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium 
                                                    @if($transaction->transferAccount->type === 'bank') bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300
                                                    @elseif($transaction->transferAccount->type === 'cash') bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300
                                                    @elseif($transaction->transferAccount->type === 'e-wallet') bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300
                                                    @elseif($transaction->transferAccount->type === 'credit-card') bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300
                                                    @else bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 @endif">
                                                    @if($transaction->transferAccount->type === 'credit-card') Credit Card
                                                    @else {{ ucfirst($transaction->transferAccount->type) }}
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                        @if($transaction->category && $transaction->type !== 'transfer')
                                            <div class="flex items-center space-x-2">
                                                <div class="w-1 h-1 bg-gray-400 rounded-full"></div>
                                                <span class="font-medium">{{ $transaction->category->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>{{ $transaction->occurred_on->format('M j, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Amount -->
                            <div class="flex-shrink-0 text-right min-w-0">
                                <div class="flex flex-col items-end">
                                    <p class="text-2xl font-bold {{ $transaction->type === 'income' ? 'text-green-600 dark:text-green-400' : ($transaction->type === 'expense' ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400') }} break-words tabular-nums">
                                        {{ $transaction->type === 'expense' ? '-' : ($transaction->type === 'income' ? '+' : '') }}{{ $transaction->account->currency_symbol }}{{ number_format($transaction->amount, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 break-words font-medium">{{ $transaction->account->currency }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if($transaction->note)
                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200/50 dark:border-gray-600/50">
                                <div class="flex items-start space-x-2">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $transaction->note }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Actions -->
                        <div class="mt-4 pt-4 border-t border-gray-200/50 dark:border-gray-700/50 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                @if($transaction->receipt_path)
                                    <a href="{{ Storage::url($transaction->receipt_path) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View Receipt
                                    </a>
                                @endif
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('transactions.edit', $transaction) }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200"
                                            onclick="return confirm('Are you sure you want to delete this transaction?')">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
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
            <div class="text-center py-20">
                <div class="mx-auto w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 rounded-3xl flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No transactions yet</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-sm mx-auto">Start tracking your finances by adding your first transaction.</p>
                <a href="{{ route('transactions.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Your First Transaction
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Floating Action Button -->
<div class="fixed bottom-20 right-4 z-50">
    <a href="{{ route('transactions.create') }}" 
       class="group w-16 h-16 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white rounded-2xl shadow-xl hover:shadow-2xl flex items-center justify-center transition-all duration-300 hover:scale-110">
        <svg class="w-7 h-7 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
    </a>
</div>
@endsection
