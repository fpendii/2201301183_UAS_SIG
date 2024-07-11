<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PomMiniModel extends Model
{
    use HasFactory;

    protected $table = 'pom_mini';

    protected $fillable = [
        'nama', 'alamat', 'latitude', 'longitude',
    ];

}
