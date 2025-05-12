<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Department;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function getStatusConstants()
    {
        return [
            'ACTIVE' => 1,
            'INACTIVE' => 0,
            'ADMIN' => 1,
            'INSTRUCTOR' => 2,
            'STUDENT' => 3
        ];
    }

    public function getDepartmentList()
    {
        return Department::lists('name', 'department_id');
    }

    public function getSemesterList()
    {
        return Semester::lists('name', 'semester_id');
    }

	public function login($username, $password)
	{
		return User::where('username', $username) 
						->where('password', $password)->exists();
	}

	public function findPassword($username)
	{
		return User::where('username', $username)->first();
	}

	public function searchName($username)
	{
		return User::where('username', $username)->exists();
	}
	
	public function createUser($data)
	{
		return DB::table('users')->insert($data);
	}
	
	public function filter($search)
	{
		$userCount = User::where('username', 'LIKE', '%' . $search . '%')
							->orWhere('email', 'LIKE', '%' . $search . '%');
		$users = $userCount->paginate(5);
		$data = compact('users', 'userCount');
		
		return $data;
	}
	
	public function showAll()
	{
		$userCount = DB::table('users')
					->leftJoin('profiles', 'users.user_id', '=', 'profiles.user_id')
					->select(
						'users.*',
						'profiles.department_id',
						'profiles.semester_id',
						'profiles.session'
					)
					->orderBy('users.user_id', 'desc');
		$users = $userCount->paginate(5);	
		return compact('users', 'userCount');
	}
	
	public function find($id)
	{
		return User::find($id);
	}
	
	public function updateUser(array $data, $userId)
	{
		return DB::table('users')
				->where('user_id', $userId)
				->update($data);
	}

	public function updateProfileDuringUserUpdate($userId, $data)
	{
		return DB::table('profiles')->where('user_id', $userId)->update($data);
	}
	
	public function deleteUser($id)
	{
		return DB::table('users')->where('user_id', $id)->delete();
	}
	
	public function statusUpdate($id, $status)
	{
		return DB::table('users')->where('user_id', $id)->update(['status' => $status]);
	}

	public function getUserId($username)
	{
		$user = User::where('username', $username)->first();
		
		return $user->user_id;
	}

	public function createProfile($data)
    {
        return DB::table('profiles')->insert($data);
    }

	public function enrollCourse($studentId) {
		return DB::table('marks')
					->join('courses', 'marks.course_id', '=', 'courses.course_id')
					->select(
						'courses.name',
						'courses.credit'
					)
					->where('marks.student_id', $studentId)
					->get();
	}

	public function allResults()
	{
		return DB::table('results')
			->join('users', 'results.student_id', '=', 'users.user_id')
			->join('profiles', 'users.user_id', '=', 'profiles.user_id')
			->join('departments', 'profiles.department_id', '=', 'departments.department_id')
			->join('semesters', 'profiles.semester_id', '=', 'semesters.semester_id')
			->select([
				'users.user_id',
				'users.username',
				'users.registration_number',
				'profiles.session',
				'results.cgpa',
				'semesters.name as semester_name',
				'departments.name as department_name'
			])
			->orderBy('users.user_id', 'asc')
			->get();
	}

	public function semesterWise($id)
	{
		return DB::table('marks')
			->join('users', 'marks.student_id', '=', 'users.user_id')
			->join('profiles', 'users.user_id', '=', 'profiles.user_id')
			->join('semesters', 'marks.semester_id', '=', 'semesters.semester_id')
			->join('courses', 'marks.course_id', '=', 'courses.course_id')
			->select([
				'courses.name as course_name',
				'marks.marks',
				'marks.gpa',
				'semesters.name as semester_name'
			])
			->where('marks.student_id', $id)
			->get();
	}
}
