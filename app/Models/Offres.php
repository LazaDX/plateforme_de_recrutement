<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offres extends Model
{
    use HasFactory;
    protected $table = 'offres';
    protected $fillable = ['nom_enquete', 'description', 'date_debut', 'date_limite', 'status', 'administrateurs_id'];

    public function postulesOffres()
    {
        return $this->hasMany(PostuleOffres::class, 'offre_id');
    }

    public function administrateurs() {
        return $this->belongsTo(Administrateurs::class, 'administrateur_id');
    }
}
