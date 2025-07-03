<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostuleOffres extends Model
{
    use HasFactory;
    protected $table = 'postuleoffres';
    protected $fillable = ['offre_id', 'enqueteur_id', 'date_candidature', 'statut'];

    public function offre()
    {
        return $this->belongsTo(Offres::class, 'offre_id');
    }

    public function enqueteur()
    {
        return $this->belongsTo(Enqueteurs::class, 'enqueteur_id');
    }
}
