<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ProfileRepository
{
    public function changePassword($password, $userId)
    {
        return DB::table('users')->where('user_id', $userId)->update($password);
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
        return DB::table('departments')->where('name', $departmentId)->first();
    }
    public function getSemesterId($semesterId)
    {
        return DB::table('semesters')->where('name', $semesterId)->first();
    }

    public function updateProfile(array $data, $id)
    {
        return DB::table('profiles')->where('profile_id', $id)->update($data);
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
    
    public function addName(array $data, $id)
    {
        return DB::table('profiles')->where('user_id', $id)->update($data);
    }

    public function edit($id)
    {
        return DB::table('profiles')->where('user_id', $id)->select('profiles.*');
    }
}
