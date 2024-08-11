<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\WeatherService;
use App\Location;

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

        $temperature = $this->weatherService->getTemperature(
            $data["long"], $data["lat"], $data["time"]
        );

        if ($temperature === null)
        {
            // log error and respond
        }

        return response()->json([
            "temperature" => $temperature
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            "long" => "required",
            "lat"  => "required",
        ]);

        // Begin transaction

        $location = new Location();
        $location->longitude = $data['long'];
        $location->latitude = $data['lat'];
        $location->save();

        // commit

    }
}
