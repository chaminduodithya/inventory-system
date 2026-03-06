<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ __('Stock Management') }}</h1>
            <p class="text-sm text-slate-500 mt-1">Manage your inventory items, quantities, and pricing.</p>
        </div>
        <div class="flex gap-3">
            <!-- Optional Top Actions -->
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Form Section -->
        <div class="lg:col-span-1">
            <div class="inventory-card">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-slate-400 mb-4">
                        {{ $editingId ? __('Update Item') : __('Add New Item') }}
                    </h2>
                    
                    @if(session()->has('message'))
                        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm rounded-xl flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="save" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">Item Name</label>
                            <input wire:model="name" type="text" placeholder="e.g. Laptop Charger" class="input-field">
                            @error('name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                            <textarea wire:model="description" rows="2" placeholder="Brief details..." class="input-field resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Quantity</label>
                                <input wire:model="quantity" type="number" placeholder="0" class="input-field">
                                @error('quantity') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Unit</label>
                                <input wire:model="unit" type="text" placeholder="Pcs/Kg" class="input-field">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Price (LKR)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">Rs.</span>
                                <input wire:model="price" type="number" step="0.01" placeholder="0.00" class="input-field pl-12">
                            </div>
                            @error('price') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-2 flex flex-col gap-2">
                            <button type="submit" class="btn-primary w-full gap-2">
                                <i data-lucide="{{ $editingId ? 'save' : 'plus' }}" class="w-4 h-4"></i>
                                {{ $editingId ? __('Update Stock') : __('Add Stock') }}
                            </button>
                            @if($editingId)
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
                                <th>Item Details</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stocks as $stock)
                                <tr>
                                    <td>
                                        <div class="font-medium text-slate-900">{{ $stock->name }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $stock->description ?: 'No description' }}</div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-slate-700">{{ $stock->quantity }}</span>
                                            <span class="text-xs text-slate-400 font-normal">{{ $stock->unit ?: 'units' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-slate-600 font-medium">
                                            Rs. {{ number_format($stock->price, 2) }}
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <button wire:click="edit({{ $stock->id }})" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </button>
                                            <button wire:click="delete({{ $stock->id }})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete" onclick="return confirm('Are you sure?')">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12 text-slate-400">
                                        <div class="flex flex-col items-center gap-2">
                                            <i data-lucide="box" class="w-8 h-8 opacity-20"></i>
                                            <p>No items found in stock.</p>
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