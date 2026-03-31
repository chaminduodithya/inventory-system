<div class="space-y-6 animate-in-fade">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 tracking-tight dark:text-white">
                {{ __('Bills') }}</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">See all your bills, payments, and overdue accounts.
            </p>
        </div>
        <a href="{{ route('invoices.add') }}" class="saas-btn-primary gap-2">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            Add New Bill
        </a>
    </div>

    <!-- Quick Stats Summary (The Differentiation Anchor) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="saas-card p-4 flex items-center justify-between">
            <div>
                <p class="text-detail">Total Outstanding</p>
                <h3 class="text-xl font-bold text-zinc-900 dark:text-white mt-1">Rs.
                    {{ number_format($totalOutstanding, 2) }}</h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-rose-500/10 flex items-center justify-center text-rose-600">
                <i data-lucide="trending-up" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="saas-card p-4 flex items-center justify-between">
            <div>
                <p class="text-detail">Total Paid</p>
                <h3 class="text-xl font-bold text-zinc-900 dark:text-white mt-1">Rs.
                    {{ number_format($totalCollected, 2) }}</h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                <i data-lucide="check-check" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="saas-card p-4 flex items-center justify-between">
            <div>
                <p class="text-detail">Total Bills</p>
                <h3 class="text-xl font-bold text-zinc-900 dark:text-white mt-1">{{ $activeLedgerCount }}</h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-brand-500/10 flex items-center justify-center text-brand-600">
                <i data-lucide="layers" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div
            class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="smile" class="w-4 h-4"></i>
            {{ session('message') }}
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="saas-card-glass p-5 border-dashed">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-4">
            <!-- Search -->
            <div class="space-y-1.5">
                <label class="text-detail">Search</label>
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400"></i>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Dealer, notes, amount..."
                        class="saas-input pl-9">
                </div>
            </div>

            <!-- Dealer -->
            <div class="space-y-1.5">
                <label class="text-detail">Partner</label>
                <select wire:model.live="dealerId" class="saas-input">
                    <option value="">All Partners</option>
                    @foreach ($dealers as $dealer)
                        <option value="{{ $dealer->id }}">{{ $dealer->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div class="space-y-1.5">
                <label class="text-detail">Status</label>
                <select wire:model.live="status" class="saas-input">
                    <option value="all">Show all</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="partial">Partially Paid</option>
                    <option value="paid">Paid in full</option>
                    <option value="overdue">Late bills</option>
                </select>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-2 gap-2">
                <div class="space-y-1.5">
                    <label class="text-detail">From</label>
                    <input wire:model.live="dateFrom" type="date" class="saas-input">
                </div>
                <div class="space-y-1.5">
                    <label class="text-detail">To</label>
                    <input wire:model.live="dateTo" type="date" class="saas-input">
                </div>
            </div>
        </div>

        <!-- Clear Filters -->
        <div class="mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800/50 flex justify-between items-center">
            <span class="text-[10px] text-zinc-400 font-mono italic">Filter active: search="{{ $search }}",
                status="{{ $status }}"</span>
            <button wire:click="clearFilters"
                class="text-xs text-zinc-500 hover:text-brand-600 transition-colors flex items-center gap-1.5 font-medium">
                <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                Clear filters
            </button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="saas-card">
        <div class="overflow-x-auto">
            <table class="data-grid">
                <thead class="bg-zinc-50/50 dark:bg-zinc-900/50">
                    <tr>
                        <th class="data-grid-header w-1/3">Partner & Notes</th>
                        <th class="data-grid-header">Total amount</th>
                        <th class="data-grid-header">Status</th>
                        <th class="data-grid-header">Timeline</th>
                        <th class="data-grid-header text-right">Options</th>
                    </tr>
                </thead>
                <tbody x-data="{ openInvoice: null }" class="divide-y divide-zinc-100 dark:divide-zinc-800/50">
                    @forelse($invoices as $invoice)
                        <tr class="data-grid-row group" wire:key="invoice-{{ $invoice->id }}">
                            <td class="data-grid-cell">
                                <div class="flex items-center gap-3">
                                    <button
                                        @click="openInvoice = (openInvoice === {{ $invoice->id }}) ? null : {{ $invoice->id }}"
                                        class="p-1 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded transition-colors text-zinc-400 group-hover:text-zinc-600">
                                        <i data-lucide="chevron-right" class="w-4 h-4 transition-transform duration-200"
                                            :class="openInvoice === {{ $invoice->id }} ? 'rotate-90 text-brand-500' : ''"></i>
                                    </button>
                                    <div>
                                        <a href="{{ route('invoice.detail', $invoice->id) }}"
                                            class="font-bold text-zinc-900 dark:text-zinc-100 hover:text-brand-600 transition-colors">
                                            {{ $invoice->dealer->name }}
                                        </a>
                                        @if ($invoice->notes)
                                            <p class="text-[11px] text-zinc-400 mt-0.5 truncate max-w-[200px]">
                                                {{ $invoice->notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="data-grid-cell">
                                <div class="space-y-1">
                                    <span class="font-mono text-zinc-800 dark:text-zinc-200">Rs.
                                        {{ number_format($invoice->total_amount, 2) }}</span>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-16 h-1 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                                            <div class="h-full bg-brand-500"
                                                style="width: {{ $invoice->total_amount > 0 ? ($invoice->paid_amount / $invoice->total_amount) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                        <span
                                            class="text-[10px] text-zinc-400 font-mono">{{ round($invoice->total_amount > 0 ? ($invoice->paid_amount / $invoice->total_amount) * 100 : 0) }}%</span>
                                    </div>
                                </div>
                            </td>

                            <td class="data-grid-cell">
                                <div class="flex flex-col gap-1.5 items-start">
                                    @if ($invoice->isFullyPaid)
                                        <span class="saas-badge saas-badge-success"><i data-lucide="check"
                                                class="w-3 h-3"></i> Paid</span>
                                    @elseif($invoice->paid_amount > 0)
                                        <span class="saas-badge saas-badge-warning"><i data-lucide="activity"
                                                class="w-3 h-3"></i> Partial (Rs.
                                            {{ number_format($invoice->unpaid_amount, 2) }})</span>
                                    @else
                                        <span class="saas-badge saas-badge-danger"><i data-lucide="clock"
                                                class="w-3 h-3"></i> Unpaid</span>
                                    @endif

                                    @if ($invoice->overdue_level >= 1)
                                        <span
                                            class="text-[10px] font-bold text-rose-600 uppercase flex items-center gap-1">
                                            <i data-lucide="alert-triangle" class="w-3 h-3"></i> LATE
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="data-grid-cell">
                                <div class="text-[11px] space-y-0.5">
                                    <div class="flex items-center gap-1.5 text-zinc-500">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        <span>Date: {{ $invoice->issue_date->format('d M y') }}</span>
                                    </div>
                                    @if ($invoice->due_date)
                                        <div
                                            class="flex items-center gap-1.5 {{ $invoice->days_overdue > 0 ? 'text-rose-500 font-bold' : 'text-zinc-400' }}">
                                            <i data-lucide="flag" class="w-3 h-3"></i>
                                            <span>Due date: {{ $invoice->due_date->format('d M y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="data-grid-cell text-right">
                                <div
                                    class="flex justify-end gap-1 lg:opacity-20 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('invoices.edit', $invoice->id) }}"
                                        class="p-1.5 text-zinc-400 hover:text-brand-600 hover:bg-brand-50 rounded-md transition-all">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    @if ($invoice->unpaid_amount > 0)
                                        <button wire:click="addPayment({{ $invoice->id }})"
                                            class="p-1.5 text-zinc-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-md transition-all">
                                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                                        </button>
                                    @endif
                                    <button wire:click="delete({{ $invoice->id }})"
                                        class="p-1.5 text-zinc-400 hover:text-rose-600 hover:bg-rose-50 rounded-md transition-all"
                                        onclick="return confirm('Remove this bill?')">
                                        <i data-lucide="trash" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Expandable Details -->
                        <tr x-show="openInvoice === {{ $invoice->id }}" x-collapse
                            class="bg-zinc-50/50 dark:bg-zinc-950/30">
                            <td colspan="5" class="p-0">
                                <div
                                    class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-8 border-t border-zinc-100 dark:border-zinc-800">
                                    <!-- Items -->
                                    <div class="space-y-4">
                                        <h4
                                            class="text-xs font-bold uppercase tracking-widest text-zinc-400 flex items-center gap-2">
                                            <i data-lucide="box" class="w-4 h-4"></i> Items
                                        </h4>
                                        <div class="saas-card overflow-hidden border-zinc-100 dark:border-zinc-800">
                                            <table class="w-full text-[12px]">
                                                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left">Item</th>
                                                        <th class="px-3 py-2 text-right">Quantity</th>
                                                        <th class="px-3 py-2 text-right">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800">
                                                    @foreach ($invoice->items as $item)
                                                        <tr>
                                                            <td class="px-3 py-2 font-medium">{{ $item->stock->name }}
                                                            </td>
                                                            <td class="px-3 py-2 text-right text-zinc-500 font-mono">
                                                                {{ $item->quantity_sold }}
                                                                {{ $item->stock->unit ?? 'u' }}</td>
                                                            <td class="px-3 py-2 text-right font-bold">Rs.
                                                                {{ number_format($item->subtotal, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Payments -->
                                    <div class="space-y-4">
                                        <h4
                                            class="text-xs font-bold uppercase tracking-widest text-zinc-400 flex items-center gap-2">
                                            <i data-lucide="credit-card" class="w-4 h-4 text-emerald-500"></i> Payment
                                            history
                                        </h4>
                                        <div class="saas-card overflow-hidden border-zinc-100 dark:border-zinc-800">
                                            <table class="w-full text-[12px]">
                                                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left">Date</th>
                                                        <th class="px-3 py-2 text-right">Paid</th>
                                                        <th class="px-3 py-2 text-left">Ref</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800">
                                                    @foreach ($invoice->payments as $payment)
                                                        <tr>
                                                            <td class="px-3 py-2 text-zinc-500">
                                                                {{ $payment->payment_date->format('d M y') }}</td>
                                                            <td
                                                                class="px-3 py-2 text-right font-bold text-emerald-600">
                                                                Rs. {{ number_format($payment->amount, 2) }}</td>
                                                            <td class="px-3 py-2 text-zinc-400 truncate max-w-[100px]">
                                                                {{ $payment->notes ?: '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Inline Payment Form -->
                        @if ($selectedInvoiceId == $invoice->id)
                            <tr>
                                <td colspan="5" class="p-0 bg-brand-50/30 dark:bg-brand-500/5">
                                    <div class="p-8 border-t border-brand-200/30">
                                        <div class="max-w-3xl mx-auto space-y-6">
                                            <div class="flex items-center justify-between">
                                                <h3
                                                    class="text-lg font-bold text-brand-950 dark:text-brand-100 flex items-center gap-3">
                                                    <div
                                                        class="w-8 h-8 rounded bg-brand-500 text-white flex items-center justify-center">
                                                        <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                                                    </div>
                                                    Add payment for {{ $invoice->dealer->name }}
                                                </h3>
                                                <span
                                                    class="text-xs font-mono px-2 py-1 bg-brand-100 text-brand-700 rounded-full">Still
                                                    to pay:
                                                    Rs. {{ number_format($invoice->unpaid_amount, 2) }}</span>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                <div class="space-y-1.5">
                                                    <label class="text-detail">Amount</label>
                                                    <div class="relative">
                                                        <span
                                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-xs">Rs.</span>
                                                        <input wire:model="payment_amount" type="number"
                                                            step="0.01"
                                                            class="saas-input pl-10 font-mono text-base">
                                                    </div>
                                                    @error('payment_amount')
                                                        <span
                                                            class="text-rose-500 text-[10px] mt-1 font-bold">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="space-y-1.5">
                                                    <label class="text-detail">Date</label>
                                                    <input wire:model="payment_date" type="date"
                                                        class="saas-input">
                                                    @error('payment_date')
                                                        <span
                                                            class="text-rose-500 text-[10px] mt-1 font-bold">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="space-y-1.5">
                                                    <label class="text-detail">Notes</label>
                                                    <input wire:model="payment_notes" type="text"
                                                        placeholder="Add notes..." class="saas-input">
                                                </div>
                                            </div>

                                            <div class="flex gap-3 justify-end pt-4">
                                                <button type="button" wire:click="cancelPayment"
                                                    class="saas-btn-secondary">Cancel</button>
                                                <button type="button" wire:click="savePayment"
                                                    class="saas-btn-primary px-8">Save Payment</button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div
                                        class="w-16 h-16 rounded-full bg-zinc-50 dark:bg-zinc-900 flex items-center justify-center text-zinc-300">
                                        <i data-lucide="file-text" class="w-8 h-8"></i>
                                    </div>
                                    <h3 class="font-bold text-zinc-400">No bills found</h3>
                                    <p class="text-xs text-zinc-500 max-w-xs">We couldn't find any bills matching your
                                        search. Try adjusting filters or add a new bill.</p>
                                    <a href="{{ route('invoices.add') }}" class="saas-btn-secondary mt-2">Add your
                                        first bill</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


</div>
