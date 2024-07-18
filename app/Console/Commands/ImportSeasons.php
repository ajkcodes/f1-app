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
        $limit = 30;
        $offset = 0;

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
                Season::updateOrCreate(
                    ['season' => $season['season']],
                    ['url' => $season['url']]
                );

                $this->output->progressAdvance();
            }

            $offset += $limit;
        }

        $this->output->progressFinish();
        $this->info('Seasons imported successfully!');
        return 0;
    }
}
