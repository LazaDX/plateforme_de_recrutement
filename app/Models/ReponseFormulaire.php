<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PostuleOffre;
use App\Models\QuestionFormulaire;

class ReponseFormulaire extends Model
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
