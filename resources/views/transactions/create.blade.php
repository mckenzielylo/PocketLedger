@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('transactions.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Transaction</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Record a new income, expense, or transfer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="px-4 py-6 sm:px-6">
        <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Transaction Type -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Transaction Type</h3>
                <div class="grid grid-cols-3 gap-3">
                    <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none transaction-type-option" data-type="income">
                        <input type="radio" name="type" value="income" class="sr-only" {{ old('type') === 'income' ? 'checked' : '' }}>
                        <div class="flex flex-1">
                            <div class="flex flex-col">
                                <div class="flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full mb-2">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Income</span>
                            </div>
                        </div>
                        <div class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent transaction-type-border"></div>
                    </label>
                    
                    <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none transaction-type-option" data-type="expense">
                        <input type="radio" name="type" value="expense" class="sr-only" {{ old('type') === 'expense' ? 'checked' : '' }}>
                        <div class="flex flex-1">
                            <div class="flex flex-col">
                                <div class="flex items-center justify-center w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full mb-2">
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Expense</span>
                            </div>
                        </div>
                        <div class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent transaction-type-border"></div>
                    </label>
                    
                    <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none transaction-type-option" data-type="transfer">
                        <input type="radio" name="type" value="transfer" class="sr-only" {{ old('type') === 'transfer' ? 'checked' : '' }}>
                        <div class="flex flex-1">
                            <div class="flex flex-col">
                                <div class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full mb-2">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Transfer</span>
                            </div>
                        </div>
                        <div class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent transaction-type-border"></div>
                    </label>
                </div>
                @error('type')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">{{ Auth::user()->preferred_currency_symbol }}</span>
                    </div>
                    <input type="number" 
                           name="amount" 
                           id="amount" 
                           step="0.01" 
                           min="0.01" 
                           value="{{ old('amount') }}"
                           class="block w-full pl-12 pr-12 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                           placeholder="0.00">
                </div>
                @error('amount')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Account Selection -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <label for="account_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Account</label>
                <select name="account_id" 
                        id="account_id" 
                        class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Select an account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->name }} ({{ $account->currency }} {{ number_format($account->current_balance, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                @error('account_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category Selection (for income/expense) -->
            <div id="category-section" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                <select name="category_id" 
                        id="category_id" 
                        class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Transfer Account (for transfers) -->
            <div id="transfer-section" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hidden">
                <label for="transfer_account_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transfer To Account</label>
                <select name="transfer_account_id" 
                        id="transfer_account_id" 
                        class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Select destination account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('transfer_account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->name }} ({{ $account->currency }} {{ number_format($account->current_balance, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                @error('transfer_account_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <label for="occurred_on" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date</label>
                <input type="date" 
                       name="occurred_on" 
                       id="occurred_on" 
                       value="{{ old('occurred_on', date('Y-m-d')) }}"
                       class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('occurred_on')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Payee -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <label for="payee" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payee/Description</label>
                <input type="text" 
                       name="payee" 
                       id="payee" 
                       value="{{ old('payee') }}"
                       class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                       placeholder="Who did you pay or receive money from?">
                @error('payee')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Note -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Note (Optional)</label>
                <textarea name="note" 
                          id="note" 
                          rows="3"
                          class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                          placeholder="Add any additional details...">{{ old('note') }}</textarea>
                @error('note')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Receipt Upload -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <label for="receipt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Receipt (Optional)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-primary-400 dark:hover:border-primary-500 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                            <label for="receipt" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-primary-600 dark:text-primary-400 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                <span>Upload a file</span>
                                <input id="receipt" name="receipt" type="file" class="sr-only" accept="image/*,.pdf">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, PDF up to 2MB</p>
                    </div>
                </div>
                @error('receipt')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('transactions.index') }}" 
                   class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Create Transaction
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeInputs = document.querySelectorAll('input[name="type"]');
    const categorySection = document.getElementById('category-section');
    const transferSection = document.getElementById('transfer-section');
    const transactionTypeOptions = document.querySelectorAll('.transaction-type-option');
    
    function updateTransactionTypeSelection() {
        const selectedType = document.querySelector('input[name="type"]:checked')?.value;
        
        // Remove all active states
        transactionTypeOptions.forEach(option => {
            const border = option.querySelector('.transaction-type-border');
            border.classList.remove('border-primary-500');
            border.classList.add('border-transparent');
        });
        
        // Add active state to selected option
        if (selectedType) {
            const selectedOption = document.querySelector(`[data-type="${selectedType}"]`);
            if (selectedOption) {
                const border = selectedOption.querySelector('.transaction-type-border');
                border.classList.remove('border-transparent');
                border.classList.add('border-primary-500');
            }
        }
    }
    
    function toggleSections() {
        const selectedType = document.querySelector('input[name="type"]:checked')?.value;
        
        if (selectedType === 'transfer') {
            categorySection.classList.add('hidden');
            transferSection.classList.remove('hidden');
        } else {
            categorySection.classList.remove('hidden');
            transferSection.classList.add('hidden');
        }
    }
    
    // Handle transaction type selection
    transactionTypeOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radioInput = this.querySelector('input[type="radio"]');
            radioInput.checked = true;
            updateTransactionTypeSelection();
            toggleSections();
        });
    });
    
    typeInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateTransactionTypeSelection();
            toggleSections();
        });
    });
    
    // Initialize on page load
    updateTransactionTypeSelection();
    toggleSections();
});
</script>
@endsection
