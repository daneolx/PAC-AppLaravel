<?php

namespace App\Models;

use App\Models\Traits\HasBaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tema extends Model
{
    use HasBaseModel;

    protected $fillable = ['name', 'status', 'created_by'];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
}
