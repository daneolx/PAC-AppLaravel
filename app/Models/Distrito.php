<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Distrito extends Model
{
    public $timestamps = false;
    protected $fillable = ['nombre'];

    public function direcciones(): HasMany
    {
        return $this->hasMany(Direccion::class);
    }
}
