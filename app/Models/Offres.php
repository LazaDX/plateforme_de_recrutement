<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offres extends Model
{
    use HasFactory;
     protected $table = 'offres';

    protected $fillable = [
        'id','nom_enquete', 'details_enquete', 'date_debut', 'date_limite', 'administrateur_id', 'status_offre'
    ];

    public function administrateurs()
    {
        return $this->belongsTo(Administrateur::class);
    }

    public function questionsFormulaires()
    {
        return $this->hasMany(QuestionFormulaire::class, 'offre_id');
    }

    public function postuleOffres()
    {
        return $this->hasMany(PostuleOffre::class, 'offre_id');
    }

    public function accesOffres()
    {
        return $this->hasMany(AccesOffre::class, 'offre_id');
    }

    public function visiteurs()
    {
        return $this->hasMany(Visiteur::class, 'offre_id');
    }
}
