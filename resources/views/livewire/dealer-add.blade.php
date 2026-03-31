<div class="space-y-6 animate-in-fade">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 tracking-tight dark:text-white">
                {{ $editingId ? __('Change Partner info') : __('Add a new Partner') }}
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Add the name, phone and address for your partner.
            </p>
        </div>
        <a href="{{ route('dealers.list') }}" class="saas-btn-secondary gap-2">
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
                        <label class="text-detail">Name</label>
                        <input wire:model="name" type="text" placeholder="e.g. Nexus Logistics Hub"
                            class="saas-input">
                        @error('name')
                            <span class="text-rose-500 text-[10px] mt-1 font-bold block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-detail">Phone or Email</label>
                        <div class="relative">
                            <i data-lucide="phone"
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400"></i>
                            <input wire:model="contact" type="text" placeholder="+94 7X XXX XXXX"
                                class="saas-input pl-9">
                        </div>
                        @error('contact')
                            <span class="text-rose-500 text-[10px] mt-1 font-bold block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-detail">Address</label>
                        <textarea wire:model="address" rows="4" placeholder="Primary dispatch center or HQ location..."
                            class="saas-input resize-none"></textarea>
                        @error('address')
                            <span class="text-rose-500 text-[10px] mt-1 font-bold block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pt-4 flex flex-col gap-2">
                        <button type="submit" class="saas-btn-primary w-full gap-2 py-4">
                            <i data-lucide="{{ $editingId ? 'save' : 'user-plus' }}" class="w-4 h-4"></i>
                            {{ $editingId ? __('Save Changes') : __('Add Partner') }}
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
