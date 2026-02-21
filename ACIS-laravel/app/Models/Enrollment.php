<?php

namespace App\Models;

use App\Models\Traits\HasBaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Enrollment extends Model
{
    use HasBaseModel;

    const STATUS_PRE_REGISTERED = 'pre_registered';
    const STATUS_REGISTERED     = 'registered';
    const STATUS_CANCELED       = 'canceled';
    const STATUS_FINISHED       = 'finished';

    const ENROLLMENT_STATUSES = [
        self::STATUS_PRE_REGISTERED => 'Pre-matriculado',
        self::STATUS_REGISTERED     => 'Matriculado',
        self::STATUS_CANCELED       => 'Anulado',
        self::STATUS_FINISHED       => 'Finalizado',
    ];

    protected $fillable = [
        'alumno_id', 'program_id', 'product_id', 'enrollment_code',
        'total_cost', 'enrollment_status', 'cancellation_reason',
        'canceled_at', 'canceled_by', 'verification_uuid',
        'created_by', 'asesor_comercial', 'habilitado_en_moodle',
        'moodle_user_id', 'via_membresia', 'status',
    ];

    protected function casts(): array
    {
        return [
            'enrollment_date'      => 'datetime',
            'canceled_at'          => 'datetime',
            'total_cost'           => 'decimal:2',
            'habilitado_en_moodle' => 'boolean',
            'via_membresia'        => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Enrollment $enrollment) {
            if (empty($enrollment->verification_uuid)) {
                $enrollment->verification_uuid = (string) Str::uuid();
            }
            if (empty($enrollment->enrollment_code)) {
                $enrollment->enrollment_code = self::generateEnrollmentCode();
            }
        });
    }

    public static function generateEnrollmentCode(): string
    {
        $prefix = 'MAT';
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now()->toDateString())->count() + 1;
        return "{$prefix}-{$date}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /* ── Relaciones ─────────────────────────────────────── */

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'product_id');
    }

    public function canceledByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'canceled_by');
    }

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asesor_comercial');
    }

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    public function recordedPayments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function paymentOrders(): HasMany
    {
        return $this->hasMany(PaymentOrder::class);
    }

    public function certificacion(): HasOne
    {
        return $this->hasOne(Certificacion::class);
    }
}
