<div class="space-y-6 animate-in-fade">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 tracking-tight dark:text-white">
                {{ $editingId ? __('Change Bill details') : __('Add New Bill') }}</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Add items and details for this bill.</p>
        </div>
        <a href="{{ route('invoices.list') }}" class="saas-btn-secondary gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to list
        </a>
    </div>

    <div class="max-w-4xl">
        <div class="saas-card">
            <div class="p-8">
                @if (session()->has('message'))
                    <div
                        class="mb-6 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-xs font-bold rounded-lg flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="save" class="space-y-8">
                    <!-- Dealer -->
                    <div class="space-y-1.5">
                        <label class="text-detail">Select Partner</label>
                        <div class="relative">
                            <i data-lucide="user"
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400"></i>
                            <select wire:model="dealer_id" class="saas-input pl-9">
                                <option value="">-- Select Partner --</option>
                                @foreach (\App\Models\Dealer::orderBy('name')->get() as $dealer)
                                    <option value="{{ $dealer->id }}">{{ $dealer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('dealer_id')
                            <span class="text-rose-500 text-[10px] font-bold block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Items -->
                    <div class="border-t border-zinc-100 dark:border-zinc-800 pt-8 mt-8">
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-4 bg-brand-500 rounded-full"></div>
                                <h3
                                    class="text-sm font-bold uppercase tracking-widest text-zinc-600 dark:text-zinc-400">
                                    Items in this bill</h3>
                            </div>
                            <button type="button" wire:click="addItemRow"
                                class="text-xs font-bold text-brand-600 hover:text-brand-700 flex items-center gap-1.5 transition-all">
                                <i data-lucide="plus-circle" class="w-4 h-4"></i> Add Item
                            </button>
                        </div>

                        <div class="space-y-3">
                            @foreach ($items as $index => $item)
                                <div
                                    class="grid grid-cols-1 md:grid-cols-12 gap-3 p-3 bg-zinc-50/50 dark:bg-zinc-900/50 rounded-lg border border-zinc-100 dark:border-zinc-800/50 relative group">
                                    <div class="md:col-span-8 space-y-1">
                                        <label class="text-[9px] font-bold uppercase text-zinc-400">Choose Item</label>
                                        <select wire:model.live="items.{{ $index }}.stock_id" class="saas-input">
                                            <option value="">-- Select Item --</option>
                                            @foreach ($availableStocks as $stock)
                                                <option value="{{ $stock->id }}">{{ $stock->name }} (Available:
                                                    {{ $stock->quantity }})</option>
                                            @endforeach
                                        </select>
                                        @error("items.$index.stock_id")
                                            <span class="text-rose-500 text-[9px] font-bold">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-3 space-y-1">
                                        <label class="text-[9px] font-bold uppercase text-zinc-400">Quantity</label>
                                        <input wire:model.live="items.{{ $index }}.quantity_sold" type="number"
                                            min="1" class="saas-input font-mono">
                                        @error("items.$index.quantity_sold")
                                            <span class="text-rose-500 text-[9px] font-bold">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-1 flex items-end justify-center pb-2">
                                        <button type="button" wire:click="removeItemRow({{ $index }})"
                                            class="p-1.5 text-zinc-300 hover:text-rose-500 transition-colors">
                                            <i data-lucide="x" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if (empty($items))
                            <div
                                class="py-12 border-2 border-dashed border-zinc-100 dark:border-zinc-800 rounded-xl text-center">
                                <p class="text-xs text-zinc-400 italic">No items added yet. Please add some items to
                                    this bill.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Dates & Notes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                        <div class="space-y-1.5">
                            <label class="text-detail">Date</label>
                            <input wire:model="issue_date" type="date" class="saas-input">
                            @error('issue_date')
                                <span class="text-rose-500 text-[10px] font-bold block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-detail">Due date</label>
                            <input wire:model="due_date" type="date" class="saas-input">
                            @error('due_date')
                                <span class="text-rose-500 text-[10px] font-bold block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-detail">Notes</label>
                        <textarea wire:model="notes" rows="3" placeholder="Add any notes here..." class="saas-input resize-none"></textarea>
                    </div>

                    <!-- Grand Total & Submit -->
                    <div
                        class="pt-8 border-t border-zinc-100 dark:border-zinc-800 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="space-y-1">
                            <p class="text-detail">Total Amount</p>
                            <div class="text-3xl font-bold text-zinc-900 dark:text-zinc-100 font-mono tracking-tighter">
                                <span class="text-sm text-zinc-400 font-medium">Rs.</span>
                                {{ number_format($this->calculateTotal(), 2) }}
                            </div>
                        </div>

                        <div class="flex gap-3">
                            @if ($editingId)
                                <button type="button" wire:click="resetForm"
                                    class="saas-btn-secondary px-8">Cancel</button>
                            @endif
                            <button type="submit" class="saas-btn-primary px-12 py-4"
                                {{ empty($items) ? 'disabled' : '' }}>
                                <i data-lucide="shield-check" class="w-4 h-4 mr-2"></i>
                                {{ $editingId ? __('Save Changes') : __('Add Bill') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
