<section>
    <header>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ __('Profile Overview') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Your personal information and PocketLedger statistics.') }}
        </p>
    </header>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- User Info Card -->
        <div class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-lg p-6 border border-primary-200 dark:border-primary-700">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    @if($user->settings['avatar'] ?? false)
                        <img class="w-16 h-16 rounded-full object-cover border-4 border-white dark:border-gray-700 shadow-lg" 
                             src="{{ Storage::url($user->settings['avatar']) }}" 
                             alt="{{ $user->name }}'s avatar">
                    @else
                        <div class="w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center text-white text-2xl font-bold border-4 border-white dark:border-gray-700 shadow-lg">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                    @if($user->settings['phone'] ?? false)
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->settings['phone'] }}</p>
                    @endif
                    @if($user->settings['birth_date'] ?? false)
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Born {{ \Carbon\Carbon::parse($user->settings['birth_date'])->format('M j, Y') }}
                        </p>
                    @endif
                    <p class="text-xs text-primary-600 dark:text-primary-400 mt-1">
                        Member since {{ $user->created_at->format('M Y') }}
                    </p>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="mt-4 grid grid-cols-2 gap-3">
                <div class="text-center">
                    <div class="text-lg font-bold text-primary-600 dark:text-primary-400">{{ $stats['transactions'] }}</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Transactions</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-bold text-primary-600 dark:text-primary-400">{{ $stats['accounts'] }}</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Accounts</div>
                </div>
            </div>

            <!-- Bio -->
            @if($user->settings['bio'] ?? false)
                <div class="mt-4 pt-4 border-t border-primary-200 dark:border-primary-700">
                    <p class="text-sm text-gray-700 dark:text-gray-300 italic">
                        "{{ $user->settings['bio'] }}"
                    </p>
                </div>
            @endif
        </div>

        <!-- Financial Summary Card -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg p-6 border border-green-200 dark:border-green-700">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Financial Summary</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Categories</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $stats['categories'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Budgets</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $stats['budgets'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Debts</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $stats['debts'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Assets</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $stats['assets'] }}</span>
                </div>
            </div>
        </div>

        <!-- Account Status Card -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-6 border border-blue-200 dark:border-blue-700">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Status</h4>
            <div class="space-y-3">
                <div class="flex items-center space-x-2">
                    @if($user->email_verified_at)
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Email Verified</span>
                    @else
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Email Unverified</span>
                    @endif
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Active Account</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Premium Features</span>
                </div>
            </div>
            
            <!-- Last Login -->
            <div class="mt-4 pt-3 border-t border-blue-200 dark:border-blue-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Last active: {{ $user->updated_at->diffForHumans() }}
                </p>
            </div>
        </div>
    </div>
</section>
