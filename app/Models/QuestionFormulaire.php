<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Offre;
use App\Models\ReponseFormulaire;
use App\Models\Region;
use App\Models\District;
use App\Models\Commune;

class QuestionFormulaire extends Model
{
    use HasFactory;
    protected $table = 'questions_formulaires';
    protected $fillable = [
        'id','offre_id', 'label', 'type', 'obligation',  'all_regions',
        'region_id',
        'all_districts',
        'district_id',
        'all_communes',
        'commune_id',
    ];

    protected $casts = [
        'obligation'    => 'boolean',
        'all_regions'   => 'boolean',
        'all_districts' => 'boolean',
        'all_communes'  => 'boolean',
    ];

    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }

    public function reponseFormulaire()
    {
        return $this->hasMany(ReponseFormulaire::class, 'question_id');
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
