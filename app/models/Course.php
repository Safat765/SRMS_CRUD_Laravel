<?php

namespace App\Models;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Course extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'courses';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'course_id';
	protected $fillable = [
		'name',
        'status',
        'credit',
        'created_by',
		'created_at', 
		'updated_at'
	];

	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;
}