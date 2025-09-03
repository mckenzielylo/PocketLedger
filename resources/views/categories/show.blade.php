@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('categories.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Category Details</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">View category information and transactions</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('categories.edit', $category) }}" 
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Edit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Details -->
    <div class="px-4 py-6 sm:px-6">
        <div class="space-y-6">
            <!-- Category Header -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center space-x-4">
                    <!-- Category Icon -->
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 rounded-full bg-{{ $category->color }}-100 dark:bg-{{ $category->color }}-900 flex items-center justify-center">
                            @if($category->icon)
                                @if(str_starts_with($category->icon, 'fas ') || str_starts_with($category->icon, 'far ') || str_starts_with($category->icon, 'fab ') || str_starts_with($category->icon, 'fal ') || str_starts_with($category->icon, 'fad '))
                                    <i class="{{ $category->icon }} text-{{ $category->color }}-600 dark:text-{{ $category->color }}-400 text-2xl"></i>
                                @else
                                    <span class="text-2xl">{{ $category->icon }}</span>
                                @endif
                            @else
                                <span class="text-{{ $category->color }}-600 dark:text-{{ $category->color }}-400 text-2xl font-bold">
                                    {{ strtoupper(substr($category->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Category Info -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $category->name }}</h2>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium 
                                @if($category->type === 'income') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                {{ ucfirst($category->type) }}
                            </span>
                            @if($category->is_archived)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    Archived
                                </span>
                            @endif
                            @if($category->parent)
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    Subcategory of {{ $category->parent->name }}
                                </span>
                            @endif
                        </div>
                        @if($category->description)
                            <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $category->description }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Category Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Transactions</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $category->transactions()->count() }}</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Subcategories</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $category->children()->count() }}</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Created</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $category->created_at->format('M j, Y') }}</p>
                </div>
            </div>

            <!-- Recent Transactions -->
            @if($category->transactions->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Transactions</h3>
                    <div class="space-y-3">
                        @foreach($category->transactions as $transaction)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center
                                        @if($transaction->type === 'income') bg-green-100 dark:bg-green-900
                                        @else bg-red-100 dark:bg-red-900 @endif">
                                        <svg class="w-4 h-4 
                                            @if($transaction->type === 'income') text-green-600 dark:text-green-400
                                            @else text-red-600 dark:text-red-400 @endif" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($transaction->type === 'income')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $transaction->payee ?: 'Transaction' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $transaction->account->name }} • {{ $transaction->occurred_on->format('M j, Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium {{ $transaction->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $transaction->type === 'expense' ? '-' : '' }}{{ $transaction->account->currency_symbol ?? Auth::user()->preferred_currency_symbol }} {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('transactions.index') }}?category={{ $category->id }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                            View All Transactions →
                        </a>
                    </div>
                </div>
            @endif

            <!-- Subcategories -->
            @if($category->children->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Subcategories</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($category->children as $child)
                            <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-{{ $child->color }}-100 dark:bg-{{ $child->color }}-900 flex items-center justify-center">
                                        @if($child->icon)
                                            <i class="{{ $child->icon }} text-{{ $child->color }}-600 dark:text-{{ $child->color }}-400 text-sm"></i>
                                        @else
                                            <span class="text-{{ $child->color }}-600 dark:text-{{ $child->color }}-400 text-sm font-medium">
                                                {{ strtoupper(substr($child->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $child->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $child->transactions()->count() }} transactions</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
