@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Budgets</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Plan and track your monthly spending</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('budgets.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Budget
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Budget List -->
    <div class="px-4 py-6 sm:px-6">
        @if($budgets->count() > 0)
            <div class="space-y-4">
                @foreach($budgets as $budget)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::createFromDate(explode('-', $budget->month)[0], explode('-', $budget->month)[1], 1)->format('F Y') }}
                        </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $budget->budgetCategories->count() }} categories
                                </p>
                            </div>
                            <div class="text-right">
                                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ Auth::user()->preferred_currency_symbol }} {{ number_format($budget->total_limit, 0, ',', '.') }}
                        </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total Budget</p>
                            </div>
                        </div>
                        
                        <!-- Budget Categories Summary -->
                        <div class="space-y-3 mb-4">
                            @foreach($budget->budgetCategories->take(3) as $budgetCategory)
                                @php
                                    $monthParts = explode('-', $budget->month);
                                    $year = $monthParts[0];
                                    $month = $monthParts[1];
                                    
                                    $spending = $budgetCategory->category->transactions()
                                        ->where('type', 'expense')
                                        ->whereYear('occurred_on', $year)
                                        ->whereMonth('occurred_on', $month)
                                        ->sum('amount');
                                    $percentage = $budgetCategory->limit_amount > 0 ? ($spending / $budgetCategory->limit_amount) * 100 : 0;
                                    $isOverBudget = $spending > $budgetCategory->limit_amount;
                                @endphp
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 rounded-full 
                                            @if($isOverBudget) bg-red-500
                                            @elseif($percentage >= 80) bg-yellow-500
                                            @else bg-green-500
                                            @endif">
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $budgetCategory->category->name }}
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ Auth::user()->preferred_currency_symbol }} {{ number_format($spending, 0, ',', '.') }}
                                            <span class="text-gray-500 dark:text-gray-400">/ {{ number_format($budgetCategory->limit_amount, 0, ',', '.') }}</span>
                                        </p>
                                        <!-- Enhanced Progress Bar -->
                                        <div class="relative w-24 mt-1">
                                            <!-- Background Gridlines -->
                                            <div class="absolute inset-0 flex justify-between items-center pointer-events-none">
                                                <div class="w-px h-full bg-gray-300 dark:bg-gray-600 opacity-15"></div>
                                                <div class="w-px h-full bg-gray-300 dark:bg-gray-600 opacity-15" style="left: 25%"></div>
                                                <div class="w-px h-full bg-gray-300 dark:bg-gray-600 opacity-15" style="left: 50%"></div>
                                                <div class="w-px h-full bg-gray-300 dark:bg-gray-600 opacity-15" style="left: 75%"></div>
                                                <div class="w-px h-full bg-gray-300 dark:bg-gray-600 opacity-15" style="right: 0"></div>
                                            </div>
                                            
                                            <!-- Progress Bar Container -->
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 relative overflow-hidden">
                                                <div class="budget-card-progress-bar h-2 rounded-full transition-all duration-800 ease-out @if($isOverBudget) budget-card-progress-bar-danger @elseif($percentage >= 80) budget-card-progress-bar-warning @else budget-card-progress-bar-success @endif" 
                                                     style="width: {{ min($percentage, 100) }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($budget->budgetCategories->count() > 3)
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
                                    +{{ $budget->budgetCategories->count() - 3 }} more categories
                                </p>
                            @endif
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('budgets.show', $budget) }}" 
                                   class="text-sm px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    View Details
                                </a>
                                <a href="{{ route('budgets.edit', $budget) }}" 
                                   class="text-sm px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    Edit
                                </a>
                            </div>
                            
                            <form action="{{ route('budgets.destroy', $budget) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-sm px-3 py-1 border border-red-300 dark:border-red-600 rounded text-red-700 dark:text-red-300 bg-white dark:bg-gray-700 hover:bg-red-50 dark:hover:bg-red-900"
                                        onclick="return confirm('Are you sure you want to delete this budget? This action cannot be undone.')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No budgets</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first monthly budget.</p>
                <div class="mt-6">
                    <a href="{{ route('budgets.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Budget
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Floating Action Button -->
<div class="fixed bottom-20 right-4 z-50">
    <a href="{{ route('budgets.create') }}" 
       class="w-14 h-14 bg-primary-600 hover:bg-primary-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors duration-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bars on page load
    const progressBars = document.querySelectorAll('.budget-card-progress-bar');
    
    progressBars.forEach((bar, index) => {
        // Reset width to 0 for animation
        const originalWidth = bar.style.width;
        bar.style.width = '0%';
        
        // Animate to original width with staggered delay
        setTimeout(() => {
            bar.style.transition = 'width 1s cubic-bezier(0.4, 0, 0.2, 1)';
            bar.style.width = originalWidth;
        }, index * 100); // Stagger animation by 100ms per bar
    });
    
    // Add hover effects for better interactivity
    progressBars.forEach(bar => {
        bar.addEventListener('mouseenter', function() {
            this.style.transform = 'scaleY(1.2)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        bar.addEventListener('mouseleave', function() {
            this.style.transform = 'scaleY(1)';
        });
    });
});
</script>
@endsection
