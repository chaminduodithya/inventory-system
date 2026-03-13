<?php

namespace App\Exports;

use App\Models\Dealer;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DealerSummaryExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Dealer::withCount('invoices')
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
            ->havingRaw('total_invoiced > 0')
            ->orderByDesc('total_outstanding')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Dealer Name',
            'Number of Invoices',
            'Total Invoiced (LKR)',
            'Total Paid (LKR)',
            'Outstanding (LKR)',
        ];
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->invoice_count,
            number_format($row->total_invoiced, 2),
            number_format($row->total_paid, 2),
            number_format($row->total_outstanding, 2),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (headings)
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF4F46E5']],
                'font-family' => ['color' => ['argb' => 'FFFFFFFF']],
            ],
            // Auto-size columns
            'A:E' => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}