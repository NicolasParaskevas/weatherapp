<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\WeatherAggregator;
use App\Services\WeatherApiWeatherProvider;
use App\Services\OpenMeteoWeatherProvider;

class AggregateWeatherData extends Command
{

    protected $signature = 'weather:aggregate';

    protected $description = 'Background job that fetches data from weather apis and stores them in DB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info("Starting Weather Data Aggregation");

        $wa = new WeatherAggregator([
            new WeatherApiWeatherProvider(),
            new OpenMeteoWeatherProvider(),
            //... Add new weather providers here
        ]);

        // get current time and location
        $hour = date("H");

        $locations = Location::all();

        foreach ($locations as $location)
        {
            $wa->aggregateData($location, $hour);
        }

        Log::info("Finished Weather Data Aggregation");

        return 0;
    } 

}