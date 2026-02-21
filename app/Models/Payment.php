<?php

namespace App\Models;

use App\Models\Traits\HasBaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasBaseModel;

    const STATUS_PENDING   = 'pending';
    const STATUS_VALIDATED = 'validated';
    const STATUS_REJECTED  = 'rejected';

    const METHOD_CASH     = 'cash';
    const METHOD_TRANSFER = 'transfer';
    const METHOD_DEPOSIT  = 'deposit';
    const METHOD_CARD     = 'card';

    const METHODS = [
        self::METHOD_CASH     => 'Efectivo',
        self::METHOD_TRANSFER => 'Transferencia',
        self::METHOD_DEPOSIT  => 'Consignación',
        self::METHOD_CARD     => 'Tarjeta',
    ];

    protected $fillable = [
        'enrollment_id', 'amount', 'payment_date', 'payment_status', 'method',
        'reference', 'notes', 'culqi_charge_id', 'authorization_code',
        'card_brand', 'card_last4', 'card_type', 'card_issuer', 'outcome_type',
        'payment_order_id', 'created_by', 'decided_by', 'decided_at',
        'rejection_reason', 'status',
    ];

    protected function casts(): array
    {
        return [
            'amount'       => 'decimal:2',
            'payment_date' => 'datetime',
            'decided_at'   => 'datetime',
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function paymentOrder(): BelongsTo
    {
        return $this->belongsTo(PaymentOrder::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function decidedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    /**
     * Aplica el pago a las cuotas pendientes del plan.
     * Solo debe ejecutarse cuando el pago ya fue VALIDADO.
     */
    public function applyToInstallments(): void
    {
        $remaining = (float) $this->amount;

        $installments = $this->enrollment->installments()
            ->whereIn('installment_status', ['pending', 'partial', 'overdue'])
            ->orderBy('due_date')
            ->orderBy('installment_number')
            ->get();

        foreach ($installments as $inst) {
            if ($remaining <= 0) break;

            $needed  = (float) ($inst->amount - $inst->paid_amount);
            $toApply = min($remaining, $needed);
            $inst->paid_amount += $toApply;
            $remaining -= $toApply;
            $inst->updateStatus();
        }
    }
}
