<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ __('Dealer Directory') }}</h1>
            <p class="text-sm text-slate-500 mt-1">View and manage all your dealers and customers.</p>
        </div>
        <a href="{{ route('dealers.add') }}" class="btn-primary gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            Add New Dealer
        </a>
    </div>

    @if(session()->has('message'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm rounded-xl flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('message') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="p-4 bg-rose-50 border border-rose-100 text-rose-700 text-sm rounded-xl flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4"></i>
            {{ session('error') }}
        </div>
    @endif

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
                                    <a href="{{ route('dealers.edit', $dealer->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
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
                                    <a href="{{ route('dealers.add') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium mt-2">
                                        + Add your first dealer
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
