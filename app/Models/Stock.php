<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'description', 'quantity', 'price', 'unit'];

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'invoice_items')
            ->withPivot('quantity_sold', 'unit_price', 'subtotal');
    }
}
