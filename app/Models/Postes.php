<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postes extends Model
{
    use HasFactory;
    protected $table = 'postes';
    protected $fillable = [
        'id',
        'nom_poste'
    ];

    public function administrateurs()
    {
        return $this->hasMany(Administrateurs::class, 'poste_id');
    }
}
