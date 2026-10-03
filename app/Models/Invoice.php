<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'company_id',
        'client_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'status',
        'notes',
        'subtotal',
        'tax',
        'total',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getBalanceDueAttribute()
    {
        return max(
            0,
            (float) $this->total - (float) $this->total_paid
        );
    }

    public function getIsFullyPaidAttribute(): bool
    {
        return $this->total_paid >= (float) $this->total;
    }

    public function getIsOverdueAttribute(): bool
    {
        return in_array($this->status, ['sent', 'partial'])
            && $this->due_date
            && $this->due_date->isPast()
            && $this->balance_due > 0;
    }

    public function syncPaymentStatus(): void
    {
        $totalPaid = (float) $this->payments()->sum('amount');
        $invoiceTotal = (float) $this->total;

        if ($totalPaid >= $invoiceTotal && $invoiceTotal > 0) {
            $this->update([
                'status' => 'paid',
            ]);

            return;
        }

        if ($totalPaid > 0) {
            $this->update([
                'status' => 'partial',
            ]);

            return;
        }

        $this->update([
            'status' => 'sent',
        ]);
    }
}