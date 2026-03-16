<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Stock;

class StockList extends Component
{
    public $stocks;

    public function mount()
    {
        $this->loadStocks();
    }

    public function loadStocks()
    {
        $this->stocks = Stock::all();
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

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.stock-list');
    }
}
