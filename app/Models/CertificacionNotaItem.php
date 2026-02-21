<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificacionNotaItem extends Model
{
    protected $fillable = [
        'certificacion_id', 'item_name', 'moodle_item_id', 'orden',
        'nota_moodle', 'nota_override', 'modificado_por_admin',
    ];

    protected function casts(): array
    {
        return [
            'nota_moodle'         => 'decimal:2',
            'nota_override'       => 'decimal:2',
            'modificado_por_admin' => 'boolean',
        ];
    }

    public function certificacion(): BelongsTo
    {
        return $this->belongsTo(Certificacion::class);
    }

    public function getNotaEfectivaAttribute(): ?float
    {
        if ($this->modificado_por_admin && $this->nota_override !== null) {
            return (float) $this->nota_override;
        }
        return $this->nota_moodle !== null ? (float) $this->nota_moodle : null;
    }
}
