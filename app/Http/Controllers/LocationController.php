<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\WeatherService;

class LocationController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $wservice)
    {
        $this->weatherService = $wservice;
    }

    public function get(Request $request)
    {
        $data = $request->validate([
            "long" => "required",
            "lat"  => "required",
            "time" => "required",
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            "long" => "required",
            "lat"  => "required",
        ]);
    }
}
