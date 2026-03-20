<?php

namespace App\Livewire;

use App\Models\Dealer;
use App\Models\Invoice;
use App\Models\Stock;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use App\Exports\DealerSummaryExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SummaryReport extends Component
{
    public $activeTab = 'dealers'; // 'dealers', 'invoices', 'inventory'

    public $dealerSummaries = [];
    public $inventorySummaries = [];
    public $invoiceMetrics = [];

    public function mount()
    {
        $this->loadData();
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->loadData();
    }

    public function loadData()
    {
        if ($this->activeTab === 'dealers') {
            $this->loadDealerSummary();
        } elseif ($this->activeTab === 'inventory') {
            $this->loadInventorySummary();
        } elseif ($this->activeTab === 'invoices') {
            $this->loadInvoiceSummary();
        }
    }

    private function loadDealerSummary()
    {
        $this->dealerSummaries = Dealer::withCount('invoices')
            ->leftJoin('invoices', 'dealers.id', '=', 'invoices.dealer_id')
            ->leftJoin('payments', 'invoices.id', '=', 'payments.invoice_id')
            ->select(
                'dealers.id',
                'dealers.name',
                DB::raw('COUNT(DISTINCT invoices.id) as invoice_count'),
                DB::raw('COALESCE(SUM(DISTINCT invoices.total_amount), 0) as total_invoiced'),
                DB::raw('COALESCE(SUM(payments.amount), 0) as total_paid'),
                DB::raw('COALESCE(SUM(DISTINCT invoices.total_amount) - SUM(payments.amount), 0) as total_outstanding')
            )
            ->groupBy('dealers.id', 'dealers.name')
            ->orderByDesc('total_outstanding')
            ->get();

        foreach ($this->dealerSummaries as $summary) {
            $invoices = Invoice::where('dealer_id', $summary->id)->get();
            $summary->total_overdue = $invoices->filter(fn($i) => $i->days_overdue > 0 && $i->unpaid_amount > 0)->count();
            $summary->exposure_level = $summary->total_outstanding > 100000 ? 'CRITICAL' : ($summary->total_outstanding > 50000 ? 'ELEVATED' : 'STABLE');
        }
    }

    private function loadInventorySummary()
    {
        $this->inventorySummaries = Stock::select(
            '*',
            DB::raw('quantity * price as valuation')
        )
            ->orderByDesc('valuation')
            ->get();

        foreach ($this->inventorySummaries as $stock) {
            $stock->turnover_rate = $stock->invoices()->sum('quantity_sold');
            $stock->health_status = $stock->quantity <= 5 ? 'LOW_STOCK' : 'OPTIMAL';
        }
    }

    private function loadInvoiceSummary()
    {
        $this->invoiceMetrics = [
            'total_volume' => Invoice::sum('total_amount'),
            'total_realized' => DB::table('payments')->sum('amount'),
            'avg_deal_size' => Invoice::avg('total_amount') ?: 0,
            'overdue_ratio' => Invoice::count() > 0 ? (Invoice::where('due_date', '<', now())->count() / Invoice::count()) * 100 : 0,
            'recent_activity' => Invoice::with('dealer')->latest()->take(10)->get()
        ];
    }

    public function exportExcel()
    {
        // For simplicity, we keep the existing dealer export for now
        return Excel::download(new DealerSummaryExport, 'enterprise-summary-' . $this->activeTab . '-' . now()->format('Y-m-d') . '.xlsx');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.summary-report');
    }
}
