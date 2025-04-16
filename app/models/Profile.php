<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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

    public function createProfile(array $data)
    {
        p($data);
        $profile = new Profile();

        $profile->first_name = $data['firstName'];
        $profile->middle_name = $data['middleName'];
        $profile->last_name = $data['lastName'];
        $profile->registration_number = $data['registrationNumber'];
        $profile->session = $data['session'];
        $profile->semester_id = $data['semesterId'];
        $profile->department_id = $data['departmentId'];
        $profile->user_id = $data['userId'];
        $profile->created_at = Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s');
        $profile->updated_at = "";
        
        $profile->save();

        return $profile;
    }

    public function changePassword($password)
    {
        $updatePassword = Hash::make($password);
        $update = DB::table('users')->where('user_id', Session::get('user_id'))
                ->update(['password'=> $updatePassword]);
        return $update;
    }

    public function checkProfile($userID)
    {
        $exist = DB::table('profiles')->where('user_id', $userID)->first();

        return $exist;
    }

    public function joinProfile($userID)
    {
        $result = DB::table('profiles')
            ->where('profiles.user_id', $userID)
            ->join('semesters', 'profiles.semester_id', '=', 'semesters.semester_id')
            ->join('departments', 'profiles.department_id', '=', 'departments.department_id')
            ->select(
                    'profiles.*', 
                    'semesters.name as semester_name', 
                    'semesters.semester_id', 
                    'departments.department_id', 
                    'departments.name as department_name')
            ->first();
        
        return $result;
    }

    public function getDepartmentId($departmentId)
    {
        $department = DB::table('departments')->where('name', $departmentId)->first();
        if ($department) {
            return $department->department_id;
        }
        return null;
    }
    public function getSemesterId($semesterId)
    {
        $semester = DB::table('semesters')->where('name', $semesterId)->first();
        if ($semester) {
            return $semester->semester_id;
        }
        return null;
    }

    public function updateProfile(array $data)
    {
        $update = DB::table('profiles')
                ->where('profile_id', $data['profileId'])                
                ->update([
                    'first_name' => $data['firstName'],
                    'middle_name' => $data['middleName'],
                    'last_name' => $data['lastName'],
                    'registration_number' => $data['registrationNumber'],
                    'session' => $data['session'],
                    'department_id' => $data['departmentId'],
                    'semester_id' => $data['semesterId'],
                    'updated_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s')
        ]);

        return $update;
    }

}