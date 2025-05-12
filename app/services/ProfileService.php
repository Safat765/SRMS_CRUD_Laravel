<?php

namespace App\Services;

use App\Repositories\ProfileRepository;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    protected $profileRepository;

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

    public function createProfile()
    {
        $addURL = $this->getURL();
		$pageName = "Change Password";			
		$url = url('/' . $addURL . '/profiles');
		return compact('url' ,'pageName');
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
        $repo = $this->profileRepository;
        $updatePassword = Hash::make($newPassword);

        if ($repo->changePassword($updatePassword)) {
            Session::put("password", $updatePassword);
            return true;
        }
        return false;
    }

    public function joinProfileWithSemester($userID)
    {
        $repo = $this->profileRepository;
        return $repo->joinProfileWithSemester($userID);
    }

    public function joinProfile($userID)
    {
        $repo = $this->profileRepository;
        return $repo->joinProfile($userID);
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

    public function updateProfile(array $data, $id)
    {
        $repo = $this->profileRepository;
        $firstName = $data['firstName'];
		$middleName = $data['middleName'];
		$lastName = $data['lastName'];
		$registrationNumber = $data['registrationNumber'];
		$departmentId = $data['departmentId'];
		$userType = Session::get('user_type');

		if ($userType == 3) {
			$session = $data['session'];
			$semesterId = $data['semesterId'];
		} else {
			$session = null;
			$semesterId = null;
		}
		$departmentId = $repo->getDepartmentId($departmentId);
		$semesterId = $repo->getSemesterId($semesterId);
		$data = [
			'profileId'=> $id,
			'firstName'=> $firstName,
			'middleName'=> $middleName,
			'lastName'=> $lastName,
			'registrationNumber'=> $registrationNumber,
			'departmentId'=> $departmentId,
			'session'=> $session,
			'semesterId'=> $semesterId
		];
		return $repo->updateProfile($data);
    }

    public function existProfile($userID)
    {
        $repo = $this->profileRepository;

        return $repo->existProfile($userID);
    }

    public function checkProfile($userID)
    {
        $repo = $this->profileRepository;

        return $repo->checkProfile($userID);
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
        $repo = $this->profileRepository;
        $data = [
            'userId'=> $id,
            'firstName'=> $data['firstName'],
            'middleName'=> $data['middleName'],
            'lastName'=> $data['lastName']
        ];
        return $repo->addName($data);
    }

}

