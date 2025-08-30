<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Picture') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Update your profile picture to personalize your account.') }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        <!-- Current Avatar Display -->
        <div class="flex items-center space-x-6">
            <div class="flex-shrink-0">
                @if($user->settings['avatar'] ?? false)
                    <img class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-gray-700 shadow-lg" 
                         src="{{ Storage::url($user->settings['avatar']) }}" 
                         alt="{{ $user->name }}'s avatar">
                @else
                    <div class="w-24 h-24 bg-primary-500 rounded-full flex items-center justify-center text-white text-4xl font-bold border-4 border-white dark:border-gray-700 shadow-lg">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            
            <div class="flex-1">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $user->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    @if($user->settings['avatar'] ?? false)
                        Current profile picture
                    @else
                        No profile picture set
                    @endif
                </p>
                
                @if($user->settings['avatar'] ?? false)
                    <form method="post" action="{{ route('profile.avatar.delete') }}" class="mt-2">
                        @csrf
                        @method('delete')
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to remove your profile picture?')"
                                class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                            Remove current picture
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Upload New Avatar -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Upload New Picture</h4>
            
            <form method="post" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('patch')

                <div>
                    <x-input-label for="avatar" :value="__('Profile Picture')" />
                    <input type="file" 
                           id="avatar" 
                           name="avatar" 
                           accept="image/*"
                           class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-primary-50 file:text-primary-700
                                  hover:file:bg-primary-100
                                  dark:file:bg-primary-900/20 dark:file:text-primary-400
                                  dark:hover:file:bg-primary-800/20" />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Accepted formats: JPG, PNG, GIF. Maximum size: 2MB.
                    </p>
                    <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Upload Picture') }}</x-primary-button>

                    @if (session('status') === 'avatar-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-green-600 dark:text-green-400"
                        >{{ __('Profile picture updated successfully.') }}</p>
                    @endif

                    @if (session('status') === 'avatar-deleted')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-blue-600 dark:text-blue-400"
                        >{{ __('Profile picture removed successfully.') }}</p>
                    @endif
                </div>
            </form>
        </div>

        <!-- Avatar Guidelines -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Picture Guidelines</h4>
            <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                <li>• Use a clear, high-quality image</li>
                <li>• Square images work best (1:1 aspect ratio)</li>
                <li>• Recommended size: 400x400 pixels or larger</li>
                <li>• Supported formats: JPG, PNG, GIF</li>
                <li>• Maximum file size: 2MB</li>
            </ul>
        </div>
    </div>
</section>
