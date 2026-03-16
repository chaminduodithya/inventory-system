<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Dealer;
use Illuminate\Support\Facades\Session;

class DealerAdd extends Component
{
    public $name = '';
    public $contact = '';
    public $address = '';
    public $editingId = null;

    protected $rules = [
        'name'    => 'required|min:2|max:100',
        'contact' => 'nullable|string|max:100',
        'address' => 'nullable|string|max:255',
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
            $dealer = Dealer::findOrFail($this->editingId);
            $dealer->update([
                'name'    => $this->name,
                'contact' => $this->contact,
                'address' => $this->address,
            ]);
            Session::flash('message', 'Dealer updated successfully.');
        } else {
            Dealer::create([
                'name'    => $this->name,
                'contact' => $this->contact,
                'address' => $this->address,
            ]);
            Session::flash('message', 'Dealer added successfully.');
        }

        $this->resetForm();
    }

    public function edit($id)
    {
        $dealer = Dealer::findOrFail($id);
        $this->editingId = $id;
        $this->name    = $dealer->name;
        $this->contact = $dealer->contact;
        $this->address = $dealer->address;
    }

    public function resetForm()
    {
        $this->name = $this->contact = $this->address = '';
        $this->editingId = null;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.dealer-add');
    }
}
