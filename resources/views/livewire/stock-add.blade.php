<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $editingId ? __('Update Stock Item') : __('Add New Stock Item') }}</h1>
            <p class="text-sm text-slate-500 mt-1">Fill in the details below to {{ $editingId ? 'update the' : 'add a new' }} inventory item.</p>
        </div>
        <a href="{{ route('stocks.list') }}" class="btn-secondary gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Stock List
        </a>
    </div>

    <div class="max-w-2xl">
        <div class="inventory-card">
            <div class="p-6">
                @if(session()->has('message'))
                    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm rounded-xl flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Item Name *</label>
                        <input wire:model="name" type="text" placeholder="e.g. Laptop Charger" class="input-field">
                        @error('name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                        <textarea wire:model="description" rows="2" placeholder="Brief details..." class="input-field resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Quantity *</label>
                            <input wire:model="quantity" type="number" placeholder="0" class="input-field">
                            @error('quantity') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Unit</label>
                            <input wire:model="unit" type="text" placeholder="Pcs/Kg" class="input-field">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Price (LKR) *</label>
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
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        lucide.createIcons();
    });

    document.addEventListener('livewire:navigated', () => {
        lucide.createIcons();
    });
</script>
