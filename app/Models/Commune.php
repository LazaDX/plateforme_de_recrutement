<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\District;

class Commune extends Model
{
    use HasFactory;

    protected $table = 'communes';

    protected $fillable = ['id', 'code_commune', 'commune', 'district_id'];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
