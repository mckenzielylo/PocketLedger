@extends('layouts.app', ['currentPage' => 'profile'])

@section('content')

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6" x-data="{ openSection: null }">
            <!-- Profile Overview Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-text-primary">Profile Overview</h3>
                </div>
                <div class="card-body">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            @if(Auth::user()->settings['avatar'] ?? false)
                                <img class="w-16 h-16 rounded-full object-cover border-4 border-primary-200 dark:border-primary-700 shadow-lg"
                                     src="{{ Storage::url(Auth::user()->settings['avatar']) }}"
                                     alt="{{ Auth::user()->name }}'s avatar">
                            @else
                                <div class="w-16 h-16 bg-gradient-primary rounded-full flex items-center justify-center text-white text-2xl font-bold border-4 border-primary-200 dark:border-primary-700 shadow-lg">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xl font-bold text-text-primary">{{ Auth::user()->name }}</h4>
                            <p class="text-text-secondary">{{ Auth::user()->email }}</p>
                            <p class="text-sm text-primary-400 mt-1">
                                Member since {{ Auth::user()->created_at->format('M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information Section -->
            <div class="card" :class="{ 'collapsed': openSection !== 'personal' }">
                <div class="card-header cursor-pointer" 
                     :class="{ 'collapsed': openSection !== 'personal' }"
                     @click="openSection = openSection === 'personal' ? null : 'personal'">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-text-primary">Personal Information</h3>
                        <svg class="w-4 h-4 text-text-secondary transition-transform duration-200" 
                             :class="{ 'rotate-180': openSection === 'personal' }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <div class="card-body py-4" x-show="openSection === 'personal'" x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 transform -translate-y-2" 
                     x-transition:enter-end="opacity-100 transform translate-y-0">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Profile Settings Section -->
            <div class="card" :class="{ 'collapsed': openSection !== 'settings' }">
                <div class="card-header cursor-pointer" 
                     :class="{ 'collapsed': openSection !== 'settings' }"
                     @click="openSection = openSection === 'settings' ? null : 'settings'">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-text-primary">Profile Settings</h3>
                        <svg class="w-4 h-4 text-text-secondary transition-transform duration-200" 
                             :class="{ 'rotate-180': openSection === 'settings' }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <div class="card-body py-4" x-show="openSection === 'settings'" x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 transform -translate-y-2" 
                     x-transition:enter-end="opacity-100 transform translate-y-0">
                    @include('profile.partials.update-profile-settings-form')
                </div>
            </div>

            <!-- Avatar Management Section -->
            <div class="card" :class="{ 'collapsed': openSection !== 'avatar' }">
                <div class="card-header cursor-pointer" 
                     :class="{ 'collapsed': openSection !== 'avatar' }"
                     @click="openSection = openSection === 'avatar' ? null : 'avatar'">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-text-primary">Avatar Management</h3>
                        <svg class="w-4 h-4 text-text-secondary transition-transform duration-200" 
                             :class="{ 'rotate-180': openSection === 'avatar' }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <div class="card-body py-4" x-show="openSection === 'avatar'" x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 transform -translate-y-2" 
                     x-transition:enter-end="opacity-100 transform translate-y-0">
                    @include('profile.partials.update-avatar-form')
                </div>
            </div>

            <!-- Password Management Section -->
            <div class="card" :class="{ 'collapsed': openSection !== 'password' }">
                <div class="card-header cursor-pointer" 
                     :class="{ 'collapsed': openSection !== 'password' }"
                     @click="openSection = openSection === 'password' ? null : 'password'">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-text-primary">Password Management</h3>
                        <svg class="w-4 h-4 text-text-secondary transition-transform duration-200" 
                             :class="{ 'rotate-180': openSection === 'password' }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <div class="card-body py-4" x-show="openSection === 'password'" x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 transform -translate-y-2" 
                     x-transition:enter-end="opacity-100 transform translate-y-0">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Account Management Section -->
            <div class="card" :class="{ 'collapsed': openSection !== 'account' }">
                <div class="card-header cursor-pointer" 
                     :class="{ 'collapsed': openSection !== 'account' }"
                     @click="openSection = openSection === 'account' ? null : 'account'">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-danger-400">Account Management</h3>
                        <svg class="w-4 h-4 text-text-secondary transition-transform duration-200" 
                             :class="{ 'rotate-180': openSection === 'account' }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <div class="card-body py-4" x-show="openSection === 'account'" x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 transform -translate-y-2" 
                     x-transition:enter-end="opacity-100 transform translate-y-0">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
