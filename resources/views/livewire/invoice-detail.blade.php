<div class="space-y-8 max-w-5xl mx-auto print:max-w-none print:space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 print:gap-2">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Invoice #{{ $invoice->id }}</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">
                Issued on {{ $invoice->issue_date->format('d M Y') }}
                @if($invoice->due_date)
                    • Due {{ $invoice->due_date->format('d M Y') }}
                @endif
            </p>
        </div>

        <div class="flex flex-col items-end gap-2 print:hidden">
            @if($invoice->unpaid_amount > 0)
                <span class="badge badge-danger text-lg px-4 py-2">Outstanding: Rs. {{ number_format($invoice->unpaid_amount, 2) }}</span>
            @else
                <span class="badge badge-success text-lg px-4 py-2">Fully Paid</span>
            @endif

            <button onclick="window.print()" class="btn-primary flex items-center gap-2 px-6 py-2">
                <i data-lucide="printer" class="w-5 h-5"></i>
                Print Invoice
            </button>
        </div>
    </div>

    <!-- Dealer Info -->
    <div class="inventory-card p-6">
        <h2 class="text-xl font-semibold mb-4 text-slate-800 dark:text-slate-200">Bill To</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="font-medium text-slate-900 dark:text-slate-100 text-lg">{{ $invoice->dealer->name }}</p>
                @if($invoice->dealer->contact)
                    <p class="text-slate-600 dark:text-slate-300">Contact: {{ $invoice->dealer->contact }}</p>
                @endif
                @if($invoice->dealer->address)
                    <p class="text-slate-600 dark:text-slate-300 whitespace-pre-line">{{ $invoice->dealer->address }}</p>
                @endif
            </div>

            <div class="text-right md:text-left">
                <p class="text-sm text-slate-500 dark:text-slate-400">Invoice Date</p>
                <p class="font-medium">{{ $invoice->issue_date->format('d M Y') }}</p>

                @if($invoice->due_date)
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Due Date</p>
                    <p class="font-medium {{ now()->gt($invoice->due_date) ? 'text-rose-600 dark:text-rose-400' : '' }}">
                        {{ $invoice->due_date->format('d M Y') }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="inventory-card overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4 text-slate-800 dark:text-slate-200">Items</h2>

            @if($invoice->items->isEmpty())
                <p class="text-slate-500 dark:text-slate-400">No items in this invoice.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table w-full">
                        <thead>
                            <tr>
                                <th class="text-left">Product</th>
                                <th class="text-right">Quantity</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td class="font-medium">{{ $item->stock->name }}</td>
                                    <td class="text-right">{{ $item->quantity_sold }} {{ $item->stock->unit ?? 'units' }}</td>
                                    <td class="text-right">Rs. {{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-right font-medium">Rs. {{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-slate-50 dark:bg-slate-800/40 font-bold">
                                <td colspan="3" class="text-right">Grand Total</td>
                                <td class="text-right">Rs. {{ number_format($invoice->total_amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Payments & Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Payments -->
        <div class="inventory-card p-6">
            <h2 class="text-xl font-semibold mb-4 text-slate-800 dark:text-slate-200">Payments Received</h2>

            @if($invoice->payments->isEmpty())
                <p class="text-slate-500 dark:text-slate-400">No payments recorded yet.</p>
            @else
                <div class="space-y-4">
                    @foreach($invoice->payments as $payment)
                        <div class="flex justify-between items-start border-b border-slate-200 dark:border-slate-700 pb-4 last:border-0">
                            <div>
                                <p class="font-medium">{{ $payment->payment_date->format('d M Y') }}</p>
                                @if($payment->notes)
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $payment->notes }}</p>
                                @endif
                            </div>
                            <p class="text-emerald-600 dark:text-emerald-400 font-semibold text-lg">
                                Rs. {{ number_format($payment->amount, 2) }}
                            </p>
                        </div>
                    @endforeach

                    <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total Paid</span>
                            <span class="text-emerald-600 dark:text-emerald-400">Rs. {{ number_format($this->totalPaid, 2) }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Summary Box -->
        <div class="inventory-card p-6 bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-950/30 dark:to-indigo-900/30">
            <h2 class="text-xl font-semibold mb-6 text-slate-800 dark:text-slate-200">Summary</h2>
            <div class="space-y-4">
                <div class="flex justify-between text-lg">
                    <span>Total Amount</span>
                    <span class="font-bold">Rs. {{ number_format($invoice->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-lg">
                    <span>Amount Paid</span>
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">Rs. {{ number_format($this->totalPaid, 2) }}</span>
                </div>
                <div class="flex justify-between text-xl font-bold pt-4 border-t border-slate-200 dark:border-slate-700">
                    <span>Balance Due</span>
                    <span class="{{ $invoice->unpaid_amount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                        Rs. {{ number_format($invoice->unpaid_amount, 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes / Footer -->
    @if($invoice->notes)
        <div class="inventory-card p-6">
            <h3 class="text-lg font-semibold mb-3">Additional Notes</h3>
            <p class="text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $invoice->notes }}</p>
        </div>
    @endif

    <div class="text-center text-slate-400 dark:text-slate-500 text-sm print:mt-8">
        Generated by StockPro • {{ now()->format('d M Y H:i') }}
    </div>

    <script>
        lucide.createIcons();
    </script>

    <style>
        @media print {
            .print\:hidden {
                display: none !important;
            }

            body {
                background: white !important;
                color: black !important;
            }

            .dark\:text-white {
                color: black !important;
            }
        }
    </style>
</div>