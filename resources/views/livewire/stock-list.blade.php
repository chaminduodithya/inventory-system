<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ __('Stock Inventory') }}</h1>
            <p class="text-sm text-slate-500 mt-1">View and manage all your inventory items.</p>
        </div>
        <a href="{{ route('stocks.add') }}" class="btn-primary gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add New Item
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
                                    <a href="{{ route('stocks.edit', $stock->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
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
                                    <a href="{{ route('stocks.add') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium mt-2">
                                        + Add your first item
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
