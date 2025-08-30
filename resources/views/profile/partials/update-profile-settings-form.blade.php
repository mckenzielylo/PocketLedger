<form method="post" action="{{ route('profile.settings') }}" class="space-y-4">
    @csrf
    @method('patch')

    <!-- Currency -->
    <div>
        <x-input-label for="currency" :value="__('Default Currency')" />
        <select id="currency" name="currency" class="mt-1 block w-full border-neutral-600 bg-background-secondary text-text-primary rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
            <option value="">Select Currency</option>
            <option value="USD" {{ ($user->settings['currency'] ?? '') === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
            <option value="EUR" {{ ($user->settings['currency'] ?? '') === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
            <option value="GBP" {{ ($user->settings['currency'] ?? '') === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
            <option value="IDR" {{ ($user->settings['currency'] ?? '') === 'IDR' ? 'selected' : '' }}>IDR - Indonesian Rupiah</option>
            <option value="JPY" {{ ($user->settings['currency'] ?? '') === 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen</option>
            <option value="SGD" {{ ($user->settings['currency'] ?? '') === 'SGD' ? 'selected' : '' }}>SGD - Singapore Dollar</option>
        </select>
        <x-input-error class="mt-1" :messages="$errors->get('currency')" />
    </div>

    <!-- Date Format -->
    <div>
        <x-input-label for="date_format" :value="__('Date Format')" />
        <select id="date_format" name="date_format" class="mt-1 block w-full border-neutral-600 bg-background-secondary text-text-primary rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
            <option value="">Select Date Format</option>
            <option value="Y-m-d" {{ ($user->settings['date_format'] ?? '') === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (2024-01-15)</option>
            <option value="d/m/Y" {{ ($user->settings['date_format'] ?? '') === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY (15/01/2024)</option>
            <option value="m/d/Y" {{ ($user->settings['date_format'] ?? '') === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY (01/15/2024)</option>
            <option value="d-m-Y" {{ ($user->settings['date_format'] ?? '') === 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY (15-01-2024)</option>
        </select>
        <x-input-error class="mt-1" :messages="$errors->get('date_format')" />
    </div>

    <!-- Language -->
    <div>
        <x-input-label for="language" :value="__('Language')" />
        <select id="language" name="language" class="mt-1 block w-full border-neutral-600 bg-background-secondary text-text-primary rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
            <option value="">Select Language</option>
            <option value="en" {{ ($user->settings['language'] ?? '') === 'en' ? 'selected' : '' }}>English</option>
            <option value="id" {{ ($user->settings['language'] ?? '') === 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
        </select>
        <x-input-error class="mt-1" :messages="$errors->get('language')" />
    </div>

    <!-- Theme -->
    <div>
        <x-input-label for="theme" :value="__('Theme')" />
        <select id="theme" name="theme" class="mt-1 block w-full border-neutral-600 bg-background-secondary text-text-primary rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
            <option value="">Select Theme</option>
            <option value="light" {{ ($user->settings['theme'] ?? '') === 'light' ? 'selected' : '' }}>Light</option>
            <option value="dark" {{ ($user->settings['theme'] ?? '') === 'dark' ? 'selected' : '' }}>Dark</option>
            <option value="auto" {{ ($user->settings['theme'] ?? '') === 'auto' ? 'selected' : '' }}>Auto (System)</option>
        </select>
        <x-input-error class="mt-1" :messages="$errors->get('theme')" />
    </div>

    <!-- Dashboard Layout -->
    <div>
        <x-input-label for="dashboard_layout" :value="__('Dashboard Layout')" />
        <select id="dashboard_layout" name="dashboard_layout" class="mt-1 block w-full border-neutral-600 bg-background-secondary text-text-primary rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
            <option value="">Select Layout</option>
            <option value="grid" {{ ($user->settings['dashboard_layout'] ?? '') === 'grid' ? 'selected' : '' }}>Grid</option>
            <option value="list" {{ ($user->settings['dashboard_layout'] ?? '') === 'list' ? 'selected' : '' }}>List</option>
            <option value="compact" {{ ($user->settings['dashboard_layout'] ?? '') === 'compact' ? 'selected' : '' }}>Compact</option>
        </select>
        <x-input-error class="mt-1" :messages="$errors->get('dashboard_layout')" />
    </div>

    <!-- Notifications -->
    <div>
        <x-input-label :value="__('Notifications')" class="mb-2" />
        <div class="space-y-2">
            <label class="flex items-center">
                <input type="checkbox" name="notifications[email]" value="1" 
                       {{ ($user->settings['notifications']['email'] ?? false) ? 'checked' : '' }}
                       class="rounded border-neutral-600 text-primary-600 shadow-sm focus:ring-primary-500 bg-background-secondary">
                <span class="ml-2 text-sm text-text-primary">Email Notifications</span>
            </label>
            
            <label class="flex items-center">
                <input type="checkbox" name="notifications[push]" value="1" 
                       {{ ($user->settings['notifications']['push'] ?? false) ? 'checked' : '' }}
                       class="rounded border-neutral-600 text-primary-600 shadow-sm focus:ring-primary-500 bg-background-secondary">
                <span class="ml-2 text-sm text-text-primary">Push Notifications</span>
            </label>
            
            <label class="flex items-center">
                <input type="checkbox" name="notifications[reminders]" value="1" 
                       {{ ($user->settings['notifications']['reminders'] ?? false) ? 'checked' : '' }}
                       class="rounded border-neutral-600 text-primary-600 shadow-sm focus:ring-primary-500 bg-background-secondary">
                <span class="ml-2 text-sm text-text-primary">Reminder Notifications</span>
            </label>
        </div>
    </div>

    <div class="flex items-center gap-4 pt-2">
        <x-primary-button>{{ __('Save Settings') }}</x-primary-button>

        @if (session('status') === 'settings-updated')
            <p
                x-data="{ show: true, timeout: null }"
                x-init="timeout = setTimeout(() => show = false, 2000)"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="text-sm text-success-400"
            >{{ __('Saved.') }}</p>
        @endif
    </div>
</form>
