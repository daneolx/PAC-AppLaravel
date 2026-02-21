<?php

namespace App\Models;

use App\Models\Traits\HasBaseModel;
use Illuminate\Database\Eloquent\Model;

class AcademicPeriod extends Model
{
    use HasBaseModel;

    protected $fillable = [
        'name', 'start_date', 'end_date', 'period_status', 'status', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }
}
