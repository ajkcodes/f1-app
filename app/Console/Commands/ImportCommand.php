<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run multiple import commands sequentially';

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
     */
    public function handle()
    {
        $this->info('Starting all import commands sequentially.');

        $this->call('import:drivers');
        $this->call('import:constructors');
        $this->call('import:seasons');
        $this->call('import:circuits');
        $this->call('import:races');
        $this->call('import:results');

        $this->info('All commands executed successfully.');
    }
}
