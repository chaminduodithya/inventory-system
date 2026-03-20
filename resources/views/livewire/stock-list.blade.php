<div class="space-y-6 animate-in-fade">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 tracking-tight dark:text-white">
                {{ __('Nexus Inventory Control') }}</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Live SKU tracking, unit valuations, and warehouse
                analytics.</p>
        </div>
        <a href="{{ route('stocks.add') }}" class="saas-btn-primary gap-2">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            Register New SKU
        </a>
    </div>

    @if (session()->has('message'))
        <div
            class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-xs font-bold rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div
            class="p-3 bg-rose-500/10 border border-rose-500/20 text-rose-600 text-xs font-bold rounded-lg flex items-center gap-2">
            <i data-lucide="alert-octagon" class="w-4 h-4"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="saas-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-grid w-full">
                <thead>
                    <tr class="bg-zinc-50/50 dark:bg-zinc-900/50">
                        <th class="data-grid-header">Identified SKU / Product</th>
                        <th class="data-grid-header">Stock Threshold</th>
                        <th class="data-grid-header text-right">Unit Valuation</th>
                        <th class="data-grid-header text-right">Ops</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50">
                    @forelse($stocks as $stock)
                        <tr class="data-grid-row group" wire:key="stock-{{ $stock->id }}">
                            <td class="data-grid-cell">
                                <div class="flex flex-col">
                                    <span class="font-bold text-zinc-900 dark:text-zinc-100">{{ $stock->name }}</span>
                                    <span
                                        class="text-[10px] text-zinc-400 mt-1 uppercase tracking-tight truncate max-w-[400px]">{{ $stock->description ?: 'No technical specifications provided' }}</span>
                                </div>
                            </td>
                            <td class="data-grid-cell">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex flex-col text-right">
                                        <span
                                            class="font-mono text-base font-bold {{ $stock->quantity <= 5 ? 'text-rose-500' : 'text-zinc-800 dark:text-zinc-200' }}">{{ $stock->quantity }}</span>
                                        <span
                                            class="text-[10px] text-zinc-400 uppercase leading-none">{{ $stock->unit ?: 'units' }}
                                            available</span>
                                    </div>
                                    @if ($stock->quantity <= 5)
                                        <div class="flex h-2 w-2 rounded-full bg-rose-500 animate-pulse"
                                            title="Low Stock Warning"></div>
                                    @endif
                                </div>
                            </td>
                            <td class="data-grid-cell text-right">
                                <div class="flex flex-col">
                                    <span class="font-mono text-zinc-900 dark:text-zinc-100 font-bold">Rs.
                                        {{ number_format($stock->price, 2) }}</span>
                                    <span class="text-[9px] text-zinc-400 uppercase">Current Market Basis</span>
                                </div>
                            </td>
                            <td class="data-grid-cell text-right">
                                <div
                                    class="flex justify-end gap-1 lg:opacity-20 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('stocks.detail', $stock->id) }}"
                                        class="p-1.5 text-zinc-400 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-all"
                                        title="Logistics Trail / Update">
                                        <i data-lucide="history" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <a href="{{ route('stocks.edit', $stock->id) }}"
                                        class="p-1.5 text-zinc-400 hover:text-brand-600 hover:bg-brand-50 rounded transition-all"
                                        title="Modify Entry">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <button wire:click="delete({{ $stock->id }})"
                                        class="p-1.5 text-zinc-400 hover:text-rose-600 hover:bg-rose-50 rounded transition-all"
                                        title="Purge Entry" onclick="return confirm('Archive this SKU?')">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-32 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div
                                        class="w-20 h-20 rounded-full bg-zinc-50 dark:bg-zinc-900 flex items-center justify-center text-zinc-200">
                                        <i data-lucide="box-select" class="w-10 h-10"></i>
                                    </div>
                                    <div class="space-y-1">
                                        <h3 class="font-bold text-zinc-500">Warehouse Empty</h3>
                                        <p class="text-[11px] text-zinc-400 max-w-[240px] mx-auto">No inventory items
                                            detected in the active registry.</p>
                                        <a href="{{ route('stocks.add') }}"
                                            class="text-brand-600 hover:text-brand-700 text-[11px] font-bold mt-3 inline-flex items-center gap-1">
                                            <i data-lucide="plus" class="w-3 h-3"></i> INITIALIZE FIRST SKU
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div
            class="p-4 bg-zinc-50/50 dark:bg-zinc-900/30 border-t border-zinc-100 dark:border-zinc-800 flex justify-between items-center text-[10px]">
            <span class="font-mono text-zinc-400 uppercase">Aggregate Registry Assets: {{ $stocks->count() }}
                SKUs</span>
            <span class="font-mono text-zinc-400 uppercase tracking-widest">Total Value: Rs.
                {{ number_format($stocks->sum(fn($s) => $s->quantity * $s->price), 2) }}</span>
        </div>
    </div>
</div>
