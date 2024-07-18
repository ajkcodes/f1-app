<?php

namespace App\Console\Commands;

use App\Models\Circuit;
use App\Models\Location;
use App\Models\Race;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportRaces extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:races';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import F1 races from the Ergast API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = 30;
        $offset = 0;

        $initResponse = Http::get('https://ergast.com/api/f1/races.json');

        $total = $initResponse->json()['MRData']['total'];

        $this->output->progressStart($total);

        while ($offset < $total) {
            $response = Http::get('https://ergast.com/api/f1/races.json', [
                'limit' => $limit,
                'offset' => $offset,
            ]);

            $data = $response->json();
            $total = $data['MRData']['total'];
            $races = $data['MRData']['RaceTable']['Races'];

            foreach($races as $race) {
                // First Location Data
                $locationData = $race['Circuit']['Location'];
                $location = Location::updateOrCreate(
                    ['lat' => $locationData['lat'], 'long' => $locationData['long']],
                    [
                        'locality' => $locationData['locality'],
                        'country' => $locationData['country'],
                    ]
                );

                // Second Circuit Data
                $circuitData = $race['Circuit'];
                $circuit = Circuit::updateOrCreate(
                    ['circuitId' => $circuitData['circuitId']],
                    [
                        'url' => $circuitData['url'],
                        'circuitName' => $circuitData['circuitName'],
                        'location_id' => $location->id,
                    ]
                );

                // Last Race Data
                Race::updateOrCreate(
                    [
                        'season' => $race['season'],
                        'round' => $race['round']
                    ],
                    [
                        'url' => $race['url'],
                        'raceName' => $race['raceName'],
                        'date' => $race['date'],
                        'circuitId' => $circuit['circuitId']
                    ]
                );

                $this->output->progressAdvance();
            }

            $offset += $limit;
        }

        $this->output->progressFinish();
        $this->info('Races imported successfully!');
        return 0;
    }
}
