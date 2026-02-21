<?php

namespace App\Models;

use App\Models\Traits\HasBaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Installment extends Model
{
    use HasBaseModel;

    const STATUS_PENDING = 'pending';
    const STATUS_PARTIAL = 'partial';
    const STATUS_PAID    = 'paid';
    const STATUS_OVERDUE = 'overdue';

    protected $fillable = [
        'enrollment_id', 'installment_number', 'amount', 'paid_amount',
        'due_date', 'installment_status', 'status', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount'      => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_date'    => 'date',
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function getBalanceAttribute(): float
    {
        return (float) ($this->amount - $this->paid_amount);
    }

    public function updateStatus(): void
    {
        if ($this->paid_amount >= $this->amount) {
            $this->installment_status = self::STATUS_PAID;
        } elseif ($this->paid_amount > 0) {
            $this->installment_status = self::STATUS_PARTIAL;
        } elseif ($this->due_date->isPast()) {
            $this->installment_status = self::STATUS_OVERDUE;
        } else {
            $this->installment_status = self::STATUS_PENDING;
        }
        $this->save();
    }
}
