<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PocketLedger') }}</title>

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#0ea5e9">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="PocketLedger">
    <link rel="apple-touch-icon" href="{{ asset('images/icon-192x192.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(function(registration) {
                        console.log('SW registered: ', registration);
                    })
                    .catch(function(registrationError) {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }
    </script>

    <!-- Theme Initialization -->
    <script>
        // Initialize theme based on user preference
        (function() {
            const userTheme = '{{ Auth::user()->effective_theme }}';
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            // Set initial theme
            if (userTheme === 'dark' || (userTheme === 'auto' && systemPrefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            // Store theme preference
            localStorage.setItem('theme', userTheme);
        })();
    </script>
</head>
<body class="font-sans antialiased h-full bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-200">
    <div class="min-h-full">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="min-h-screen">
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot }}
            @endif
        </main>

        <!-- Bottom Navigation -->
        <x-bottom-navigation :current-page="$currentPage ?? ''" />

        <!-- Footer -->
        <footer class="border-t bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="text-gray-600 dark:text-gray-400 text-sm">
                        © {{ date('Y') }} PocketLedger. Built with ❤️ using Laravel & Tailwind.
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors duration-200">
                            Privacy Policy
                        </a>
                        <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors duration-200">
                            Terms of Service
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- PWA Install Prompt -->
    <div id="pwa-install-prompt" class="hidden fixed bottom-4 right-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg p-4 max-w-sm">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white">Install PocketLedger</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Add to your home screen for quick access</p>
            </div>
            <button id="pwa-install-btn" class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1.5 rounded-lg font-medium transition-colors">
                Install
            </button>
        </div>
        <button id="pwa-dismiss" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <script>
        // PWA Install Prompt Logic
        let deferredPrompt;
        const installPrompt = document.getElementById('pwa-install-prompt');
        const installBtn = document.getElementById('pwa-install-btn');
        const dismissBtn = document.getElementById('pwa-dismiss');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            installPrompt.classList.remove('hidden');
        });

        installBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    installPrompt.classList.add('hidden');
                }
                deferredPrompt = null;
            }
        });

        dismissBtn.addEventListener('click', () => {
            installPrompt.classList.add('hidden');
        });

        // Theme switching functionality
        window.addEventListener('storage', function(e) {
            if (e.key === 'theme') {
                const newTheme = e.newValue;
                if (newTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                    document.documentElement.setAttribute('data-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    document.documentElement.setAttribute('data-theme', 'light');
                }
            }
        });

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            const userTheme = localStorage.getItem('theme');
            if (userTheme === 'auto') {
                if (e.matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        });

        // Theme toggle functionality
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                console.log('Theme toggle clicked!');
                console.log('Current dark class:', document.documentElement.classList.contains('dark'));

                
                // Get current theme state
                const isDark = document.documentElement.classList.contains('dark');
                const newTheme = isDark ? 'light' : 'dark';
                
                console.log('Switching to theme:', newTheme);
                
                // Update DOM
                if (newTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                    console.log('Added dark class');
                } else {
                    document.documentElement.classList.remove('dark');
                    console.log('Removed dark class');
                }
                
                // Update localStorage
                localStorage.setItem('theme', newTheme);
                
                // Update user settings in database
                updateUserTheme(newTheme);
                
                // Update icon visibility
                updateThemeToggleIcon();
                
                console.log('Switched to', newTheme, 'theme');
                console.log('Final dark class:', document.documentElement.classList.contains('dark'));

            });
        }

        // Function to update theme toggle icon
        function updateThemeToggleIcon() {
            if (document.documentElement.classList.contains('dark')) {
                themeToggleDarkIcon.classList.remove('hidden');
                themeToggleLightIcon.classList.add('hidden');
            } else {
                themeToggleDarkIcon.classList.add('hidden');
                themeToggleLightIcon.classList.remove('hidden');
            }
        }

        // Function to update user theme in database
        async function updateUserTheme(theme) {
            try {
                const response = await fetch('/user/theme', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ theme: theme })
                });
                
                if (!response.ok) {
                    console.error('Failed to update theme setting');
                }
            } catch (error) {
                console.error('Error updating theme:', error);
            }
        }

        // Initialize theme toggle icon
        updateThemeToggleIcon();
    </script>
</body>
</html>
