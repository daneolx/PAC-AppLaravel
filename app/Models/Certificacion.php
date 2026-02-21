<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Certificacion extends Model
{
    protected $table = 'certificaciones';

    const ESTADO_PENDIENTE            = 'pendiente';
    const ESTADO_LISTO_PARA_CERTIFICAR = 'listo_para_certificar';
    const ESTADO_VALIDADO             = 'validado';
    const ESTADO_CERTIFICADO_EMITIDO  = 'certificado_emitido';

    const ESTADOS = [
        self::ESTADO_PENDIENTE            => 'Pendiente de revisión',
        self::ESTADO_LISTO_PARA_CERTIFICAR => 'Listo para certificar',
        self::ESTADO_VALIDADO             => 'Validado',
        self::ESTADO_CERTIFICADO_EMITIDO  => 'Certificado emitido',
    ];

    const ESTADO_ENVIO_DIPLOMADO = [
        'impreso'               => 'Impreso',
        'enviado_a_universidad' => 'Enviado a universidad',
        'escaneado_con_firma'   => 'Escaneado con firma',
        'enviado_por_courier'   => 'Enviado por courier',
        'enviado'               => 'Enviado',
        'conforme_por_usuario'  => 'Conforme por usuario',
    ];

    protected $fillable = [
        'enrollment_id', 'estado', 'nota_curso',
        'notas_validadas_at', 'notas_validadas_por',
        'pagos_validados_at', 'pagos_validados_por',
        'validado_at', 'validado_por',
        'certificado_emitido_at', 'certificado_codigo',
        'estado_envio_diplomado', 'codigo_tracking',
        'diploma_firmado', 'diploma_firmado_cargado_at',
    ];

    protected function casts(): array
    {
        return [
            'nota_curso'               => 'decimal:2',
            'notas_validadas_at'       => 'datetime',
            'pagos_validados_at'       => 'datetime',
            'validado_at'              => 'datetime',
            'certificado_emitido_at'   => 'datetime',
            'diploma_firmado_cargado_at' => 'datetime',
        ];
    }

    /* ── Relaciones ─────────────────────────────────────── */

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function notasValidadasPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'notas_validadas_por');
    }

    public function pagosValidadosPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pagos_validados_por');
    }

    public function validadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validado_por');
    }

    public function notasItems(): HasMany
    {
        return $this->hasMany(CertificacionNotaItem::class);
    }

    /* ── Lógica de negocio ──────────────────────────────── */

    public function getEsDiplomadoAttribute(): bool
    {
        return $this->enrollment?->product?->isDiplomado() ?? false;
    }

    public function getNotaFinalDiplomadoAttribute(): ?float
    {
        $items = $this->notasItems()->orderBy('orden')->get();
        if ($items->isEmpty()) return null;

        $valores = $items->map(function ($item) {
            return ($item->modificado_por_admin && $item->nota_override !== null)
                ? (float) $item->nota_override
                : ($item->nota_moodle !== null ? (float) $item->nota_moodle : null);
        })->filter()->values();

        return $valores->isEmpty() ? null : round($valores->avg(), 2);
    }
}
