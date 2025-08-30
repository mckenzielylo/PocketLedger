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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('SW registered: ', registration);
                    })
                    .catch(function(registrationError) {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }
    </script>
</head>
<body class="font-sans antialiased h-full bg-background-primary">
    <div class="min-h-full">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-background-secondary border-b border-neutral-700 shadow-ynab">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold text-text-primary">
                        {{ $header }}
                    </h1>
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="bg-background-primary min-h-screen">
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot }}
            @endif
        </main>

        <!-- Footer -->
        <footer class="bg-background-secondary border-t border-neutral-700 mt-auto">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="text-text-secondary text-sm">
                        © {{ date('Y') }} PocketLedger. Built with ❤️ using Laravel & Tailwind.
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="#" class="text-text-secondary hover:text-text-primary transition-colors duration-200">
                            Privacy Policy
                        </a>
                        <a href="#" class="text-text-secondary hover:text-text-primary transition-colors duration-200">
                            Terms of Service
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- PWA Install Prompt -->
    <div id="pwa-install-prompt" class="hidden fixed bottom-4 right-4 bg-background-card border border-neutral-700 rounded-xl shadow-ynab-xl p-4 max-w-sm">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-medium text-text-primary">Install PocketLedger</h3>
                <p class="text-sm text-text-secondary mt-1">Add to your home screen for quick access</p>
            </div>
            <button id="pwa-install-btn" class="btn-primary text-xs px-3 py-1.5">
                Install
            </button>
        </div>
        <button id="pwa-dismiss" class="absolute top-2 right-2 text-text-tertiary hover:text-text-secondary">
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
    </script>
</body>
</html>
