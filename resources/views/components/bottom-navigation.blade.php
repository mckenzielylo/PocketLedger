@props(['currentPage' => ''])

<!-- Bottom Navigation (Mobile) -->
<div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 md:hidden z-50">
    <div class="flex justify-around py-2">
        <!-- Home -->
        <a href="{{ route('dashboard') }}" 
           class="flex flex-col items-center py-2 px-3 {{ $currentPage === 'dashboard' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }} hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
            <span class="text-xs font-medium">Home</span>
        </a>
        
        <!-- Add Transaction -->
        <a href="{{ route('transactions.create') }}" 
           class="flex flex-col items-center py-2 px-3 {{ $currentPage === 'add' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }} hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span class="text-xs font-medium">Add</span>
        </a>
        
        <!-- Reports -->
        <a href="{{ route('reports') }}" 
           class="flex flex-col items-center py-2 px-3 {{ $currentPage === 'reports' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }} hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="text-xs font-medium">Reports</span>
        </a>
        
        <!-- Profile -->
        <a href="{{ route('profile.edit') }}" 
           class="flex flex-col items-center py-2 px-3 {{ $currentPage === 'profile' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }} hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-xs font-medium">Profile</span>
        </a>
    </div>
</div>

<!-- Add bottom padding to main content to account for fixed navigation -->
<div class="h-20 md:hidden"></div>
