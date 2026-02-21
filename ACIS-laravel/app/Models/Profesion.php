<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profesion extends Model
{
    public $timestamps = false;
    protected $table = 'profesiones';
    protected $fillable = ['nombre', 'descripcion'];

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class);
    }
}
