<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      class="h-full"
      x-data="{ 
          darkMode: localStorage.getItem('color-theme') === 'dark' || 
                   (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) 
      }"
      x-init="$watch('darkMode', val => {
          localStorage.setItem('color-theme', val ? 'dark' : 'light');
      })"
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'StockPro') }} - Access</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

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
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
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
                font-family: 'Inter', sans-serif;
            }
            [x-cloak] { display: none !important; }
        </style>
    </head>

    <body class="antialiased min-h-screen relative bg-slate-50 dark:bg-[#020617] text-slate-900 dark:text-slate-100 flex flex-col selection:bg-indigo-500/30">

        <!-- Theme Toggle -->
        <div class="absolute top-6 right-6 z-50">
            <button @click="darkMode = !darkMode" class="p-2.5 rounded-full bg-white/50 dark:bg-slate-800/50 backdrop-blur-md border border-slate-200 dark:border-slate-700 shadow-sm text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all hover:scale-110 focus:outline-none ring-offset-2 ring-offset-slate-50 dark:ring-offset-[#020617] focus:ring-2 focus:ring-indigo-500">
                <i data-lucide="moon" class="w-5 h-5 block dark:hidden"></i>
                <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
            </button>
        </div>

        <!-- Animated Background Elements -->
        <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
            <!-- Blobs -->
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-indigo-400/20 dark:bg-indigo-600/20 rounded-full blur-[100px] mix-blend-multiply dark:mix-blend-screen animate-blob"></div>
            <div class="absolute top-[20%] right-[-10%] w-[40%] h-[50%] bg-purple-400/20 dark:bg-purple-600/20 rounded-full blur-[100px] mix-blend-multiply dark:mix-blend-screen animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-[-20%] left-[20%] w-[50%] h-[50%] bg-pink-400/20 dark:bg-pink-600/20 rounded-full blur-[100px] mix-blend-multiply dark:mix-blend-screen animate-blob animation-delay-4000"></div>
            
            <!-- Grid Pattern -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]"></div>
        </div>

        <!-- Main Content Area -->
        <div class="relative z-10 flex flex-col items-center justify-center flex-1 px-4 sm:px-6 lg:px-8 py-12">
            
            {{ $slot }}

            <!-- Footer -->
            <p class="mt-12 text-sm text-slate-500 dark:text-slate-500 font-medium tracking-wide">
                &copy; {{ date('Y') }} StockPro. All rights reserved.
            </p>
        </div>

        <script>
            lucide.createIcons();
            document.addEventListener('livewire:load', () => lucide.createIcons());
            document.addEventListener('livewire:initialized', () => lucide.createIcons());
            document.addEventListener('livewire:navigated', () => lucide.createIcons());
        </script>
    </body>
</html>