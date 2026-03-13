<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Dealer Summary') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Overview of invoicing, payments and outstanding
                balances by dealer</p>
        </div>
    </div>

    <div class="inventory-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Dealer</th>
                        <th>Invoices</th>
                        <th>Total Invoiced</th>
                        <th>Total Paid</th>
                        <th>Outstanding</th>
                        <th>Overdue Breakdown</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($summaries as $summary)
                        <tr
                            class="{{ $summary->total_outstanding > 0 ? 'bg-rose-50/30 dark:bg-rose-950/10' : 'bg-emerald-50/20 dark:bg-emerald-950/10' }}">
                            <td>
                                <div class="font-medium text-slate-900 dark:text-slate-200">{{ $summary->name }}</div>
                            </td>
                            <td class="text-center font-medium">
                                {{ $summary->invoice_count }}
                            </td>
                            <td class="font-semibold text-slate-800 dark:text-slate-200">
                                Rs. {{ number_format($summary->total_invoiced, 2) }}
                            </td>
                            <td class="text-emerald-600 dark:text-emerald-400 font-medium">
                                Rs. {{ number_format($summary->total_paid, 2) }}
                            </td>
                            <td
                                class="{{ $summary->total_outstanding > 0 ? 'text-rose-600 dark:text-rose-400 font-bold' : 'text-emerald-600 dark:text-emerald-400' }}">
                                Rs. {{ number_format($summary->total_outstanding, 2) }}
                            </td>
                            <td class="text-sm">
                                @if ($summary->total_overdue > 0)
                                    <div class="flex flex-col gap-1">
                                        <span>Total Overdue: <strong>{{ $summary->total_overdue }}</strong></span>
                                        @if ($summary->overdue_60 > 0)
                                            <span class="text-amber-600 dark:text-amber-400">>60 days:
                                                {{ $summary->overdue_60 }}</span>
                                        @endif
                                        @if ($summary->overdue_90 > 0)
                                            <span class="text-rose-600 dark:text-rose-400">>90 days:
                                                {{ $summary->overdue_90 }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500">No overdue</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="users" class="w-8 h-8 opacity-20"></i>
                                    <p>No dealer activity yet. Create some invoices first.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>


        </div>
    </div>

    <!-- Optional: Quick totals row at bottom -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="inventory-card p-6 bg-gradient-to-br from-indigo-600 to-indigo-700 text-white">
            <h3 class="text-indigo-100 text-sm font-medium">Total Outstanding</h3>
            <p class="text-3xl font-bold mt-1">
                Rs. {{ number_format($summaries->sum('total_outstanding'), 2) }}
            </p>
        </div>

        <div class="inventory-card p-6">
            <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Invoiced</h3>
            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">
                Rs. {{ number_format($summaries->sum('total_invoiced'), 2) }}
            </p>
        </div>

        <div class="inventory-card p-6">
            <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Paid</h3>
            <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                Rs. {{ number_format($summaries->sum('total_paid'), 2) }}
            </p>
        </div>
    </div>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Dealer Summary') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Overview of invoicing, payments and outstanding
                balances by dealer</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('summary') }}" wire:click="exportExcel" class="btn-primary flex items-center gap-2">
                <i data-lucide="download" class="w-4 h-4"></i>
                Export Excel
            </a>
            <a href="{{ route('summary') }}" wire:click="exportPdf" class="btn-secondary flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                Export PDF
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        lucide.createIcons();
    });
    document.addEventListener('livewire:navigated', () => {
        lucide.createIcons();
    });
</script>
