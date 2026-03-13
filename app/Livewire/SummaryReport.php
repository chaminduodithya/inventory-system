<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Dealer;
use App\Models\Invoice;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use App\Exports\DealerSummaryExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SummaryReport extends Component
{
    public $summaries = [];

    public function mount()
    {
        $this->loadSummary();
    }

    public function loadSummary()
    {
        $this->summaries = Dealer::withCount('invoices')
            ->leftJoin('invoices', 'dealers.id', '=', 'invoices.dealer_id')
            ->leftJoin('payments', 'invoices.id', '=', 'payments.invoice_id')
            ->select(
                'dealers.id',
                'dealers.name',
                DB::raw('COUNT(invoices.id) as invoice_count'),
                DB::raw('COALESCE(SUM(invoices.total_amount), 0) as total_invoiced'),
                DB::raw('COALESCE(SUM(payments.amount), 0) as total_paid'),
                DB::raw('COALESCE(SUM(invoices.total_amount) - SUM(payments.amount), 0) as total_outstanding')
            )
            ->groupBy('dealers.id', 'dealers.name')
            ->havingRaw('total_invoiced > 0') // optional: only show dealers with invoices
            ->orderByDesc('total_outstanding')
            ->get();

        // Add overdue counts per dealer
        foreach ($this->summaries as $summary) {
            $invoices = Invoice::where('dealer_id', $summary->id)->with('payments')->get();

            $summary->overdue_60 = $invoices->filter(function ($inv) {
                return $inv->days_overdue > 60 && $inv->unpaid_amount > 0;
            })->count();

            $summary->overdue_90 = $invoices->filter(function ($inv) {
                return $inv->days_overdue > 90 && $inv->unpaid_amount > 0;
            })->count();

            $summary->total_overdue = $invoices->filter(function ($inv) {
                return $inv->days_overdue > 0 && $inv->unpaid_amount > 0;
            })->count();
        }
    }

    public function exportExcel()
    {
        return Excel::download(new DealerSummaryExport, 'dealer-summary-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportPdf()
    {
        $summaries = $this->summaries; // or reload if needed

        $pdf = Pdf::loadView('pdf.dealer-summary', compact('summaries'))
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'dealer-summary-' . now()->format('Y-m-d') . '.pdf'
        );
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.summary-report');
    }
}
