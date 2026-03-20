<?php

namespace App\Livewire;

use App\Models\Stock;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationCenter extends Component
{
    public $lowStocks = [];
    public $unreadCount = 0;

    public function mount()
    {
        $this->refreshNotifications();
    }

    #[On('refresh-notifications')]
    public function refreshNotifications()
    {
        $this->lowStocks = Stock::whereRaw('quantity <= min_stock_level')
            ->orderBy('quantity', 'asc')
            ->take(5)
            ->get();

        $this->unreadCount = Stock::whereRaw('quantity <= min_stock_level')->count();
    }

    public function render()
    {
        return view('livewire.notification-center');
    }
}
