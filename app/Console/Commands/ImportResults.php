<?php

namespace App\Console\Commands;

use App\Models\Circuit;
use App\Models\Constructor;
use App\Models\Driver;
use App\Models\Location;
use App\Models\Race;
use App\Models\Result;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import F1 results from the Ergast API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Import Results.');

        // Starttime of import
        $startTime = microtime(true);

        $limit = 30;
        $offset = 0;

        // Initial request to get the total number of results
        $initResponse = Http::get('https://ergast.com/api/f1/results.json');
        $total = $initResponse->json()['MRData']['total'];

        $this->output->progressStart($total);

        while ($offset < $total) {
            $response = Http::get('https://ergast.com/api/f1/results.json', [
                'limit' => $limit,
                'offset' => $offset,
            ]);

            $data = $response->json();
            $results = $data['MRData']['RaceTable']['Races'];

            foreach ($results as $raceData) {
                // Race Data
                $race = Race::where('season', $raceData['season'])
                            ->where('round', $raceData['round'])
                            ->first();

                // Results Data
                foreach ($raceData['Results'] as $resultData) {
                    Result::firstOrCreate(
                        [
                            'race_id' => $race->id,
                            'driverId' => $resultData['Driver']['driverId'],
                            'constructorId' => $resultData['Constructor']['constructorId'],
                            'position' => $resultData['position']
                        ],
                        [
                            'number' => $resultData['number'],
                            'positionText' => $resultData['positionText'],
                            'points' => $resultData['points'],
                            'grid' => $resultData['grid'],
                            'laps' => $resultData['laps'],
                            'status' => $resultData['status'],
                            'time' => $resultData['Time']['time'] ?? null,
                            'time_millis' => $resultData['Time']['millis'] ?? null
                        ]
                    );
                }

                $this->output->progressAdvance();
            }

            $offset += $limit;
        }

        // Endtime of import
        $endTime = microtime(true);

        // Calculate the total duration of the import in seconds
        $duration = round($endTime - $startTime, 2);

        $this->output->progressFinish();
        $this->info("Results imported successfully in {$duration} seconds.");
        return 0;
    }
}
