<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Settings') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Customize your PocketLedger experience and preferences.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.settings') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Currency -->
        <div>
            <x-input-label for="currency" :value="__('Default Currency')" />
            <select id="currency" name="currency" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">Select Currency</option>
                <option value="USD" {{ ($user->settings['currency'] ?? '') === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                <option value="EUR" {{ ($user->settings['currency'] ?? '') === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                <option value="GBP" {{ ($user->settings['currency'] ?? '') === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                <option value="IDR" {{ ($user->settings['currency'] ?? '') === 'IDR' ? 'selected' : '' }}>IDR - Indonesian Rupiah</option>
                <option value="JPY" {{ ($user->settings['currency'] ?? '') === 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen</option>
                <option value="SGD" {{ ($user->settings['currency'] ?? '') === 'SGD' ? 'selected' : '' }}>SGD - Singapore Dollar</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('currency')" />
        </div>

        <!-- Date Format -->
        <div>
            <x-input-label for="date_format" :value="__('Date Format')" />
            <select id="date_format" name="date_format" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">Select Date Format</option>
                <option value="Y-m-d" {{ ($user->settings['date_format'] ?? '') === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (2024-01-15)</option>
                <option value="d/m/Y" {{ ($user->settings['date_format'] ?? '') === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY (15/01/2024)</option>
                <option value="m/d/Y" {{ ($user->settings['date_format'] ?? '') === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY (01/15/2024)</option>
                <option value="d-m-Y" {{ ($user->settings['date_format'] ?? '') === 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY (15-01-2024)</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('date_format')" />
        </div>

        <!-- Language -->
        <div>
            <x-input-label for="language" :value="__('Language')" />
            <select id="language" name="language" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">Select Language</option>
                <option value="en" {{ ($user->settings['language'] ?? '') === 'en' ? 'selected' : '' }}>English</option>
                <option value="id" {{ ($user->settings['language'] ?? '') === 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('language')" />
        </div>

        <!-- Theme -->
        <div>
            <x-input-label for="theme" :value="__('Theme')" />
            <select id="theme" name="theme" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">Select Theme</option>
                <option value="light" {{ ($user->settings['theme'] ?? '') === 'light' ? 'selected' : '' }}>Light</option>
                <option value="dark" {{ ($user->settings['theme'] ?? '') === 'dark' ? 'selected' : '' }}>Dark</option>
                <option value="auto" {{ ($user->settings['theme'] ?? '') === 'auto' ? 'selected' : '' }}>Auto (System)</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('theme')" />
        </div>

        <!-- Dashboard Layout -->
        <div>
            <x-input-label for="dashboard_layout" :value="__('Dashboard Layout')" />
            <select id="dashboard_layout" name="dashboard_layout" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">Select Layout</option>
                <option value="grid" {{ ($user->settings['dashboard_layout'] ?? '') === 'grid' ? 'selected' : '' }}>Grid</option>
                <option value="list" {{ ($user->settings['dashboard_layout'] ?? '') === 'list' ? 'selected' : '' }}>List</option>
                <option value="compact" {{ ($user->settings['dashboard_layout'] ?? '') === 'compact' ? 'selected' : '' }}>Compact</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('dashboard_layout')" />
        </div>

        <!-- Notifications -->
        <div>
            <x-input-label :value="__('Notifications')" class="mb-3" />
            <div class="space-y-3">
                <label class="flex items-center">
                    <input type="checkbox" name="notifications[email]" value="1" 
                           {{ ($user->settings['notifications']['email'] ?? false) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Email Notifications</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" name="notifications[push]" value="1" 
                           {{ ($user->settings['notifications']['push'] ?? false) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Push Notifications</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" name="notifications[reminders]" value="1" 
                           {{ ($user->settings['notifications']['reminders'] ?? false) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Payment Reminders</span>
                </label>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('notifications.*')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Settings') }}</x-primary-button>

            @if (session('status') === 'settings-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Settings saved successfully.') }}</p>
            @endif
        </div>
    </form>
</section>
