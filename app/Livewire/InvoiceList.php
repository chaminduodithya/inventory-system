<?php

namespace App\Livewire;

use App\Models\Dealer;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class InvoiceList extends Component
{

    use WithPagination;
    public $selectedInvoiceId = null;
    public $payment_amount = 0.00;
    public $payment_date = '';
    public $payment_notes = '';

    // Search & Filters
    public $search = '';
    public $status = 'all'; // all, unpaid, partial, paid, overdue
    public $dateFrom = '';
    public $dateTo = '';
    public $dealerId = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'dealerId' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function updatingDealerId()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->status = 'all';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->dealerId = '';
        $this->resetPage();
    }

    public function mount()
    {
        $this->payment_date = Carbon::today()->format('Y-m-d');
    }

    public function savePayment()
    {
        $this->validate([
            'selectedInvoiceId' => 'required|exists:invoices,id',
            'payment_amount'    => 'required|numeric|min:0.01',
            'payment_date'      => 'required|date',
            'payment_notes'     => 'nullable|string|max:300',
        ]);

        $invoice = Invoice::findOrFail($this->selectedInvoiceId);

        $remaining = $invoice->unpaid_amount;
        if ($this->payment_amount > $remaining) {
            $this->addError('payment_amount', "Cannot pay more than remaining (Rs. " . number_format($remaining, 2) . ")");
            return;
        }

        Payment::create([
            'invoice_id'   => $this->selectedInvoiceId,
            'amount'       => $this->payment_amount,
            'payment_date' => $this->payment_date,
            'notes'        => $this->payment_notes,
        ]);

        Session::flash('message', 'Payment of Rs. ' . number_format($this->payment_amount, 2) . ' recorded successfully.');

        $this->selectedInvoiceId = null;
        $this->dispatch('refresh-icons');
    }

    public function cancelPayment()
    {
        $this->selectedInvoiceId = null;
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $invoice = Invoice::findOrFail($id);
            foreach ($invoice->items as $item) {
                $item->stock->increment('quantity', $item->quantity_sold);
            }
            $invoice->delete();
        });

        Session::flash('message', 'Invoice deleted and stock restored.');
        $this->dispatch('refresh-icons');
    }

    public function getUnpaidAmount($invoice)
    {
        return $invoice->total_amount - $invoice->payments->sum('amount');
    }

    public function addPayment($invoiceId)
    {
        $this->selectedInvoiceId = $invoiceId;
        $this->payment_amount = 0.00;
        $this->payment_date = Carbon::today()->format('Y-m-d');
        $this->payment_notes = '';
    }

    #[Layout('layouts.app')]
    public function render()
    {

        $query = Invoice::with(['dealer', 'payments'])
            ->latest();

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('dealer', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                    ->orWhere('notes', 'like', '%' . $this->search . '%')
                    ->orWhere('total_amount', 'like', '%' . $this->search . '%');
            });
        }

        // Dealer filter
        if ($this->dealerId) {
            $query->where('dealer_id', $this->dealerId);
        }

        // Date range (issued date)
        if ($this->dateFrom) {
            $query->whereDate('issue_date', '>=', Carbon::parse($this->dateFrom));
        }
        if ($this->dateTo) {
            $query->whereDate('issue_date', '<=', Carbon::parse($this->dateTo));
        }

        // Status filter
        if ($this->status !== 'all') {
            $query->where(function ($q) {
                $q->whereHas('payments', function ($q) {
                    $q->select(DB::raw('SUM(amount) as paid'))
                        ->havingRaw('paid < invoices.total_amount');
                }, '=', 0);

                if ($this->status === 'unpaid') {
                    $q->whereDoesntHave('payments');
                } elseif ($this->status === 'partial') {
                    $q->whereHas('payments')
                        ->whereRaw('(SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id) < invoices.total_amount')
                        ->whereRaw('(SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id) > 0');
                } elseif ($this->status === 'paid') {
                    $q->whereRaw('(SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id) >= invoices.total_amount');
                } elseif ($this->status === 'overdue') {
                    $q->where('due_date', '<', now())
                        ->whereRaw('(SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id) < invoices.total_amount');
                }
            });
        }

        $invoices = $query->paginate(15);

        return view('livewire.invoice-list', [
            'invoices' => $invoices,
            'dealers' => Dealer::orderBy('name')->get(),
        ]);
    }
}
