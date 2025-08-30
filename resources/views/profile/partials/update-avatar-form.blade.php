<div class="space-y-6">
    <!-- Current Avatar Display -->
    <div class="flex items-center space-x-6">
        <div class="flex-shrink-0">
            @if($user->settings['avatar'] ?? false)
                <img class="w-24 h-24 rounded-full object-cover border-4 border-primary-200 dark:border-primary-700 shadow-lg" 
                     src="{{ Storage::url($user->settings['avatar']) }}" 
                     alt="{{ $user->name }}'s avatar">
            @else
                <div class="w-24 h-24 bg-gradient-primary rounded-full flex items-center justify-center text-white text-4xl font-bold border-4 border-primary-200 dark:border-primary-700 shadow-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
        </div>
        
        <div class="flex-1">
            <h3 class="text-lg font-medium text-text-primary">{{ $user->name }}</h3>
            <p class="text-sm text-text-secondary">
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
                            class="text-sm text-danger-400 hover:text-danger-300">
                        Remove current picture
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Upload New Avatar -->
    <div class="border-t border-neutral-700 pt-6">
        <h4 class="text-md font-medium text-text-primary mb-4">Upload New Picture</h4>
        
        <form method="post" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('patch')

            <div>
                <x-input-label for="avatar" :value="__('Profile Picture')" />
                <input type="file" 
                       id="avatar" 
                       name="avatar" 
                       accept="image/*"
                       class="mt-1 block w-full text-sm text-text-secondary
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-primary-500 file:text-white
                              hover:file:bg-primary-600" />
                <p class="mt-1 text-xs text-text-tertiary">
                    Accepted formats: JPG, PNG, GIF. Maximum size: 2MB.
                </p>
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Upload Picture') }}</x-primary-button>

                @if (session('status') === 'avatar-updated')
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
                    >{{ __('Profile picture updated successfully.') }}</p>
                @endif

                @if (session('status') === 'avatar-deleted')
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
                        class="text-sm text-primary-400"
                    >{{ __('Profile picture removed successfully.') }}</p>
                @endif
            </div>
        </form>
    </div>
</div>
