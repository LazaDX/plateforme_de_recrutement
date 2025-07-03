<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enqueteurs extends Model
{
    use HasFactory;
    protected $table = 'enqueteurs';

    protected $fillable = [
        'id','nom', 'prenom', 'email', 'password', 'date_de_naissance', 'photo', 'diplomes', 'experiences'
    ];

    public function postuleOffres()
    {
        return $this->hasMany(PostuleOffre::class, 'enqueteur_id');
    }
}
