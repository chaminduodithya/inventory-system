<div class="space-y-6 animate-in-fade">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 tracking-tight dark:text-white">{{ __('Entity Directory') }}</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Manage wholesale dealers, logistical partners, and
                key customers.</p>
        </div>
        <a href="{{ route('dealers.add') }}" class="saas-btn-primary gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            Register New Entity
        </a>
    </div>

    @if (session()->has('message'))
        <div
            class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-xs font-bold rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div
            class="p-3 bg-rose-500/10 border border-rose-500/20 text-rose-600 text-xs font-bold rounded-lg flex items-center gap-2">
            <i data-lucide="alert-octagon" class="w-4 h-4"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="saas-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-grid w-full">
                <thead>
                    <tr class="bg-zinc-50/50 dark:bg-zinc-900/50">
                        <th class="data-grid-header">Entity Identifier</th>
                        <th class="data-grid-header">Contact Intel</th>
                        <th class="data-grid-header">Operations Center (Address)</th>
                        <th class="data-grid-header text-right">Ops</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50">
                    @forelse($dealers as $dealer)
                        <tr class="data-grid-row group" wire:key="dealer-{{ $dealer->id }}">
                            <td class="data-grid-cell">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded bg-brand-500/10 flex items-center justify-center text-brand-600 font-bold text-[10px] border border-brand-500/20">
                                        {{ substr($dealer->name, 0, 1) }}
                                    </div>
                                    <div class="font-bold text-zinc-900 dark:text-zinc-100">{{ $dealer->name }}</div>
                                </div>
                            </td>
                            <td class="data-grid-cell">
                                <div class="text-zinc-600 dark:text-zinc-400 font-mono text-[12px]">
                                    {{ $dealer->contact ?: 'N/A' }}</div>
                            </td>
                            <td class="data-grid-cell">
                                <div class="text-zinc-500 dark:text-zinc-500 text-[11px] leading-tight truncate max-w-[300px]"
                                    title="{{ $dealer->address }}">
                                    {{ $dealer->address ?: 'No registry entry' }}
                                </div>
                            </td>
                            <td class="data-grid-cell text-right">
                                <div
                                    class="flex justify-end gap-1 lg:opacity-20 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('dealers.edit', $dealer->id) }}"
                                        class="p-1.5 text-zinc-400 hover:text-brand-600 hover:bg-brand-50 rounded transition-all"
                                        title="Edit Record">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    </a>
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
                            <td colspan="4" class="py-24 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div
                                        class="w-16 h-16 rounded-full bg-zinc-50 dark:bg-zinc-900 flex items-center justify-center text-zinc-200">
                                        <i data-lucide="users-2" class="w-8 h-8"></i>
                                    </div>
                                    <div class="space-y-1">
                                        <h3 class="font-bold text-zinc-400 text-sm">Registry Vacant</h3>
                                        <p class="text-[11px] text-zinc-500 max-w-[240px] mx-auto">No operational
                                            entities have been registered.</p>
                                        <a href="{{ route('dealers.add') }}"
                                            class="text-brand-600 hover:text-brand-700 text-[11px] font-bold mt-2 inline-flex items-center gap-1">
                                            <i data-lucide="plus" class="w-3 h-3"></i> REGISTER FIRST ENTITY
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div
            class="p-4 bg-zinc-50/50 dark:bg-zinc-900/30 border-t border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
            <span class="text-[10px] font-mono text-zinc-400">TOTAL ENTITIES LOGGED: {{ $dealers->count() }}</span>
        </div>
    </div>
</div>
