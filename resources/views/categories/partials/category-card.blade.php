<div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
    <div class="p-4">
        <!-- Category Header -->
        <div class="flex items-start justify-between mb-3">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-{{ $category->color }}-100 dark:bg-{{ $category->color }}-900 flex items-center justify-center">
                        @if($category->icon)
                            <i class="{{ $category->icon }} text-{{ $category->color }}-600 dark:text-{{ $category->color }}-400 text-lg"></i>
                        @else
                            <span class="text-{{ $category->color }}-600 dark:text-{{ $category->color }}-400 text-lg font-medium">
                                {{ strtoupper(substr($category->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white truncate">
                        {{ $category->name }}
                    </h3>
                    @if($category->parent)
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Subcategory of {{ $category->parent->name }}
                        </p>
                    @endif
                    @if($category->description)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ Str::limit($category->description, 60) }}
                        </p>
                    @endif
                </div>
            </div>
            
            <!-- Status Badge -->
            <div class="flex-shrink-0">
                @if($category->is_archived)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                        Archived
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if($category->type === 'income') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                        {{ ucfirst($category->type) }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Category Stats -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Transactions</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $category->transactions()->count() }}
                </p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Subcategories</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $category->children()->count() }}
                </p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-700">
            <div class="flex space-x-2">
                <a href="{{ route('categories.show', $category) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                    View
                </a>
                <a href="{{ route('categories.edit', $category) }}" class="text-gray-600 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 text-sm font-medium">
                    Edit
                </a>
            </div>
            
            <div class="flex space-x-2">
                <!-- Archive/Activate Button -->
                <form action="{{ route('categories.archive', $category) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-sm text-gray-600 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        @if($category->is_archived)
                            Activate
                        @else
                            Archive
                        @endif
                    </button>
                </form>
                
                <!-- Delete Button -->
                @if(!$category->transactions()->exists() && !$category->children()->exists())
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
