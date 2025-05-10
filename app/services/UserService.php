<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUser($search)
    {
        $repo = $this->userRepository;

        $statusConstants = $repo->getStatusConstants();
        $ACTIVE = $statusConstants['ACTIVE'];
		$INACTIVE = $statusConstants['INACTIVE'];
        $ADMIN = $statusConstants['ADMIN'];
        $INSTRUCTOR = $statusConstants['INSTRUCTOR'];
        $STUDENT = $statusConstants['STUDENT'];
		
		if ($search != '') {
			$data = $repo->filter($search);
		} else {
			$data = $repo->showAll();
		}
			
        $totalUsers = $data['userCount']->count();
        $users = $data['users'];
		$info = ['Admin' => $ADMIN, 'Instructor' => $INSTRUCTOR, 'Student' => $STUDENT, 'Active' => $ACTIVE, 'Inactive' => $INACTIVE];
		$list = [
			'department' => $repo->getDepartmentList(),
			'semester' => $repo->getSemesterList()
		];

        return compact('users', 'totalUsers', 'search', 'info', 'list');
    }

    public function checkValidation(array $data)
    {
        return Validator::make($data, [
			'username' => 'required|min:3|max:20|unique:users',
			'email' => 'required|email|unique:users',
			'password' => 'required|min:4',
			'userType' => 'required|in:1,2,3',
			'registrationNumber' => 'required|min:3|unique:users,registration_number',
			'phoneNumber' => ['required', 'regex:/^(\+?\d{1,4}[-.\s]?)?(\(?\d{2,4}\)?[-.\s]?)?[\d\-.\s]{6,15}$/'],
			'session' => 'sometimes|min:3',
			'departmentId' => 'sometimes|min:1',
			'semesterId' => 'sometimes|min:1'
		], [
			'required' => 'The :attribute field is required.',
			'unique' => 'This :attribute is already taken.',
			'regex' => 'Please enter a valid phone number.',
			'min' => 'The :attribute must be at least :min characters.',
			'in' => 'Please select a valid :attribute.',
			'sometimes' => 'The :attribute must be a string when provided.'
		]);
    }

    public function storeUser(array $data)
    {
        $repo = $this->userRepository;
        $exist = $repo->searchName($data['username']);

        if ($exist) {
            return false;
        } else {
            $statusConstants = $repo->getStatusConstants();
            $ACTIVE = $statusConstants['ACTIVE'];

            $userName = $data['username'];
            $email = $data['email'];
            $password = $data['password'];
            $userType = $data['userType'];
            $status = $ACTIVE;
            $registrationNumber = $data['registrationNumber'];
            $phoneNumber = $data['phoneNumber'];

            return $repo->createUser($userName, $email, $password, $userType, $status, $registrationNumber, $phoneNumber);
        }
    }

    public function createProfile(array $data)
    {
        $repo = $this->userRepository;        
        $userType = $data['userType'];
        $userName = $data['username'];
        $registrationNumber = $data['registrationNumber'];
        $firstName = null;
        $middleName = null;
        $lastName = null;
        
        if ($userType == 3) {
            $session = $data['session'];
            $semesterId = $data['semesterId'];
            $departmentId = $data['departmentId'];
        } elseif ($userType == 2) {
            $session = null;
            $semesterId = null;
            $departmentId = $data['departmentId'];
        } else {
            $session = null;
            $semesterId = null;
            $departmentId = null;
        }
        $userId = $repo->getUserId($userName);

        return $repo->createProfile($firstName, $middleName, $lastName, $registrationNumber, $session, $departmentId, $semesterId, $userId);
    }

    public function findUser($id)
    {
        $repo = $this->userRepository;
        return $repo->find($id);
    }

    public function updateValidation(array $data, $id)
    {
        return Validator::make($data, [
			'username' => 'required|min:3|max:20|unique:users,username,'.$id.',user_id',
			'email' => 'required|email|unique:users,email,'.$id.',user_id',
			'userType' => 'required|in:1,2,3',
			'registrationNumber' => 'required|min:3|unique:users,registration_number,'.$id.',user_id',
			'phoneNumber' => ['required', 'regex:/^(\+?\d{1,4}[-.\s]?)?(\(?\d{2,4}\)?[-.\s]?)?[\d\-.\s]{6,15}$/'],
			'session' => 'sometimes|min:3',
			'departmentId' => 'sometimes|min:1',
			'semesterId' => 'sometimes|min:1'
		], [
			'required' => 'The :attribute field is required.',
			'unique' => 'This :attribute is already taken.',
			'regex' => 'Please enter a valid phone number.',
			'min' => 'The :attribute must be at least :min characters.',
			'in' => 'Please select a valid :attribute.',
			'sometimes' => 'The :attribute must be a string when provided.'
		]);
    }

    public function updateUser(array $data, $id)
    {
        $repo = $this->userRepository;
        return $repo->updateUser($data, $id);
    }

    public function updateProfileDuringUserUpdate(array $data)
    {
		$userType = $data['userType'];
		$userId = $data['userId'];

		if ($userType == 3) {
			$session = $data['session'];
			$semesterId = $data['semesterId'];
			$departmentId = $data['departmentId'];
		} elseif ($userType == 2) {
			$session = null;
			$semesterId = null;
			$departmentId = $data['departmentId'];
		} else {
			$session = null;
			$semesterId = null;
			$departmentId = null;
		}
        $repo = $this->userRepository;
        return $repo->updateProfileDuringUserUpdate($userId, $departmentId, $session, $semesterId);
    }

    public function destroyUser($id)
    {
        $repo = $this->userRepository;
        return $repo->deleteUser($id);
    }

    public function statusUpdate($id)
    {
        $repo = $this->userRepository;
        return $repo->statusUpdate($id);
    }

    public function allStudents()
    {
        $repo = $this->userRepository;
		$students = $repo->allResults();
		$totalStudents = count($students);
		$getResults = [];
		
		foreach ($students as $student) {
			$getResults[$student->department_name][] = $student;
		}

		return compact('getResults', 'totalStudents');
    }

    public function semesterWise($id)
    {
        $repo = $this->userRepository;
		$students = $repo->semesterWise($id);
		$getResults = [];
		
		foreach ($students as $student) {
			$getResults[$student->semester_name][] = $student;
		}

		return compact('getResults');
    }
}
