<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PostuleOffre;
use App\Models\QuestionFormulaire;
use App\Models\Region;
use App\Models\District;
use App\Models\Commune;

class ReponseFormulaire extends Model
{
    use HasFactory;
    protected $table = 'reponses_formulaires';

    protected $fillable = [
        'id','postule_offre_id', 'question_id','region_id',
        'district_id',
        'commune_id', 'valeur', 'fichier_path'
    ];

    public function postuleOffre()
    {
        return $this->belongsTo(PostuleOffre::class);
    }

    public function questionFormulaire()
    {
        return $this->belongsTo(QuestionFormulaire::class, 'question_id');
    }

      public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }
}
