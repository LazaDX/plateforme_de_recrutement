<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostuleOffres extends Model
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

    public function reponsesFormulaire()
    {
        return $this->hasMany(ReponseFormulaire::class, 'postuleoffre_id');
    }
}
