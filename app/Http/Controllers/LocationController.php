<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Location;

class LocationController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $wservice)
    {
        $this->weatherService = $wservice;
    }

    public function get(Request $request)
    {
        $data = $this->validate($request, [
            "long" => "required",
            "lat"  => "required",
            "date" => "required",
            "hour" => "required",
        ]);

        $temperature = $this->weatherService->getTemperature(
            $data["long"], $data["lat"], $data["time"]
        );

        if ($temperature === null)
        {
            return response()->json(["error" => "Not Found"], 404);
        }

        return response()->json([
            "temperature" => $temperature
        ]);
    }

    public function add(Request $request)
    {
        $data = $this->validate($request, [
            "long" => "required",
            "lat"  => "required",
        ]);

        // Begin transaction
        try
        {
            DB::beginTransaction();
            $location = new Location();
            $location->longitude = $data['long'];
            $location->latitude = $data['lat'];
            $location->save();
            DB::commit();

            Log::info("New location added: long: " . $location->longitude . " lat: " . $location->latitude);
    
        }
        catch (\Exception $e)
        {
            DB::rollback();
            Log::error("Error when adding location: long: " . $location->longitude . " lat: " . $location->latitude . " " . $e->getMessage());
        }
        
    }
}
