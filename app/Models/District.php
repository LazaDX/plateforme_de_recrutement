<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Region;
use App\Models\Commune;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts';

    protected $fillable = ['id', 'code_district', 'district', 'region_id'];

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function commune()
    {
        return $this->hasMany(Commune::class, 'district_id');
    }
}
