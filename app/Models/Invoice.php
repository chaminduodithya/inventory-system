<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'dealer_id',
        'total_amount',
        'issue_date',
        'due_date',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date'   => 'date',
    ];

    /**
     * An invoice belongs to one dealer
     */
    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    /**
     * An invoice can have many partial payments
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function stocks()
    {
        return $this->belongsToMany(Stock::class, 'invoice_items')
            ->withPivot('quantity_sold', 'unit_price', 'subtotal');
    }

    /**
     * Calculated attribute: total amount paid so far
     */
    public function getPaidAmountAttribute(): float
    {
        return $this->payments->sum('amount');
    }

    /**
     * Calculated attribute: remaining / unpaid amount
     */
    public function getUnpaidAmountAttribute(): float
    {
        return $this->total_amount - $this->paid_amount;
    }

    /**
     * Check if invoice is fully paid
     */
    public function getIsFullyPaidAttribute(): bool
    {
        return $this->unpaid_amount <= 0;
    }

    /**
     * Check if invoice is overdue (after due date)
     */
    public function isOverdue(int $days = 0): bool
    {
        if (!$this->due_date) {
            return false;
        }

        return now()->startOfDay()->gt($this->due_date->addDays($days));
    }

    /**
     * Days overdue (0 if not overdue)
     */
    public function getDaysOverdueAttribute(): int
    {
        if (!$this->due_date || $this->isFullyPaid) {
            return 0;
        }

        return max(0, now()->startOfDay()->diffInDays($this->due_date->endOfDay(), false));
    }

    /**
     * Overdue level for styling
     * 0 = not overdue, 1 = mild, 2 = medium, 3 = severe
     */
    public function getOverdueLevelAttribute(): int
    {
        if ($this->days_overdue === 0) {
            return 0;
        }

        if ($this->days_overdue > 90) {
            return 3; // severe
        }

        if ($this->days_overdue > 60) {
            return 2; // medium
        }

        return 1; // mild (past due but ≤60)
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->isOverdue();
    }

    public function getStatusAttribute()
    {
        if ($this->isFullyPaid) return 'paid';
        if ($this->paid_amount > 0) return 'partial';
        return 'unpaid';
    }
}
