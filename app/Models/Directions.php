<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directions extends Model
{
    use HasFactory;
    protected $table = 'directions';
    protected $fillable = [
        'id',
        'nom_direction'
    ];

    public function administrateurs()
    {
        return $this->hasMany(Administrateurs::class, 'direction_id');
    }
}
