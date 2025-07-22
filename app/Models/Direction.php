<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administrateur;

class Direction extends Model
{
    use HasFactory;
    protected $table = 'directions';
    protected $fillable = [
        'id',
        'nom_direction'
    ];

    public function administrateur()
    {
        return $this->hasMany(Administrateur::class, 'direction_id');
    }
}
