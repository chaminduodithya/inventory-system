<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Dealer;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class InvoiceManagement extends Component
{
    public $dealer_id = '';
    public $total_amount = 0.00;
    public $issue_date = '';
    public $due_date = '';
    public $notes = '';

    public $selectedInvoiceId = null;
    public $payment_amount = 0.00;
    public $payment_date = '';
    public $payment_notes = '';

    public $invoices;

    public $editingId = null;

    protected $rules = [
        'dealer_id'     => 'required|exists:dealers,id',
        'total_amount'  => 'required|numeric|min:0.01',
        'issue_date'    => 'required|date',
        'due_date'      => 'nullable|date|after_or_equal:issue_date',
        'notes'         => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->issue_date = Carbon::today()->format('Y-m-d');
        $this->due_date   = Carbon::today()->addDays(30)->format('Y-m-d');
        $this->payment_date = Carbon::today()->format('Y-m-d');
        $this->loadInvoices();
    }

    public function loadInvoices()
    {
        $this->invoices = Invoice::with('dealer', 'payments')
            ->get()
            ->sortByDesc(function ($invoice) {
                return $invoice->overdue_level * 10000 + $invoice->days_overdue;
            });
    }

    public function save()
    {
        $this->validate();

        $data = [
            'dealer_id'    => $this->dealer_id,
            'total_amount' => $this->total_amount,
            'issue_date'   => $this->issue_date,
            'due_date'     => $this->due_date ?: null,
            'notes'        => $this->notes,
        ];

        if ($this->editingId) {
            $invoice = Invoice::findOrFail($this->editingId);
            $invoice->update($data);
            Session::flash('message', 'Invoice updated successfully.');
        } else {
            Invoice::create($data);
            Session::flash('message', 'Invoice created successfully.');
        }

        $this->resetForm();
        $this->loadInvoices();
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

        // Optional: prevent over-payment
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

        $this->selectedInvoiceId = null; // close form
        $this->loadInvoices(); // refresh list
    }

    // Close payment form
    public function cancelPayment()
    {
        $this->selectedInvoiceId = null;
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);

        $this->editingId   = $id;
        $this->dealer_id   = $invoice->dealer_id;
        $this->total_amount = $invoice->total_amount;
        $this->issue_date  = $invoice->issue_date->format('Y-m-d');
        $this->due_date    = $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '';
        $this->notes       = $invoice->notes ?? '';
    }

    public function delete($id)
    {
        Invoice::findOrFail($id)->delete();
        Session::flash('message', 'Invoice deleted.');
        $this->loadInvoices();
    }

    public function resetForm()
    {
        $this->dealer_id    = '';
        $this->total_amount = 0.00;
        $this->issue_date   = Carbon::today()->format('Y-m-d');
        $this->due_date     = Carbon::today()->addDays(30)->format('Y-m-d');
        $this->notes        = '';
        $this->editingId    = null;
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
        return view('livewire.invoice-management');
    }
}
