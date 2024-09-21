<?php

namespace App\Models\Party;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreparationTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_at',
        'end_at',
    ];
}
