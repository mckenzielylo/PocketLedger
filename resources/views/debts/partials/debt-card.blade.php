<div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6 hover:shadow-md transition-shadow">
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <div class="flex items-center space-x-3 mb-2">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full 
                        @if($debt->type === 'borrowed') bg-red-100 dark:bg-red-900
                        @else bg-green-100 dark:bg-green-900 @endif 
                        flex items-center justify-center">
                        <svg class="w-5 h-5 
                            @if($debt->type === 'borrowed') text-red-600 dark:text-red-400
                            @else text-green-600 dark:text-green-400 @endif" 
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($debt->type === 'borrowed')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            @endif
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ $debt->name }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ ucfirst($debt->type) }} • {{ $debt->account->name }}
                    </p>
                </div>
            </div>
            
            @if($debt->description)
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                    {{ $debt->description }}
                </p>
            @endif
        </div>
        
        <!-- Status Badge -->
        <div class="flex-shrink-0">
            @if($debt->is_paid)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                    Paid
                </span>
            @elseif($debt->due_date && $debt->due_date->isPast())
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                    Overdue
                </span>
            @elseif($debt->due_date && $debt->due_date->diffInDays(now()) <= 7)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                    Due Soon
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                    Active
                </span>
            @endif
        </div>
    </div>

    <!-- Debt Details -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Amount</p>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                IDR {{ number_format($debt->amount, 0, ',', '.') }}
            </p>
        </div>
        
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Paid</p>
            <p class="text-lg font-semibold text-green-600 dark:text-green-400">
                IDR {{ number_format($debt->payments->sum('amount'), 0, ',', '.') }}
            </p>
        </div>
        
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Remaining</p>
            <p class="text-lg font-semibold 
                @if($debt->payments->sum('amount') >= $debt->amount) text-green-600 dark:text-green-400
                @else text-yellow-600 dark:text-yellow-400 @endif">
                IDR {{ number_format($debt->amount - $debt->payments->sum('amount'), 0, ',', '.') }}
            </p>
        </div>
        
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Due Date</p>
            <p class="text-lg font-semibold 
                @if($debt->due_date)
                    @if($debt->due_date->isPast()) text-red-600 dark:text-red-400
                    @elseif($debt->due_date->diffInDays(now()) <= 7) text-yellow-600 dark:text-yellow-400
                    @else text-gray-900 dark:text-white @endif
                @else text-gray-500 dark:text-gray-400 @endif">
                @if($debt->due_date)
                    {{ $debt->due_date->format('M d, Y') }}
                @else
                    No due date
                @endif
            </p>
        </div>
    </div>

    <!-- Payment Progress -->
    @if($debt->amount > 0)
        <div class="mb-4">
            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                <span>Payment Progress</span>
                <span class="font-medium">
                    {{ number_format(($debt->payments->sum('amount') / $debt->amount) * 100, 1) }}%
                </span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                <div class="h-2 rounded-full transition-all duration-300 
                    @if(($debt->payments->sum('amount') / $debt->amount) >= 1) bg-green-500
                    @elseif(($debt->payments->sum('amount') / $debt->amount) >= 0.5) bg-yellow-500
                    @else bg-blue-500 @endif"
                     style="width: {{ min(($debt->payments->sum('amount') / $debt->amount) * 100, 100) }}%">
                </div>
            </div>
        </div>
    @endif

    <!-- Interest Rate -->
    @if($debt->interest_rate && $debt->interest_rate > 0)
        <div class="mb-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
            <div class="flex items-center">
                <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <span class="text-sm text-yellow-800 dark:text-yellow-200">
                    Interest Rate: {{ $debt->interest_rate }}% per year
                </span>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex space-x-2">
            <a href="{{ route('debts.show', $debt) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                View Details
            </a>
            <a href="{{ route('debts.edit', $debt) }}" class="text-gray-600 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 text-sm font-medium">
                Edit
            </a>
        </div>
        
        <div class="flex space-x-2">
            @if(!$debt->is_paid)
                <form action="{{ route('debts.mark-paid', $debt) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-sm text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300">
                        Mark Paid
                    </button>
                </form>
            @else
                <form action="{{ route('debts.mark-unpaid', $debt) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-sm text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-300">
                        Mark Unpaid
                    </button>
                </form>
            @endif
            
            @if(!$debt->payments()->exists())
                <form action="{{ route('debts.destroy', $debt) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this debt?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                        Delete
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

