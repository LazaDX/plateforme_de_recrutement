<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReponsesFormulaires extends Model
{
    use HasFactory;
    protected $table = 'reponses_formulaires';

    protected $fillable = [
        'id','postule_offre_id', 'question_id', 'valeur'
    ];

    public function postuleOffre()
    {
        return $this->belongsTo(PostuleOffre::class);
    }

    public function questionFormulaire()
    {
        return $this->belongsTo(QuestionFormulaire::class, 'question_id');
    }
}
