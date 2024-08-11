<?php

namespace App\Services;

use App\Interfaces\WeatherProviderInterface;

class OpenWeatherMapWeatherProvider implements WeatherProviderInterface
{
    public function getData(float $long, float $lat): array | false
    {
        $apiKey = env('OPENWEATHERMAP_KEY');

        if (empty($apiKey))
        {
            Log::error("OpenWeatherMap - API key not provided");
            return false;
        }

        $ch = curl_init();

        $url = "https://api.openweathermap.org/data/3.0/onecall?lat=".$lat."lon=".$long."&exclude=hourly,daily,minutely,alerts&units=metric&appid=".$apiKey;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch))
        {
            Log::error("OpenWeatherMap - Curl Error: " . curl_error($ch));
            curl_close($ch);
            return false;
        }
        
        curl_close($ch);

        $result_json = json_decode($result, true);

        if (!is_array($result_json))
        {
            Log::error("OpenWeatherMap - error parsing json response");
            return false;
        }

        if (isset($result_json["cod"])) // cod means Code of Error
        {
            Log::error("OpenWeatherMap - Error: " . $result_json["cod"] . " " . $result_json["message"]);
            return false;
        }

        return $result_json;
    }
}