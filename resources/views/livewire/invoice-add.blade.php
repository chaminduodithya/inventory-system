<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $editingId ? __('Update Invoice') : __('Create New Invoice') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Fill in the details below to {{ $editingId ? 'update the' : 'create a new' }} invoice.</p>
        </div>
        <a href="{{ route('invoices.list') }}" class="btn-secondary gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Invoices
        </a>
    </div>

    <div class="max-w-4xl">
        <div class="inventory-card">
            <div class="p-6">
                @if (session()->has('message'))
                    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 dark:bg-emerald-900/20 dark:border-emerald-800/40 dark:text-emerald-300 text-sm rounded-xl flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="save" class="space-y-6">
                    <!-- Dealer -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Dealer *</label>
                        <select wire:model="dealer_id" class="input-field">
                            <option value="">-- Select Dealer --</option>
                            @foreach (\App\Models\Dealer::orderBy('name')->get() as $dealer)
                                <option value="{{ $dealer->id }}">{{ $dealer->name }}</option>
                            @endforeach
                        </select>
                        @error('dealer_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Items -->
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">Invoice Items</h3>
                            <button type="button" wire:click="addItemRow" class="btn-primary px-4 py-2 text-sm gap-2">
                                <i data-lucide="plus" class="w-4 h-4"></i> Add Item
                            </button>
                        </div>

                        @foreach ($items as $index => $item)
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 p-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Product *</label>
                                    <select wire:model="items.{{ $index }}.stock_id" class="input-field">
                                        <option value="">-- Select Product --</option>
                                        @foreach ($availableStocks as $stock)
                                            <option value="{{ $stock->id }}" {{ $item['stock_id'] == $stock->id ? 'selected' : '' }}>
                                                {{ $stock->name }} (Stock: {{ $stock->quantity }} {{ $stock->unit ?? 'units' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("items.$index.stock_id") <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Quantity Sold *</label>
                                    <input wire:model="items.{{ $index }}.quantity_sold" type="number" min="1" class="input-field">
                                    @error("items.$index.quantity_sold") <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2 flex items-end">
                                    <button type="button" wire:click="removeItemRow({{ $index }})" class="text-rose-600 hover:text-rose-800 text-sm font-medium">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        @if (empty($items))
                            <p class="text-slate-500 dark:text-slate-400 text-center py-4">Add at least one item to create invoice</p>
                        @endif
                    </div>

                    <!-- Dates & Notes -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Issue Date *</label>
                            <input wire:model="issue_date" type="date" class="input-field">
                            @error('issue_date') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Due Date</label>
                            <input wire:model="due_date" type="date" class="input-field">
                            @error('due_date') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Notes</label>
                        <textarea wire:model="notes" rows="3" placeholder="Payment terms, items summary..." class="input-field resize-none"></textarea>
                    </div>

                    <!-- Grand Total & Submit -->
                    <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                        <div class="text-right text-xl font-bold">
                            Grand Total: Rs. {{ number_format($this->calculateTotal(), 2) }}
                        </div>
                    </div>

                    <div class="pt-2 flex flex-col gap-2">
                        <button type="submit" class="btn-primary w-full gap-2" {{ empty($items) ? 'disabled' : '' }}>
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
</div>

<script>
    document.addEventListener('livewire:initialized', () => { lucide.createIcons(); });
    document.addEventListener('livewire:navigated', () => { lucide.createIcons(); });
</script>
