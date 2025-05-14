<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
	const USER_TYPE_ADMIN = 1;
	const USER_TYPE_INSTRUCTOR = 2;
	const USER_TYPE_STUDENT = 3;
	
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;
	
	/**
	* The database table used by the model.
	*
	* @var string
	*/
	protected $table = 'users';
	
	/**
	* The attributes excluded from the model's JSON form.
	*
	* @var array
	*/
	protected $hidden = array('password', 'remember_token');
	protected $primaryKey = 'user_id';
	protected $fillable = [
		'username', 
		'email', 
		'password',
		'user_type',
		'status',
		'registration_number', 
		'phone_number',
		'created_at', 
		'updated_at',
        'first_name',
        'middle_name',
        'last_name',
        'session',
        'department_id'
	];

    public static function getStatus()
    {
        return [
            'ACTIVE' => self::STATUS_ACTIVE,
            'INACTIVE' => self::STATUS_INACTIVE,
            'ADMIN' => self::USER_TYPE_ADMIN,
            'INSTRUCTOR' => self::USER_TYPE_INSTRUCTOR,
            'STUDENT' => self::USER_TYPE_STUDENT
        ];
    }
}
