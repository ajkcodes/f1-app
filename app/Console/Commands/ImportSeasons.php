<?php

namespace App\Console\Commands;

use App\Models\Season;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportSeasons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:seasons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import F1 seasons from the Ergast API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Import Seasons.');

        // Starttime of import
        $startTime = microtime(true);

        $limit = 30;
        $offset = 0;

        // Initial request to get the total number of results
        $initResponse = Http::get('https://ergast.com/api/f1/seasons.json');
        $total = $initResponse->json()['MRData']['total'];

        $this->output->progressStart($total);

        while ($offset < $total) {
            $response = Http::get('https://ergast.com/api/f1/seasons.json', [
                'limit' => $limit,
                'offset' => $offset,
            ]);

            $data = $response->json();
            $total = $data['MRData']['total'];
            $seasons = $data['MRData']['SeasonTable']['Seasons'];

            foreach($seasons as $season) {
                Season::firstOrCreate(
                    ['season' => $season['season']],
                    ['url' => $season['url']]
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
        $this->info("Seasons imported successfully in {$duration} seconds!");
        return 0;
    }
}
