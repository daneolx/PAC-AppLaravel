<?php

namespace App\Models;

use App\Models\Traits\HasBaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PaymentOrder extends Model
{
    use HasBaseModel;

    const STATUS_PENDING  = 'pending_payment';
    const STATUS_PAID     = 'paid';
    const STATUS_FAILED   = 'failed';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_CANCELLED = 'cancelled';

    const PROVIDER_CULQI    = 'culqi';
    const PROVIDER_CASH     = 'cash';
    const PROVIDER_TRANSFER = 'transfer';

    protected $fillable = [
        'order_id', 'user_id', 'product_id', 'amount', 'currency', 'status', 'provider',
        'culqi_charge_id', 'culqi_token_id', 'authorization_code',
        'card_brand', 'card_last4', 'card_type', 'card_issuer',
        'outcome_type', 'decline_code', 'decline_message',
        'paid_at', 'failed_at', 'metadata', 'email', 'enrollment_id', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount'   => 'decimal:2',
            'paid_at'  => 'datetime',
            'failed_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PaymentOrder $order) {
            if (empty($order->order_id)) {
                $order->order_id = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'product_id');
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
