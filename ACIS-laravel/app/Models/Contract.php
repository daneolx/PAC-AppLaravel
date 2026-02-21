<?php

namespace App\Models;

use App\Models\Traits\HasBaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Contract extends Model
{
    use HasBaseModel;

    const STATUS_GENERATED = 'generated';
    const STATUS_SIGNED    = 'signed';
    const STATUS_CANCELED  = 'canceled';

    const CONTRACT_STATUSES = [
        self::STATUS_GENERATED => 'Generado',
        self::STATUS_SIGNED    => 'Firmado',
        self::STATUS_CANCELED  => 'Anulado',
    ];

    protected $fillable = [
        'enrollment_id', 'contract_number', 'pdf_file', 'contract_status',
        'status_changed_by', 'verification_uuid', 'status', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'generated_at'       => 'datetime',
            'last_status_change' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Contract $contract) {
            if (empty($contract->verification_uuid)) {
                $contract->verification_uuid = (string) Str::uuid();
            }
        });
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function statusChangedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'status_changed_by');
    }

    public function canSign(): bool
    {
        return $this->contract_status !== self::STATUS_CANCELED;
    }

    public function markSigned(User $user): bool
    {
        if ($this->canSign()) {
            $this->update([
                'contract_status'  => self::STATUS_SIGNED,
                'status_changed_by' => $user->id,
            ]);
            return true;
        }
        return false;
    }

    public function markCanceled(User $user): void
    {
        $this->update([
            'contract_status'  => self::STATUS_CANCELED,
            'status_changed_by' => $user->id,
        ]);
    }
}
