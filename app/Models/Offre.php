<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\QuestionFormulaire;
use App\Models\Administrateur;
use App\Models\PostuleOffre;
use App\Models\AccesOffre;
use App\Models\Visiteur;

class Offre extends Model
{
    use HasFactory;
     protected $table = 'offres';

    protected $fillable = [
        'id','nom_enquete', 'details_enquete', 'date_debut', 'date_limite', 'administrateur_id', 'status_offre', 'priorite'
    ];

    public function administrateur()
    {
        return $this->belongsTo(Administrateur::class);
    }

    public function questionFormulaire()
    {
        return $this->hasMany(QuestionFormulaire::class, 'offre_id');
    }

    public function postuleOffre()
    {
        return $this->hasMany(PostuleOffre::class, 'offre_id');
    }

    public function accesOffre()
    {
        return $this->hasMany(AccesOffre::class, 'offre_id');
    }

    public function visiteur()
    {
        return $this->hasMany(Visiteur::class, 'offre_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
}
