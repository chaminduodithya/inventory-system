<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Stock;

class StockAdd extends Component
{
    public $name = '', $description = '', $quantity = 0, $price = 0.00, $unit = '';
    public $editingId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->edit($id);
        }
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
    }

    public function edit($id)
    {
        $stock = Stock::find($id);
        if ($stock) {
            $this->editingId = $id;
            $this->name = $stock->name;
            $this->description = $stock->description;
            $this->quantity = $stock->quantity;
            $this->price = $stock->price;
            $this->unit = $stock->unit;
        }
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
        return view('livewire.stock-add');
    }
}
