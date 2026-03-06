<x-app-layout>
    <div class="space-y-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Welcome back, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
            <p class="text-slate-500 mt-2">Here's what's happening with your inventory today.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="inventory-card p-6 bg-gradient-to-br from-indigo-600 to-indigo-700 text-white border-0 shadow-lg shadow-indigo-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-indigo-500/20 rounded-lg">
                        <i data-lucide="package" class="w-6 h-6"></i>
                    </div>
                </div>
                <h3 class="text-indigo-100 text-sm font-medium">Total Products</h3>
                <p class="text-2xl font-bold mt-1">Ready to manage</p>
                <a href="{{ route('stocks') }}" class="mt-4 flex items-center gap-2 text-xs font-semibold text-white/80 hover:text-white transition-colors">
                    View Inventory <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>

            <div class="inventory-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-slate-100 rounded-lg text-slate-600">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                </div>
                <h3 class="text-slate-500 text-sm font-medium">Active Dealers</h3>
                <p class="text-2xl font-bold text-slate-900 mt-1">Partners Overview</p>
                <a href="{{ route('dealers') }}" class="mt-4 flex items-center gap-2 text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                    Manage Dealers <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>

            <div class="inventory-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600">
                        <i data-lucide="trending-up" class="w-6 h-6"></i>
                    </div>
                </div>
                <h3 class="text-slate-500 text-sm font-medium">System Status</h3>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <p class="text-lg font-bold text-slate-900">Online</p>
                </div>
            </div>
        </div>

        <div class="inventory-card p-8">
            <div class="flex items-center justify-center p-12">
                <div class="text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="sparkles" class="text-slate-300 w-8 h-8"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Ready to expand?</h3>
                    <p class="text-slate-500 max-w-sm mx-auto mt-2">Start by adding your first stock item or setting up your dealer profiles.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

