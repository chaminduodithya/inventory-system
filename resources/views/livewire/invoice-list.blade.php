<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('All Invoices') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">View and manage all customer/supplier invoices.</p>
        </div>
        <a href="{{ route('invoices.add') }}" class="btn-primary gap-2">
            <i data-lucide="file-plus" class="w-4 h-4"></i>
            Create Invoice
        </a>
    </div>

    @if(session()->has('message'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 dark:bg-emerald-900/20 dark:border-emerald-800/40 dark:text-emerald-300 text-sm rounded-xl flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('message') }}
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="inventory-card p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Search</label>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Dealer, notes, amount..." class="input-field">
            </div>

            <!-- Dealer -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Dealer</label>
                <select wire:model.live="dealerId" class="input-field">
                    <option value="">All Dealers</option>
                    @foreach($dealers as $dealer)
                        <option value="{{ $dealer->id }}">{{ $dealer->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status</label>
                <select wire:model.live="status" class="input-field">
                    <option value="all">All</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="partial">Partial</option>
                    <option value="paid">Paid</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">From</label>
                    <input wire:model.live="dateFrom" type="date" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">To</label>
                    <input wire:model.live="dateTo" type="date" class="input-field">
                </div>
            </div>
        </div>

        <!-- Clear Filters -->
        <div class="mt-4 flex justify-end">
            <button wire:click="clearFilters" class="text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 flex items-center gap-1">
                <i data-lucide="x-circle" class="w-4 h-4"></i>
                Clear Filters
            </button>
        </div>
    </div>

    <div class="inventory-card">
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Dealer</th>
                        <th>Total</th>
                        <th>Paid / Unpaid</th>
                        <th>Dates</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody x-data="{ openInvoice: null }">
                    @forelse($invoices as $invoice)
                        <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <!-- Clickable area for expand (only first cell) -->
                            <td class="cursor-pointer" @click="openInvoice = (openInvoice === {{ $invoice->id }}) ? null : {{ $invoice->id }}">
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-colors">
                                        <i data-lucide="chevron-down" class="w-5 h-5 transition-transform" :class="openInvoice === {{ $invoice->id }} ? 'rotate-180' : ''"></i>
                                    </span>
                                    <a href="{{ route('invoice.detail', $invoice->id) }}"
                                       class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium"
                                       @click.stop>
                                        {{ $invoice->dealer->name }}
                                    </a>
                                </div>
                                @if($invoice->notes)
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate max-w-xs">
                                        {{ Str::limit($invoice->notes, 40) }}
                                    </div>
                                @endif
                            </td>

                            <td class="font-semibold">
                                Rs. {{ number_format($invoice->total_amount, 2) }}
                            </td>
                            <td>
                                <div class="text-sm">
                                    <span class="text-emerald-600 dark:text-emerald-400">
                                        Paid: {{ number_format($invoice->paid_amount, 2) }}
                                    </span><br>
                                    <span class="{{ $invoice->unpaid_amount > 0 ? 'text-rose-600 dark:text-rose-400 font-medium' : '' }}">
                                        Unpaid: {{ number_format($invoice->unpaid_amount, 2) }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-sm">
                                Issued: {{ $invoice->issue_date->format('d M Y') }}<br>
                                @if($invoice->due_date)
                                    Due: <span class="{{ $invoice->days_overdue > 0 ? 'text-rose-600 dark:text-rose-400' : '' }}">
                                        {{ $invoice->due_date->format('d M Y') }}
                                    </span>
                                    @if($invoice->days_overdue > 0)
                                        ({{ $invoice->days_overdue }} days overdue)
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($invoice->isFullyPaid)
                                    <span class="badge badge-success">Paid</span>
                                @elseif($invoice->paid_amount > 0)
                                    <span class="badge badge-warning">Partial</span>
                                @else
                                    <span class="badge badge-danger">Unpaid</span>
                                @endif

                                @if($invoice->overdue_level >= 1)
                                    <span class="badge ml-2 {{ $invoice->overdue_level >= 2 ? 'bg-rose-600' : 'bg-amber-500' }} text-white">
                                        Overdue
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="text-right">
                                <div class="flex justify-end gap-2 items-center">
                                    <a href="{{ route('invoices.edit', $invoice->id) }}"
                                       class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                       title="Edit"
                                       @click.stop>
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>

                                    @if($invoice->unpaid_amount > 0)
                                        <button 
                                            wire:click="addPayment({{ $invoice->id }})" 
                                            class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                                            title="Add Payment"
                                            @click.stop
                                        >
                                            <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                                        </button>
                                    @endif

                                    <button 
                                        wire:click="delete({{ $invoice->id }})" 
                                        class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                        title="Delete"
                                        @click.stop
                                        onclick="return confirm('Delete this invoice and its payments?')"
                                    >
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Expandable Details -->
                        <tr x-show="openInvoice === {{ $invoice->id }}" class="bg-slate-50/70 dark:bg-slate-800/40">
                            <td colspan="6" class="p-0">
                                <div class="p-6 space-y-8 border-t border-slate-200 dark:border-slate-700">
                                    <!-- Items -->
                                    <div>
                                        <h4 class="text-lg font-semibold mb-3 flex items-center gap-2">
                                            <i data-lucide="package" class="w-5 h-5 text-indigo-600 dark:text-indigo-400"></i>
                                            Items Sold
                                        </h4>
                                        @if($invoice->items->isEmpty())
                                            <p class="text-slate-500 dark:text-slate-400 text-sm">No items recorded.</p>
                                        @else
                                            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">
                                                <table class="w-full text-sm">
                                                    <thead class="bg-slate-100 dark:bg-slate-700/50">
                                                        <tr>
                                                            <th class="px-4 py-3 text-left">Product</th>
                                                            <th class="px-4 py-3 text-right">Qty</th>
                                                            <th class="px-4 py-3 text-right">Unit Price</th>
                                                            <th class="px-4 py-3 text-right">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                                        @foreach($invoice->items as $item)
                                                            <tr>
                                                                <td class="px-4 py-3">{{ $item->stock->name }}</td>
                                                                <td class="px-4 py-3 text-right">{{ $item->quantity_sold }} {{ $item->stock->unit ?? 'units' }}</td>
                                                                <td class="px-4 py-3 text-right">Rs. {{ number_format($item->unit_price, 2) }}</td>
                                                                <td class="px-4 py-3 text-right font-medium">Rs. {{ number_format($item->subtotal, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Payments -->
                                    <div>
                                        <h4 class="text-lg font-semibold mb-3 flex items-center gap-2">
                                            <i data-lucide="dollar-sign" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                                            Payment History
                                        </h4>
                                        @if($invoice->payments->isEmpty())
                                            <p class="text-slate-500 dark:text-slate-400 text-sm">No payments recorded yet.</p>
                                        @else
                                            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">
                                                <table class="w-full text-sm">
                                                    <thead class="bg-slate-100 dark:bg-slate-700/50">
                                                        <tr>
                                                            <th class="px-4 py-3 text-left">Date</th>
                                                            <th class="px-4 py-3 text-right">Amount</th>
                                                            <th class="px-4 py-3 text-left">Notes</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                                        @foreach($invoice->payments as $payment)
                                                            <tr>
                                                                <td class="px-4 py-3">{{ $payment->payment_date->format('d M Y') }}</td>
                                                                <td class="px-4 py-3 text-right font-medium text-emerald-600 dark:text-emerald-400">
                                                                    Rs. {{ number_format($payment->amount, 2) }}
                                                                </td>
                                                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">
                                                                    {{ $payment->notes ?: '-' }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Inline Payment Form -->
                        @if($selectedInvoiceId == $invoice->id)
                            <tr>
                                <td colspan="6" class="p-0 bg-slate-50 dark:bg-slate-900/50">
                                    <div class="p-6 border-t border-slate-200 dark:border-slate-700">
                                        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-emerald-700 dark:text-emerald-300">
                                            <i data-lucide="dollar-sign" class="w-5 h-5"></i>
                                            Record Payment for {{ $invoice->dealer->name }}
                                        </h3>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Amount (LKR) *</label>
                                                <div class="relative">
                                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-sm">Rs.</span>
                                                    <input wire:model="payment_amount" type="number" step="0.01" class="input-field pl-12">
                                                </div>
                                                @error('payment_amount') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Payment Date *</label>
                                                <input wire:model="payment_date" type="date" class="input-field">
                                                @error('payment_date') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Notes</label>
                                                <input wire:model="payment_notes" type="text" placeholder="Cheque #, Bank transfer..." class="input-field">
                                            </div>
                                        </div>

                                        <div class="flex gap-3 justify-end">
                                            <button type="button" wire:click="cancelPayment" class="btn-secondary px-6">
                                                Cancel
                                            </button>
                                            <button type="button" wire:click="savePayment" class="btn-primary px-6 gap-2">
                                                <i data-lucide="save" class="w-4 h-4"></i>
                                                Record Payment
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="file-text" class="w-8 h-8 opacity-20"></i>
                                    <p>No invoices yet.</p>
                                    <a href="{{ route('invoices.add') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium mt-2">
                                        + Create your first invoice
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => { lucide.createIcons(); });
        document.addEventListener('livewire:navigated', () => { lucide.createIcons(); });
    </script>
</div>
