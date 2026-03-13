<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class InvoiceDetail extends Component
{
    public Invoice $invoice;

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice->load(['dealer', 'items.stock', 'payments']);

        // Optional: authorize access if multi-user later
        // $this->authorize('view', $this->invoice);
    }

    #[Computed]
    public function totalPaid()
    {
        return $this->invoice->payments->sum('amount');
    }

    #[Computed]
    public function totalItems()
    {
        return $this->invoice->items->sum('quantity_sold');
    }
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.invoice-detail');
    }
}