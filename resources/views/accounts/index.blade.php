@extends('layouts.app', ['currentPage' => 'accounts'])

@section('content')
<x-page-layout 
    :title="'Accounts'" 
    :description="'Manage your bank accounts, cash, and e-wallets'"
    :icon="'<svg class=\"w-6 h-6 text-primary\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z\"></path></svg>'"
    :actions="'<a href=\"' . route('accounts.create') . '\" class=\"inline-flex items-center px-4 py-2 bg-primary text-primary-foreground hover:bg-primary/90 rounded-md text-sm font-medium\"><svg class=\"-ml-1 mr-2 h-5 w-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 6v6m0 0v6m0-6h6m-6 0H6\"></path></svg>Add Account</a>'"
>

    <!-- Information Section -->
    @if(Auth::user()->settings['default_account_id'])
        <div class="bg-blue-50 dark:bg-blue-900/20 border-b border-blue-200 dark:border-blue-800">
            <div class="px-4 py-3 sm:px-6">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>Default Account:</strong> 
                        @php
                            $defaultAccount = Auth::user()->accounts()->find(Auth::user()->settings['default_account_id']);
                        @endphp
                        @if($defaultAccount)
                            <span class="font-medium">{{ $defaultAccount->name }}</span> 
                            is set as your default account for recurring transactions and quick actions.
                        @else
                            Your default account has been removed. Please set a new default account.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Account List -->
    @if($accounts->count() > 0)
        <div class="space-y-4">
            @foreach($accounts as $account)
                <x-ui.card>
                    <x-ui.card-content class="p-6">
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
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $account->name }}</h3>
                                        @if($account->is_archived)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                Archived
                                            </span>
                                        @endif
                                        @if(Auth::user()->settings['default_account_id'] == $account->id)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Default
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="capitalize">{{ $account->type }}</span>
                                        <span>•</span>
                                        <span>{{ $account->currency }}</span>
                                        @if($account->transactions_count > 0)
                                            <span>•</span>
                                            <span>{{ $account->transactions_count }} transactions</span>
                                        @endif
                                    </div>
                                    @if($account->note)
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $account->note }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Balance and Actions -->
                            <div class="flex flex-col items-end space-y-3">
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ $account->currency_symbol }} {{ number_format($account->current_balance, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $account->currency }}</p>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex items-center space-x-2">
                                    @if(!$account->is_archived)
                                        @if(Auth::user()->settings['default_account_id'] != $account->id)
                                            <form action="{{ route('accounts.default', $account) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-xs px-3 py-1.5 border border-blue-300 dark:border-blue-600 rounded text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors">
                                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Set Default
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs px-3 py-1.5 border border-blue-200 dark:border-blue-700 rounded text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 cursor-default">
                                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Current Default
                                            </span>
                                        @endif
                                    @endif
                                    
                                    <a href="{{ route('accounts.edit', $account) }}" 
                                       class="text-xs px-2 py-1 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        Edit
                                    </a>
                                    
                                    <form action="{{ route('accounts.archive', $account) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-xs px-2 py-1 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            {{ $account->is_archived ? 'Activate' : 'Archive' }}
                                        </button>
                                    </form>
                                    
                                    @if($account->transactions_count === 0 && $account->transfer_transactions_count === 0)
                                        <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-xs px-2 py-1 border border-red-300 dark:border-red-600 rounded text-red-700 dark:text-red-300 bg-white dark:bg-gray-700 hover:bg-red-50 dark:hover:bg-red-900"
                                                    onclick="return confirm('Are you sure you want to delete this account? This action cannot be undone.')">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </x-ui.card-content>
                </x-ui.card>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <x-ui.card>
            <x-ui.card-content class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-foreground">No accounts</h3>
                <p class="mt-1 text-sm text-muted-foreground">Get started by creating your first account.</p>
                <div class="mt-6">
                    <x-ui.button tag="a" href="{{ route('accounts.create') }}">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Account
                    </x-ui.button>
                </div>
            </x-ui.card-content>
        </x-ui.card>
    @endif
</x-page-layout>

<!-- Floating Action Button -->
<div class="fixed bottom-20 right-4 z-50">
    <x-ui.button 
        tag="a" 
        href="{{ route('accounts.create') }}" 
        size="icon"
        class="w-14 h-14 rounded-full shadow-lg"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
    </x-ui.button>
</div>
@endsection
