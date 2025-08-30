@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background-primary">
    <!-- Header -->
    <div class="bg-background-secondary border-b border-neutral-700 shadow-ynab">
        <div class="px-4 py-6 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-text-primary">Accounts</h1>
                    <p class="mt-1 text-sm text-text-secondary">Manage your bank accounts, cash, and e-wallets</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('accounts.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Account
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Account List -->
    <div class="px-4 py-6 sm:px-6">
        @if($accounts->count() > 0)
            <div class="space-y-4">
                @foreach($accounts as $account)
                    <div class="card">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <!-- Account Type Icon -->
                                <div class="flex-shrink-0">
                                    @if($account->type === 'cash')
                                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                        </div>
                                    @elseif($account->type === 'bank')
                                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Account Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <h3 class="text-lg font-medium text-text-primary">{{ $account->name }}</h3>
                                        @if($account->is_archived)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-neutral-100 dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                                                Archived
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2 text-sm text-text-secondary">
                                        <span class="capitalize">{{ $account->type }}</span>
                                        <span>•</span>
                                        <span>{{ $account->currency }}</span>
                                        @if($account->transactions_count > 0)
                                            <span>•</span>
                                            <span>{{ $account->transactions_count }} transactions</span>
                                        @endif
                                    </div>
                                    @if($account->note)
                                        <p class="text-sm text-text-secondary mt-1">{{ $account->note }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Balance and Actions -->
                            <div class="flex flex-col items-end space-y-4">
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-text-primary">
                                        {{ number_format($account->current_balance, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-text-secondary">{{ $account->currency }}</p>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex flex-wrap items-center gap-2">
                                    @if(!$account->is_archived)
                                        <form action="{{ route('accounts.default', $account) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-xs px-3 py-1.5 border border-neutral-300 dark:border-neutral-600 rounded-md text-neutral-700 dark:text-neutral-300 bg-background-card hover:bg-background-tertiary transition-colors duration-200">
                                                Set Default
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('accounts.edit', $account) }}" 
                                       class="text-xs px-3 py-1.5 border border-neutral-300 dark:border-neutral-600 rounded-md text-neutral-700 dark:text-neutral-300 bg-background-card hover:bg-background-tertiary transition-colors duration-200">
                                        Edit
                                    </a>
                                    
                                    <form action="{{ route('accounts.archive', $account) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-xs px-3 py-1.5 border border-neutral-300 dark:border-neutral-600 rounded-md text-neutral-700 dark:text-neutral-300 bg-background-card hover:bg-background-tertiary transition-colors duration-200">
                                            {{ $account->is_archived ? 'Activate' : 'Archive' }}
                                        </button>
                                    </form>
                                    
                                    @if($account->transactions_count === 0 && $account->transfer_transactions_count === 0)
                                        <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-xs px-3 py-1.5 border border-red-300 dark:border-red-600 rounded-md text-red-700 dark:text-red-300 bg-background-card hover:bg-red-50 dark:hover:bg-red-900 transition-colors duration-200"
                                                    onclick="return confirm('Are you sure you want to delete this account? This action cannot be undone.')">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-text-primary">No accounts</h3>
                <p class="mt-1 text-sm text-text-secondary">Get started by creating your first account.</p>
                <div class="mt-6">
                    <a href="{{ route('accounts.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-ynab text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Account
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
