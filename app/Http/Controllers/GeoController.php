<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use App\Models\Region;
use App\Models\District;
use App\Models\Commune;

class GeoController extends Controller
{
     public function regions(): JsonResponse
    {
        $regions = Region::select('id', 'region')->orderBy('region')->get();
        return response()->json($regions);
    }

    public function districts(Region $region): JsonResponse
    {
        $districts = $region->districts()->select('id', 'district')->orderBy('district')->get();
        return response()->json($districts);
    }

    public function communes(District $district): JsonResponse
    {
        $communes = $district->commune()->select('id', 'commune')->orderBy('commune')->get();
        return response()->json($communes);
    }
}
