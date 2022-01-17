<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Movie extends Model
{
    use HasFactory;

    /**
     * @var string the table name
     */
    protected $table = 'movie';

    /**
     * @var string the primary key name
     */
    protected $primaryKey = 'movie_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Return NULL if the value is empty.
     *
     * @return string|null
     */
    protected function getOfficialWebsiteAttribute(): ?string
    {
        $value = $this->attributes['official_website'];

        return isset($value) && !empty($value) ? trim($value) : null;
    }

    public function fonctions(int $person_id): Collection
    {
        return DB::table('movie_person AS mp')
                 ->join('person_type AS pt', 'mp.person_type_person_type_id', 'pt.person_type_id')
                 ->select('person_type_person_type_id AS type_id', 'name_person_type AS name')
                 ->where('movie_movie_id', $this->movie_id)
                 ->where('person_person_id', $person_id)
                 ->get();
    }

    /**
     * Return all the persons linked to this movie.
     *
     * @return BelongsToMany
     */
    public function persons(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'movie_person');
    }

    /**
     * Return all the pictures of this movie.
     *
     * @return BelongsToMany
     */
    public function pictures(): BelongsToMany
    {
        return $this->belongsToMany(Picture::class, 'picture_movie', 'movie_movie_id', 'picture_picture_id');
    }
}
