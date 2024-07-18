<?php

namespace App\Console\Commands;

use App\Models\Driver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportDrivers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:drivers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import F1 drivers from the Ergast API';

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
        $this->info('Starting Import Drivers.');

        // Starttime of import
        $startTime = microtime(true);

        $limit = 30;
        $offset = 0;

        // Initial request to get the total number of results
        $initResponse = Http::get('https://ergast.com/api/f1/drivers.json');
        $total = $initResponse->json()['MRData']['total'];

        $this->output->progressStart($total);

        while ($offset < $total) {
            $response = Http::get('https://ergast.com/api/f1/drivers.json', [
                'limit' => $limit,
                'offset' => $offset,
            ]);

            $data = $response->json();
            $total = $data['MRData']['total'];
            $drivers = $data['MRData']['DriverTable']['Drivers'];

            foreach($drivers as $driver) {
                Driver::firstOrCreate(
                    ['driverId' => $driver['driverId']],
                    [
                        'code' => $driver['code'] ?? null,
                        'url' => $driver['url'],
                        'givenName' => $driver['givenName'],
                        'familyName' => $driver['familyName'],
                        'dateOfBirth' => $driver['dateOfBirth'],
                        'nationality' => $driver['nationality'],
                        'permanentNumber' => $driver['permanentNumber'] ?? null,
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
        $this->info("Drivers imported successfully in {$duration} seconds!");
        return 0;
    }
}
