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
        $this->info('Starting Import Circuits.');

        // Starttime of import
        $startTime = microtime(true);

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

            foreach ($circuits as $circuit) {
                $locationData = $circuit['Location'];
                $location = Location::firstOrCreate(
                    ['lat' => $locationData['lat'], 'long' => $locationData['long']],
                    [
                        'locality' => $locationData['locality'],
                        'country' => $locationData['country'],
                    ]
                );

                Circuit::firstOrCreate(
                    ['circuitId' => $circuit['circuitId']],
                    [
                        'url' => $circuit['url'],
                        'circuitName' => $circuit['circuitName'],
                        'location_id' => $location->id,
                    ]
                );

                $this->output->progressAdvance();
            }

            $offset += $limit;
        }

        // Endtime of import
        $endTime = microtime(true);

        // Calculate the total duration of the import in seconds
        $duration = round($endTime - $startTime, 2);

        $this->output->progressFinish();
        $this->info("Circuits imported successfully in {$duration} seconds!");
        return 0;
    }
}
