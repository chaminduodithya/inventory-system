<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Dealer;
use App\Models\Invoice;
use App\Models\Stock;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\StockLog;
use Illuminate\Support\Facades\Auth;

class InvoiceAdd extends Component
{
    public $dealer_id = '';
    public $total_amount = 0.00;
    public $issue_date = '';
    public $due_date = '';
    public $notes = '';
    public $items = [];

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

    public function mount($id = null)
    {
        $this->issue_date = Carbon::today()->format('Y-m-d');
        $this->due_date   = Carbon::today()->addDays(30)->format('Y-m-d');
        $this->items = [
            ['stock_id' => '', 'quantity_sold' => 1]
        ];
        $this->loadStocks();

        if ($id) {
            $this->edit($id);
        }
    }

    public function loadStocks()
    {
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

                // Create Log
                StockLog::create([
                    'stock_id' => $itemData['stock_id'],
                    'user_id' => Auth::id(),
                    'quantity_change' => -$itemData['quantity_sold'],
                    'previous_quantity' => $stock->quantity + $itemData['quantity_sold'], // Since we decremented
                    'new_quantity' => $stock->quantity,
                    'type' => 'invoice_sale',
                    'reason' => "Invoice #SKP-ORD-" . str_pad($invoice->id, 5, '0', STR_PAD_LEFT),
                ]);

                // Deduct from stock
                $stock->decrement('quantity', $itemData['quantity_sold']);
            }

            Session::flash('message', $this->editingId ? 'Invoice updated successfully.' : 'Invoice created successfully.');
            $this->resetForm();
            $this->loadStocks();
            $this->dispatch('refresh-notifications');
        });
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

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.invoice-add');
    }
}
