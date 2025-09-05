@extends('layouts.app', ['currentPage' => 'dashboard'])

@section('content')
<x-page-layout 
    :title="'Welcome back, ' . Auth::user()->name . '! 👋'" 
    :description="'Here\'s your financial overview for ' . now()->format('F Y')"
    :icon="'<svg class=\"w-6 h-6 text-primary\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z\"></path><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z\"></path></svg>'"
>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Balance -->
        <x-ui.card class="bg-gradient-to-br from-primary to-primary/80 text-primary-foreground">
            <x-ui.card-content class="text-center">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-lg mb-4 mx-auto">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-white/80 mb-1">Total Balance</h3>
                <p class="text-2xl font-bold">{{ Auth::user()->preferred_currency_symbol }}{{ number_format($totalBalance, 2) }}</p>
                <div class="mt-2 text-sm text-white/70">
                    Across {{ $accounts->count() }} accounts
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Monthly Income -->
        <x-ui.card class="bg-gradient-to-br from-green-500 to-green-600 text-white">
            <x-ui.card-content class="text-center">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-lg mb-4 mx-auto">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-white/80 mb-1">Monthly Income</h3>
                <p class="text-2xl font-bold">{{ Auth::user()->preferred_currency_symbol }}{{ number_format($monthlyIncome, 2) }}</p>
                <div class="mt-2 text-sm text-white/70">
                    This month
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Monthly Expenses -->
        <x-ui.card class="bg-gradient-to-br from-orange-500 to-orange-600 text-white">
            <x-ui.card-content class="text-center">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-lg mb-4 mx-auto">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 13l5 5m0 0l5-5m-5 5V6"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-white/80 mb-1">Monthly Expenses</h3>
                <p class="text-2xl font-bold">{{ Auth::user()->preferred_currency_symbol }}{{ number_format($monthlyExpenses, 2) }}</p>
                <div class="mt-2 text-sm text-white/70">
                    This month
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Net Worth -->
        <x-ui.card class="bg-gradient-to-br from-purple-500 to-purple-600 text-white">
            <x-ui.card-content class="text-center">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-lg mb-4 mx-auto">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-white/80 mb-1">Net Worth</h3>
                <p class="text-2xl font-bold">{{ Auth::user()->preferred_currency_symbol }}{{ number_format($netWorth, 2) }}</p>
                <div class="mt-2 text-sm text-white/70">
                    Total assets
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    <!-- Quick Actions -->
    <x-ui.card>
        <x-ui.card-header>
            <x-ui.card-title>Quick Actions</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <x-ui.button tag="a" href="{{ route('transactions.create') }}" class="flex flex-col items-center py-4">
                    <svg class="w-5 h-5 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Transaction
                </x-ui.button>
                
                <x-ui.button tag="a" href="{{ route('accounts.create') }}" variant="secondary" class="flex flex-col items-center py-4">
                    <svg class="w-5 h-5 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Account
                </x-ui.button>
                
                <x-ui.button tag="a" href="{{ route('budgets.create') }}" variant="secondary" class="flex flex-col items-center py-4">
                    <svg class="w-5 h-5 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Budget
                </x-ui.button>
                
                <x-ui.button tag="a" href="{{ route('reports') }}" variant="secondary" class="flex flex-col items-center py-4">
                    <svg class="w-5 h-5 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    View Reports
                </x-ui.button>
            </div>
        </x-ui.card-content>
    </x-ui.card>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Accounts Summary -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Account Summary</h2>
                    <a href="{{ route('accounts.index') }}" class="text-primary-400 hover:text-primary-300 text-sm font-medium">
                        View All →
                    </a>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        @forelse($accounts->take(5) as $account)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center 
                                        @if($account->type === 'bank') bg-blue-500
                                        @elseif($account->type === 'cash') bg-yellow-500
                                        @elseif($account->type === 'e-wallet') bg-purple-500
                                        @elseif($account->type === 'credit-card') bg-red-500
                                        @else bg-gray-500 @endif">
                                        @if($account->type === 'bank')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                        @elseif($account->type === 'cash')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        @elseif($account->type === 'e-wallet')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                        @elseif($account->type === 'credit-card')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-900 dark:text-white">{{ $account->name }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            @if($account->type === 'credit-card')
                                                Credit Card
                                            @else
                                                {{ ucfirst($account->type) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $account->currency_symbol }} {{ number_format($account->current_balance, 2) }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $account->currency }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                                                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No accounts yet</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">Create your first account to get started</p>
                                <a href="{{ route('accounts.create') }}" class="btn-primary">
                                    Create Account
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Recent Transactions</h2>
                    <a href="{{ route('transactions.index') }}" class="text-primary-400 hover:text-primary-300 text-sm font-medium">
                        View All →
                    </a>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        @forelse($recentTransactions->take(5) as $transaction)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $transaction->isIncome ? 'bg-success-500/20' : 'bg-warning-500/20' }}">
                                        <svg class="w-4 h-4 {{ $transaction->isIncome ? 'text-success-400' : 'text-warning-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $transaction->isIncome ? 'M7 11l5-5m0 0l5 5m-5-5v12' : 'M7 13l5 5m0 0l5-5m-5 5V6' }}"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ Str::limit($transaction->payee ?: ($transaction->category ? $transaction->category->name : 'Transfer'), 20) }}</h4>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $transaction->account ? $transaction->account->name : 'Unknown Account' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold {{ $transaction->isIncome ? 'text-success-400' : 'text-warning-400' }}">
                                        {{ $transaction->isIncome ? '+' : '-' }}{{ $transaction->account->currency_symbol }} {{ number_format($transaction->amount, 2) }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $transaction->occurred_on->format('M j') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">No transactions yet</h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Start tracking your finances</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Budget Summary -->
            @if($budgets->count() > 0)
                <div class="card mt-6">
                    <div class="card-header flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Budget Overview</h2>
                        <a href="{{ route('budgets.index') }}" class="text-primary-400 hover:text-primary-300 text-sm font-medium">
                            View All →
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            @foreach($budgets->take(3) as $budget)
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $budget->month }}</span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ Auth::user()->preferred_currency_symbol }}{{ number_format($budget->total_spent, 2) }} / {{ Auth::user()->preferred_currency_symbol }}{{ number_format($budget->total_limit, 2) }}</span>
                                    </div>
                                    <!-- Enhanced Progress Bar -->
                                    <div class="relative">
                                        <!-- Background Gridlines -->
                                        <div class="absolute inset-0 flex justify-between items-center pointer-events-none">
                                            <div class="w-px h-full bg-gray-300 dark:bg-gray-600 opacity-20"></div>
                                            <div class="w-px h-full bg-gray-300 dark:bg-gray-600 opacity-20" style="left: 25%"></div>
                                            <div class="w-px h-full bg-gray-300 dark:bg-gray-600 opacity-20" style="left: 50%"></div>
                                            <div class="w-px h-full bg-gray-300 dark:bg-gray-600 opacity-20" style="left: 75%"></div>
                                            <div class="w-px h-full bg-gray-300 dark:bg-gray-600 opacity-20" style="right: 0"></div>
                                        </div>
                                        
                                        <!-- Progress Bar Container -->
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 relative overflow-hidden">
                                            <div class="dashboard-progress-bar h-2 rounded-full transition-all duration-800 ease-out dashboard-progress-bar-success" 
                                                    style="width: {{ min(100, ($budget->total_spent / $budget->total_limit) * 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-page-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate dashboard progress bars on page load
    const dashboardProgressBars = document.querySelectorAll('.dashboard-progress-bar');
    
    dashboardProgressBars.forEach((bar, index) => {
        // Reset width to 0 for animation
        const originalWidth = bar.style.width;
        bar.style.width = '0%';
        
        // Animate to original width with staggered delay
        setTimeout(() => {
            bar.style.transition = 'width 1s cubic-bezier(0.4, 0, 0.2, 1)';
            bar.style.width = originalWidth;
        }, index * 150); // Stagger animation by 150ms per bar
    });
    
    // Add hover effects for better interactivity
    dashboardProgressBars.forEach(bar => {
        bar.addEventListener('mouseenter', function() {
            this.style.transform = 'scaleY(1.1)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        bar.addEventListener('mouseleave', function() {
            this.style.transform = 'scaleY(1)';
        });
    });
});
</script>
@endsection
