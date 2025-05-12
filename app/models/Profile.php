<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Profile extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'profiles';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'profile_id';
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'registration_number',
        'session',
        'department_id',
        'created_at',
        'updated_at'
    ];
}