@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('categories.show', $category) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Category</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update category information</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="px-4 py-6 sm:px-6">
        <div class="max-w-2xl mx-auto">
            <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Basic Information</h3>
                    
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category Name</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $category->name) }}" 
                                   required
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category Type</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 focus:outline-none">
                                    <input type="radio" name="type" value="income" class="sr-only" {{ old('type', $category->type) === 'income' ? 'checked' : '' }}>
                                    <div class="flex w-full items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="text-sm">
                                                <p class="font-medium text-gray-900 dark:text-white">Income</p>
                                                <p class="text-gray-500 dark:text-gray-400">Money coming in</p>
                                            </div>
                                        </div>
                                        <div class="shrink-0 text-green-600 dark:text-green-400">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                                <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 focus:outline-none">
                                    <input type="radio" name="type" value="expense" class="sr-only" {{ old('type', $category->type) === 'expense' ? 'checked' : '' }}>
                                    <div class="flex w-full items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="text-sm">
                                                <p class="font-medium text-gray-900 dark:text-white">Expense</p>
                                                <p class="text-gray-500 dark:text-gray-400">Money going out</p>
                                            </div>
                                        </div>
                                        <div class="shrink-0 text-red-600 dark:text-red-400">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Category -->
                        <div>
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Parent Category (Optional)</label>
                            <select name="parent_id" 
                                    id="parent_id" 
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">No parent category</option>
                                @foreach($categories as $parentCategory)
                                    @if($parentCategory->id !== $category->id && $parentCategory->type === $category->type)
                                        <option value="{{ $parentCategory->id }}" {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                                            {{ $parentCategory->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('parent_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Appearance -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Appearance</h3>
                    
                    <div class="space-y-4">
                        <!-- Color -->
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Color</label>
                            <div class="grid grid-cols-6 gap-2">
                                @php
                                    $colors = ['red', 'orange', 'yellow', 'green', 'blue', 'indigo', 'purple', 'pink', 'gray', 'emerald', 'teal', 'cyan'];
                                @endphp
                                @foreach($colors as $color)
                                    <label class="relative flex cursor-pointer">
                                        <input type="radio" name="color" value="{{ $color }}" class="sr-only" {{ old('color', $category->color) === $color ? 'checked' : '' }}>
                                        <div class="w-8 h-8 rounded-full bg-{{ $color }}-500 border-2 border-transparent hover:border-gray-300 dark:hover:border-gray-600 transition-all duration-200 hover:scale-110 {{ old('color', $category->color) === $color ? 'ring-2 ring-primary-500 ring-offset-2' : '' }}"></div>
                                    </label>
                                @endforeach
                            </div>
                            @error('color')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Icon -->
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
                            
                            <input type="text" 
                                   name="icon" 
                                   id="icon" 
                                   value="{{ old('icon', $category->icon) }}" 
                                   placeholder="e.g., fas fa-home, fas fa-car, fas fa-utensils"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Enter a FontAwesome icon class (e.g., fas fa-home). Leave empty to use the first letter of the category name.</p>
                            @error('icon')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Additional Information</h3>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description (Optional)</label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                  placeholder="Add any additional details about this category...">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('categories.show', $category) }}" 
                       class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeInputs = document.querySelectorAll('input[name="type"]');
    const parentSelect = document.getElementById('parent_id');
    const colorInputs = document.querySelectorAll('input[name="color"]');
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');
    const iconDisplay = document.getElementById('icon-display');
    const nameInput = document.getElementById('name');
    
    function updateParentOptions() {
        const selectedType = document.querySelector('input[name="type"]:checked')?.value;
        const currentParentId = '{{ $category->parent_id }}';
        
        // Reset parent selection if type changes
        if (selectedType !== '{{ $category->type }}') {
            parentSelect.value = '';
        }
        
        // Update parent options based on selected type
        Array.from(parentSelect.options).forEach(option => {
            if (option.value === '') return; // Skip "No parent category" option
            
            const parentCategory = option.closest('option');
            if (parentCategory) {
                // This is a simplified approach - in a real app you might want to fetch options dynamically
                option.disabled = false;
            }
        });
    }
    
    function updateIconPreview() {
        const selectedColor = document.querySelector('input[name="color"]:checked')?.value || 'red';
        const iconValue = iconInput.value.trim();
        const categoryName = nameInput.value.trim();
        
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
    
    function updateColorSelection() {
        const selectedColor = document.querySelector('input[name="color"]:checked')?.value;
        
        // Update color selection visual feedback
        document.querySelectorAll('input[name="color"]').forEach(input => {
            const colorDiv = input.nextElementSibling;
            if (input.checked) {
                colorDiv.classList.add('ring-2', 'ring-primary-500', 'ring-offset-2');
            } else {
                colorDiv.classList.remove('ring-2', 'ring-primary-500', 'ring-offset-2');
            }
        });
        
        updateIconPreview();
    }
    
    // Handle type selection
    typeInputs.forEach(input => {
        input.addEventListener('change', updateParentOptions);
    });
    
    // Handle color selection
    colorInputs.forEach(input => {
        input.addEventListener('change', updateColorSelection);
    });
    
    // Handle icon input changes
    iconInput.addEventListener('input', updateIconPreview);
    nameInput.addEventListener('input', updateIconPreview);
    
    // Initialize
    updateParentOptions();
    updateColorSelection();
    updateIconPreview();
});
</script>
@endsection
