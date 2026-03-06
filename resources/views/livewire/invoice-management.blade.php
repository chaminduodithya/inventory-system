<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Invoices') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Create and manage customer/supplier invoices</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
        <!-- Form Section -->
        <div class="lg:col-span-1">
            <div class="inventory-card">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-4">
                        {{ $editingId ? __('Update Invoice') : __('New Invoice') }}
                    </h2>

                    @if (session()->has('message'))
                        <div
                            class="mb-4 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 dark:bg-emerald-900/20 dark:border-emerald-800/40 dark:text-emerald-300 text-sm rounded-xl flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="save" class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Dealer</label>
                            <select wire:model="dealer_id" class="input-field">
                                <option value="">-- Select Dealer --</option>
                                @foreach (\App\Models\Dealer::orderBy('name')->get() as $dealer)
                                    <option value="{{ $dealer->id }}">{{ $dealer->name }}</option>
                                @endforeach
                            </select>
                            @error('dealer_id')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Total
                                Amount (LKR) *</label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-sm font-medium">Rs.</span>
                                <input wire:model="total_amount" type="number" step="0.01" placeholder="0.00"
                                    class="input-field pl-12">
                            </div>
                            @error('total_amount')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Issue
                                    Date *</label>
                                <input wire:model="issue_date" type="date" class="input-field">
                                @error('issue_date')
                                    <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Due
                                    Date</label>
                                <input wire:model="due_date" type="date" class="input-field">
                                @error('due_date')
                                    <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Notes</label>
                            <textarea wire:model="notes" rows="3" placeholder="Payment terms, items summary..."
                                class="input-field resize-none"></textarea>
                        </div>

                        <div class="pt-2 flex flex-col gap-2">
                            <button type="submit" class="btn-primary w-full gap-2">
                                <i data-lucide="{{ $editingId ? 'save' : 'file-plus' }}" class="w-4 h-4"></i>
                                {{ $editingId ? __('Update Invoice') : __('Create Invoice') }}
                            </button>
                            @if ($editingId)
                                <button type="button" wire:click="resetForm" class="btn-secondary w-full">
                                    {{ __('Cancel') }}
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="lg:col-span-2">
            <div class="inventory-card">
                <div class="overflow-x-auto">
                    <table class="data-table">
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
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr
                                    class="{{ $invoice->isFullyPaid ? 'bg-emerald-50/40 dark:bg-emerald-950/20' : '' }} {{ $invoice->overdue_level >= 1 ? 'bg-rose-50/40 dark:bg-rose-950/20' : '' }} transition-colors">
                                    <td>
                                        <div class="font-medium text-slate-900 dark:text-slate-200">
                                            {{ $invoice->dealer->name }}</div>
                                        @if ($invoice->notes)
                                            <div
                                                class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate max-w-xs">
                                                {{ Str::limit($invoice->notes, 60) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="font-semibold text-slate-800 dark:text-slate-200">
                                        Rs. {{ number_format($invoice->total_amount, 2) }}
                                    </td>
                                    <td>
                                        <div class="text-sm space-y-0.5">
                                            <div>
                                                <span class="text-emerald-600 dark:text-emerald-400 font-medium">
                                                    Paid: Rs. {{ number_format($invoice->paid_amount, 2) }}
                                                </span>
                                            </div>
                                            <div class="{{ $invoice->unpaid_amount > 0 ? 'font-semibold' : '' }}">
                                                <span
                                                    class="{{ $invoice->unpaid_amount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                                    Unpaid: Rs. {{ number_format($invoice->unpaid_amount, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-sm">
                                        <div>Issued: {{ $invoice->issue_date->format('d M Y') }}</div>
                                        @if ($invoice->due_date)
                                            <div class="{{ $invoice->days_overdue > 0 ? 'font-medium' : '' }}">
                                                Due: {{ $invoice->due_date->format('d M Y') }}
                                                @if ($invoice->days_overdue > 0)
                                                    <span class="text-rose-600 dark:text-rose-400">
                                                        ({{ $invoice->days_overdue }} days overdue)
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-slate-400 dark:text-slate-500">No due date</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($invoice->isFullyPaid)
                                            <span class="badge badge-success">Fully Paid</span>
                                        @elseif($invoice->paid_amount > 0)
                                            <span class="badge badge-warning">Partial Payment</span>
                                        @else
                                            <span class="badge badge-danger">Unpaid</span>
                                        @endif

                                        @if ($invoice->overdue_level === 3)
                                            <span class="badge bg-rose-600 text-white ml-2">Overdue >90d</span>
                                        @elseif($invoice->overdue_level === 2)
                                            <span class="badge bg-orange-500 text-white ml-2">Overdue >60d</span>
                                        @elseif($invoice->overdue_level === 1)
                                            <span class="badge bg-amber-500 text-white ml-2">Overdue</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-2 items-center">
                                            <button wire:click="edit({{ $invoice->id }})"
                                                class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition-colors"
                                                title="Edit Invoice">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </button>

                                            @if ($invoice->unpaid_amount > 0)
                                                <button wire:click="addPayment({{ $invoice->id }})"
                                                    class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition-colors"
                                                    title="Add Payment">
                                                    <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                                                </button>
                                            @endif

                                            <button wire:click="delete({{ $invoice->id }})"
                                                class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition-colors"
                                                title="Delete Invoice"
                                                onclick="return confirm('Are you sure? All payments will be deleted too.')">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>


                                <!-- Inline Payment Form (shows when selected) -->
                                @if ($selectedInvoiceId === $invoice->id)
                                    <tr>
                                        <td colspan="6" class="bg-slate-50 dark:bg-slate-800/40 p-4">
                                            <div
                                                class="border border-slate-200 dark:border-slate-700 rounded-xl p-5 bg-white dark:bg-slate-900">
                                                <h3
                                                    class="text-lg font-semibold mb-4 text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                                    <i data-lucide="dollar-sign" class="w-5 h-5 text-emerald-600"></i>
                                                    Record Payment for {{ $invoice->dealer->name }}
                                                </h3>

                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Amount
                                                            (LKR) *</label>
                                                        <div class="relative">
                                                            <span
                                                                class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">Rs.</span>
                                                            <input wire:model="payment_amount" type="number"
                                                                step="0.01" class="input-field pl-12">
                                                        </div>
                                                        @error('payment_amount')
                                                            <span
                                                                class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Payment
                                                            Date *</label>
                                                        <input wire:model="payment_date" type="date"
                                                            class="input-field">
                                                        @error('payment_date')
                                                            <span
                                                                class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Notes</label>
                                                        <input wire:model="payment_notes" type="text"
                                                            placeholder="Cheque#, Bank transfer..."
                                                            class="input-field">
                                                    </div>
                                                </div>

                                                <div class="flex gap-3 justify-end">
                                                    <button type="button" wire:click="cancelPayment"
                                                        class="btn-secondary px-6">
                                                        Cancel
                                                    </button>
                                                    <button type="button" wire:click="savePayment"
                                                        class="btn-primary px-6 gap-2">
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
                                    <td colspan="5" class="text-center py-12 text-slate-400 dark:text-slate-500">
                                        <div class="flex flex-col items-center gap-2">
                                            <i data-lucide="file-text" class="w-8 h-8 opacity-20"></i>
                                            <p>No invoices yet. Create one using the form.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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
