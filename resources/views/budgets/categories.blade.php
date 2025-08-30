@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow">
        <div class="px-4 py-6 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Budget Categories</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Track spending across all budget categories</p>
                </div>
                <a href="{{ route('budgets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Budget
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="px-4 py-6 sm:px-6">
        <!-- Filters -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="month-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Month</label>
                    <select id="month-filter" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">All Months</option>
                        @foreach($months as $month)
                            <option value="{{ $month }}">{{ \Carbon\Carbon::createFromDate(explode('-', $month)[0], explode('-', $month)[1], 1)->format('F Y') }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="category-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                    <select id="category-filter" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select id="status-filter" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="on-track">On Track</option>
                        <option value="near-limit">Near Limit</option>
                        <option value="over-budget">Over Budget</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Budget Categories List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                @if($budgetCategories->count() > 0)
                    <div class="space-y-4">
                        @foreach($budgetCategories as $budgetCategory)
                            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full bg-{{ $budgetCategory->category->color }}-100 dark:bg-{{ $budgetCategory->category->color }}-900 flex items-center justify-center">
                                                    <span class="text-{{ $budgetCategory->color }}-600 dark:text-{{ $budgetCategory->color }}-400 text-sm font-medium">
                                                        {{ strtoupper(substr($budgetCategory->category->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                                    {{ $budgetCategory->category->name }}
                                                </h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::createFromDate(explode('-', $budgetCategory->budget->month)[0], explode('-', $budgetCategory->budget->month)[1], 1)->format('F Y') }} Budget
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                            IDR {{ number_format($budgetCategory->spending, 0, ',', '.') }} / {{ number_format($budgetCategory->limit_amount, 0, ',', '.') }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ number_format($budgetCategory->percentage, 1) }}% used
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="mt-4">
                                    <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        <span>Progress</span>
                                        <span class="font-medium">
                                            @if($budgetCategory->percentage <= 80)
                                                <span class="text-green-600 dark:text-green-400">On Track</span>
                                            @elseif($budgetCategory->percentage <= 100)
                                                <span class="text-yellow-600 dark:text-yellow-400">Near Limit</span>
                                            @else
                                                <span class="text-red-600 dark:text-red-400">Over Budget</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="h-2 rounded-full transition-all duration-300 
                                            @if($budgetCategory->percentage <= 80) bg-green-500
                                            @elseif($budgetCategory->percentage <= 100) bg-yellow-500
                                            @else bg-red-500 @endif"
                                             style="width: {{ min($budgetCategory->percentage, 100) }}%">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        @if($budgetCategory->remaining > 0)
                                            <span class="text-green-600 dark:text-green-400">
                                                IDR {{ number_format($budgetCategory->remaining, 0, ',', '.') }} remaining
                                            </span>
                                        @else
                                            <span class="text-red-600 dark:text-red-400">
                                                IDR {{ number_format(abs($budgetCategory->remaining), 0, ',', '.') }} over budget
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('budgets.show', $budgetCategory->budget) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                            View Budget
                                        </a>
                                        <a href="{{ route('budgets.edit', $budgetCategory->budget) }}" class="text-gray-600 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 text-sm font-medium">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $budgetCategories->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No budget categories found</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first budget.</p>
                        <div class="mt-6">
                            <a href="{{ route('budgets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create Budget
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthFilter = document.getElementById('month-filter');
    const categoryFilter = document.getElementById('category-filter');
    const statusFilter = document.getElementById('status-filter');
    
    function applyFilters() {
        const month = monthFilter.value;
        const category = categoryFilter.value;
        const status = statusFilter.value;
        
        let url = new URL(window.location);
        if (month) url.searchParams.set('month', month);
        else url.searchParams.delete('month');
        
        if (category) url.searchParams.set('category', category);
        else url.searchParams.delete('category');
        
        if (status) url.searchParams.set('status', status);
        else url.searchParams.delete('status');
        
        window.location.href = url.toString();
    }
    
    monthFilter.addEventListener('change', applyFilters);
    categoryFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    
    // Set current filter values from URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('month')) monthFilter.value = urlParams.get('month');
    if (urlParams.get('category')) categoryFilter.value = urlParams.get('category');
    if (urlParams.get('status')) statusFilter.value = urlParams.get('status');
});
</script>
@endsection
