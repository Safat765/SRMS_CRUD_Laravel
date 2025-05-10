<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Department;
use App\Models\Semester;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
		$result = User::where('username', $username) 
						->where('password', $password)->exists();
		
		return $result;
	}

	public function findPassword($username)
	{
		$result = User::where('username', $username)->first();

		return $result;
	}

	public function searchName($username)
	{
		return User::where('username', $username)->exists();
	}
	
	public function createUser($username, $email, $password, $userType, $status, $registrationNumber, $phoneNumber)
	{
		$user = new User();
		
		$user->username = $username;
		$user->email = $email;
		$user->password = $password;
		$user->user_type = $userType;
		$user->status = $status;
		$user->registration_number = $registrationNumber;
		$user->phone_number = $phoneNumber;
		$user->created_at = Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s');
		$user->updated_at = "";
		
		$user->save();
		
		return $user;
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
		$data = compact('users', 'userCount');

		return $data;
	}
	
	public function find($id)
	{
		$user = User::find($id);
		return $user;
	}
	
	public function updateUser(array $data, $user_id)
	{
		$result = DB::table('users')
				->where('user_id', $user_id)
				->update([
					'username' => $data['username'],
					'email' => $data['email'],
					'registration_number' => $data['registrationNumber'],
					'user_type' => $data['userType'],
					'phone_number' => $data['phoneNumber'],
					'updated_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s')
				]);
		
		return $result;
	}

	public function updateProfileDuringUserUpdate($userId, $departmentId, $session, $semesterId)
	{
		$result = DB::table('profiles')
					->where('user_id', $userId)
					->update([
						'semester_id'=> $semesterId,
						'department_id'=> $departmentId,
						'session'=> $session,
					]);
		return $result;
	}
	
	public function deleteUser($id)
	{
		$user = $this->find($id);
		
		if (!$user) {
			return false;
		}
		$user->delete();
		
		return $user;
	}
	
	public function statusUpdate($id)
	{
		$user = $this->find($id);
		
		if (!$user) {
			return false;
		}
		
		if ($user->status == User::STATUS_ACTIVE) {
			$user->status = User::STATUS_INACTIVE;
			Session::flash('message', 'User Inactive successfully');
		} else {
			$user->status = User::STATUS_ACTIVE;
			Session::flash('success', 'User activated successfully');
		}
		$user->save();
		
		return $user;
	}

	public function getUserId($username)
	{
		$user = User::where('username', $username)->first();
		
		if (!$user) {
			return false;
		}
		
		return $user->user_id;
	}

	public function createProfile($firstName, $middleName, $lastName, $registrationNumber, $session, $departmentId, $semesterId, $userId)
    {
        $insert = DB::table('profiles')->insertGetId(array(
            'user_id' => $userId, 
            'first_name' => $firstName, 
            'middle_name' => $middleName,  
            'last_name' => $lastName, 
            'registration_number' => $registrationNumber, 
            'session' => $session, 
            'department_id' => $departmentId,
            'semester_id' => $semesterId,
            'created_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s'),
            'updated_at' => ''
            )
		);

        return $insert;
    }

	public function enrollCourse($studentId) {
		$result = DB::table('marks')
					->join('courses', 'marks.course_id', '=', 'courses.course_id')
					->select(
						'courses.name',
						'courses.credit'
					)
					->where('marks.student_id', $studentId)
					->get();
		
		return $result;
	}

	public function allResults()
	{
		$students = DB::table('results')
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

		return $students;
	}

	public function semesterWise($id)
	{
		$students = DB::table('marks')
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

		return $students;
	}
}
