<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Province;
use App\Models\District;

class Region extends Model
{
    use HasFactory;

    protected $table = 'regions';

    protected $fillable = ['id', 'code_region', 'region', 'province_id'];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'region_id');
    }

}
