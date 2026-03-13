<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dealer extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'contact', 'address'];
    
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
