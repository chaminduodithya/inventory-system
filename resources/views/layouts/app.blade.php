<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Inventory Manager') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
        <script src="https://unpkg.com/lucide@latest"></script>

        {{-- Prevent flash of wrong theme on page load --}}
        <script>
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased h-full">
        <div class="flex min-h-screen bg-slate-50/50 dark:bg-[#020617]">
            <!-- Sidebar -->
            <aside class="w-72 bg-white border-r border-slate-200 hidden lg:flex flex-col sticky top-0 h-screen transition-all duration-300 dark:bg-[#0f172a] dark:border-slate-800/60">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                            <i data-lucide="package" class="text-white w-6 h-6"></i>
                        </div>
                        <span class="font-bold text-xl text-slate-900 tracking-tight dark:text-white">StockPro</span>
                    </div>
                </div>

                <nav class="flex-1 p-6 space-y-1.5">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 shadow-sm font-semibold dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/50 dark:hover:text-slate-200' }}">
                        <i data-lucide="layout-grid" class="w-5 h-5"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('stocks') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('stocks') ? 'bg-indigo-50 text-indigo-700 shadow-sm font-semibold dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/50 dark:hover:text-slate-200' }}">
                        <i data-lucide="archive" class="w-5 h-5"></i>
                        <span>Inventory</span>
                    </a>
                    <a href="{{ route('dealers') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dealers') ? 'bg-indigo-50 text-indigo-700 shadow-sm font-semibold dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/50 dark:hover:text-slate-200' }}">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span>Dealers</span>
                    </a>
                    <a href="{{ route('invoices') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('invoices') ? 'bg-indigo-50 text-indigo-700 shadow-sm font-semibold dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/50 dark:hover:text-slate-200' }}">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        <span>Invoices</span>
                    </a>
                </nav>

                <!-- Bottom Section: Profile & Theme Toggle -->
                <div class="p-6 border-t border-slate-100 dark:border-slate-800/60 space-y-4">
                    <button id="theme-toggle-btn"
                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all border border-transparent dark:border-slate-800/60">
                        <div class="flex items-center gap-3">
                            <i data-lucide="moon" class="w-5 h-5 block dark:hidden"></i>
                            <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
                            <span class="text-sm font-medium">Appearance</span>
                        </div>
                        <div class="w-9 h-5 bg-slate-200 dark:bg-slate-700 rounded-full relative transition-colors">
                            <div id="theme-toggle-dot" class="absolute w-3.5 h-3.5 bg-white rounded-full top-0.5 left-0.5 transition-all duration-300"></div>
                        </div>
                    </button>

                    <div class="bg-slate-50 dark:bg-[#1e293b]/40 rounded-2xl p-4 flex items-center gap-3 border border-transparent dark:border-slate-800/40">
                        <div class="w-10 h-10 bg-slate-200 dark:bg-indigo-500/20 rounded-lg flex items-center justify-center text-slate-600 dark:text-indigo-400 font-bold">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-200 truncate">{{ Auth::user()->name ?? 'User' }}</p>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-xs text-rose-500 hover:text-rose-600 font-medium cursor-pointer">Sign Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Mobile Navigation Bar -->
                <nav class="lg:hidden bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between dark:bg-[#0f172a] dark:border-slate-800/60">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <i data-lucide="package" class="text-white w-5 h-5"></i>
                        </div>
                        <span class="font-bold text-lg text-slate-900 dark:text-white">StockPro</span>
                    </div>
                    <button class="p-2 text-slate-500 hover:bg-slate-50 rounded-lg">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </nav>

                <main class="flex-1 p-6 lg:p-10 max-w-7xl mx-auto w-full">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <script>
            lucide.createIcons();

            document.addEventListener('livewire:navigated', () => {
                lucide.createIcons();
            });

            // Theme Toggle (vanilla JS — does NOT interfere with Livewire)
            const toggleBtn = document.getElementById('theme-toggle-btn');
            const toggleDot = document.getElementById('theme-toggle-dot');

            function applyDotPosition() {
                if (document.documentElement.classList.contains('dark')) {
                    toggleDot.style.transform = 'translateX(1rem)';
                } else {
                    toggleDot.style.transform = 'translateX(0)';
                }
            }
            applyDotPosition();

            toggleBtn.addEventListener('click', function() {
                document.documentElement.classList.toggle('dark');
                const isDark = document.documentElement.classList.contains('dark');
                localStorage.setItem('color-theme', isDark ? 'dark' : 'light');
                applyDotPosition();
            });
        </script>
    </body>
</html>