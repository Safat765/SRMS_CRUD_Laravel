<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Department extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'departments';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'department_id';
	protected $fillable = [
		'name',
        'created_by'
	];
}