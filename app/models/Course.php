<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Course extends Eloquent
{
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;
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

    public static function getStatus()
    {
        return [
            'ACTIVE' => self::STATUS_ACTIVE,
            'INACTIVE' => self::STATUS_INACTIVE,
        ];
    }
}