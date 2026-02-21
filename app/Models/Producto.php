<?php

namespace App\Models;

use App\Models\Traits\HasBaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasBaseModel;

    const TIPO_CURSO     = 'curso';
    const TIPO_DIPLOMADO = 'diplomado';

    const TIPOS = [
        self::TIPO_CURSO     => 'Curso',
        self::TIPO_DIPLOMADO => 'Diplomado',
    ];

    protected $fillable = [
        'tema_id', 'categoria_id', 'tipo_producto', 'descripcion', 'sku',
        'fecha_inicio', 'fecha_fin', 'modulo', 'horas', 'creditos',
        'precio', 'meses', 'matricula', 'mensualidad', 'derecho',
        'imagen', 'moodle_course_id', 'requiere_membresia',
        'status', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio'       => 'date',
            'fecha_fin'          => 'date',
            'precio'             => 'decimal:2',
            'matricula'          => 'decimal:2',
            'mensualidad'        => 'decimal:2',
            'derecho'            => 'decimal:2',
            'requiere_membresia' => 'boolean',
        ];
    }

    /* ── Relaciones ─────────────────────────────────────── */

    public function tema(): BelongsTo
    {
        return $this->belongsTo(Tema::class);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function etiquetas(): BelongsToMany
    {
        return $this->belongsToMany(Etiqueta::class, 'producto_etiqueta');
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teacher_producto');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'product_id');
    }

    /* ── Lógica de negocio ──────────────────────────────── */

    public function isDiplomado(): bool
    {
        return $this->tipo_producto === self::TIPO_DIPLOMADO;
    }

    public function getTotalDiplomado(): float
    {
        if (!$this->isDiplomado()) {
            return (float) $this->precio;
        }
        return (float) ($this->matricula + ($this->meses * $this->mensualidad) + $this->derecho);
    }
}
