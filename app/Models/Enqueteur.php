<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\PostuleOffre;

class Enqueteur extends Authenticatable
{
    use HasFactory;
    protected $table = 'enqueteurs';

    protected $fillable = [
        'id','nom', 'prenom', 'email', 'password', 'date_de_naissance', 'photo', 'diplomes', 'experiences'
    ];

    public function postuleOffre()
    {
        return $this->hasMany(PostuleOffre::class, 'enqueteur_id');
    }
}
