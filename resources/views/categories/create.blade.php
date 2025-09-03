@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow">
        <div class="px-4 py-6 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Category</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add a new income or expense category</p>
                </div>
                <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Categories
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="px-4 py-6 sm:px-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <form action="{{ route('categories.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    
                    <!-- Category Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Category Type</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none category-type-option" data-type="income">
                                <input type="radio" name="type" value="income" class="sr-only" {{ old('type') === 'income' ? 'checked' : '' }}>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Income</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">For money coming in</p>
                                    </div>
                                </div>
                                <div class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent category-type-border"></div>
                            </label>
                            
                            <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none category-type-option" data-type="expense">
                                <input type="radio" name="type" value="expense" class="sr-only" {{ old('type') === 'expense' ? 'checked' : '' }}>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Expense</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">For money going out</p>
                                    </div>
                                </div>
                                <div class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent category-type-border"></div>
                            </label>
                        </div>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Color Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Color</label>
                        <div class="grid grid-cols-6 gap-2">
                            @php
                                $colors = ['red', 'orange', 'yellow', 'green', 'blue', 'indigo', 'purple', 'pink', 'gray', 'emerald', 'teal', 'cyan'];
                            @endphp
                            @foreach($colors as $color)
                                <label class="relative">
                                    <input type="radio" name="color" value="{{ $color }}" class="sr-only" {{ old('color') === $color ? 'checked' : '' }}>
                                    <div class="w-8 h-8 rounded-full bg-{{ $color }}-500 cursor-pointer border-2 border-transparent hover:border-gray-300 dark:hover:border-gray-600 transition-all duration-200 hover:scale-110 color-option {{ old('color') === $color ? 'ring-2 ring-primary-500 ring-offset-2' : '' }}" data-color="{{ $color }}"></div>
                                </label>
                            @endforeach
                        </div>
                        @error('color')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent Category -->
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Parent Category (Optional)</label>
                        <select name="parent_id" id="parent_id" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">No parent category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Icon (Optional) -->
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Icon (Optional)</label>
                        
                        <!-- Icon Preview -->
                        <div class="mb-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center space-x-3">
                                <div id="icon-preview" class="w-10 h-10 rounded-lg flex items-center justify-center text-white text-lg font-semibold bg-red-500">
                                    <i id="icon-display" class="fas fa-circle"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Icon Preview</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">This is how your category icon will appear</p>
                                </div>
                            </div>
                        </div>
                        
                        <input type="text" name="icon" id="icon" value="{{ old('icon') }}" placeholder="e.g., fas fa-home, fas fa-car, fas fa-utensils"
                               class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter a FontAwesome icon class (e.g., fas fa-home). Leave empty to use the first letter of the category name.</p>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description (Optional)</label>
                        <textarea name="description" id="description" rows="3" placeholder="Brief description of this category"
                                  class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('categories.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeInputs = document.querySelectorAll('input[name="type"]');
    const categoryTypeOptions = document.querySelectorAll('.category-type-option');
    const colorOptions = document.querySelectorAll('.color-option');
    const parentSelect = document.getElementById('parent_id');
    
    function updateCategoryTypeSelection() {
        const selectedType = document.querySelector('input[name="type"]:checked')?.value;
        categoryTypeOptions.forEach(option => {
            const border = option.querySelector('.category-type-border');
            border.classList.remove('border-primary-500');
            border.classList.add('border-transparent');
        });
        if (selectedType) {
            const selectedOption = document.querySelector(`[data-type="${selectedType}"]`);
            if (selectedOption) {
                const border = selectedOption.querySelector('.category-type-border');
                border.classList.remove('border-transparent');
                border.classList.add('border-primary-500');
            }
        }
    }
    
    function updateColorSelection() {
        const selectedColor = document.querySelector('input[name="color"]:checked')?.value;
        colorOptions.forEach(option => {
            option.classList.remove('ring-2', 'ring-primary-500', 'ring-offset-2');
        });
        if (selectedColor) {
            const selectedOption = document.querySelector(`[data-color="${selectedColor}"]`);
            if (selectedOption) {
                selectedOption.classList.add('ring-2', 'ring-primary-500', 'ring-offset-2');
            }
        }
        updateIconPreview();
    }
    
    function updateIconPreview() {
        const selectedColor = document.querySelector('input[name="color"]:checked')?.value || 'red';
        const iconValue = document.getElementById('icon').value.trim();
        const categoryName = document.getElementById('name').value.trim();
        const iconPreview = document.getElementById('icon-preview');
        const iconDisplay = document.getElementById('icon-display');
        
        // Update color
        iconPreview.className = `w-10 h-10 rounded-lg flex items-center justify-center text-white text-lg font-semibold bg-${selectedColor}-500`;
        
        // Update icon or fallback to first letter
        if (iconValue) {
            iconDisplay.className = iconValue;
            iconDisplay.style.display = 'block';
        } else if (categoryName) {
            iconDisplay.className = '';
            iconDisplay.textContent = categoryName.charAt(0).toUpperCase();
            iconDisplay.style.display = 'block';
        } else {
            iconDisplay.className = 'fas fa-circle';
            iconDisplay.style.display = 'block';
        }
    }
    
    function updateParentOptions() {
        const selectedType = document.querySelector('input[name="type"]:checked')?.value;
        if (selectedType) {
            // Filter parent options based on selected type
            Array.from(parentSelect.options).forEach(option => {
                if (option.value === '') return; // Keep "No parent" option
                // For now, show all categories as potential parents
                // You could implement filtering here if needed
            });
        }
    }
    
    categoryTypeOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radioInput = this.querySelector('input[type="radio"]');
            radioInput.checked = true;
            updateCategoryTypeSelection();
            updateParentOptions();
        });
    });
    
    typeInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateCategoryTypeSelection();
            updateParentOptions();
        });
    });
    
    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radioInput = this.parentElement.querySelector('input[type="radio"]');
            radioInput.checked = true;
            updateColorSelection();
        });
    });
    
    // Handle icon and name input changes for preview
    document.getElementById('icon').addEventListener('input', updateIconPreview);
    document.getElementById('name').addEventListener('input', updateIconPreview);
    
    // Initialize selections
    updateCategoryTypeSelection();
    updateColorSelection();
    updateIconPreview();
    updateParentOptions();
});
</script>
@endsection
