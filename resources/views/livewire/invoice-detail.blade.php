<div class="space-y-8 max-w-5xl mx-auto print:max-w-none print:space-y-6 animate-in-fade pb-12">
    <!-- Header: Strategic Overview -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 print:gap-2">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-zinc-900 dark:bg-brand-600 rounded flex items-center justify-center text-white">
                    <i data-lucide="file-text" class="w-6 h-6"></i>
                </div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white tracking-tighter">Document
                    #{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</h1>
            </div>
            <p class="text-xs font-mono text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">
                Timestamp: {{ $invoice->issue_date->format('d.m.Y') }}
                @if ($invoice->due_date)
                    <span class="mx-2 text-zinc-300">|</span> Deadline: {{ $invoice->due_date->format('d.m.Y') }}
                @endif
            </p>
        </div>

        <div class="flex flex-col items-end gap-3 print:hidden">
            <div class="flex items-center gap-4">
                @if ($invoice->unpaid_amount > 0)
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-zinc-400 uppercase">Outstanding Balance</p>
                        <p class="text-xl font-bold text-rose-600 font-mono tracking-tighter">Rs.
                            {{ number_format($invoice->unpaid_amount, 2) }}</p>
                    </div>
                @else
                    <div
                        class="px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 rounded-lg flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        <span class="text-xs font-bold uppercase tracking-widest">Settlement Complete</span>
                    </div>
                @endif

                <button onclick="window.print()"
                    class="saas-btn-primary flex items-center gap-2 py-3 px-6 rounded-none">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    Export PDF/Print
                </button>
            </div>
        </div>
    </div>

    <!-- Data Grid: Entities & Temporal Details -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="saas-card p-6 md:col-span-2">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-1.5 h-4 bg-brand-500 rounded-full"></div>
                <h2 class="text-xs font-bold uppercase tracking-widest text-zinc-500">Bill To / Registered Entity</h2>
            </div>

            <div class="space-y-4">
                <div>
                    <p class="text-xl font-bold text-zinc-900 dark:text-zinc-100">{{ $invoice->dealer->name }}</p>
                    @if ($invoice->dealer->contact)
                        <div class="mt-2 flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                            <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                            <span>{{ $invoice->dealer->contact }}</span>
                        </div>
                    @endif
                </div>

                @if ($invoice->dealer->address)
                    <div class="pt-4 border-t border-zinc-50 dark:border-zinc-800/50">
                        <p class="text-[10px] font-bold text-zinc-400 uppercase mb-2">Registered Address</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed whitespace-pre-line">
                            {{ $invoice->dealer->address }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="saas-card p-6 bg-zinc-900 border-zinc-800 text-white">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-1.5 h-4 bg-emerald-500 rounded-full"></div>
                <h2 class="text-xs font-bold uppercase tracking-widest text-zinc-400">Quick Metrics</h2>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="text-[10px] font-bold text-zinc-500 uppercase block mb-1">Total Valuation</label>
                    <p class="text-2xl font-bold font-mono tracking-tighter">Rs.
                        {{ number_format($invoice->total_amount, 2) }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-zinc-500 uppercase block mb-1">Aggregated Credits</label>
                    <p class="text-lg font-bold font-mono text-emerald-400">Rs. {{ number_format($this->totalPaid, 2) }}
                    </p>
                </div>
                <div class="pt-4 border-t border-zinc-800">
                    <label class="text-[10px] font-bold text-zinc-500 uppercase block mb-1">Status</label>
                    @if ($invoice->isFullyPaid)
                        <span class="text-xs font-bold text-emerald-400 flex items-center gap-1.5"><span
                                class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]"></span>
                            VERIFIED SETTLEMENT</span>
                    @else
                        <span class="text-xs font-bold text-rose-400 flex items-center gap-1.5"><span
                                class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span> PENDING ACTION</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Items Manifest -->
    <div class="saas-card overflow-hidden">
        <div class="p-6 border-b border-zinc-50 dark:border-zinc-800/50">
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-4 bg-brand-500 rounded-full"></div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-zinc-500">Transaction Manifest / Line Items
                </h3>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="data-grid w-full">
                <thead>
                    <tr class="bg-zinc-50/50 dark:bg-zinc-900/50">
                        <th class="data-grid-header">Identified SKU / Product</th>
                        <th class="data-grid-header text-right">Quantity Sold</th>
                        <th class="data-grid-header text-right">Unit Valuation</th>
                        <th class="data-grid-header text-right">Aggregate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50">
                    @foreach ($invoice->items as $item)
                        <tr class="data-grid-row">
                            <td class="data-grid-cell font-bold text-zinc-900 dark:text-zinc-100">
                                {{ $item->stock->name }}</td>
                            <td class="data-grid-cell text-right font-mono">{{ $item->quantity_sold }} <span
                                    class="text-[10px] text-zinc-400 font-sans uppercase">{{ $item->stock->unit ?? 'units' }}</span>
                            </td>
                            <td class="data-grid-cell text-right font-mono text-zinc-500">Rs.
                                {{ number_format($item->unit_price, 2) }}</td>
                            <td class="data-grid-cell text-right font-bold font-mono">Rs.
                                {{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-zinc-100/50 dark:bg-zinc-900/80 font-bold">
                        <td colspan="3" class="px-6 py-4 text-right text-xs uppercase tracking-wider text-zinc-500">
                            Document Total</td>
                        <td class="px-6 py-4 text-right text-lg font-mono tracking-tighter">Rs.
                            {{ number_format($invoice->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Payments Ledger -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <div class="saas-card overflow-hidden">
            <div class="p-6 border-b border-zinc-50 dark:border-zinc-800/50">
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-4 bg-emerald-500 rounded-full"></div>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-zinc-500">Payment Audit Log</h3>
                </div>
            </div>

            @if ($invoice->payments->isEmpty())
                <div class="p-12 text-center text-zinc-400 text-xs italic">No credits recorded for this ledger entry.
                </div>
            @else
                <div class="divide-y divide-zinc-50 dark:divide-zinc-800/50">
                    @foreach ($invoice->payments as $payment)
                        <div
                            class="p-4 flex justify-between items-start hover:bg-zinc-50/50 dark:hover:bg-zinc-900/50 transition-colors">
                            <div class="space-y-1">
                                <p class="text-[11px] font-bold text-zinc-500 font-mono tracking-tight">
                                    {{ $payment->payment_date->format('d M, Y') }}</p>
                                @if ($payment->notes)
                                    <p class="text-[10px] text-zinc-400 max-w-xs leading-relaxed">{{ $payment->notes }}
                                    </p>
                                @endif
                            </div>
                            <p class="text-emerald-600 dark:text-emerald-400 font-bold font-mono">
                                + Rs. {{ number_format($payment->amount, 2) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if ($invoice->notes)
            <div class="saas-card p-6">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1.5 h-4 bg-zinc-400 rounded-full"></div>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-zinc-500">Contextual Notes</h3>
                </div>
                <div class="p-4 bg-zinc-50 dark:bg-zinc-900/50 rounded border border-zinc-100 dark:border-zinc-800">
                    <p class="text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed whitespace-pre-line">
                        {{ $invoice->notes }}</p>
                </div>
            </div>
        @endif
    </div>

    <div class="text-center py-10 print:mt-12">
        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em] mb-1">Generated by StockPro
            Infrastructure</p>
        <p class="text-[9px] font-mono text-zinc-300 dark:text-zinc-600">{{ now()->format('Y-m-d H:i:s') }} /
            NODE-CORE-4</p>
    </div>

    
</div>

