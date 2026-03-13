<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Dealer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Stock;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceManagement extends Component
{
    public $dealer_id = '';
    public $total_amount = 0.00;
    public $issue_date = '';
    public $due_date = '';
    public $notes = '';
    public $items = [];

    public $selectedInvoiceId = null;
    public $payment_amount = 0.00;
    public $payment_date = '';
    public $payment_notes = '';
    public $invoices;
    public $availableStocks;

    public $editingId = null;

    protected $rules = [
        'dealer_id'     => 'required|exists:dealers,id',
        'total_amount'  => 'required|numeric|min:0.01',
        'issue_date'    => 'required|date',
        'due_date'      => 'nullable|date|after_or_equal:issue_date',
        'notes'         => 'nullable|string|max:500',
        'items.*.stock_id'    => 'required|exists:stocks,id',
        'items.*.quantity_sold' => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->issue_date = Carbon::today()->format('Y-m-d');
        $this->due_date   = Carbon::today()->addDays(30)->format('Y-m-d');
        $this->payment_date = Carbon::today()->format('Y-m-d');
        $this->items = [
            ['stock_id' => '', 'quantity_sold' => 1]
        ];
        $this->loadInvoices();
    }

    public function loadInvoices()
    {
        $this->invoices = Invoice::with('dealer', 'items.stock', 'payments')
            ->latest()
            ->get()
            ->sortByDesc(function ($invoice) {
                return $invoice->overdue_level * 10000 + $invoice->days_overdue;
            });

        $this->availableStocks = Stock::where('quantity', '>', 0)
            ->orderBy('name')
            ->get();
    }

    public function addItemRow()
    {
        $this->items[] = ['stock_id' => '', 'quantity_sold' => 1];
        $this->total_amount = $this->calculateTotal();
    }

    public function removeItemRow($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->total_amount = $this->calculateTotal();
    }

    public function updatedItems($value, $key)
    {
        $this->total_amount = $this->calculateTotal();

        [$index, $field] = explode('.', $key);

        if ($field === 'stock_id' && !empty($value)) {
            $stock = Stock::find($value);
            if ($stock) {
                $this->items[$index]['unit_price'] = $stock->price;
            }
        }
    }

    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->items as $item) {
            if (!empty($item['stock_id']) && !empty($item['quantity_sold'])) {
                $stock = Stock::find($item['stock_id']);
                if ($stock) {
                    $subtotal = $stock->price * $item['quantity_sold'];
                    $total += $subtotal;
                }
            }
        }
        return $total;
    }

    public function save()
    {
        $this->validate();


        foreach ($this->items as $index => $item) {
            $stock = Stock::find($item['stock_id']);
            if (!$stock || $stock->quantity < $item['quantity_sold']) {
                $this->addError("items.$index.quantity_sold", "Not enough stock for {$stock->name}");
                return;
            }
        }

        DB::transaction(function () {
            $data = [
                'dealer_id'    => $this->dealer_id,
                'issue_date'   => $this->issue_date,
                'due_date'     => $this->due_date ?: null,
                'notes'        => $this->notes,
                'total_amount' => $this->calculateTotal(),
            ];

            if ($this->editingId) {
                $invoice = Invoice::findOrFail($this->editingId);

                // Add back old quantities
                foreach ($invoice->items as $oldItem) {
                    $oldItem->stock->increment('quantity', $oldItem->quantity_sold);
                }

                $invoice->items()->delete();
                $invoice->update($data);
            } else {
                $invoice = Invoice::create($data);
            }

            foreach ($this->items as $itemData) {
                $stock = Stock::find($itemData['stock_id']);
                $subtotal = $stock->price * $itemData['quantity_sold'];

                $invoice->items()->create([
                    'stock_id'      => $itemData['stock_id'],
                    'quantity_sold' => $itemData['quantity_sold'],
                    'unit_price'    => $stock->price,
                    'subtotal'      => $subtotal,
                ]);

                // Deduct from stock
                $stock->decrement('quantity', $itemData['quantity_sold']);
            }

            Session::flash('message', $this->editingId ? 'Invoice updated successfully.' : 'Invoice created successfully.');
            $this->resetForm();
            $this->loadInvoices();
        });
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
        $invoice = Invoice::with('items')->findOrFail($id);

        $this->editingId   = $id;
        $this->dealer_id   = $invoice->dealer_id;
        $this->total_amount = $invoice->total_amount;
        $this->issue_date  = $invoice->issue_date->format('Y-m-d');
        $this->due_date    = $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '';
        $this->notes       = $invoice->notes ?? '';
        $this->items       = $invoice->items->map(function ($item) {
            return [
                'stock_id'      => $item->stock_id,
                'quantity_sold' => $item->quantity_sold,
            ];
        })->toArray();
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $invoice = Invoice::findOrFail($id);
            foreach ($invoice->items as $item) {
                // Restore stock
                $item->stock->increment('quantity', $item->quantity_sold);
            }
            $invoice->delete();
        });

        Session::flash('message', 'Invoice deleted and stock restored.');
        $this->loadInvoices();
    }

    public function resetForm()
    {
        $this->dealer_id    = '';
        $this->total_amount = 0.00;
        $this->issue_date   = Carbon::today()->format('Y-m-d');
        $this->due_date     = Carbon::today()->addDays(30)->format('Y-m-d');
        $this->notes        = '';
        $this->items        = [
            ['stock_id' => '', 'quantity_sold' => 1]
        ];
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
