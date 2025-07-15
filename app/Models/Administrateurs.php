<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Administrateurs extends Authenticatable
{
    use HasFactory;


    protected $table = 'administrateurs';
    protected $fillable = [
        'id',
        'nom',
        'prenom',
        'email',
        'password',
        'role_id',
        'poste_id',
        'direction_id',
        'status',
    ];

     public function roles()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    public function postes()
    {
        return $this->belongsTo(Postes::class, 'poste_id');
    }

    public function directions()
    {
        return $this->belongsTo(Directions::class, 'direction_id');
    }

    public function offres()
    {
        return $this->hasMany(Offres::class, 'administrateur_id');
    }

    public function accessOffres()
    {
        return $this->hasMany(AccessOffres::class, 'administrateur_id');
    }
}
