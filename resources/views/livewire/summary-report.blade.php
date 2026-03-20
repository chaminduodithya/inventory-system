<div class="space-y-8 animate-in-fade">
    <!-- Header: Analytical Engine -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white tracking-tighter">Strategic Enterprise Analytics
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 italic font-mono">Report Engine Status: OPERATIONAL
                // DATA_DATE: {{ now()->format('Y.m.d') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="exportExcel"
                class="saas-btn-secondary gap-2 text-[11px] uppercase tracking-widest px-6 py-3">
                <i data-lucide="download-cloud" class="w-4 h-4"></i>
                Export Dataset
            </button>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-zinc-100 dark:border-zinc-800 gap-8">
        <button wire:click="switchTab('dealers')"
            class="pb-4 text-xs font-bold uppercase tracking-widest transition-all relative {{ $activeTab === 'dealers' ? 'text-brand-600 dark:text-brand-400' : 'text-zinc-400 hover:text-zinc-600' }}">
            Partner Exposure
            @if ($activeTab === 'dealers')
                <div class="absolute bottom-0 left-0 w-full h-0.5 bg-brand-600 dark:bg-brand-400"></div>
            @endif
        </button>
        <button wire:click="switchTab('inventory')"
            class="pb-4 text-xs font-bold uppercase tracking-widest transition-all relative {{ $activeTab === 'inventory' ? 'text-brand-600 dark:text-brand-400' : 'text-zinc-400 hover:text-zinc-600' }}">
            Inventory Logistics
            @if ($activeTab === 'inventory')
                <div class="absolute bottom-0 left-0 w-full h-0.5 bg-brand-600 dark:bg-brand-400"></div>
            @endif
        </button>
        <button wire:click="switchTab('invoices')"
            class="pb-4 text-xs font-bold uppercase tracking-widest transition-all relative {{ $activeTab === 'invoices' ? 'text-brand-600 dark:text-brand-400' : 'text-zinc-400 hover:text-zinc-600' }}">
            Transaction Flow
            @if ($activeTab === 'invoices')
                <div class="absolute bottom-0 left-0 w-full h-0.5 bg-brand-600 dark:bg-brand-400"></div>
            @endif
        </button>
    </div>

    @if ($activeTab === 'dealers')
        <!-- Dealer Report View -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="saas-card p-6 border-l-4 border-l-rose-500">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1">Aggregate Outstanding</p>
                <h3 class="text-2xl font-bold text-zinc-900 dark:text-white font-mono">Rs.
                    {{ number_format($dealerSummaries->sum('total_outstanding'), 2) }}</h3>
            </div>
            <div class="saas-card p-6 border-l-4 border-l-brand-600">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1">Total Pipeline Volume</p>
                <h3 class="text-2xl font-bold text-zinc-900 dark:text-white font-mono">Rs.
                    {{ number_format($dealerSummaries->sum('total_invoiced'), 2) }}</h3>
            </div>
            <div class="saas-card p-6 border-l-4 border-l-emerald-500">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1">Realized Liquidity</p>
                <h3 class="text-2xl font-bold text-zinc-900 dark:text-white font-mono">Rs.
                    {{ number_format($dealerSummaries->sum('total_paid'), 2) }}</h3>
            </div>
        </div>

        <div class="saas-card overflow-hidden mt-8">
            <table class="data-grid w-full">
                <thead>
                    <tr class="bg-zinc-50/50 dark:bg-zinc-900/50">
                        <th class="data-grid-header">Partner Identity</th>
                        <th class="data-grid-header text-right">Invoiced Amt.</th>
                        <th class="data-grid-header text-right">Settled Amt.</th>
                        <th class="data-grid-header text-right">Active Exposure</th>
                        <th class="data-grid-header text-center">Security Risk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50" wire:key="dealer-table">
                    @foreach ($dealerSummaries as $dealer)
                        <tr class="data-grid-row">
                            <td class="data-grid-cell">
                                <div class="font-bold text-zinc-900 dark:text-zinc-100">{{ $dealer->name }}</div>
                                <div class="text-[10px] text-zinc-500">{{ $dealer->invoice_count }} Documents in
                                    Registry</div>
                            </td>
                            <td class="data-grid-cell text-right font-mono">Rs.
                                {{ number_format($dealer->total_invoiced, 2) }}</td>
                            <td class="data-grid-cell text-right font-mono text-emerald-600">Rs.
                                {{ number_format($dealer->total_paid, 2) }}</td>
                            <td class="data-grid-cell text-right font-mono text-rose-600 font-bold">Rs.
                                {{ number_format($dealer->total_outstanding, 2) }}</td>
                            <td class="data-grid-cell text-center">
                                <span
                                    class="text-[9px] font-bold px-2 py-0.5 rounded border 
                                    {{ $dealer->exposure_level === 'CRITICAL'
                                        ? 'bg-rose-500/10 text-rose-600 border-rose-500/20'
                                        : ($dealer->exposure_level === 'ELEVATED'
                                            ? 'bg-amber-500/10 text-amber-600 border-amber-500/20'
                                            : 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20') }}">
                                    {{ $dealer->exposure_level }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif($activeTab === 'inventory')
        <!-- Inventory Report View -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="saas-card p-6">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1">Total Assets Valuation</p>
                <h3 class="text-2xl font-bold text-zinc-900 dark:text-white font-mono">Rs.
                    {{ number_format($inventorySummaries->sum('valuation'), 2) }}</h3>
            </div>
            <div class="saas-card p-6 text-center">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1">Low Stock SKU Indicators
                </p>
                <h3 class="text-2xl font-bold text-rose-600 font-mono">
                    {{ $inventorySummaries->where('health_status', 'LOW_STOCK')->count() }}</h3>
            </div>
            <div class="saas-card p-6 text-right">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1">Total SKU In Registry</p>
                <h3 class="text-2xl font-bold text-zinc-900 dark:text-white font-mono">
                    {{ $inventorySummaries->count() }}</h3>
            </div>
        </div>

        <div class="saas-card overflow-hidden mt-8">
            <table class="data-grid w-full">
                <thead>
                    <tr class="bg-zinc-50/50 dark:bg-zinc-900/50">
                        <th class="data-grid-header">Stock Identity</th>
                        <th class="data-grid-header text-right">Unit Price</th>
                        <th class="data-grid-header text-right">Balance</th>
                        <th class="data-grid-header text-right">Net Valuation</th>
                        <th class="data-grid-header text-center">Turnover (Units)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50" wire:key="inventory-table">
                    @foreach ($inventorySummaries as $stock)
                        <tr class="data-grid-row">
                            <td class="data-grid-cell">
                                <div class="font-bold text-zinc-900 dark:text-zinc-100">{{ $stock->name }}</div>
                                <div class="text-[10px] text-zinc-500">
                                    {{ $stock->health_status === 'LOW_STOCK' ? 'NEEDS REPLENISHMENT' : 'INVENTORY OPTIMAL' }}
                                </div>
                            </td>
                            <td class="data-grid-cell text-right font-mono">Rs. {{ number_format($stock->price, 2) }}
                            </td>
                            <td
                                class="data-grid-cell text-right font-mono {{ $stock->health_status === 'LOW_STOCK' ? 'text-rose-600 font-bold' : '' }}">
                                {{ number_format($stock->quantity, 2) }} {{ $stock->unit ?? 'u' }}
                            </td>
                            <td class="data-grid-cell text-right font-mono">Rs.
                                {{ number_format($stock->valuation, 2) }}</td>
                            <td class="data-grid-cell text-center font-mono">
                                {{ number_format($stock->turnover_rate, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif($activeTab === 'invoices')
        <!-- Invoice Report View -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="saas-card p-6">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1">Gross Issued Value</p>
                <h3 class="text-xl font-bold text-zinc-900 dark:text-white font-mono">Rs.
                    {{ number_format($invoiceMetrics['total_volume'], 2) }}</h3>
            </div>
            <div class="saas-card p-6">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1">Average Ledger Size</p>
                <h3 class="text-xl font-bold text-zinc-900 dark:text-white font-mono">Rs.
                    {{ number_format($invoiceMetrics['avg_deal_size'], 2) }}</h3>
            </div>
            <div class="saas-card p-6">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1">Liquidity Realization</p>
                <h3 class="text-xl font-bold text-emerald-600 font-mono">Rs.
                    {{ number_format($invoiceMetrics['total_realized'], 2) }}</h3>
            </div>
            <div class="saas-card p-6">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1">Overdue Ratio</p>
                <h3
                    class="text-xl font-bold {{ $invoiceMetrics['overdue_ratio'] > 20 ? 'text-rose-600' : 'text-zinc-900 dark:text-white' }} font-mono">
                    {{ round($invoiceMetrics['overdue_ratio'], 1) }}%</h3>
            </div>
        </div>

        <div class="mt-8 space-y-6">
            <h3 class="text-xs font-bold uppercase tracking-widest text-zinc-400">Recent Transaction Chronology</h3>
            <div class="saas-card overflow-hidden">
                <table class="data-grid w-full">
                    <thead>
                        <tr class="bg-zinc-50/50 dark:bg-zinc-900/50">
                            <th class="data-grid-header">Timeline</th>
                            <th class="data-grid-header">Issuer / Entity</th>
                            <th class="data-grid-header text-right">Valuation</th>
                            <th class="data-grid-header text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50" wire:key="invoice-table">
                        @foreach ($invoiceMetrics['recent_activity'] as $record)
                            <tr class="data-grid-row">
                                <td class="data-grid-cell font-mono text-[10px] text-zinc-500">
                                    {{ $record->issue_date->format('d M Y') }}</td>
                                <td class="data-grid-cell font-bold text-zinc-900 dark:text-zinc-100">
                                    {{ $record->dealer->name }}</td>
                                <td class="data-grid-cell text-right font-mono">Rs.
                                    {{ number_format($record->total_amount, 2) }}</td>
                                <td class="data-grid-cell text-center">
                                    <span
                                        class="text-[9px] font-bold px-2 py-0.5 rounded border {{ $record->isFullyPaid ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 border-amber-500/20' }}">
                                        {{ $record->isFullyPaid ? 'SETTLED' : 'PARTIAL' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    
</div>

