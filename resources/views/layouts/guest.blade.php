<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" x-data="{
    darkMode: localStorage.getItem('color-theme') === 'dark' ||
        (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
}" x-init="$watch('darkMode', val => {
    localStorage.setItem('color-theme', val ? 'dark' : 'light');
})"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'StockPro') }} - Control Access</title>

    <!-- Fonts: Plus Jakarta Sans for Premium SaaS feel -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest" defer></script>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Prevent flash of wrong theme -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' ||
            (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 10s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body
    class="antialiased min-h-screen relative bg-zinc-50 dark:bg-[#020617] text-zinc-900 dark:text-zinc-100 flex flex-col selection:bg-brand-500/30">

    <!-- Theme Toggle -->
    <div class="absolute top-6 right-6 z-50">
        <button @click="darkMode = !darkMode"
            class="p-2.5 rounded-full bg-white/50 dark:bg-zinc-800/50 backdrop-blur-md border border-zinc-200 dark:border-zinc-700 shadow-sm text-zinc-500 dark:text-zinc-400 hover:text-brand-600 dark:hover:text-brand-400 transition-all hover:scale-110 focus:outline-none ring-offset-2 ring-offset-zinc-50 dark:ring-offset-[#020617] focus:ring-2 focus:ring-brand-500">
            <i data-lucide="moon" class="w-5 h-5 block dark:hidden"></i>
            <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
        </button>
    </div>

    <!-- Animated Background Elements -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <!-- Blobs (Brand Matching) -->
        <div
            class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-brand-400/10 dark:bg-brand-600/10 rounded-full blur-[100px] mix-blend-multiply dark:mix-blend-screen animate-blob">
        </div>
        <div
            class="absolute top-[20%] right-[-10%] w-[40%] h-[50%] bg-purple-400/10 dark:bg-purple-600/10 rounded-full blur-[100px] mix-blend-multiply dark:mix-blend-screen animate-blob animation-delay-2000">
        </div>

        <!-- High-Tech Grid Pattern -->
        <div
            class="absolute inset-0 bg-[linear-gradient(to_right,#8b5cf608_1px,transparent_1px),linear-gradient(to_bottom,#8b5cf608_1px,transparent_1px)] bg-[size:32px:32px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]">
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="relative z-10 flex flex-col items-center justify-center flex-1 px-4 sm:px-6 lg:px-8 py-12">

        <!-- Branding Header -->
        <div class="mb-10 flex flex-col items-center gap-4">
            <div
                class="w-16 h-16 bg-brand-600 rounded-2xl flex items-center justify-center shadow-[0_8px_24px_rgba(139,92,246,0.3)]">
                <i data-lucide="package" class="text-white w-8 h-8"></i>
            </div>
            <div class="text-center">
                <h2 class="text-2xl font-bold tracking-tighter text-zinc-900 dark:text-white">STOCKPRO ENTERPRISE</h2>
                <p class="text-xs font-mono text-zinc-500 uppercase tracking-widest mt-1">Operational Intelligence
                    System</p>
            </div>
        </div>

        <div class="w-full max-w-md">
            <div class="saas-card-glass border-zinc-200 dark:border-zinc-800 p-8 shadow-2xl">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 flex flex-col items-center gap-2">
            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">
                Protected by Nexus Security Protocol
            </p>
            <p class="text-[10px] text-zinc-500 font-medium">
                &copy; {{ date('Y') }} StockPro. All rights reserved.
            </p>
        </div>
    </div>

    <script>
        lucide.createIcons();
        document.addEventListener('livewire:initialized', () => lucide.createIcons());
        document.addEventListener('livewire:navigated', () => lucide.createIcons());
    </script>
</body>

</html>
