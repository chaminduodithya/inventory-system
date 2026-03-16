<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $editingId ? __('Update Dealer') : __('Add New Dealer') }}</h1>
            <p class="text-sm text-slate-500 mt-1">Fill in the details below to {{ $editingId ? 'update the' : 'add a new' }} dealer.</p>
        </div>
        <a href="{{ route('dealers.list') }}" class="btn-secondary gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Dealer List
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
                        <label class="block text-sm font-medium text-slate-700 mb-1">Dealer/Customer Name *</label>
                        <input wire:model="name" type="text" placeholder="e.g. ABC Holdings" class="input-field">
                        @error('name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Contact Details</label>
                        <input wire:model="contact" type="text" placeholder="Phone or Email" class="input-field">
                        @error('contact') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Address</label>
                        <textarea wire:model="address" rows="3" placeholder="Full address..." class="input-field resize-none"></textarea>
                        @error('address') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2 flex flex-col gap-2">
                        <button type="submit" class="btn-primary w-full gap-2">
                            <i data-lucide="{{ $editingId ? 'save' : 'user-plus' }}" class="w-4 h-4"></i>
                            {{ $editingId ? __('Update Dealer') : __('Add Dealer') }}
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
