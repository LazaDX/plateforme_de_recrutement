<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visiteurs extends Model
{
    use HasFactory;
    protected $table = 'visiteurs';

    protected $fillable = [
        'id','offre_id', 'nombre_visiteurs'
    ];

    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }
}
