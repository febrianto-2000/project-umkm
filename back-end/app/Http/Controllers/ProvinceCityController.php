<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProvinceCityController extends Controller
{

    public function getCitiesAndProvinces()
    {
        $citiesAndProvinces = DB::table('cities')
            ->join('provinces', 'cities.prov_id', '=', 'provinces.prov_id')
            ->select('cities.city_name', 'provinces.prov_name', 'cities.city_id', 'provinces.prov_id')
            ->get();
    
        return response()->json([
            'status' => 1,
            'cities_and_provinces' => $citiesAndProvinces
        ]);
    }

}
