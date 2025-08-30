<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Overview -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-4xl">
                    <!-- Temporarily commented out to debug -->
                    <!-- @include('profile.partials.profile-overview') -->
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Profile Overview</h2>
                    <p class="text-gray-600 dark:text-gray-400">This section is temporarily disabled for debugging.</p>
                </div>
            </div>

            <!-- Profile Information -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Profile Settings -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <!-- Temporarily commented out to debug -->
                    <!-- @include('profile.partials.update-profile-settings-form') -->
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Profile Settings</h2>
                    <p class="text-gray-600 dark:text-gray-400">This section is temporarily disabled for debugging.</p>
                </div>
            </div>

            <!-- Avatar Management -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <!-- Temporarily commented out to debug -->
                    <!-- @include('profile.partials.update-avatar-form') -->
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Avatar Management</h2>
                    <p class="text-gray-400">This section is temporarily disabled for debugging.</p>
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
