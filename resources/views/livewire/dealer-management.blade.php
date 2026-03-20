<div class="space-y-6 animate-in-fade">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 tracking-tight dark:text-white">{{ __('Entity Management') }}
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Directory of wholesale dealers, suppliers, and key
                customers.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">
        <!-- Form Section -->
        <div class="xl:col-span-1 sticky top-6">
            <div class="saas-card">
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-2 h-6 bg-brand-500 rounded-full"></div>
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">
                            {{ $editingId ? __('Modify Entity') : __('Register New Entity') }}
                        </h2>
                    </div>

                    @if (session()->has('message'))
                        <div
                            class="mb-6 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-xs font-bold rounded-lg flex items-center gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="save" class="space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-detail">Full Registered Name</label>
                            <input wire:model="name" type="text" placeholder="e.g. Acme Logistics Pvt Ltd"
                                class="saas-input">
                            @error('name')
                                <span class="text-rose-500 text-[10px] mt-1 font-bold block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-detail">Contact Protocol (Phone/Mail)</label>
                            <div class="relative">
                                <i data-lucide="phone"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400"></i>
                                <input wire:model="contact" type="text" placeholder="+94 77 XXX XXXX"
                                    class="saas-input pl-9">
                            </div>
                            @error('contact')
                                <span class="text-rose-500 text-[10px] mt-1 font-bold block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-detail">Operational Address</label>
                            <textarea wire:model="address" rows="3" placeholder="Primary warehouse or office location..."
                                class="saas-input resize-none"></textarea>
                            @error('address')
                                <span class="text-rose-500 text-[10px] mt-1 font-bold block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="pt-2 flex flex-col gap-2">
                            <button type="submit" class="saas-btn-primary w-full gap-2">
                                <i data-lucide="{{ $editingId ? 'save' : 'plus-circle' }}" class="w-4 h-4"></i>
                                {{ $editingId ? __('Update Record') : __('Commit Registration') }}
                            </button>
                            @if ($editingId)
                                <button type="button" wire:click="resetForm" class="saas-btn-secondary w-full">
                                    {{ __('Discard Changes') }}
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="xl:col-span-2">
            <div class="saas-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="data-grid w-full">
                        <thead class="bg-zinc-50/50 dark:bg-zinc-900/50">
                            <tr>
                                <th class="data-grid-header w-1/3">Entity Detail</th>
                                <th class="data-grid-header">Contact Intel</th>
                                <th class="data-grid-header">Address</th>
                                <th class="data-grid-header text-right">Ops</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50">
                            @forelse($dealers as $dealer)
                                <tr class="data-grid-row group">
                                    <td class="data-grid-cell">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded bg-brand-500/10 flex items-center justify-center text-brand-600 font-bold text-[10px] border border-brand-500/20">
                                                {{ substr($dealer->name, 0, 1) }}
                                            </div>
                                            <div class="font-bold text-zinc-900 dark:text-zinc-100">{{ $dealer->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="data-grid-cell">
                                        <div class="text-zinc-600 dark:text-zinc-400 font-mono text-[12px]">
                                            {{ $dealer->contact ?: 'N/A' }}</div>
                                    </td>
                                    <td class="data-grid-cell">
                                        <div class="text-zinc-500 dark:text-zinc-500 text-[11px] leading-tight truncate max-w-[200px]"
                                            title="{{ $dealer->address }}">
                                            {{ $dealer->address ?: 'No registry entry' }}
                                        </div>
                                    </td>
                                    <td class="data-grid-cell text-right">
                                        <div
                                            class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button wire:click="edit({{ $dealer->id }})"
                                                class="p-1.5 text-zinc-400 hover:text-brand-600 hover:bg-brand-50 rounded transition-all"
                                                title="Edit Record">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <button wire:click="delete({{ $dealer->id }})"
                                                class="p-1.5 text-zinc-400 hover:text-rose-600 hover:bg-rose-50 rounded transition-all"
                                                title="Purge Record" onclick="return confirm('Archive this entity?')">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-20 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div
                                                class="w-12 h-12 rounded-full bg-zinc-50 dark:bg-zinc-900 flex items-center justify-center text-zinc-200">
                                                <i data-lucide="users-2" class="w-6 h-6"></i>
                                            </div>
                                            <h3 class="font-bold text-zinc-400 text-sm">Registry Vacant</h3>
                                            <p class="text-[11px] text-zinc-500 max-w-[200px]">No operational entities
                                                have been registered in the system yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div
                    class="p-4 bg-zinc-50/50 dark:bg-zinc-900/30 border-t border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                    <span class="text-[10px] font-mono text-zinc-400">Total Entries: {{ $dealers->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>



