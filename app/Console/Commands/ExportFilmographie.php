<?php

namespace App\Console\Commands;

use App\Http\Resources\PersonResource;
use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\OutputInterface;

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
     * Total movies to process.
     *
     * @var integer
     */
    protected int $total = 0;

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
     * If we are in '-vvv' verbose mode, we also display messages to the user (console).
     *
     * @param string $message
     * @param string $level
     *
     * @return void
     */
    protected function log(string $level, string $message, array $params = []): void
    {
        $message = __(sprintf('command.lp.export.%s', $message), $params);
        $this->info($message, OutputInterface::VERBOSITY_DEBUG);
        Log::channel('command')->log($level, $message);
    }

    /**
     * The command is starting.
     *
     * @return void
     */
    protected function _start()
    {
        $this->log(LogLevel::INFO, 'start', ['command' => $this->signature]);
        $this->log(LogLevel::DEBUG, 'batch.size', ['batch' => self::MAX_SIZE]);

        if($this->getOutput()->isdebug())
        {
            $this->total = Movie::query()
                                ->from('movie AS m')
                                ->select(DB::raw(sprintf('CEIL(COUNT(DISTINCT m.movie_id) / %d) AS total', self::MAX_SIZE)))
                                ->where('m.a_mettre_a_jour', true)
                                ->value('total');
            $this->log(LogLevel::DEBUG, 'batch.total', ['total' => $this->total]);
        }
    }

    /**
     * The command has finished.
     *
     * @param integer $code
     *
     * @return integer
     */
    protected function _end(int $code = 0)
    {
        $this->log(LogLevel::DEBUG, 'memory_usage', ['memory' => convert_units(memory_get_usage())]);
        $this->log(LogLevel::INFO, 'end', ['command' => $this->signature, 'code' => $code]);

        return $code;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->_start();

        $batch = 0; // le mode DEBUG compte le nombre de lots traités.

        return DB::transaction(function () use(&$batch) {
            Movie::query()
                ->from('movie AS m')
                ->where('m.a_mettre_a_jour', true) // Seuls les films mis à jour doivent être traités
                ->with(['persons', 'persons.movies', 'pictures']) // eager-loading (évite le "N+1 query problem")
                ->chunkById(self::MAX_SIZE, function($movies) use(&$batch) { // On limite le nombre de films traités à la fois
                    $this->log(LogLevel::DEBUG, 'batch.count', ['batch' => ++$batch, 'total' => $this->total]);

                    // On boucle sur les films
                    foreach ($movies as $movie) {
                        // On boucle sur les personnes concernées dans chaque film
                        foreach($movie->persons as $person) {
                            // On génère la ressource complète
                            $personJson = (new PersonResource($person))->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                            // On sauvegarde les fiches-personnes dans des fichiers JSON.
                            Storage::disk('public')->put(sprintf('%d.json', $person->person_id), $personJson);
                        }
                    }
                }, 'movie_id');

            // On met à jour les films, pour ne pas les retraiter la prochaine fois.
            Movie::query()
                ->where('a_mettre_a_jour', true)
                ->update([
                    'a_mettre_a_jour' => false,
                ]);

            return $this->_end(0);
        });

        return $this->_end(1);
    }
}
