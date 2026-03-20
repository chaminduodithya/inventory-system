<div class="space-y-10 animate-in-fade pb-10">
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">
                Control Center
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Operational intelligence for <span
                    class="font-bold text-brand-600">StockPro</span> Enterprise.</p>
        </div>
        <div class="flex items-center gap-3">
            <div
                class="px-3 py-1 bg-zinc-100 dark:bg-zinc-800 rounded text-[10px] font-mono font-bold text-zinc-500 border border-zinc-200 dark:border-zinc-700">
                LATEST REVISION: {{ now()->format('d.m.Y/H:i') }}
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Stock Value -->
        <div class="saas-card p-5 group hover:border-brand-500/50 transition-all">
            <div class="flex items-center justify-between mb-3">
                <p class="text-detail">Inventory Valuation</p>
                <div class="p-2 bg-brand-500/10 rounded text-brand-600">
                    <i data-lucide="database" class="w-4 h-4"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 font-mono tracking-tighter">Rs.
                {{ number_format($totalStockValue, 2) }}</p>
            <div class="mt-4 pt-4 border-t border-zinc-50 dark:border-zinc-800 flex items-center justify-between">
                <span class="text-[10px] text-zinc-400 font-medium">GLOBAL ASSETS</span>
                <a href="{{ route('stocks.list') }}"
                    class="text-[10px] font-bold text-brand-600 flex items-center gap-1">ANALYZE <i
                        data-lucide="chevron-right" class="w-3 h-3"></i></a>
            </div>
        </div>

        <!-- Low Stock Items -->
        <div class="saas-card p-5 border-l-4 border-l-amber-500">
            <div class="flex items-center justify-between mb-3">
                <p class="text-detail">Depletion Risks</p>
                <div class="p-2 bg-amber-500/10 rounded text-amber-600">
                    <i data-lucide="alert-octagon" class="w-4 h-4"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 font-mono tracking-tighter">
                {{ $lowStockItems->count() }} <span class="text-xs text-zinc-400 font-sans tracking-normal">SKUs</span>
            </p>
            <div class="mt-4 pt-4 border-t border-zinc-50 dark:border-zinc-800 flex items-center justify-between">
                <span class="text-[10px] text-amber-600 font-bold uppercase">Critical Threshold Reached</span>
                <a href="{{ route('stocks.list') }}"
                    class="text-[10px] font-bold text-zinc-400 hover:text-zinc-600 flex items-center gap-1">REPLENISH <i
                        data-lucide="chevron-right" class="w-3 h-3"></i></a>
            </div>
        </div>

        <!-- Total Outstanding -->
        <div class="saas-card p-5 border-l-4 border-l-rose-500">
            <div class="flex items-center justify-between mb-3">
                <p class="text-detail">Unsettled Ledger</p>
                <div class="p-2 bg-rose-500/10 rounded text-rose-600">
                    <i data-lucide="landmark" class="w-4 h-4"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 font-mono tracking-tighter">Rs.
                {{ number_format($totalOutstanding, 2) }}</p>
            <div class="mt-4 pt-4 border-t border-zinc-50 dark:border-zinc-800 flex items-center justify-between">
                <span class="text-[10px] text-rose-600 font-bold uppercase">Awaiting Collection</span>
                <a href="{{ route('invoices.list') }}"
                    class="text-[10px] font-bold text-zinc-400 hover:text-zinc-600 flex items-center gap-1">COLLECT <i
                        data-lucide="chevron-right" class="w-3 h-3"></i></a>
            </div>
        </div>

        <!-- Overdue Dealers -->
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-detail">Risk Entities</p>
                <div class="p-2 bg-zinc-500/10 rounded text-zinc-500">
                    <i data-lucide="users-2" class="w-4 h-4"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 font-mono tracking-tighter">
                {{ $overdueDealers->count() }} <span
                    class="text-xs text-zinc-400 font-sans tracking-normal">Dealers</span></p>
            <div class="mt-4 pt-4 border-t border-zinc-50 dark:border-zinc-800 flex items-center justify-between">
                <span class="text-[10px] text-zinc-400 font-medium">OVERDUE ACCOUNTS</span>
                <a href="{{ route('summary') }}"
                    class="text-[10px] font-bold text-zinc-400 hover:text-zinc-600 flex items-center gap-1">REVIEW <i
                        data-lucide="chevron-right" class="w-3 h-3"></i></a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Ledger Activity -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between px-1">
                <h2 class="text-sm font-bold uppercase tracking-widest text-zinc-500 flex items-center gap-2">
                    <i data-lucide="activity" class="w-4 h-4"></i> Transaction Stream
                </h2>
                <a href="{{ route('invoices.list') }}"
                    class="text-[10px] font-bold text-brand-600 hover:underline flex items-center gap-1">FULL REGISTRY
                    <i data-lucide="external-link" class="w-3 h-3"></i></a>
            </div>

            <div class="saas-card">
                <div class="overflow-x-auto">
                    <table class="data-grid">
                        <thead>
                            <tr class="bg-zinc-50/50 dark:bg-zinc-900/50">
                                <th class="data-grid-header">Entity / Dealer</th>
                                <th class="data-grid-header">Valuation</th>
                                <th class="data-grid-header">Status</th>
                                <th class="data-grid-header text-right">Commit Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50">
                            @foreach ($recentInvoices as $invoice)
                                <tr class="data-grid-row">
                                    <td class="data-grid-cell font-bold">
                                        <a href="{{ route('invoice.detail', $invoice->id) }}"
                                            class="hover:text-brand-600 transition-colors">
                                            {{ $invoice->dealer->name }}
                                        </a>
                                    </td>
                                    <td class="data-grid-cell font-mono">Rs.
                                        {{ number_format($invoice->total_amount, 2) }}</td>
                                    <td class="data-grid-cell">
                                        @if ($invoice->isFullyPaid)
                                            <span class="saas-badge saas-badge-success">SETTLED</span>
                                        @elseif($invoice->paid_amount > 0)
                                            <span class="saas-badge saas-badge-warning">PARTIAL</span>
                                        @else
                                            <span class="saas-badge saas-badge-danger">UNPAID</span>
                                        @endif
                                    </td>
                                    <td class="data-grid-cell text-right text-[11px] text-zinc-400 font-mono">
                                        {{ $invoice->issue_date->format('d.m.y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($recentInvoices->isEmpty())
                    <div class="py-12 text-center text-zinc-400 text-xs italic">Stream inactive. No recent transactions
                        detected.</div>
                @endif
            </div>
        </div>

        <!-- Critical Alerts -->
        <div class="lg:col-span-1 space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-widest text-zinc-500 flex items-center gap-2 px-1">
                <i data-lucide="zap" class="w-4 h-4 text-brand-500"></i> Critical Alerts
            </h2>

            <div class="space-y-3">
                @forelse($lowStockItems as $stock)
                    <div class="saas-card p-4 flex items-center gap-4 border-l-2 border-l-amber-500 animate-in-fade">
                        <div
                            class="w-8 h-8 rounded bg-amber-500/10 flex items-center justify-center text-amber-600 shrink-0">
                            <i data-lucide="box" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-[12px] font-bold text-zinc-800 dark:text-zinc-200 truncate">
                                {{ $stock->name }}</h4>
                            <p class="text-[10px] text-zinc-500 uppercase font-bold mt-0.5">Critical Stock:
                                {{ $stock->quantity }} {{ $stock->unit ?? 'u' }} left</p>
                        </div>
                    </div>
                @empty
                    <div class="saas-card p-8 text-center bg-emerald-500/5 border-emerald-500/20">
                        <i data-lucide="check-circle" class="w-8 h-8 text-emerald-500 mx-auto mb-2 opacity-50"></i>
                        <p class="text-[10px] font-bold text-emerald-600 uppercase">All systems nominal</p>
                        <p class="text-[9px] text-zinc-400 mt-1">Stock levels within safe range</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>



