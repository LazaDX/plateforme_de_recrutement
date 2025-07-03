<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionsFormulaire extends Model
{
    use HasFactory;
    protected $table = 'questions_formulaires';
    protected $fillable = [
        'id','offre_id', 'label', 'type', 'obligation'
    ];

    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }

    public function reponsesFormulaire()
    {
        return $this->hasMany(ReponseFormulaire::class, 'question_id');
    }

}
