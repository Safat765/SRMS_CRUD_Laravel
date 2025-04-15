<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Profile extends Eloquent implements UserInterface, RemindableInterface
{
    use UserTrait, RemindableTrait;

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

    public function createProfile($firstName, $middleName, $lastName, $registrationNumber, $session, $departmentId, $userID)
    {
        $profile = $firstName .'---'. $middleName .'---'. $lastName .'---'. $session .'---'. $departmentId .'---'. $userID;

        echo $profile;
        // $insert = DB::table('profiles')->insert(array(
        //     'user_id' => $userID, 
        //     'first_name' => $firstName, 
        //     'middle_name' => $middleName,  
        //     'last_name' => $lastName, 
        //     'registration_number' => $registrationNumber, 
        //     'session' => $session, 
        //     'department_id' => $departmentId,
        //     'created_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s'),
        //     'updated_at' => null
        //     )
		// );
        $profile = new Profile();

        $profile->user_id = $userID;
        $profile->first_name = $firstName;
        $profile->middle_name = $middleName;
        $profile->last_name = $lastName;
        $profile->registration_number = $registrationNumber;
        $profile->session = $session;
        $profile->department_id = $departmentId;
        $profile->created_at = Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s');
        $profile->updated_at = "";
        
        $profile->save();

        return $profile;
    }

}