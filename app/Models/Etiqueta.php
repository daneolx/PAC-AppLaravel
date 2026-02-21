<?php

namespace App\Models;

use App\Models\Traits\HasBaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Etiqueta extends Model
{
    use HasBaseModel;

    protected $fillable = ['name', 'status', 'created_by'];

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'producto_etiqueta');
    }
}
