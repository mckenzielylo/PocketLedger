<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Simple Profile Content -->
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-4xl">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    Welcome, {{ $user->name }}!
                </h1>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- User Info -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">User Info</h3>
                        <p class="text-gray-600 dark:text-gray-400">Name: {{ $user->name }}</p>
                        <p class="text-gray-600 dark:text-gray-400">Email: {{ $user->email }}</p>
                        <p class="text-gray-600 dark:text-gray-400">Member since: {{ $user->created_at->format('M Y') }}</p>
                    </div>
                    
                    <!-- Statistics -->
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Statistics</h3>
                        <p class="text-gray-600 dark:text-gray-400">Accounts: {{ $stats['accounts'] }}</p>
                        <p class="text-gray-600 dark:text-gray-400">Transactions: {{ $stats['transactions'] }}</p>
                        <p class="text-gray-600 dark:text-gray-400">Categories: {{ $stats['categories'] }}</p>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Quick Actions</h3>
                        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 block">Go to Dashboard</a>
                        <a href="{{ route('transactions.create') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 block">Add Transaction</a>
                        <a href="{{ route('accounts.create') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 block">Add Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
