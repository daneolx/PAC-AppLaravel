<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoMembresia extends Model
{
    protected $fillable = ['nombre', 'duracion_dias', 'precio', 'activo'];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'tipo_membresia_producto');
    }

    public function membresiasAlumno(): HasMany
    {
        return $this->hasMany(MembresiaAlumno::class);
    }

    public function getDuracionMesesAproxAttribute(): float
    {
        return $this->duracion_dias ? round($this->duracion_dias / 30, 1) : 0;
    }
}
