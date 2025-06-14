<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExperimentView extends Model
{
    use HasFactory;

    protected $fillable = [
        'experiment_id',
        'variation_id',
        'visitor_id',
    ];
}
