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
                // Location Data
                $locationData = $raceData['Circuit']['Location'];
                $location = Location::updateOrCreate(
                    [
                        'lat' => $locationData['lat'],
                        'long' => $locationData['long']
                    ],
                    [
                        'locality' => $locationData['locality'],
                        'country' => $locationData['country']
                    ]
                );

                // Circuit Data
                $circuitData = $raceData['Circuit'];
                $circuit = Circuit::updateOrCreate(
                    [
                        'circuitId' => $circuitData['circuitId']
                    ],
                    [
                        'url' => $circuitData['url'],
                        'circuitName' => $circuitData['circuitName'],
                        'location_id' => $location->id
                    ]
                );

                // Race Data
                $race = Race::updateOrCreate(
                    [
                        'season' => $raceData['season'],
                        'round' => $raceData['round']
                    ],
                    [
                        'url' => $raceData['url'],
                        'raceName' => $raceData['raceName'],
                        'date' => $raceData['date'],
                        'circuitId' => $circuit['circuitId']
                    ]
                );

                // Results Data
                foreach ($raceData['Results'] as $resultData) {
                    // Driver Data
                    $driverData = $resultData['Driver'];
                    $driver = Driver::updateOrCreate(
                        [
                            'driverId' => $driverData['driverId']
                        ],
                        [
                            'url' => $driverData['url'],
                            'givenName' => $driverData['givenName'],
                            'familyName' => $driverData['familyName'],
                            'dateOfBirth' => $driverData['dateOfBirth'],
                            'nationality' => $driverData['nationality']
                        ]
                    );

                    // Constructor Data
                    $constructorData = $resultData['Constructor'];
                    $constructor = Constructor::updateOrCreate(
                        [
                            'constructorId' => $constructorData['constructorId']
                        ],
                        [
                            'url' => $constructorData['url'],
                            'name' => $constructorData['name'],
                            'nationality' => $constructorData['nationality']
                        ]
                    );

                    // Result Data
                    Result::updateOrCreate(
                        [
                            'race_id' => $race->id,
                            'driverId' => $driver['driverId'],
                            'constructorId' => $constructor['constructorId'],
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
                            'milliseconds' => $resultData['Time']['millis'] ?? null
                        ]
                    );
                }

                $this->output->progressAdvance();
            }

            $offset += $limit;
        }

        $this->output->progressFinish();
        $this->info('Results imported successfully.');
        return 0;
    }
}
