<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Offre;
use App\Models\Enqueteur;
use App\Models\ReponsesFormulaire;


class PostuleOffre extends Model
{
    use HasFactory;
    protected $table = 'postules_offres';

    protected $fillable = [
        'id','offre_id', 'enqueteur_id', 'date_postule', 'type_enqueteur', 'status_postule'
    ];

    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }

    public function enqueteur()
    {
        return $this->belongsTo(Enqueteur::class);
    }

    public function reponseFormulaire()
    {
        return $this->hasMany(ReponseFormulaire::class, 'postule_offre_id');
    }
}
