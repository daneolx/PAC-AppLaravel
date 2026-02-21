<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembresiaAlumno extends Model
{
    protected $fillable = [
        'alumno_id', 'tipo_membresia_id', 'fecha_compra', 'fecha_vencimiento', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'fecha_compra'      => 'date',
            'fecha_vencimiento' => 'date',
        ];
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function tipoMembresia(): BelongsTo
    {
        return $this->belongsTo(TipoMembresia::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getVigenteAttribute(): bool
    {
        return now()->toDateString() <= $this->fecha_vencimiento->toDateString();
    }

    /**
     * Verifica si el alumno puede acceder a un producto por membresía.
     */
    public static function alumnoPuedeAccederProducto(Alumno $alumno, Producto $product): bool
    {
        if (!$product->requiere_membresia) {
            return true;
        }

        return self::where('alumno_id', $alumno->id)
            ->where('fecha_vencimiento', '>=', now()->toDateString())
            ->whereHas('tipoMembresia', function ($q) use ($product) {
                $q->where('activo', true)
                  ->whereHas('productos', fn($q2) => $q2->where('productos.id', $product->id));
            })
            ->exists();
    }
}
