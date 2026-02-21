<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumno extends Model
{
    const TIPO_DNI       = 'DNI';
    const TIPO_CE        = 'CE';
    const TIPO_PASAPORTE = 'PASAPORTE';

    const TIPOS_DOCUMENTO = [
        self::TIPO_DNI       => 'DNI',
        self::TIPO_CE        => 'Cédula de Extranjería',
        self::TIPO_PASAPORTE => 'Pasaporte',
    ];

    protected $fillable = [
        'tipo_documento', 'numero_documento', 'nombre', 'apellido_paterno',
        'apellido_materno', 'fecha_nacimiento', 'email', 'telefono',
        'discapacidad', 'grado_academico', 'profesion_id', 'colegiatura',
        'estado', 'user_id', 'created_by',
    ];

    protected function casts(): array
    {
        return ['fecha_nacimiento' => 'date'];
    }

    /* ── Relaciones ─────────────────────────────────────── */

    public function profesion(): BelongsTo
    {
        return $this->belongsTo(Profesion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function direcciones(): HasMany
    {
        return $this->hasMany(Direccion::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function membresias(): HasMany
    {
        return $this->hasMany(MembresiaAlumno::class);
    }

    /* ── Accessors ──────────────────────────────────────── */

    public function getFullNameAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}");
    }
}
