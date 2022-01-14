<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /**
     * Return all the persons linked to this movie.
     *
     * @return BelongsToMany
     */
    public function persons(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'movie_person')
                    ->whereNotNull('person.id_kwm_lp');
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
