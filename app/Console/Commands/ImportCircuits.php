<?php

namespace App\Console\Commands;

use App\Models\Circuit;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportCircuits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:circuits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import F1 circuits from the Ergast API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = 30;
        $offset = 0;

        $initResponse = Http::get('http://ergast.com/api/f1/circuits.json');

        $total = $initResponse->json()['MRData']['total'];

        $this->output->progressStart($total);

        while ($offset < $total) {
            $response = Http::get('http://ergast.com/api/f1/circuits.json', [
                'limit' => $limit,
                'offset' => $offset,
            ]);

            $data = $response->json();
            $circuits = $data['MRData']['CircuitTable']['Circuits'];

            foreach ($circuits as $circuitData) {
                $locationData = $circuitData['Location'];
                $location = Location::updateOrCreate(
                    ['lat' => $locationData['lat'], 'long' => $locationData['long']],
                    [
                        'locality' => $locationData['locality'],
                        'country' => $locationData['country'],
                    ]
                );

                Circuit::updateOrCreate(
                    ['circuitId' => $circuitData['circuitId']],
                    [
                        'url' => $circuitData['url'],
                        'circuitName' => $circuitData['circuitName'],
                        'location_id' => $location->id,
                    ]
                );

                $this->output->progressAdvance();
            }

            $offset += $limit;
        }

        $this->output->progressFinish();
        $this->info('Circuits imported successfully!');
        return 0;
    }
}
