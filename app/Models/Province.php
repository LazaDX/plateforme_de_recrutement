<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Region;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces';

    protected $fillable = ['id', 'nom_province'];

    public function region()
    {
        return $this->hasMany(Region::class, 'province_id');
    }
}
