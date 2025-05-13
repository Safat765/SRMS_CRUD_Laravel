<?php

namespace App\Services;

use App\Repositories\ProfileRepository;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    private $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function getURL()
    {
        $userType = Session::get('user_type');
		if ($userType == 1) {
		  $addURL = 'admin';
		} elseif ($userType == 2) {
		  $addURL = 'instructor';
		} elseif ($userType == 3) {
		  $addURL = 'students';
		}
        return $addURL;
    }

    public function create()
    {
        $addURL = $this->getURL();
		$url = url('/' . $addURL . '/profiles');

        return [
            'url' => $url,
            'pageName' => "Change Password"
        ];
    }

    public function checkValidation(array $data)
    {
        return Validator::make($data, [
			'oldPassword' => 'required|min:4',
			'newPassword' => 'required|min:4'
		], [
			'required' => 'The :attribute field is required.',
			'min' => 'The :attribute must be at least :min characters.'
		]);
    }

    public function checkPassword($oldPassword, $newPassword)
    {
        if (password_verify($oldPassword, $newPassword)) {
            return true;
        }
        return false;
    }

    public function matchPassword($oldPassword, $newPassword)
    {
        if ($oldPassword === $newPassword) {
            return true;
        }
        return false;
    }

    public function changePassword($newPassword)
    {
        $updatePassword = Hash::make($newPassword);

        if ($this->profileRepository->changePassword(['password' => $updatePassword], Session::get('user_id'))) {
            Session::put("password", $updatePassword);

            return true;
        }
        return false;
    }

    public function joinWithSemester($userID)
    {
        return $this->profileRepository->joinWithSemester($userID);
    }

    public function join($userID)
    {
        return $this->profileRepository->join($userID);
    }

    public function updateValidation(array$data)
    {
        return Validator::make($data, [
			'firstName'=> 'required|string|min:3|max:30',
			'middleName' => 'sometimes|string|max:50',
			'lastName' => 'required|string|max:50',
			'registrationNumber' => 'required|min:3|unique:profiles,registration_number',
			'departmentId'=> 'required',
			'session'=> 'sometimes|min:3',
			'semesterId'=> 'sometimes'
		], [
			'required' => 'The :attribute field is required.',
			'unique' => 'This :attribute is already taken.',
			'min' => 'The :attribute must be at least :min characters.',
    		'sometimes' => 'The :attribute must be a string when provided.'
		]);
    }

    public function update(array $data, $id)
    {
        $firstName = $data['firstName'];
		$middleName = $data['middleName'];
		$lastName = $data['lastName'];
		$registrationNumber = $data['registrationNumber'];
		$departmentId = $data['departmentId'];
		$userType = Session::get('user_type');

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
        $semester = $this->profileRepository->getSemesterId($semesterId);
        
        if (!$semester) {
            $semesterId = null;
        } else {
            $semesterId = $semester->semester_id;
        }
        $department = $this->profileRepository->getDepartmentId($departmentId);
        
        if (!$department) {
            $departmentId = null;
        } else {
            $departmentId = $department->department_id;
        }

		return $this->profileRepository->update([
			'first_name'=> $firstName,
			'middle_name'=> $middleName,
			'last_name'=> $lastName,
			'registration_number'=> $registrationNumber,
			'department_id'=> $departmentId,
			'session'=> $session,
			'semester_id'=> $semesterId
		], $id);
    }

    public function exist($userID)
    {
        $exist = $this->profileRepository->exist($userID);
        if ($exist) {
            return $exist;
        } else {
            return false;
        }
    }

    public function check($userID)
    {
        return $this->profileRepository->check($userID);
    }
    
    public function addNameValidation(array $data)
    {
        return Validator::make($data, [
			'firstName'=> 'required|string|min:3|max:30',
			'middleName' => 'sometimes|string|max:50',
			'lastName' => 'required|string|max:50'
		], [
			'required' => 'The :attribute field is required.',
			'min' => 'The :attribute must be at least :min characters.',
    		'sometimes' => 'The :attribute must be a string when provided.'
		]);
    }

    public function addName(array $data, $id)
    {
        return $this->profileRepository->addName([
            'first_name'=> $data['firstName'],
            'middle_name'=> $data['middleName'],
            'last_name'=> $data['lastName']
        ], $id);
    }

}

