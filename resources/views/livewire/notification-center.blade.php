<div class="relative" x-data="{ open: false }">
    <!-- Trigger -->
    <button @click="open = !open"
        class="relative p-2 text-zinc-400 hover:text-brand-600 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-all group">
        <i data-lucide="bell" class="w-5 h-5 {{ $unreadCount > 0 ? 'animate-ring' : '' }}"></i>
        @if ($unreadCount > 0)
            <span
                class="absolute top-1 right-1 w-4 h-4 bg-rose-600 text-white text-[9px] font-bold rounded-full flex items-center justify-center ring-2 ring-white dark:ring-zinc-950">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        class="absolute right-0 mt-2 w-80 bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 shadow-2xl rounded-xl overflow-hidden z-50">

        <div
            class="p-4 border-b border-zinc-50 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900 flex justify-between items-center">
            <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-500">System Alerts</h3>
            <span class="text-[9px] font-mono text-zinc-400">CORE-MONITOR-V1</span>
        </div>

        <div class="max-h-[320px] overflow-y-auto">
            @forelse($lowStocks as $item)
                <a href="{{ route('stocks.detail', $item->id) }}"
                    class="flex items-start gap-4 p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 border-b border-zinc-50 dark:border-zinc-800/30 transition-colors group">
                    <div
                        class="mt-1 w-8 h-8 rounded bg-rose-500/10 flex items-center justify-center text-rose-600 border border-rose-500/20 shadow-sm">
                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p
                            class="text-[11px] font-bold text-zinc-900 dark:text-zinc-100 truncate group-hover:text-brand-600 transition-colors">
                            {{ $item->name }}</p>
                        <p class="text-[10px] text-zinc-500 mt-0.5">Quantity Critical: <span
                                class="font-mono font-bold text-rose-500">{{ $item->quantity }}</span> /
                            {{ $item->min_stock_level }}</p>
                    </div>
                </a>
            @empty
                <div class="p-12 text-center">
                    <div
                        class="w-12 h-12 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-600 mx-auto mb-4 border border-emerald-500/20">
                        <i data-lucide="check" class="w-6 h-6"></i>
                    </div>
                    <p class="text-xs font-bold text-zinc-400">Inventory Levels Nominal</p>
                    <p class="text-[10px] text-zinc-500 mt-1">No SKU replenishment required.</p>
                </div>
            @endforelse
        </div>

        @if ($unreadCount > 5)
            <a href="{{ route('stocks.list') }}"
                class="block p-3 bg-zinc-50/50 dark:bg-zinc-800/30 text-center text-[10px] font-bold text-brand-600 hover:text-brand-700 transition-colors uppercase tracking-widest border-t border-zinc-100 dark:border-zinc-800">
                View All {{ $unreadCount }} Alerts
            </a>
        @endif
    </div>

    <style>
        @keyframes ring {
            0% {
                transform: rotate(0);
            }

            10% {
                transform: rotate(15deg);
            }

            20% {
                transform: rotate(-15deg);
            }

            30% {
                transform: rotate(10deg);
            }

            40% {
                transform: rotate(-10deg);
            }

            50% {
                transform: rotate(0);
            }

            100% {
                transform: rotate(0);
            }
        }

        .animate-ring {
            animation: ring 2s ease infinite;
        }
    </style>
</div>

