<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

// On change l'heritage en Authenticable si l'on veut passer à l'authentification
class Administrateurs extends Model
{
    protected $table = 'administrateurs';
    protected $fillable = ['nom', 'prenom', 'email', 'password'];
    //protected $hidden = ['password', 'remember_token'];

    public function offres() {
        return $this->hasMany(Offres::class, 'administrateurs_id');
    }
}
