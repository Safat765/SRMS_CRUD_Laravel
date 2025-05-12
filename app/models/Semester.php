<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Semester extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'semesters';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'semester_id';
	protected $fillable = [
		'name',
        'created_by'
	];
}