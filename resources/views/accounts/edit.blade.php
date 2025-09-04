@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('accounts.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Account</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update account information</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="px-4 py-6 sm:px-6">
        <form action="{{ route('accounts.update', $account) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Account Type -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Account Type</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none account-type-option" data-type="cash">
                        <input type="radio" name="type" value="cash" class="sr-only" {{ old('type', $account->type) === 'cash' ? 'checked' : '' }}>
                        <div class="flex flex-1">
                            <div class="flex flex-col">
                                <div class="flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full mb-2">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Cash</span>
                            </div>
                        </div>
                        <div class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent account-type-border"></div>
                    </label>
                    
                    <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none account-type-option" data-type="bank">
                        <input type="radio" name="type" value="bank" class="sr-only" {{ old('type', $account->type) === 'bank' ? 'checked' : '' }}>
                        <div class="flex flex-1">
                            <div class="flex flex-col">
                                <div class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full mb-2">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Bank</span>
                            </div>
                        </div>
                        <div class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent account-type-border"></div>
                    </label>
                    
                    <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none account-type-option" data-type="e-wallet">
                        <input type="radio" name="type" value="e-wallet" class="sr-only" {{ old('type', $account->type) === 'e-wallet' ? 'checked' : '' }}>
                        <div class="flex flex-1">
                            <div class="flex flex-col">
                                <div class="flex items-center justify-center w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-full mb-2">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">E-Wallet</span>
                            </div>
                        </div>
                        <div class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent account-type-border"></div>
                    </label>
                    
                    <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none account-type-option" data-type="credit-card">
                        <input type="radio" name="type" value="credit-card" class="sr-only" {{ old('type', $account->type) === 'credit-card' ? 'checked' : '' }}>
                        <div class="flex flex-1">
                            <div class="flex flex-col">
                                <div class="flex items-center justify-center w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full mb-2">
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Credit Card</span>
                            </div>
                        </div>
                        <div class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent account-type-border"></div>
                    </label>
                </div>
                @error('type')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Account Name -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Account Name</label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name', $account->name) }}"
                       class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                       placeholder="e.g., Main Bank Account, Cash Wallet, Credit Card">
                @error('name')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Currency -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Currency</label>
                <select name="currency" 
                        id="currency" 
                        class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="IDR" {{ old('currency', $account->currency) === 'IDR' ? 'selected' : '' }}>IDR - Indonesian Rupiah</option>
                    <option value="USD" {{ old('currency', $account->currency) === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                    <option value="EUR" {{ old('currency', $account->currency) === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                    <option value="GBP" {{ old('currency', $account->currency) === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                    <option value="JPY" {{ old('currency', $account->currency) === 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen</option>
                    <option value="SGD" {{ old('currency', $account->currency) === 'SGD' ? 'selected' : '' }}>SGD - Singapore Dollar</option>
                    <option value="MYR" {{ old('currency', $account->currency) === 'MYR' ? 'selected' : '' }}>MYR - Malaysian Ringgit</option>
                    <option value="THB" {{ old('currency', $account->currency) === 'THB' ? 'selected' : '' }}>THB - Thai Baht</option>
                </select>
                @error('currency')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Balance (Read-only) -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Balance</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">{{ $account->currency_symbol }}</span>
                    </div>
                    <input type="text" 
                           value="{{ number_format($account->current_balance, 0, ',', '.') }}"
                           class="block w-full pl-8 pr-12 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white"
                           readonly>
                </div>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Current balance is calculated from transactions and cannot be edited directly</p>
            </div>

            <!-- Note -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Note (Optional)</label>
                <textarea name="note" 
                          id="note" 
                          rows="3"
                          class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                          placeholder="Add any additional details about this account...">{{ old('note', $account->note) }}</textarea>
                @error('note')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('accounts.index') }}" 
                   class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Update Account
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeInputs = document.querySelectorAll('input[name="type"]');
    const currencySelect = document.getElementById('currency');
    const accountTypeOptions = document.querySelectorAll('.account-type-option');
    
    function updateAccountTypeSelection() {
        const selectedType = document.querySelector('input[name="type"]:checked')?.value;
        
        // Remove all active states
        accountTypeOptions.forEach(option => {
            const border = option.querySelector('.account-type-border');
            border.classList.remove('border-primary-500');
            border.classList.add('border-transparent');
        });
        
        // Add active state to selected option
        if (selectedType) {
            const selectedOption = document.querySelector(`[data-type="${selectedType}"]`);
            if (selectedOption) {
                const border = selectedOption.querySelector('.account-type-border');
                border.classList.remove('border-transparent');
                border.classList.add('border-primary-500');
            }
        }
    }
    
    function updateCurrencySymbol() {
        const selectedCurrency = currencySelect.value;
        const balanceInput = document.querySelector('input[readonly]');
        
        // Update currency symbol based on selected currency
        let symbol = '$';
        if (selectedCurrency === 'IDR') symbol = 'Rp';
        else if (selectedCurrency === 'EUR') symbol = '€';
        else if (selectedCurrency === 'GBP') symbol = '£';
        else if (selectedCurrency === 'JPY') symbol = '¥';
        else if (selectedCurrency === 'SGD') symbol = 'S$';
        else if (selectedCurrency === 'MYR') symbol = 'RM';
        else if (selectedCurrency === 'THB') symbol = '฿';
        
        balanceInput.previousElementSibling.querySelector('span').textContent = symbol;
    }
    
    // Handle account type selection
    accountTypeOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radioInput = this.querySelector('input[type="radio"]');
            radioInput.checked = true;
            updateAccountTypeSelection();
            updateCurrencySymbol();
        });
    });
    
    typeInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateAccountTypeSelection();
            updateCurrencySymbol();
        });
    });
    
    currencySelect.addEventListener('change', updateCurrencySymbol);
    
    // Initialize on page load
    updateAccountTypeSelection();
    updateCurrencySymbol();
});
</script>
@endsection
