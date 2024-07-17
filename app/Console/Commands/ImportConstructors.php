<?php

namespace App\Console\Commands;

use App\Models\Constructor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportConstructors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:constructors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import F1 constructors from the Ergast API';

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
        $limit = 30;
        $offset = 0;

        $initResponse = Http::get('https://ergast.com/api/f1/constructors.json');

        $total = $initResponse->json()['MRData']['total'];

        $this->output->progressStart($total);

        while ($offset < $total) {
            $response = Http::get('https://ergast.com/api/f1/constructors.json', [
                'limit' => $limit,
                'offset' => $offset,
            ]);

            $data = $response->json();
            $total = $data['MRData']['total'];
            $constructors = $data['MRData']['ConstructorTable']['Constructors'];

            foreach($constructors as $constructor) {
                Constructor::updateOrCreate(
                    ['constructorId' => $constructor['constructorId']],
                    [
                        'url' => $constructor['url'],
                        'name' => $constructor['name'],
                        'nationality' => $constructor['nationality'],
                    ]
                );

                $this->output->progressAdvance();
            }

            $offset += $limit;
        }

        $this->output->progressFinish();
        $this->info('Constructors imported successfully!');
        return 0;
    }
}
