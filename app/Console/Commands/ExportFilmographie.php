<?php

namespace App\Console\Commands;

use App\Http\Resources\PersonResource;
use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Psr\Log\LogLevel;

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
     * Log string into the 'command' channel.
     *
     * @param string $message
     * @param string $level
     * @return void
     */
    protected static function log(string $level, string $message, array $params = []): void
    {
        Log::channel('command')->log($level, __(sprintf('command.lp.export.%s', $message), $params));
    }

    /**
     * The command is starting.
     *
     * @return void
     */
    protected function _start()
    {
        self::log(LogLevel::INFO, 'start', ['command' => $this->signature]);
        self::log(LogLevel::DEBUG, 'batch.size', ['size' => self::MAX_SIZE]);

        $this->total = Movie::query()
                            ->where('a_mettre_a_jour', true)
                            ->count();
        self::log(LogLevel::DEBUG, 'batch.total', ['total' => $this->total]);
    }

    /**
     * The command has finished.
     *
     * @param integer $code
     * @return void
     */
    protected function _end($code = 0)
    {
        self::log(LogLevel::DEBUG, 'memory_usage', ['memory' => convert_units(memory_get_usage())]);
        self::log(LogLevel::INFO, 'end', ['command' => $this->signature, 'code' => $code]);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->_start();

        $batch = 0; // On peut compter le nombre de lots traités
        Movie::query()
            ->from('movie AS m')
            ->where('m.a_mettre_a_jour', true) // Seuls les films mis à jour doivent être traités
            ->with(['persons', 'persons.movies', 'pictures']) // eager-loading (évite le "N+1 query problem")
            ->chunkById(self::MAX_SIZE, function($movies) use(&$batch) { // On limite le nombre de films traités à la fois
                self::log(LogLevel::DEBUG, 'batch.count', ['batch' => ++$batch, 'total' => $total]);

                foreach ($movies as $movie) {
                    foreach($movie->persons as $person) {
                        // On génère la ressource complète
                        $personJson = (new PersonResource($person))->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                        // On sauvegarde les fiches-personnes dans des fichiers JSON.
                        Storage::disk('public')->put(sprintf('%d.json', $person->person_id), $personJson);
                    }
                }
            }, 'movie_id');

        Movie::query()
            ->where('a_mettre_a_jour', true)
            ->update([
                'a_mettre_a_jour' => false,
            ]);

        $this->_end(0);

        return 0;
    }
}
