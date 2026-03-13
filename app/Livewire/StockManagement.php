<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Stock;
use Illuminate\Support\Facades\Validator;

class StockManagement extends Component
{
    public $name = '', $description = '', $quantity = 0, $price = 0.00, $unit = '';
    public $editingId = null;  // For edit mode
    public $stocks;  // To hold list

    protected $rules = [
        'name' => 'required|string|max:255',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        // Add others as needed
    ];

    public function mount()
    {
        $this->loadStocks();
    }

    public function loadStocks()
    {
        $this->stocks = Stock::all();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $stock = Stock::findOrFail($this->editingId);
            $stock->update([
                'name' => $this->name,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'price' => $this->price,
                'unit' => $this->unit,
            ]);
            session()->flash('message', 'Stock updated!');
        } else {
            Stock::create([
                'name' => $this->name,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'price' => $this->price,
                'unit' => $this->unit,
            ]);
            session()->flash('message', 'Stock added!');
        }

        $this->resetForm();
        $this->loadStocks();  // Refresh list
    }

    public function edit($id)
    {
        $stock = Stock::find($id);
        $this->editingId = $id;
        $this->name = $stock->name;
        $this->description = $stock->description;
        $this->quantity = $stock->quantity;
        $this->price = $stock->price;
        $this->unit = $stock->unit;
    }

    public function delete($id)
    {
        $stock = Stock::findOrFail($id);
        
        if ($stock->invoices()->whereNull('invoices.deleted_at')->exists()) {
        session()->flash('error', 'Cannot delete this stock — it is used in one or more active invoices.');
        return;
        }

        $stock->delete();

        session()->flash('message', 'Stock deleted!');
        $this->loadStocks();
    }

    public function resetForm()
    {
        $this->name = $this->description = $this->unit = '';
        $this->quantity = 0;
        $this->price = 0.00;
        $this->editingId = null;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.stock-management');
    }
}