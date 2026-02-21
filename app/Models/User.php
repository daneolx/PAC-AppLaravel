<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /* ── Roles ─────────────────────────────────────────── */
    const ROLE_ASESOR_COMERCIAL  = 'asesor_comercial';
    const ROLE_ADMISION          = 'admision';
    const ROLE_TESORERIA         = 'tesoreria_cobranza';
    const ROLE_CERTIFICACION     = 'certificacion';
    const ROLE_ADMIN_GERENCIA    = 'admin_gerencia';
    const ROLE_TEACHER           = 'teacher';
    const ROLE_STUDENT           = 'student';
    const ROLE_STAFF             = 'staff';

    const ROLES = [
        self::ROLE_ASESOR_COMERCIAL => 'Asesor Comercial',
        self::ROLE_ADMISION         => 'Admisión',
        self::ROLE_TESORERIA        => 'Tesorería / Cobranza',
        self::ROLE_CERTIFICACION    => 'Certificación',
        self::ROLE_ADMIN_GERENCIA   => 'Administrador / Gerencia',
        self::ROLE_TEACHER          => 'Docente',
        self::ROLE_STUDENT          => 'Alumno',
        self::ROLE_STAFF            => 'Personal Administrativo',
    ];

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'phone',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ── Helpers ────────────────────────────────────────── */

    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? '')) ?: $this->name;
    }

    public function getRoleDisplayAttribute(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    public function isAdminGerencia(): bool
    {
        return $this->role === self::ROLE_ADMIN_GERENCIA;
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    /* ── Relaciones ─────────────────────────────────────── */

    public function teacherProfile(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    public function alumno(): HasOne
    {
        return $this->hasOne(Alumno::class);
    }
}
