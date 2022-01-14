<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Picture extends Model
{
    use HasFactory;

    /**
     * @var string the table name
     */
    protected $table = 'picture';

    /**
     * @var string the primary key name
     */
    protected $primaryKey = 'picture_id';

    public function getNameAttribute(): string
    {
        return sprintf('Movie_%d.%s', $this->picture_id, $this->extensionName());
    }

    /**
     * Return all the movies this picture is linked to.
     *
     * @return BelongsToMany
     */
    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'pciture_movie');
    }

    /**
     * Return the extension name of this picture.
     *
     * @return string
     */
    protected function extensionName(): string
    {
        switch ($this->mime_type) {
            case 'image/jpeg':
                return 'jpg';
            case 'image/png':
                return 'png';
            default:
                return '';
        }
    }
}
