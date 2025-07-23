<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Offre;
use App\Models\Administrateur;

class AccesOffre extends Model
{
    use HasFactory;
    protected $table = 'acces_offres';

    protected $fillable = [
        'id','offre_id', 'administrateur_id', 'etat'
    ];

    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }

    public function administrateur()
    {
        return $this->belongsTo(Administrateur::class);
    }
}
