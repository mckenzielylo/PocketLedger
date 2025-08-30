<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Welcome Section -->
            <div class="text-center">
                <h1 class="text-4xl font-bold text-text-primary mb-2">
                    Welcome back, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-text-secondary text-lg">
                    Here's your financial overview for {{ now()->format('F Y') }}
                </p>
            </div>

            <!-- Simple Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="card bg-gradient-primary text-white">
                    <div class="card-body text-center">
                        <h3 class="text-sm font-medium text-white/80 mb-1">Total Balance</h3>
                        <p class="text-2xl font-bold">${{ number_format($totalBalance ?? 0, 2) }}</p>
                    </div>
                </div>

                <div class="card bg-gradient-success text-white">
                    <div class="card-body text-center">
                        <h3 class="text-sm font-medium text-white/80 mb-1">Monthly Income</h3>
                        <p class="text-2xl font-bold">${{ number_format($monthlyIncome ?? 0, 2) }}</p>
                    </div>
                </div>

                <div class="card bg-gradient-warning text-white">
                    <div class="card-body text-center">
                        <h3 class="text-sm font-medium text-white/80 mb-1">Monthly Expenses</h3>
                        <p class="text-2xl font-bold">${{ number_format($monthlyExpenses ?? 0, 2) }}</p>
                    </div>
                </div>

                <div class="card bg-gradient-accent text-white">
                    <div class="card-body text-center">
                        <h3 class="text-sm font-medium text-white/80 mb-1">Net Worth</h3>
                        <p class="text-2xl font-bold">${{ number_format($netWorth ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-xl font-semibold text-text-primary">Quick Actions</h2>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('transactions.create') }}" class="btn-primary text-center">
                            Add Transaction
                        </a>
                        
                        <a href="{{ route('accounts.create') }}" class="btn-secondary text-center">
                            Add Account
                        </a>
                        
                        <a href="{{ route('budgets.create') }}" class="btn-secondary text-center">
                            Create Budget
                        </a>
                        
                        <a href="{{ route('reports') }}" class="btn-secondary text-center">
                            View Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
