<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ __('Dealer Management') }}</h1>
            <p class="text-sm text-slate-500 mt-1">Manage your suppliers, dealers, and customers in one place.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Form Section -->
        <div class="lg:col-span-1">
            <div class="inventory-card">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-4">
                        {{ $editingId ? __('Update Dealer') : __('Add New Dealer') }}
                    </h2>
                    
                    @if(session()->has('message'))
                        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm rounded-xl flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="save" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Dealer/Customer Name</label>
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

        <!-- Table Section -->
        <div class="lg:col-span-2">
            <div class="inventory-card">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Dealer Info</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dealers as $dealer)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                                {{ substr($dealer->name, 0, 1) }}
                                            </div>
                                            <div class="font-medium text-slate-900">{{ $dealer->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-slate-600 truncate max-w-[150px]">{{ $dealer->contact ?: '-' }}</div>
                                    </td>
                                    <td>
                                        <div class="text-slate-500 text-xs truncate max-w-[200px]">{{ $dealer->address ?: 'No address' }}</div>
                                    </td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <button wire:click="edit({{ $dealer->id }})" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </button>
                                            <button wire:click="delete({{ $dealer->id }})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete" onclick="return confirm('Are you sure?')">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12 text-slate-400">
                                        <div class="flex flex-col items-center gap-2">
                                            <i data-lucide="users" class="w-8 h-8 opacity-20"></i>
                                            <p>No dealers found.</p>
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