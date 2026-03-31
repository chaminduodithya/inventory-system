<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'StockPro') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap"
        rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    {{-- Prevent flash of wrong theme on page load --}}
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased h-full" x-data="{ mobileMenuOpen: false }">
    <div class="flex min-h-screen bg-zinc-50 dark:bg-[#020617]">
        <!-- Sidebar -->
        <aside
            class="w-72 bg-white border-r border-zinc-200 hidden lg:flex flex-col sticky top-0 h-screen transition-all duration-300 dark:bg-zinc-950 dark:border-zinc-800/60">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-brand-600 rounded-xl flex items-center justify-center shadow-[0_4px_12px_rgba(139,92,246,0.35)] dark:shadow-none transition-transform hover:scale-105">
                        <i data-lucide="package" class="text-white w-5 h-5"></i>
                    </div>
                    <div class="flex flex-col">
                        <span
                            class="font-bold text-xl text-zinc-900 tracking-tight dark:text-white leading-none">StockPro</span>
                        <span class="text-[10px] font-mono text-emerald-500 mt-1 flex items-center gap-1"><span
                                class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> SYSTEM
                            ACTIVE</span>
                    </div>
                </div>
            </div>

            <!-- Sidebar Navigation -->

            <!--Dashboard-->
            <nav class="flex-1 p-6 space-y-1">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-brand-50 text-brand-700 shadow-sm font-semibold dark:bg-brand-500/10 dark:text-brand-400' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/50 dark:hover:text-zinc-200' }}">
                    <i data-lucide="layout-grid" class="w-4 h-4"></i>
                    <span class="text-sm">Dashboard</span>
                </a>

                <!--Inventory-->
                <div x-data="{ inventoryOpen: {{ request()->routeIs('stocks.*') ? 'true' : 'false' }} }">
                    <button @click="inventoryOpen = !inventoryOpen"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('stocks.*') ? 'bg-brand-50 text-brand-700 shadow-sm font-semibold dark:bg-brand-500/10 dark:text-brand-400' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/50 dark:hover:text-zinc-200' }}">
                        <div class="flex items-center gap-3">
                            <i data-lucide="archive" class="w-4 h-4"></i>
                            <span class="text-sm">Inventory</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform"
                            :class="inventoryOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="inventoryOpen" x-collapse
                        class="mt-0.5 ml-4 border-l border-zinc-100 dark:border-zinc-800 space-y-0.5">
                        <a href="{{ route('stocks.add') }}"
                            class="flex items-center gap-3 px-4 py-1.5 text-xs rounded-r-lg transition-all {{ request()->routeIs('stocks.add') || request()->routeIs('stocks.edit') ? 'text-brand-700 bg-brand-50/50 font-medium dark:text-brand-400 dark:bg-brand-500/5 border-l-2 border-brand-500' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/30' }}">
                            <span>Add Stock</span>
                        </a>
                        <a href="{{ route('stocks.list') }}"
                            class="flex items-center gap-3 px-4 py-1.5 text-xs rounded-r-lg transition-all {{ request()->routeIs('stocks.list') ? 'text-brand-700 bg-brand-50/50 font-medium dark:text-brand-400 dark:bg-brand-500/5 border-l-2 border-brand-500' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/30' }}">
                            <span>View All</span>
                        </a>
                    </div>
                </div>

                <!--Dealers-->
                <div x-data="{ dealersOpen: {{ request()->routeIs('dealers.*') ? 'true' : 'false' }} }">
                    <button @click="dealersOpen = !dealersOpen"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('dealers.*') ? 'bg-brand-50 text-brand-700 shadow-sm font-semibold dark:bg-brand-500/10 dark:text-brand-400' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/50 dark:hover:text-zinc-200' }}">
                        <div class="flex items-center gap-3">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            <span class="text-sm">Dealers</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform"
                            :class="dealersOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="dealersOpen" x-collapse
                        class="mt-0.5 ml-4 border-l border-zinc-100 dark:border-zinc-800 space-y-0.5">
                        <a href="{{ route('dealers.add') }}"
                            class="flex items-center gap-3 px-4 py-1.5 text-xs rounded-r-lg transition-all {{ request()->routeIs('dealers.add') || request()->routeIs('dealers.edit') ? 'text-brand-700 bg-brand-50/50 font-medium dark:text-brand-400 dark:bg-brand-500/5 border-l-2 border-brand-500' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/30' }}">
                            Add Dealer
                        </a>
                        <a href="{{ route('dealers.list') }}"
                            class="flex items-center gap-3 px-4 py-1.5 text-xs rounded-r-lg transition-all {{ request()->routeIs('dealers.list') ? 'text-brand-700 bg-brand-50/50 font-medium dark:text-brand-400 dark:bg-brand-500/5 border-l-2 border-brand-500' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/30' }}">
                            View All
                        </a>
                    </div>
                </div>

                <!--Invoices-->
                <div x-data="{ invoicesOpen: {{ request()->routeIs('invoices.*') || request()->routeIs('invoice.*') ? 'true' : 'false' }} }">
                    <button @click="invoicesOpen = !invoicesOpen"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('invoices.*') || request()->routeIs('invoice.*') ? 'bg-brand-50 text-brand-700 shadow-sm font-semibold dark:bg-brand-500/10 dark:text-brand-400' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/50 dark:hover:text-zinc-200' }}">
                        <div class="flex items-center gap-3">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            <span class="text-sm">Invoices</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform"
                            :class="invoicesOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="invoicesOpen" x-collapse
                        class="mt-0.5 ml-4 border-l border-zinc-100 dark:border-zinc-800 space-y-0.5">
                        <a href="{{ route('invoices.add') }}"
                            class="flex items-center gap-3 px-4 py-1.5 text-xs rounded-r-lg transition-all {{ request()->routeIs('invoices.add') || request()->routeIs('invoices.edit') ? 'text-brand-700 bg-brand-50/50 font-medium dark:text-brand-400 dark:bg-brand-500/5 border-l-2 border-brand-500' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/30' }}">
                            Create Invoice
                        </a>
                        <a href="{{ route('invoices.list') }}"
                            class="flex items-center gap-3 px-4 py-1.5 text-xs rounded-r-lg transition-all {{ request()->routeIs('invoices.list') ? 'text-brand-700 bg-brand-50/50 font-medium dark:text-brand-400 dark:bg-brand-500/5 border-l-2 border-brand-500' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/30' }}">
                            View All
                        </a>
                    </div>
                </div>

                <!--Summary-->
                <a href="{{ route('summary') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('summary') ? 'bg-brand-50 text-brand-700 shadow-sm font-semibold dark:bg-brand-500/10 dark:text-brand-400' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/50 dark:hover:text-zinc-200' }}">
                    <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                    <span class="text-sm">Summary</span>
                </a>
            </nav>

            <!-- Bottom Section: Profile & Theme Toggle -->
            <div class="p-6 border-t border-zinc-100 dark:border-zinc-800/60 space-y-4">
                <button
                    class="theme-toggle-btn w-full flex items-center justify-between px-4 py-3 rounded-xl bg-zinc-50 dark:bg-zinc-800/40 text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-all border border-transparent dark:border-zinc-800/60">
                    <div class="flex items-center gap-3">
                        <i data-lucide="moon" class="w-5 h-5 block dark:hidden"></i>
                        <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
                        <span class="text-sm font-medium">Appearance</span>
                    </div>
                    <div class="w-9 h-5 bg-slate-200 dark:bg-slate-700 rounded-full relative transition-colors">
                        <div
                            class="theme-toggle-dot absolute w-3.5 h-3.5 bg-white rounded-full top-0.5 left-0.5 transition-all duration-300">
                        </div>
                    </div>
                </button>

                <div
                    class="bg-zinc-50 dark:bg-[#1e293b]/40 rounded-2xl p-4 flex items-center gap-3 border border-transparent dark:border-zinc-800/40">
                    <div
                        class="w-10 h-10 bg-zinc-200 dark:bg-brand-500/20 rounded-lg flex items-center justify-center text-zinc-600 dark:text-brand-400 font-bold">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-200 truncate">
                            {{ Auth::user()->name ?? 'User' }}</p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="text-xs text-rose-500 hover:text-rose-600 font-medium cursor-pointer">Sign
                                Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Desktop Top Bar -->
            <header
                class="hidden lg:flex items-center justify-between px-10 py-4 bg-white/80 dark:bg-[#020617]/80 backdrop-blur-md sticky top-0 z-40 border-b border-zinc-100 dark:border-zinc-800/60">
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Network Status:
                            Nominal</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <livewire:notification-center />
                </div>
            </header>

            <!-- Mobile Navigation Bar -->
            <nav
                class="lg:hidden bg-white dark:bg-[#020617] border-b border-zinc-200 dark:border-zinc-800/60 px-6 py-4 flex items-center justify-between sticky top-0 z-50 backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-brand-600 rounded-xl flex items-center justify-center shadow-md">
                        <i data-lucide="package" class="text-white w-5 h-5"></i>
                    </div>
                    <span class="font-bold text-xl text-zinc-900 dark:text-white tracking-tight">StockPro</span>
                </div>

                <div class="flex items-center gap-2">
                    <livewire:notification-center />
                    <button @click="mobileMenuOpen = true"
                        class="p-2 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                        <i data-lucide="menu" class="w-7 h-7"></i>
                    </button>
                </div>
            </nav>

            <!-- Mobile Sidebar / Drawer -->
            <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" x-cloak
                class="fixed inset-0 z-50 lg:hidden" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>

                <!-- Sidebar -->
                <aside x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false"
                    x-transition:enter="transform transition ease-out duration-300"
                    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in duration-200"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                    class="absolute inset-y-0 left-0 w-80 bg-white dark:bg-[#020617] border-r border-zinc-200 dark:border-zinc-800/60 shadow-2xl overflow-y-auto">

                    <!-- Mobile Header inside sidebar -->
                    <div
                        class="p-6 border-b border-zinc-200 dark:border-zinc-800/60 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-brand-600 rounded-xl flex items-center justify-center">
                                <i data-lucide="package" class="text-white w-6 h-6"></i>
                            </div>
                            <span class="font-bold text-xl text-zinc-900 dark:text-white">StockPro</span>
                        </div>
                        <button @click="mobileMenuOpen = false"
                            class="p-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <!-- Mobile Nav Links (copy your desktop nav here) -->
                    <nav class="p-6 space-y-2 flex-1">
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-brand-50 text-brand-700 shadow-sm font-semibold dark:bg-brand-500/10 dark:text-brand-400' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/50 dark:hover:text-zinc-200' }}">
                            <i data-lucide="layout-grid" class="w-5 h-5"></i>
                            <span>Dashboard</span>
                        </a>

                        <!--Inventory-->
                        <div x-data="{ inventoryOpen: {{ request()->routeIs('stocks.*') ? 'true' : 'false' }} }">
                            <button @click="inventoryOpen = !inventoryOpen"
                                class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('stocks.*') ? 'bg-brand-50 text-brand-700 shadow-sm font-semibold dark:bg-brand-500/10 dark:text-brand-400' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/50 dark:hover:text-zinc-200' }}">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="archive" class="w-5 h-5"></i>
                                    <span>Inventory</span>
                                </div>
                                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform"
                                    :class="inventoryOpen ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="inventoryOpen" x-collapse class="mt-1 ml-6 space-y-1">
                                <a href="{{ route('stocks.add') }}"
                                    class="flex items-center gap-3 px-4 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('stocks.add') || request()->routeIs('stocks.edit') ? 'text-brand-700 bg-brand-50/50 font-medium dark:text-brand-400 dark:bg-brand-500/5' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/30' }}">
                                    <i data-lucide="circle-plus" class="w-4 h-4 flex-shrink-0"></i> <span>Add
                                        Stock</span>
                                </a>
                                <a href="{{ route('stocks.list') }}"
                                    class="flex items-center gap-3 px-4 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('stocks.list') ? 'text-brand-700 bg-brand-50/50 font-medium dark:text-brand-400 dark:bg-brand-500/5' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/30' }}">
                                    <i data-lucide="list" class="w-4 h-4 flex-shrink-0"></i> <span>View All</span>
                                </a>
                            </div>
                        </div>

                        <!--Dealers-->
                        <div x-data="{ dealersOpen: {{ request()->routeIs('dealers.*') ? 'true' : 'false' }} }">
                            <button @click="dealersOpen = !dealersOpen"
                                class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dealers.*') ? 'bg-brand-50 text-brand-700 shadow-sm font-semibold dark:bg-brand-500/10 dark:text-brand-400' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/50 dark:hover:text-zinc-200' }}">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="users" class="w-5 h-5"></i>
                                    <span>Dealers</span>
                                </div>
                                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform"
                                    :class="dealersOpen ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="dealersOpen" x-collapse class="mt-1 ml-8 space-y-1">
                                <a href="{{ route('dealers.add') }}"
                                    class="flex items-center gap-3 px-4 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('dealers.add') || request()->routeIs('dealers.edit') ? 'text-brand-700 bg-brand-50/50 font-medium dark:text-brand-400 dark:bg-brand-500/5' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/30' }}">
                                    <i data-lucide="circle-plus" class="w-4 h-4 flex-shrink-0"></i> Add Dealer
                                </a>
                                <a href="{{ route('dealers.list') }}"
                                    class="flex items-center gap-3 px-4 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('dealers.list') ? 'text-brand-700 bg-brand-50/50 font-medium dark:text-brand-400 dark:bg-brand-500/5' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/30' }}">
                                    <i data-lucide="list" class="w-4 h-4 flex-shrink-0"></i> View All
                                </a>
                            </div>
                        </div>

                        <!--Invoices-->
                        <div x-data="{ invoicesOpen: {{ request()->routeIs('invoices.*') || request()->routeIs('invoice.*') ? 'true' : 'false' }} }">
                            <button @click="invoicesOpen = !invoicesOpen"
                                class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('invoices.*') || request()->routeIs('invoice.*') ? 'bg-brand-50 text-brand-700 shadow-sm font-semibold dark:bg-brand-500/10 dark:text-brand-400' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/50 dark:hover:text-zinc-200' }}">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="file-text" class="w-5 h-5"></i>
                                    <span>Invoices</span>
                                </div>
                                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform"
                                    :class="invoicesOpen ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="invoicesOpen" x-collapse class="mt-1 ml-8 space-y-1">
                                <a href="{{ route('invoices.add') }}"
                                    class="flex items-center gap-3 px-4 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('invoices.add') || request()->routeIs('invoices.edit') ? 'text-brand-700 bg-brand-50/50 font-medium dark:text-brand-400 dark:bg-brand-500/5' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/30' }}">
                                    <i data-lucide="circle-plus" class="w-4 h-4 flex-shrink-0"></i> Create Invoice
                                </a>
                                <a href="{{ route('invoices.list') }}"
                                    class="flex items-center gap-3 px-4 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('invoices.list') ? 'text-brand-700 bg-brand-50/50 font-medium dark:text-brand-400 dark:bg-brand-500/5' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/30' }}">
                                    <i data-lucide="list" class="w-4 h-4 flex-shrink-0"></i> View All
                                </a>
                            </div>
                        </div>

                        <!--Summary-->
                        <a href="{{ route('summary') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('summary') ? 'bg-brand-50 text-brand-700 shadow-sm font-semibold dark:bg-brand-500/10 dark:text-brand-400' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/50 dark:hover:text-zinc-200' }}">
                            <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                            <span>Summary</span>
                        </a>
                    </nav>

                    <!-- Bottom: Theme Toggle + Profile -->
                    <div class="p-6 border-t border-zinc-200 dark:border-zinc-800/60 space-y-4">
                        <button
                            class="theme-toggle-btn w-full flex items-center justify-between px-4 py-3 rounded-xl bg-zinc-50 dark:bg-zinc-800/40 text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-all border border-transparent dark:border-zinc-800/60">
                            <div class="flex items-center gap-3">
                                <i data-lucide="moon" class="w-5 h-5 block dark:hidden"></i>
                                <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
                                <span class="text-sm font-medium">Appearance</span>
                            </div>
                            <div class="w-9 h-5 bg-zinc-200 dark:bg-zinc-700 rounded-full relative transition-colors">
                                <div
                                    class="theme-toggle-dot absolute w-3.5 h-3.5 bg-white rounded-full top-0.5 left-0.5 transition-all duration-300">
                                </div>
                            </div>
                        </button>

                        <div
                            class="bg-zinc-50 dark:bg-[#1e293b]/40 rounded-2xl p-4 flex items-center gap-3 border border-transparent dark:border-slate-800/40">
                            <div
                                class="w-10 h-10 bg-zinc-200 dark:bg-brand-500/20 rounded-lg flex items-center justify-center text-zinc-600 dark:text-brand-400 font-bold">
                                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-200 truncate">
                                    {{ Auth::user()->name ?? 'User' }}</p>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="text-xs text-rose-500 hover:text-rose-600 font-medium cursor-pointer">Sign
                                        Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>

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

        // Universal recovery for Lucide icons after any Livewire request
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('request.finished', () => {
                lucide.createIcons();
            });
        });

        // Theme Toggle (vanilla JS — does NOT interfere with Livewire)
        const toggleBtns = document.querySelectorAll('.theme-toggle-btn');
        const toggleDots = document.querySelectorAll('.theme-toggle-dot');

        function applyDotPosition() {
            toggleDots.forEach(dot => {
                if (document.documentElement.classList.contains('dark')) {
                    dot.style.transform = 'translateX(1rem)';
                } else {
                    dot.style.transform = 'translateX(0)';
                }
            });
        }
        applyDotPosition();

        toggleBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                document.documentElement.classList.toggle('dark');
                const isDark = document.documentElement.classList.contains('dark');
                localStorage.setItem('color-theme', isDark ? 'dark' : 'light');
                applyDotPosition();
            });
        });
    </script>
</body>

</html>
