<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MoviePerson extends Pivot
{
    /**
     * @var string the table name
     */
    protected $table = 'movie_person';

    public function fonction(): BelongsTo
    {
        return $this->belongsTo(Fonction::class, 'person_type_person_type_id', 'type_id');
    }
}
