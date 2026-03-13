<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'stock_id',
        'quantity_sold',
        'unit_price',
        'subtotal',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}