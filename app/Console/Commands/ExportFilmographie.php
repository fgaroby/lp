<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportFilmographie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lp:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exporte la filmographique complète des personnes dont au moins un film a été mis à jour.';

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
        return 0;
    }
}
