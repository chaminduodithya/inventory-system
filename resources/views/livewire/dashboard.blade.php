<div class="space-y-8">
    <!-- Welcome Header -->
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
            Welcome back, {{ auth()->user()->name }}!
        </h1>
        <p class="text-slate-500 dark:text-slate-400 mt-2">Here's a quick overview of your inventory and finances today.</p>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Stock Value -->
        <div class="inventory-card p-6 bg-gradient-to-br from-indigo-600 to-indigo-700 text-white border-0 shadow-lg shadow-indigo-100 dark:shadow-none">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-xl">
                    <i data-lucide="dollar-sign" class="w-6 h-6"></i>
                </div>
            </div>
            <h3 class="text-indigo-100 text-sm font-medium uppercase tracking-wide">Total Stock Value</h3>
            <p class="text-3xl font-bold mt-2">Rs. {{ number_format($totalStockValue, 2) }}</p>
            <a href="{{ route('stocks') }}" class="mt-4 inline-flex items-center text-xs font-medium text-white/90 hover:text-white">
                View Stock <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
            </a>
        </div>

        <!-- Low Stock Items -->
        <div class="inventory-card p-6 bg-gradient-to-br from-amber-500 to-amber-600 text-white border-0 shadow-lg shadow-amber-100 dark:shadow-none">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-xl">
                    <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                </div>
            </div>
            <h3 class="text-amber-100 text-sm font-medium uppercase tracking-wide">Low Stock Items</h3>
            <p class="text-3xl font-bold mt-2">{{ $lowStockItems->count() }}</p>
            @if($lowStockItems->count() > 0)
                <p class="text-sm text-amber-100/90 mt-1">Below {{ $lowStockThreshold }} units</p>
            @endif
            <a href="{{ route('stocks') }}" class="mt-4 inline-flex items-center text-xs font-medium text-white/90 hover:text-white">
                Manage Stock <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
            </a>
        </div>

        <!-- Total Outstanding -->
        <div class="inventory-card p-6 bg-gradient-to-br from-rose-600 to-rose-700 text-white border-0 shadow-lg shadow-rose-100 dark:shadow-none">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-xl">
                    <i data-lucide="alert-circle" class="w-6 h-6"></i>
                </div>
            </div>
            <h3 class="text-rose-100 text-sm font-medium uppercase tracking-wide">Total Outstanding</h3>
            <p class="text-3xl font-bold mt-2">Rs. {{ number_format($totalOutstanding, 2) }}</p>
            <a href="{{ route('invoices') }}" class="mt-4 inline-flex items-center text-xs font-medium text-white/90 hover:text-white">
                View Invoices <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
            </a>
        </div>

        <!-- Overdue Dealers -->
        <div class="inventory-card p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-slate-100 dark:bg-slate-700 rounded-xl text-slate-600 dark:text-slate-300">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
            </div>
            <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium uppercase tracking-wide">Overdue Dealers</h3>
            <p class="text-3xl font-bold mt-2 text-slate-900 dark:text-white">{{ $overdueDealers->count() }}</p>
            <a href="{{ route('summary') }}" class="mt-4 inline-flex items-center text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700">
                View Summary <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Low Stock List -->
    @if($lowStockItems->isNotEmpty())
        <div class="inventory-card overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center gap-3 text-amber-700 dark:text-amber-300">
                    <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                    Low Stock Alert (≤ {{ $lowStockThreshold }} units)
                </h2>

                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-right">Current Stock</th>
                                <th class="text-right">Unit</th>
                                <th class="text-right">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockItems as $stock)
                                <tr>
                                    <td class="font-medium">{{ $stock->name }}</td>
                                    <td class="text-right font-bold text-amber-600 dark:text-amber-400">{{ $stock->quantity }}</td>
                                    <td class="text-right">{{ $stock->unit ?? 'units' }}</td>
                                    <td class="text-right">Rs. {{ number_format($stock->quantity * $stock->price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Invoices -->
    <div class="inventory-card overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center justify-between">
                Recent Invoices
                <a href="{{ route('invoices') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                    View All <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </h2>

            @if($recentInvoices->isEmpty())
                <p class="text-slate-500 dark:text-slate-400 text-center py-8">No invoices yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Dealer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentInvoices as $invoice)
                                <tr>
                                    <td>
                                        <a href="{{ route('invoice.detail', $invoice->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $invoice->dealer->name }}
                                        </a>
                                    </td>
                                    <td class="font-medium">Rs. {{ number_format($invoice->total_amount, 2) }}</td>
                                    <td>
                                        @if($invoice->isFullyPaid)
                                            <span class="badge badge-success">Paid</span>
                                        @elseif($invoice->paid_amount > 0)
                                            <span class="badge badge-warning">Partial</span>
                                        @else
                                            <span class="badge badge-danger">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="text-sm text-slate-500 dark:text-slate-400">
                                        {{ $invoice->issue_date->format('d M Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>