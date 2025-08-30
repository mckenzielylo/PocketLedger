@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow">
        <div class="px-4 py-6 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Debt</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Modify debt information</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('debts.show', $debt) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Details
                    </a>
                    <a href="{{ route('debts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Debts
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="px-4 py-6 sm:px-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <form action="{{ route('debts.update', $debt) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Debt Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Debt Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $debt->name) }}" required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="e.g., Car Loan, Credit Card, Personal Loan">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Debt Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Debt Type</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none debt-type-option" data-type="borrowed">
                                <input type="radio" name="type" value="borrowed" class="sr-only" {{ old('type', $debt->type) === 'borrowed' ? 'checked' : '' }} required>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center">
                                            <div class="w-3 h-3 rounded-full bg-primary-600 hidden debt-type-dot"></div>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900 dark:text-white">Borrowed</span>
                                        <span class="block text-sm text-gray-500 dark:text-gray-400">Money you owe to others</span>
                                    </div>
                                </div>
                                <div class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent debt-type-border"></div>
                            </label>
                            
                            <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none debt-type-option" data-type="lent">
                                <input type="radio" name="type" value="lent" class="sr-only" {{ old('type', $debt->type) === 'lent' ? 'checked' : '' }}>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center">
                                            <div class="w-3 h-3 rounded-full bg-primary-600 hidden debt-type-dot"></div>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900 dark:text-white">Lent</span>
                                        <span class="block text-sm text-gray-500 dark:text-gray-400">Money others owe to you</span>
                                    </div>
                                </div>
                                <div class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent debt-type-border"></div>
                            </label>
                        </div>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 sm:text-sm">{{ Auth::user()->preferred_currency_symbol }}</span>
                            </div>
                            <input type="number" name="amount" id="amount" value="{{ old('amount', $debt->amount) }}" step="0.01" min="0.01" required
                                class="block w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="0.00">
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account -->
                    <div>
                        <label for="account_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Account</label>
                        <select name="account_id" id="account_id" required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Select an account</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ old('account_id', $debt->account_id) == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }} ({{ $account->type }})
                                </option>
                            @endforeach
                        </select>
                        @error('account_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Interest Rate -->
                    <div>
                        <label for="interest_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Interest Rate (Annual %)</label>
                        <div class="relative">
                            <input type="number" name="interest_rate" id="interest_rate" value="{{ old('interest_rate', $debt->interest_rate) }}" step="0.01" min="0" max="100"
                                class="block w-full pr-8 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">%</span>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Leave empty if no interest</p>
                        @error('interest_rate')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Due Date</label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $debt->due_date ? $debt->due_date->format('Y-m-d') : '') }}"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Leave empty if no specific due date</p>
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Additional details about this debt...">{{ old('description', $debt->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('debts.show', $debt) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">Cancel</a>
                        <button type="submit" class="px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">Update Debt</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeInputs = document.querySelectorAll('input[name="type"]');
    const debtTypeOptions = document.querySelectorAll('.debt-type-option');
    
    function updateDebtTypeSelection() {
        debtTypeOptions.forEach(option => {
            const input = option.querySelector('input[type="radio"]');
            const dot = option.querySelector('.debt-type-dot');
            const border = option.querySelector('.debt-type-border');
            
            if (input.checked) {
                option.classList.add('ring-2', 'ring-primary-500');
                border.classList.add('border-primary-500');
                dot.classList.remove('hidden');
            } else {
                option.classList.remove('ring-2', 'ring-primary-500');
                border.classList.remove('border-primary-500');
                dot.classList.add('hidden');
            }
        });
    }
    
    debtTypeOptions.forEach(option => {
        option.addEventListener('click', function() {
            const input = this.querySelector('input[type="radio"]');
            input.checked = true;
            updateDebtTypeSelection();
        });
    });
    
    typeInputs.forEach(input => {
        input.addEventListener('change', updateDebtTypeSelection);
    });
    
    // Initialize selection
    updateDebtTypeSelection();
});
</script>
@endsection

