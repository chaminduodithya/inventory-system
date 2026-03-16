<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Dealer;
use Illuminate\Support\Facades\Session;

class DealerList extends Component
{
    public $dealers;

    public function mount()
    {
        $this->loadDealers();
    }

    public function loadDealers()
    {
        $this->dealers = Dealer::latest()->get();
    }

    public function delete($id)
    {
        $dealer = Dealer::findOrFail($id);

        if ($dealer->invoices()->whereNull('invoices.deleted_at')->exists()) {
            session()->flash('error', 'Cannot delete this dealer — it is used in one or more active invoices.');
            return;
        }

        $dealer->delete();
        Session::flash('message', 'Dealer deleted successfully.');
        $this->loadDealers();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.dealer-list');
    }
}
