@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">PocketLedger</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Personal Finance Manager</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button id="theme-toggle" class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"/>
                        </svg>
                    </button>
                    <div class="relative">
                        <button class="flex items-center space-x-2 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Total Balance Card -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-6 mb-6">
            <div class="text-center">
                <h2 class="text-lg font-medium text-white mb-2">Total Balance</h2>
                <p class="text-3xl font-bold text-white">IDR {{ number_format($totalBalance, 0, ',', '.') }}</p>
                <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-blue-100 text-sm">Assets</p>
                        <p class="text-white font-semibold">IDR {{ number_format($totalAssets, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-blue-100 text-sm">Debts</p>
                        <p class="text-white font-semibold">IDR {{ number_format($totalDebt, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-blue-100 text-sm">Net Worth</p>
                        <p class="text-white font-semibold">IDR {{ number_format($netWorth, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <a href="{{ route('transactions.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg text-center transition-colors">
                <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add Transaction
            </a>
            <a href="{{ route('reports') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg text-center transition-colors">
                <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                View Reports
            </a>
        </div>

        <!-- Accounts Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Accounts</h3>
            <div class="space-y-3">
                @foreach($accounts as $account)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 rounded-full 
                            @if($account->type === 'cash') bg-green-500
                            @elseif($account->type === 'bank') bg-blue-500
                            @else bg-purple-500
                            @endif">
                        </div>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $account->name }}</span>
                    </div>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        IDR {{ number_format($account->current_balance, 0, ',', '.') }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Transactions</h3>
                <a href="{{ route('transactions.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentTransactions as $transaction)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 rounded-full 
                            @if($transaction->type === 'income') bg-green-500
                            @elseif($transaction->type === 'expense') bg-red-500
                            @else bg-blue-500
                            @endif">
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">
                                @if($transaction->payee)
                                    {{ $transaction->payee }}
                                @else
                                    {{ $transaction->type }}
                                @endif
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $transaction->account->name }}
                                @if($transaction->category)
                                    • {{ $transaction->category->name }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900 dark:text-white 
                            @if($transaction->type === 'income') text-green-600
                            @elseif($transaction->type === 'expense') text-red-600
                            @else text-blue-600
                            @endif">
                            @if($transaction->type === 'expense')-@endif
                            IDR {{ number_format($transaction->amount, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $transaction->occurred_on->format('M d') }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p>No transactions yet</p>
                    <p class="text-sm">Start by adding your first transaction</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Budget Summary -->
        @if($budget)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Budget ({{ now()->format('M Y') }})</h3>
            <div class="space-y-3">
                @foreach($budget->budgetCategories as $budgetCategory)
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">{{ $budgetCategory->category->name }}</span>
                        <span class="text-gray-900 dark:text-white">
                            IDR {{ number_format($budgetCategory->total_spent, 0, ',', '.') }} / 
                            IDR {{ number_format($budgetCategory->limit_amount, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="h-2 rounded-full 
                            @if($budgetCategory->usage_percentage > 100) bg-red-500
                            @elseif($budgetCategory->usage_percentage > 80) bg-yellow-500
                            @else bg-green-500
                            @endif"
                            style="width: {{ min($budgetCategory->usage_percentage, 100) }}%">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Bottom Navigation (Mobile) -->
    <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 md:hidden">
        <div class="flex justify-around py-2">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-2 px-3 text-blue-600">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <span class="text-xs">Home</span>
            </a>
            <a href="{{ route('transactions.create') }}" class="flex flex-col items-center py-2 px-3 text-gray-600 dark:text-gray-400">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span class="text-xs">Add</span>
            </a>
            <a href="{{ route('reports') }}" class="flex flex-col items-center py-2 px-3 text-gray-600 dark:text-gray-400">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="text-xs">Reports</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center py-2 px-3 text-gray-600 dark:text-gray-400">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-xs">Profile</span>
            </a>
        </div>
    </div>
</div>

<script>
// Theme toggle functionality
document.getElementById('theme-toggle').addEventListener('click', function() {
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.theme = 'light';
    } else {
        document.documentElement.classList.add('dark');
        localStorage.theme = 'dark';
    }
});

// Check for saved theme preference or default to light mode
if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark');
}
</script>
@endsection
