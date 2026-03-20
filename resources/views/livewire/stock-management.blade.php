<div class="space-y-6 animate-in-fade">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 tracking-tight dark:text-white">
                {{ __('Nexus Inventory Control') }}</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Global stock levels, unit tracking, and valuation
                console.</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="px-3 py-1.5 bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center gap-2">
                <i data-lucide="package" class="w-3.5 h-3.5 text-zinc-400"></i>
                <span class="text-[10px] font-mono font-bold text-zinc-500">{{ $stocks->count() }} SKUs LOGGED</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">
        <!-- SKU Input Section -->
        <div class="xl:col-span-1 sticky top-6">
            <div class="saas-card">
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-2 h-6 bg-brand-500 rounded-full"></div>
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">
                            {{ $editingId ? __('Update SKU') : __('Register New SKU') }}
                        </h2>
                    </div>

                    @if (session()->has('message'))
                        <div
                            class="mb-6 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-xs font-bold rounded-lg flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="save" class="space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-detail">Product Title / Identifier</label>
                            <input wire:model="name" type="text" placeholder="e.g. RTX 4090 GPU - Batch A"
                                class="saas-input">
                            @error('name')
                                <span class="text-rose-500 text-[10px] mt-1 font-bold block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-detail">Technical Description</label>
                            <textarea wire:model="description" rows="2" placeholder="Specifications, batch notes..."
                                class="saas-input resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-detail">Qty in Stock</label>
                                <input wire:model="quantity" type="number" placeholder="0"
                                    class="saas-input font-mono">
                                @error('quantity')
                                    <span class="text-rose-500 text-[10px] mt-1 font-bold block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-detail">Measurement Unit</label>
                                <input wire:model="unit" type="text" placeholder="Pcs / Units" class="saas-input">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-detail">Standard Price Points (LKR)</label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-xs font-mono">Rs.</span>
                                <input wire:model="price" type="number" step="0.01" placeholder="0.00"
                                    class="saas-input pl-10 font-mono text-base">
                            </div>
                            @error('price')
                                <span class="text-rose-500 text-[10px] mt-1 font-bold block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="pt-4 flex flex-col gap-2">
                            <button type="submit" class="saas-btn-primary w-full gap-2 py-3">
                                <i data-lucide="{{ $editingId ? 'save' : 'zap' }}" class="w-4 h-4"></i>
                                {{ $editingId ? __('Commit Update') : __('Initialize SKU') }}
                            </button>
                            @if ($editingId)
                                <button type="button" wire:click="resetForm" class="saas-btn-secondary w-full">
                                    {{ __('Abort Edit') }}
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ledger View Section -->
        <div class="xl:col-span-2">
            <div class="saas-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="data-grid w-full">
                        <thead class="bg-zinc-50/50 dark:bg-zinc-900/50">
                            <tr>
                                <th class="data-grid-header w-1/2">SKU Identity & Core Details</th>
                                <th class="data-grid-header">Stock Threshold</th>
                                <th class="data-grid-header text-right">Valuation</th>
                                <th class="data-grid-header text-right">Ops</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50">
                            @forelse($stocks as $stock)
                                <tr class="data-grid-row group">
                                    <td class="data-grid-cell">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-zinc-900 dark:text-zinc-100">{{ $stock->name }}</span>
                                            <span
                                                class="text-[10px] text-zinc-400 mt-1 uppercase tracking-tight truncate max-w-[300px]">{{ $stock->description ?: 'No technical specifications provided' }}</span>
                                        </div>
                                    </td>
                                    <td class="data-grid-cell">
                                        <div class="flex items-center gap-2.5">
                                            <div class="flex flex-col">
                                                <span
                                                    class="font-mono text-base font-bold {{ $stock->quantity <= 5 ? 'text-rose-500' : 'text-zinc-800 dark:text-zinc-200' }}">{{ $stock->quantity }}</span>
                                                <span
                                                    class="text-[10px] text-zinc-400 uppercase leading-none">{{ $stock->unit ?: 'units' }}
                                                    available</span>
                                            </div>
                                            @if ($stock->quantity <= 5)
                                                <span class="flex h-2 w-2 rounded-full bg-rose-500 animate-ping"
                                                    title="Low Stock Warning"></span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="data-grid-cell text-right">
                                        <div class="flex flex-col">
                                            <span class="font-mono text-zinc-900 dark:text-zinc-100 font-bold">Rs.
                                                {{ number_format($stock->price, 2) }}</span>
                                            <span class="text-[9px] text-zinc-400 uppercase">Per Unit Basis</span>
                                        </div>
                                    </td>
                                    <td class="data-grid-cell text-right">
                                        <div
                                            class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button wire:click="edit({{ $stock->id }})"
                                                class="p-1.5 text-zinc-400 hover:text-brand-600 hover:bg-brand-50 rounded transition-all">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <button wire:click="delete({{ $stock->id }})"
                                                class="p-1.5 text-zinc-400 hover:text-rose-600 hover:bg-rose-50 rounded transition-all"
                                                onclick="return confirm('Purge SKU from Registry?')">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-32 text-center">
                                        <div class="flex flex-col items-center gap-4">
                                            <div
                                                class="w-20 h-20 rounded-full bg-zinc-50 dark:bg-zinc-900 flex items-center justify-center text-zinc-200">
                                                <i data-lucide="box-select" class="w-10 h-10"></i>
                                            </div>
                                            <div class="space-y-1">
                                                <h3 class="font-bold text-zinc-500">Warehouse Empty</h3>
                                                <p class="text-[11px] text-zinc-400 max-w-[240px] mx-auto">No inventory
                                                    items detected in the active registry. Initialize your first SKU to
                                                    begin tracking.</p>
                                            </div>
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



