<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Mark extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'marks';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'mark_id';
}