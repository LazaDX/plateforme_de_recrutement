<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\Role;
use App\Models\Poste;
use App\Models\Direction;
use App\Models\AccessOffre;
use App\Models\Offre;
use App\Models\Visiteur;

class Administrateur extends Authenticatable
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

     public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function poste()
    {
        return $this->belongsTo(Poste::class, 'poste_id');
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'direction_id');
    }

    public function offre()
    {
        return $this->hasMany(Offre::class, 'administrateur_id');
    }

    public function accessOffre()
    {
        return $this->hasMany(AccessOffre::class, 'administrateur_id');
    }
}
