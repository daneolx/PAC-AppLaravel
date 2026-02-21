<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Direccion extends Model
{
    public $timestamps = false;
    protected $table = 'direcciones';

    const TIPO_DOMICILIO = 'DOMICILIO';
    const TIPO_TRABAJO   = 'TRABAJO';
    const TIPO_OTRO      = 'OTRO';

    const TIPOS = [
        self::TIPO_DOMICILIO => 'Domicilio',
        self::TIPO_TRABAJO   => 'Trabajo',
        self::TIPO_OTRO      => 'Otro',
    ];

    protected $fillable = ['tipo', 'direccion', 'referencia', 'alumno_id', 'distrito_id', 'estado'];

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function distrito(): BelongsTo
    {
        return $this->belongsTo(Distrito::class);
    }
}
