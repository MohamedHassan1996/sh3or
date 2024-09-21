<?php

namespace App\Models\Party;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_id',
        'path'
    ];
}
