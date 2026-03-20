<?php

namespace App\Livewire;

use App\Models\Stock;
use App\Models\StockLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class StockDetail extends Component
{
    public Stock $stock;
    public $quantity_change = 0;
    public $update_type = 'addition'; // 'addition' or 'subtraction'
    public $reason = '';

    public function mount($id)
    {
        $this->stock = Stock::findOrFail($id);
    }

    public function updateStock()
    {
        $this->validate([
            'quantity_change' => 'required|numeric|min:0.01',
            'update_type' => 'required|in:addition,subtraction',
            'reason' => 'nullable|string|max:255',
        ]);

        $previous_quantity = $this->stock->quantity;
        $change = $this->update_type === 'addition' ? $this->quantity_change : -$this->quantity_change;
        $new_quantity = $previous_quantity + $change;

        if ($new_quantity < 0) {
            $this->addError('quantity_change', 'Negative stock levels are not permitted.');
            return;
        }

        // Update Stock
        $this->stock->update([
            'quantity' => $new_quantity
        ]);

        // Create Log
        StockLog::create([
            'stock_id' => $this->stock->id,
            'user_id' => Auth::id(),
            'quantity_change' => $change,
            'previous_quantity' => $previous_quantity,
            'new_quantity' => $new_quantity,
            'type' => $this->update_type,
            'reason' => $this->reason ?: ($this->update_type === 'addition' ? 'Manual replenishment' : 'Manual adjustment'),
        ]);

        $this->reset(['quantity_change', 'reason']);
        session()->flash('message', 'Registry balance updated successfully.');
        $this->dispatch('refresh-notifications');

        // Refresh stock model
        $this->stock->refresh();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.stock-detail', [
            'logs' => $this->stock->logs()->with('user')->paginate(10)
        ]);
    }
}
