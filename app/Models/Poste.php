<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Administrateur;

class Poste extends Model
{
    use HasFactory;
    protected $table = 'postes';
    protected $fillable = [
        'id',
        'nom_poste'
    ];

    public function administrateur()
    {
        return $this->hasMany(Administrateur::class, 'poste_id');
    }
}
