@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('budgets.show', $budget) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Budget</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update spending limits for {{ \Carbon\Carbon::createFromDate(explode('-', $budget->month)[0], explode('-', $budget->month)[1], 1)->format('F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="px-4 py-6 sm:px-6">
        <form action="{{ route('budgets.update', $budget) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Budget Period (Read-only) -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Budget Period</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Month</label>
                        <input type="text" 
                               value="{{ \Carbon\Carbon::createFromDate(explode('-', $budget->month)[0], explode('-', $budget->month)[1], 1)->format('F') }}"
                               class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white"
                               readonly>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Year</label>
                        <input type="text" 
                               value="{{ explode('-', $budget->month)[0] }}"
                               class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white"
                               readonly>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Budget period cannot be changed after creation</p>
            </div>

            <!-- Budget Categories -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Budget Categories</h3>
                    <button type="button" 
                            onclick="addCategory()"
                            class="text-sm px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Category
                    </button>
                </div>
                
                <div id="budget-categories" class="space-y-4">
                    <!-- Existing categories will be loaded here -->
                </div>
                
                @error('categories')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Total Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Total Budget</h3>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="total-amount">IDR 0</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Sum of all categories</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('budgets.show', $budget) }}" 
                   class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Update Budget
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize with existing budget categories
    const existingCategories = @json($budget->budgetCategories);
    const availableCategories = @json($categories);
    
    // Load existing budget categories
    existingCategories.forEach(budgetCategory => {
        addCategory(budgetCategory);
    });
    
    // Update total when amounts change
    document.addEventListener('input', function(e) {
        if (e.target.name && e.target.name.includes('amount')) {
            updateTotal();
        }
    });
    
    // Initialize total
    updateTotal();
});

let categoryCounter = 0;

function addCategory(budgetCategory = null) {
    const container = document.getElementById('budget-categories');
    const availableCategories = @json($categories);
    
    const categoryDiv = document.createElement('div');
    categoryDiv.className = 'flex items-center space-x-3 p-4 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700';
    categoryDiv.innerHTML = `
        <div class="flex-1">
            <select name="categories[${categoryCounter}][category_id]" 
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="">Select a category</option>
                ${availableCategories.map(cat => 
                    `<option value="${cat.id}" ${budgetCategory && budgetCategory.category_id === cat.id ? 'selected' : ''}>${cat.name}</option>`
                ).join('')}
            </select>
        </div>
        <div class="w-32">
                                <input type="number" 
                           name="categories[${categoryCounter}][amount]" 
                           step="0.01" 
                           min="0.01" 
                           value="${budgetCategory ? budgetCategory.limit_amount : ''}"
                           placeholder="0.00"
                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
        </div>
        <button type="button" 
                onclick="removeCategory(this)" 
                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    `;
    
    container.appendChild(categoryDiv);
    categoryCounter++;
    updateTotal();
}

function removeCategory(button) {
    const container = document.getElementById('budget-categories');
    if (container.children.length > 1) {
        button.closest('div').remove();
        updateTotal();
    }
}

function updateTotal() {
    const amounts = Array.from(document.querySelectorAll('input[name*="[amount]"]'))
        .map(input => parseFloat(input.value) || 0);
    
    const total = amounts.reduce((sum, amount) => sum + amount, 0);
    document.getElementById('total-amount').textContent = `IDR ${total.toLocaleString('id-ID')}`;
}
</script>
@endsection
