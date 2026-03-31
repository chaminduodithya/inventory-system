<div class="space-y-6 animate-in-fade">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 tracking-tight dark:text-white">
                {{ $editingId ? __('Change Item info') : __('Add New Item') }}</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Enter the details and price for your item.</p>
        </div>
        <a href="{{ route('stocks.list') }}" class="saas-btn-secondary gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to list
        </a>
    </div>

    <div class="max-w-2xl">
        <div class="saas-card">
            <div class="p-8">
                @if (session()->has('message'))
                    <div
                        class="mb-6 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-xs font-bold rounded-lg flex items-center gap-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="save" class="space-y-6">
                    <div class="space-y-1.5">
                        <label class="text-detail">Item Name</label>
                        <input wire:model="name" type="text" placeholder="e.g. Logic Core Controller"
                            class="saas-input">
                        @error('name')
                            <span class="text-rose-500 text-[10px] mt-1 font-bold block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-detail">Details</label>
                        <textarea wire:model="description" rows="3" placeholder="What is this item for?" class="saas-input resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-detail">In Stock</label>
                            <input wire:model="quantity" type="number" placeholder="0" class="saas-input font-mono">
                            @error('quantity')
                                <span class="text-rose-500 text-[10px] mt-1 font-bold block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-detail">Unit (e.g. Kg, Box)</label>
                            <input wire:model="unit" type="text" placeholder="Pcs / Modules" class="saas-input">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-detail font-bold text-rose-500">Alert when stock is below</label>
                            <input wire:model="min_stock_level" type="number" placeholder="5"
                                class="saas-input font-mono border-rose-100 dark:border-rose-900/30">
                            @error('min_stock_level')
                                <span class="text-rose-500 text-[10px] mt-1 font-bold block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-detail">Price (Rs.)</label>
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
                        <button type="submit" class="saas-btn-primary w-full gap-2 py-4">
                            <i data-lucide="{{ $editingId ? 'save' : 'zap' }}" class="w-4 h-4"></i>
                            {{ $editingId ? __('Save Changes') : __('Add Item') }}
                        </button>
                        @if ($editingId)
                            <button type="button" wire:click="resetForm" class="saas-btn-secondary w-full">
                                {{ __('Cancel') }}
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
