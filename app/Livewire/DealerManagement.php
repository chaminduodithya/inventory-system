<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Dealer;
use Illuminate\Support\Facades\Session;

class DealerManagement extends Component
{
    public $name = '';
    public $contact = '';
    public $address = '';
    public $dealers;

    public $editingId = null; // for edit mode

    protected $rules = [
        'name'    => 'required|min:2|max:100',
        'contact' => 'nullable|string|max:100',
        'address' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        $this->loadDealers();
    }

    public function loadDealers()
    {
        $this->dealers = Dealer::latest()->get();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            // Update existing dealer
            $dealer = Dealer::findOrFail($this->editingId);
            $dealer->update([
                'name'    => $this->name,
                'contact' => $this->contact,
                'address' => $this->address,
            ]);
            Session::flash('message', 'Dealer updated successfully.');
        } else {
            // Create new dealer
            Dealer::create([
                'name'    => $this->name,
                'contact' => $this->contact,
                'address' => $this->address,
            ]);
            Session::flash('message', 'Dealer added successfully.');
        }

        $this->resetForm();
        $this->loadDealers();
    }

    public function edit($id)
    {
        $dealer = Dealer::findOrFail($id);
        $this->editingId = $id;
        $this->name    = $dealer->name;
        $this->contact = $dealer->contact;
        $this->address = $dealer->address;
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

    public function resetForm()
    {
        $this->name = $this->contact = $this->address = '';
        $this->editingId = null;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.dealer-management');
    }
}