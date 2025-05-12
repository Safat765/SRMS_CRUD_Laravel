<?php

namespace App\Repositories;

use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ProfileRepository
{
    
    public function createProfile(array $data)
    {
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
        // return DB::table('profiles')->insert($data);
    }

    public function changePassword($password)
    {
        $updatePassword = Hash::make($password);
        return DB::table('users')->where('user_id', Session::get('user_id'))
                ->update(['password'=> $updatePassword]);
    }

    public function checkProfile($userID)
    {
        return DB::table('profiles')->where('user_id', $userID)->first();
    }

    public function joinProfile($userID)
    {
        return DB::table('profiles')
            ->join('departments', 'profiles.department_id', '=', 'departments.department_id')
            ->select(
                    'profiles.*',
                    'departments.department_id', 
                    'departments.name as department_name')                    
            ->where('profiles.user_id', $userID)
            ->first();
    }
    public function joinProfileWithSemester($userID)
    {
        return DB::table('profiles')
        ->leftJoin('semesters', 'profiles.semester_id', '=', 'semesters.semester_id')
            ->join('departments', 'profiles.department_id', '=', 'departments.department_id')
            ->select(
                    'profiles.*',
                    'semesters.name as semester_name', 
                    'semesters.semester_id', 
                    'departments.department_id', 
                    'departments.name as department_name')                    
            ->where('profiles.user_id', $userID)
            ->first();
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
        return DB::table('profiles')
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
    }
    
    public function existProfile($userID)
    {
        return DB::table('profiles')
            ->join('departments', 'profiles.department_id', '=', 'departments.department_id')
            ->select(
                    'profiles.*', 
                    'profiles.user_id as profile_user_id', 
                    'departments.department_id', 
                    'departments.name as department_name')                    
            ->where('profiles.user_id', $userID)
            ->first();
    }
    
    public function addName(array $data)
    {
        return DB::table('profiles')
                ->where('user_id', $data['userId'])                
                ->update([
                    'first_name' => $data['firstName'],
                    'middle_name' => $data['middleName'],
                    'last_name' => $data['lastName'],
                    'updated_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s')
        ]);
    }

    public function edit($id)
    {
        return DB::table('profiles')
                ->where('user_id', $id)
                ->select('profiles.*');
    }
}
