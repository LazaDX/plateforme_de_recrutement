<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Offre;
use App\Models\ReponseFormulaire;

class QuestionFormulaire extends Model
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

    public function reponseFormulaire()
    {
        return $this->hasMany(ReponseFormulaire::class, 'question_id');
    }

}
