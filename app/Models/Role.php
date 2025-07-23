<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Administrateur;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';
    protected $fillable = [
        'id',
        'nom_role'
    ];

    public function administrateur()
    {
        return $this->hasMany(Administrateur::class, 'role_id');
    }
}

