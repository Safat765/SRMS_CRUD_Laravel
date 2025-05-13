<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUser($search)
    {
        $statusConstants = $this->userRepository->getStatusConstants();
        $ACTIVE = $statusConstants['ACTIVE'];
		$INACTIVE = $statusConstants['INACTIVE'];
        $ADMIN = $statusConstants['ADMIN'];
        $INSTRUCTOR = $statusConstants['INSTRUCTOR'];
        $STUDENT = $statusConstants['STUDENT'];
		
		if ($search != '') {
			$data = $this->userRepository->filter($search);
		} else {
			$data = $this->userRepository->showAll();
		}
			
        $totalUsers = $data['userCount']->count();
        $users = $data['users'];
		$info = ['Admin' => $ADMIN, 'Instructor' => $INSTRUCTOR, 'Student' => $STUDENT, 'Active' => $ACTIVE, 'Inactive' => $INACTIVE];
		$list = [
			'department' => $this->userRepository->getDepartmentList(),
			'semester' => $this->userRepository->getSemesterList()
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
        $exist = $this->userRepository->searchName($data['username']);

        if ($exist) {
            return false;
        } else {
            $statusConstants = $this->userRepository->getStatusConstants();
            $ACTIVE = $statusConstants['ACTIVE'];
            $result = [
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password'],
                'user_type' => $data['userType'],
                'status' => $ACTIVE,
                'registration_number' => $data['registrationNumber'],
                'phone_number' => $data['phoneNumber']
            ];

            return $this->userRepository->createUser($result);
        }
    }

    public function createProfile(array $data)
    {       
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
        $userId = $this->userRepository->getUserId($userName);
        
        if ($userId) {
            $result = [
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
            ];

            return $this->userRepository->createProfile($result);
        } else {
            return false;
        }
    }

    public function findUser($id)
    {
        return $this->userRepository->find($id);
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
        $result = [
            'username' => $data['username'],
            'email' => $data['email'],
            'registration_number' => $data['registrationNumber'],
            'user_type' => $data['userType'],
            'phone_number' => $data['phoneNumber'],
            'updated_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s')
        ];
        return $this->userRepository->updateUser($result, $id);
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
        $result = [
            'semester_id' => $semesterId,
            'department_id' => $departmentId,
            'session' => $session
        ];

        return $this->userRepository->updateProfileDuringUserUpdate($userId, $result);
    }

    public function destroyUser($id)
    {
        if ($this->findUser($id)) {
            return $this->userRepository->deleteUser($id);
        } else {
            return false;
        }
    }

    public function statusUpdate($id)
    {
        $statusConstants = $this->userRepository->getStatusConstants();
        $ACTIVE = $statusConstants['ACTIVE'];
		$INACTIVE = $statusConstants['INACTIVE'];
        $exist = $this->userRepository->find($id);

        if ($exist) {
            if ($exist->status == $ACTIVE) {
                $status = $INACTIVE;
            } else {
                $status = $ACTIVE;
            }

            return $this->userRepository->statusUpdate($id, $status);
        } else {
            return false;
        }
    }

    public function allStudents()
    {
		$students = $this->userRepository->allResults();
		$totalStudents = count($students);
		$getResults = [];
		
		foreach ($students as $student) {
			$getResults[$student->department_name][] = $student;
		}

		return compact('getResults', 'totalStudents');
    }

    public function semesterWise($id)
    {
		$students = $this->userRepository->semesterWise($id);
		$getResults = [];
		
		foreach ($students as $student) {
			$getResults[$student->semester_name][] = $student;
		}

		return compact('getResults');
    }
}
