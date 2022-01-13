<?php

namespace App\Console\Commands;

use App\Http\Resources\PersonResource;
use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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
    protected $description = 'Exporte la filmographie complète des personnes dont au moins un film a été mis à jour.';

    /**
     * Nombre maximal de films à traiter en un lot, pour limiter l'impact sur la RAM.
     *
     * @var int
     */
    const MAX_SIZE = 20;

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
        Movie::query()
            ->from('movie AS m')
            ->where('m.a_mettre_a_jour', true) // Seuls les films mis à jour doivent être traités
            ->with(['persons', 'persons.movies', 'pictures']) // eager-loading (évite le "N+1 query problem")
            ->chunkById(self::MAX_SIZE, function($movies) { // On limite le nombre de films traités à la fois
                foreach ($movies as $movie) {
                    foreach($movie->persons as $person) {
                        // On génère la ressource complète
                        $personJson = (new PersonResource($person))->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                        // On sauvegarde les fiches-personnes dans des fichiers JSON.
                        Storage::disk('public')->put(sprintf('%d.json', $person->person_id), $personJson);
                    }
                }
            }, 'movie_id');

        return 0;
    }
}
