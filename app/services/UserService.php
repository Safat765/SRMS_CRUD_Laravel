<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll($search)
    {
        $statusConstants = User::getStatus();
		
		if ($search != '') {
			$data = $this->userRepository->filter($search);
		} else {
			$data = $this->userRepository->showAll();
		}			
        $totalUsers = $data['userCount']->count();
        $users = $data['usersPaginated'];

        return [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'search' => $search,
            'info' => [
                'Admin' => $statusConstants['ADMIN'],
                'Instructor' => $statusConstants['INSTRUCTOR'],
                'Student' => $statusConstants['STUDENT'],
                'Active' => $statusConstants['ACTIVE'],
                'Inactive' => $statusConstants['INACTIVE']
            ],
            'list' => [
                'department' => $this->userRepository->getDepartmentList(),
                'semester' => $this->userRepository->getSemesterList()
            ]
        ];
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

    public function store(array $data)
    {
        if ($this->userRepository->searchName($data['username'])) {
            return false;
        } else {
            $statusConstants = User::getStatus();

            return $this->userRepository->create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'user_type' => $data['userType'],
                'status' => $statusConstants['ACTIVE'],
                'registration_number' => $data['registrationNumber'],
                'phone_number' => $data['phoneNumber']
            ]);
        }
    }

    public function create(array $data)
    {       
        $userType = $data['userType'];
        $firstName = null;
        $middleName = null;
        $lastName = null;
        
        if ($userType == User::USER_TYPE_STUDENT) {
            $session = $data['session'];
            $semesterId = $data['semesterId'];
            $departmentId = $data['departmentId'];
        } elseif ($userType == User::USER_TYPE_INSTRUCTOR) {
            $session = null;
            $semesterId = null;
            $departmentId = $data['departmentId'];
        } else {
            $session = null;
            $semesterId = null;
            $departmentId = null;
        }
        $userId = $this->userRepository->getId($data['username']);
        
        if ($userId) {

            return $this->userRepository->createProfile([
                'user_id' => $userId, 
                'first_name' => $firstName, 
                'middle_name' => $middleName,  
                'last_name' => $lastName, 
                'registration_number' => $data['registrationNumber'], 
                'session' => $session, 
                'department_id' => $departmentId,
                'semester_id' => $semesterId,
                'created_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s'),
                'updated_at' => ''
            ]);
        } else {
            return false;
        }
    }

    public function find($id)
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

    public function update(array $data, $id)
    {
        return $this->userRepository->update([
                'username' => $data['username'],
                'email' => $data['email'],
                'registration_number' => $data['registrationNumber'],
                'user_type' => $data['userType'],
                'phone_number' => $data['phoneNumber'],
                'updated_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s')
            ], $id);
    }

    public function updateProfileDuringUserUpdate(array $data)
    {
		$userType = $data['userType'];

		if ($userType == User::USER_TYPE_STUDENT) {
			$session = $data['session'];
			$semesterId = $data['semesterId'];
			$departmentId = $data['departmentId'];
		} elseif ($userType == User::USER_TYPE_INSTRUCTOR) {
			$session = null;
			$semesterId = null;
			$departmentId = $data['departmentId'];
		} else {
			$session = null;
			$semesterId = null;
			$departmentId = null;
		}

        return $this->userRepository->updateProfileDuringUserUpdate($data['userId'], [
            'semester_id' => $semesterId,
            'department_id' => $departmentId,
            'session' => $session
        ]);
    }

    public function destroy($id)
    {
        if ($this->find($id)) {
            return $this->userRepository->delete($id);
        } else {
            return false;
        }
    }

    public function statusUpdate($id)
    {
        $statusConstants = User::getStatus();
        $ACTIVE = $statusConstants['ACTIVE'];
        $exist = $this->userRepository->find($id);

        if ($exist) {
            if ($exist->status == $ACTIVE) {
                $status = $statusConstants['INACTIVE'];
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

		return ['getResults' => $getResults, 'totalStudents' => $totalStudents];
    }

    public function semesterWise($id)
    {
		$students = $this->userRepository->semesterWise($id);
		$getResults = [];
		
		foreach ($students as $student) {
			$getResults[$student->semester_name][] = $student;
		}

		return ['getResults' => $getResults];
    }
}
