<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Fonction extends Pivot
{
    /**
     * @var string the table name
     */
    protected $table = 'person_type';
}
