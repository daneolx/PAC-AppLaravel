<?php

namespace App\Models;

use App\Models\Traits\HasBaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasBaseModel;

    const MODALITY_PRESENCIAL = 'presencial';
    const MODALITY_VIRTUAL    = 'virtual';
    const MODALITY_HIBRIDO    = 'hibrido';

    const MODALITIES = [
        self::MODALITY_PRESENCIAL => 'Presencial',
        self::MODALITY_VIRTUAL    => 'Virtual',
        self::MODALITY_HIBRIDO    => 'Híbrido',
    ];

    protected $fillable = [
        'name', 'code', 'description', 'modality', 'duration',
        'base_cost', 'max_students', 'status', 'created_by',
    ];

    protected function casts(): array
    {
        return ['base_cost' => 'decimal:2'];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}
