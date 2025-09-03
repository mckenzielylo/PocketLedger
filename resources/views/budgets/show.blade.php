@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('budgets.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::createFromDate(explode('-', $budget->month)[0], explode('-', $budget->month)[1], 1)->format('F Y') }} Budget
                        </h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Track your spending progress</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('budgets.edit', $budget) }}" 
                       class="text-sm px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        Edit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Budget Overview -->
    <div class="px-4 py-6 sm:px-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="grid grid-cols-2 gap-6">
                <div class="text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Budget</p>
                                            <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ Auth::user()->preferred_currency_symbol }} {{ number_format($budget->total_limit, 0, ',', '.') }}
                        </p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Spent</p>
                    @php
                        $totalSpent = $budget->budgetCategories->sum('spending');
                        $totalPercentage = $budget->total_limit > 0 ? ($totalSpent / $budget->total_limit) * 100 : 0;
                    @endphp
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ Auth::user()->preferred_currency_symbol }} {{ number_format($totalSpent, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            
            <!-- Overall Progress Bar -->
            <div class="mt-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Overall Progress</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($totalPercentage, 1) }}%</span>
                </div>
                
                <!-- Enhanced Progress Bar with Gridlines -->
                <div class="relative">
                    <!-- Background Gridlines -->
                    <div class="absolute inset-0 flex justify-between items-center pointer-events-none">
                        <div class="w-px h-full bg-gray-300 dark:bg-gray-600 opacity-30"></div>
                        <div class="w-px h-full bg-gray-300 dark:bg-gray-600 opacity-30" style="left: 25%"></div>
                        <div class="w-px h-full bg-gray-300 dark:bg-gray-600 opacity-30" style="left: 50%"></div>
                        <div class="w-px h-full bg-gray-300 dark:bg-gray-600 opacity-30" style="left: 75%"></div>
                        <div class="w-px h-full bg-gray-300 dark:bg-gray-600 opacity-30" style="right: 0"></div>
                    </div>
                    
                    <!-- Progress Bar Container -->
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4 relative overflow-hidden">
                        <div class="progress-bar h-4 rounded-full transition-all duration-1000 ease-out @if($totalPercentage > 100) progress-bar-danger @elseif($totalPercentage >= 80) progress-bar-warning @else progress-bar-success @endif" 
                             style="width: {{ min($totalPercentage, 100) }}%"></div>
                    </div>
                    
                    <!-- Percentage Markers -->
                    <div class="absolute -top-6 left-0 w-full flex justify-between text-xs text-gray-400 dark:text-gray-500">
                        <span>0%</span>
                        <span>25%</span>
                        <span>50%</span>
                        <span>75%</span>
                        <span>100%</span>
                    </div>
                </div>
                
                <div class="flex justify-between mt-6">
                    <span class="text-xs text-gray-500 dark:text-gray-400">0</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($budget->total_limit, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Category Breakdown -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Category Breakdown</h3>
            <div class="space-y-4">
                @foreach($budget->budgetCategories as $budgetCategory)
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 rounded-full 
                                    @if($budgetCategory->spending > $budgetCategory->limit_amount) bg-red-500
                                    @elseif($budgetCategory->percentage >= 80) bg-yellow-500
                                    @else bg-green-500
                                    @endif">
                                </div>
                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $budgetCategory->category->name }}</h4>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ Auth::user()->preferred_currency_symbol }} {{ number_format($budgetCategory->spending, 0, ',', '.') }}
                                    <span class="text-gray-500 dark:text-gray-400">/ {{ number_format($budgetCategory->limit_amount, 0, ',', '.') }}</span>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Enhanced Progress Bar -->
                        <div class="mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Progress</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($budgetCategory->percentage, 1) }}%</span>
                            </div>
                            
                            <!-- Enhanced Progress Bar with Gridlines -->
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
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 relative overflow-hidden">
                                    <div class="category-progress-bar h-3 rounded-full transition-all duration-800 ease-out @if($budgetCategory->spending > $budgetCategory->limit_amount) category-progress-bar-danger @elseif($budgetCategory->percentage >= 80) category-progress-bar-warning @else category-progress-bar-success @endif" 
                                         style="width: {{ min($budgetCategory->percentage, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status and Remaining -->
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center space-x-2">
                                @if($budgetCategory->spending > $budgetCategory->limit_amount)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                        Over Budget
                                    </span>
                                @elseif($budgetCategory->percentage >= 80)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                        Near Limit
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        On Track
                                    </span>
                                @endif
                            </div>
                            
                            <div class="text-right">
                                @if($budgetCategory->remaining > 0)
                                    <p class="text-green-600 dark:text-green-400">
                                        {{ Auth::user()->preferred_currency_symbol }} {{ number_format($budgetCategory->remaining, 0, ',', '.') }} remaining
                                    </p>
                                @else
                                    <p class="text-red-600 dark:text-red-400">
                                        {{ Auth::user()->preferred_currency_symbol }} {{ number_format(abs($budgetCategory->remaining), 0, ',', '.') }} over
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Budget Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mt-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Budget Summary</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total Budget</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ Auth::user()->preferred_currency_symbol }} {{ number_format($budget->total_limit, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total Spent</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ Auth::user()->preferred_currency_symbol }} {{ number_format($totalSpent, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Remaining</span>
                    @php
                        $remaining = $budget->total_limit - $totalSpent;
                    @endphp
                    <span class="font-medium @if($remaining >= 0) text-green-600 dark:text-green-400 @else text-red-600 dark:text-red-400 @endif">
                        {{ Auth::user()->preferred_currency_symbol }} {{ number_format(abs($remaining), 0, ',', '.') }} {{ $remaining >= 0 ? 'remaining' : 'over' }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Categories</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $budget->budgetCategories->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bars on page load
    const progressBars = document.querySelectorAll('.progress-bar, .category-progress-bar');
    
    progressBars.forEach((bar, index) => {
        // Reset width to 0 for animation
        const originalWidth = bar.style.width;
        bar.style.width = '0%';
        
        // Animate to original width with staggered delay
        setTimeout(() => {
            bar.style.transition = 'width 1.2s cubic-bezier(0.4, 0, 0.2, 1)';
            bar.style.width = originalWidth;
        }, index * 150); // Stagger animation by 150ms per bar
    });
    
    // Add hover effects for better interactivity
    progressBars.forEach(bar => {
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
