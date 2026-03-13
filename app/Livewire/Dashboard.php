<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Stock;
use App\Models\Invoice;
use App\Models\Dealer;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $totalStockValue = 0;
    public $lowStockItems;
    public $totalOutstanding = 0;
    public $overdueDealers;
    public $recentInvoices;

    public $lowStockThreshold = 10; // You can make this configurable later

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        // 1. Total stock value
        $this->totalStockValue = Stock::sum(DB::raw('quantity * price'));

        // 2. Low stock items
        $this->lowStockItems = Stock::where('quantity', '<=', $this->lowStockThreshold)
            ->orderBy('quantity', 'asc')
            ->limit(5)
            ->get();

        // 3. Total outstanding (unpaid across all invoices)
        $this->totalOutstanding = Invoice::get()->sum('unpaid_amount');

        // 4. Top 5 overdue dealers (highest outstanding + overdue)
        $this->overdueDealers = Dealer::whereHas('invoices', function ($q) {
                $q->where('due_date', '<', now())
                  ->whereRaw('(SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id) < invoices.total_amount');
            })
            ->withSum('invoices as outstanding', DB::raw('invoices.total_amount - (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.invoice_id = invoices.id)'))
            ->orderByDesc('outstanding')
            ->limit(5)
            ->get();

        // 5. Recent 5 invoices
        $this->recentInvoices = Invoice::with('dealer')
            ->latest()
            ->limit(5)
            ->get();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.dashboard');
    }
}