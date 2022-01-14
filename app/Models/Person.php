<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    use HasFactory;

    /**
     * @var string the table name
     */
    protected $table = 'person';

    /**
     * @var string the primary key name
     */
    protected $primaryKey = 'person_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_naissance' => 'datetime:Y-m-d',
    ];

    public function getPhotoAttribute(): string
    {
        return sprintf('Person_%d.%s', $this->person_id, 'jpg');
    }

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_person')
                    ->orderBy('movie_id');
    }
}
