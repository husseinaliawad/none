<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'name',
        'min_points',
        'perks',
    ];

    protected $casts = [
        'perks' => 'array',
    ];
}

