<div class="space-y-8 animate-in-fade pb-12">
    <!-- Header: Operational Intelligence -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('stocks.list') }}"
                    class="p-2 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors text-zinc-400">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <div
                    class="w-10 h-10 bg-brand-600 rounded flex items-center justify-center text-white shadow-lg shadow-brand-500/20">
                    <i data-lucide="box" class="w-6 h-6"></i>
                </div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white tracking-tighter">{{ $stock->name }}</h1>
            </div>
            <p class="text-xs font-mono text-zinc-500 dark:text-zinc-400 uppercase tracking-widest ml-12">
                SKU IDENTIFIER: {{ str_pad($stock->id, 6, '0', STR_PAD_LEFT) }} | UNIT: {{ $stock->unit ?: 'UNITS' }}
            </p>
        </div>

        <div
            class="flex items-center gap-4 bg-zinc-900 px-6 py-3 rounded border border-zinc-800 shadow-xl ml-12 lg:ml-0">
            <div class="text-right border-r border-zinc-800 pr-4">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-[0.2em]">Live Inventory</p>
                <p class="text-2xl font-bold font-mono text-white tracking-tighter">
                    {{ number_format($stock->quantity, 2) }}</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-[0.2em]">Net Valuation</p>
                <p class="text-lg font-bold font-mono text-emerald-400 tracking-tighter">Rs.
                    {{ number_format($stock->quantity * $stock->price, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Control Interface -->
        <div class="lg:col-span-1 space-y-6">
            <div class="saas-card p-6">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-1.5 h-4 bg-brand-500 rounded-full"></div>
                    <h2 class="text-xs font-bold uppercase tracking-widest text-zinc-500">Registry Adjustment</h2>
                </div>

                @if (session()->has('message'))
                    <div
                        class="mb-6 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-[11px] font-bold rounded flex items-center gap-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="updateStock" class="space-y-5">
                    <div class="space-y-1.5">
                        <label class="text-detail">Transaction Type</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" wire:click="$set('update_type', 'addition')"
                                class="py-2.5 px-3 rounded text-[11px] font-bold uppercase transition-all flex items-center justify-center gap-2 border {{ $update_type === 'addition' ? 'bg-zinc-900 border-zinc-900 text-white dark:bg-brand-600 dark:border-brand-500 shadow-md' : 'bg-white border-zinc-200 text-zinc-500 hover:bg-zinc-50 dark:bg-zinc-900 dark:border-zinc-800 cursor-pointer' }}">
                                <i data-lucide="plus-circle" class="w-4 h-4"></i> Addition
                            </button>
                            <button type="button" wire:click="$set('update_type', 'subtraction')"
                                class="py-2.5 px-3 rounded text-[11px] font-bold uppercase transition-all flex items-center justify-center gap-2 border {{ $update_type === 'subtraction' ? 'bg-rose-600 border-rose-500 text-white shadow-md' : 'bg-white border-zinc-200 text-zinc-500 hover:bg-zinc-50 dark:bg-zinc-900 dark:border-zinc-800 cursor-pointer' }}">
                                <i data-lucide="minus-circle" class="w-4 h-4"></i> Subtraction
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-detail">Delta Magnitude (Quantity)</label>
                        <input wire:model="quantity_change" type="number" step="0.01"
                            class="saas-input font-mono text-lg" placeholder="0.00">
                        @error('quantity_change')
                            <span class="text-rose-500 text-[10px] mt-1 font-bold block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-detail">Log Context / Reason</label>
                        <textarea wire:model="reason" rows="2" placeholder="e.g. System Audit, Re-stocking..."
                            class="saas-input resize-none text-xs"></textarea>
                    </div>

                    <button type="submit"
                        class="saas-btn-primary w-full py-4 uppercase text-[11px] tracking-widest gap-2">
                        <i data-lucide="shield-check" class="w-4 h-4"></i> Authorize Log Entry
                    </button>
                </form>
            </div>

            <div class="saas-card p-6 bg-zinc-50 dark:bg-zinc-900/30">
                <h3 class="text-[10px] font-bold uppercase text-zinc-400 mb-4 tracking-widest">Pricing Metadata</h3>
                <div
                    class="flex justify-between items-center bg-white dark:bg-zinc-900 p-3 rounded border border-zinc-100 dark:border-zinc-800 shadow-sm">
                    <span class="text-xs text-zinc-500">Unit Valuation</span>
                    <span class="font-bold font-mono text-zinc-900 dark:text-zinc-100">Rs.
                        {{ number_format($stock->price, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Audit Ledger -->
        <div class="lg:col-span-2">
            <div class="saas-card overflow-hidden">
                <div
                    class="p-5 border-b border-zinc-50 dark:border-zinc-800/50 bg-zinc-50/30 dark:bg-zinc-900/30 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></div>
                        <h2 class="text-xs font-bold uppercase tracking-widest text-zinc-500">Historical Audit Trail
                        </h2>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="data-grid w-full">
                        <thead>
                            <tr class="bg-zinc-50/50 dark:bg-zinc-900/50">
                                <th class="data-grid-header">Timeline</th>
                                <th class="data-grid-header">Entity / Action</th>
                                <th class="data-grid-header text-right">Delta</th>
                                <th class="data-grid-header text-right">Registry Baseline</th>
                                <th class="data-grid-header">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50">
                            @forelse($logs as $log)
                                <tr class="data-grid-row">
                                    <td class="data-grid-cell font-mono text-[10px] text-zinc-400">
                                        {{ $log->created_at->format('d.m.Y') }}<br>{{ $log->created_at->format('H:i:s') }}
                                    </td>
                                    <td class="data-grid-cell">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-zinc-900 dark:text-zinc-100 text-xs">{{ $log->user->name ?? 'SYSTEM' }}</span>
                                            <span
                                                class="text-[10px] text-zinc-500 truncate max-w-[180px]">{{ $log->reason }}</span>
                                        </div>
                                    </td>
                                    <td class="data-grid-cell text-right">
                                        <span
                                            class="font-mono font-bold {{ $log->quantity_change > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                            {{ $log->quantity_change > 0 ? '+' : '' }}{{ number_format($log->quantity_change, 2) }}
                                        </span>
                                    </td>
                                    <td class="data-grid-cell text-right font-mono text-zinc-400 text-xs">
                                        {{ number_format($log->previous_quantity, 2) }} →
                                        {{ number_format($log->new_quantity, 2) }}
                                    </td>
                                    <td class="data-grid-cell">
                                        @if ($log->type === 'addition')
                                            <span
                                                class="text-[9px] font-bold text-emerald-500 bg-emerald-500/5 px-1.5 py-0.5 rounded border border-emerald-500/10">INGRESS</span>
                                        @elseif($log->type === 'invoice_sale')
                                            <span
                                                class="text-[9px] font-bold text-brand-500 bg-brand-500/5 px-1.5 py-0.5 rounded border border-brand-500/10">DISPATCH</span>
                                        @else
                                            <span
                                                class="text-[9px] font-bold text-rose-500 bg-rose-500/5 px-1.5 py-0.5 rounded border border-rose-500/10">ADJUST</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-24 text-center">
                                        <div class="flex flex-col items-center gap-4">
                                            <div
                                                class="w-16 h-16 rounded-full bg-zinc-50 dark:bg-zinc-900 flex items-center justify-center text-zinc-200">
                                                <i data-lucide="history" class="w-8 h-8"></i>
                                            </div>
                                            <div class="space-y-1">
                                                <h3 class="font-bold text-zinc-400 text-sm">Audit Trail Nil</h3>
                                                <p class="text-[11px] text-zinc-500">No logistical events have been
                                                    recorded for this SKU.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-zinc-50/50 dark:bg-zinc-900/30 border-t border-zinc-100 dark:border-zinc-800">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

    
</div>

