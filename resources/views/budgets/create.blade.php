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
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Budget</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Set spending limits for expense categories</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="px-4 py-6 sm:px-6">
        <form action="{{ route('budgets.store') }}" method="POST" class="space-y-6" onsubmit="return validateForm()">
            @csrf
            
            <!-- Month and Year Selection -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Budget Period</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Month</label>
                        <select name="month" 
                                id="month" 
                                class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('month', now()->month) == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromDate(now()->year, $i, 1)->format('F') }}
                                </option>
                            @endfor
                        </select>
                        @error('month')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Year</label>
                        <select name="year" 
                                id="year" 
                                class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            @for($i = now()->year - 2; $i <= now()->year + 2; $i++)
                                <option value="{{ $i }}" {{ old('year', now()->year) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                        @error('year')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Copy from Previous Month -->
                <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Quick Setup</p>
                            <p class="text-sm text-blue-700 dark:text-blue-300">Copy budget amounts from the previous month to save time</p>
                        </div>
                        <button type="button" 
                                onclick="copyFromPrevious()"
                                class="text-sm px-3 py-1 border border-blue-300 dark:border-blue-600 rounded text-blue-700 dark:text-blue-300 bg-white dark:bg-gray-700 hover:bg-blue-50 dark:hover:bg-blue-900">
                            Copy Previous
                        </button>
                    </div>
                </div>
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
                    <!-- Categories will be added here dynamically -->
                </div>
                
                <div id="category-error" class="hidden mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <p class="text-sm text-red-600 dark:text-red-400">Please add at least one category with a valid amount.</p>
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
                <a href="{{ route('budgets.index') }}" 
                   class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Create Budget
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize with available categories
    const categories = @json($categories);
    
    // Add initial categories (at least 3)
    for (let i = 0; i < Math.min(3, categories.length); i++) {
        addCategory(categories[i]);
    }
    
    // Update total when amounts change
    document.addEventListener('input', function(e) {
        if (e.target.name && e.target.name.includes('amount')) {
            updateTotal();
        }
    });
});

let categoryCounter = 0;

function addCategory(category = null) {
    const container = document.getElementById('budget-categories');
    const categories = @json($categories);
    
    const categoryDiv = document.createElement('div');
    categoryDiv.className = 'flex items-center space-x-3 p-4 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700';
    categoryDiv.innerHTML = `
        <div class="flex-1">
            <select name="categories[${categoryCounter}][category_id]" 
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="">Select a category</option>
                ${categories.map(cat => 
                    `<option value="${cat.id}" ${category && category.id === cat.id ? 'selected' : ''}>${cat.name}</option>`
                ).join('')}
            </select>
        </div>
        <div class="w-32">
            <input type="number" 
                   name="categories[${categoryCounter}][amount]" 
                   step="0.01" 
                   min="0.01" 
                   value="${category ? category.amount || '' : ''}"
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
    
    // Hide error if we have valid data
    const errorDiv = document.getElementById('category-error');
    if (total > 0) {
        errorDiv.classList.add('hidden');
    }
}

function validateForm() {
    const categoryInputs = document.querySelectorAll('select[name*="[category_id]"]');
    const amountInputs = document.querySelectorAll('input[name*="[amount]"]');
    const errorDiv = document.getElementById('category-error');
    
    let hasValidCategory = false;
    
    for (let i = 0; i < categoryInputs.length; i++) {
        const categoryId = categoryInputs[i].value;
        const amount = parseFloat(amountInputs[i].value) || 0;
        
        if (categoryId && amount > 0) {
            hasValidCategory = true;
            break;
        }
    }
    
    if (!hasValidCategory) {
        errorDiv.classList.remove('hidden');
        return false;
    } else {
        errorDiv.classList.add('hidden');
    }
    
    return true;
}

function copyFromPrevious() {
    const month = parseInt(document.getElementById('month').value);
    const year = parseInt(document.getElementById('year').value);
    
    // Calculate previous month
    const date = new Date(year, month - 1, 1);
    date.setMonth(date.getMonth() - 1);
    const prevMonth = date.getMonth() + 1;
    const prevYear = date.getFullYear();
    
    // Show confirmation
    if (confirm(`Copy budget from ${new Date(prevYear, prevMonth - 1, 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}?`)) {
        // This would typically make an AJAX call to copy the budget
        // For now, we'll just show a message
        alert('Budget copying feature will be implemented in the next update!');
    }
}
</script>
@endsection
