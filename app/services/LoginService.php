<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class LoginService
{
    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loginValidation(array $data)
    {
        return Validator::make($data, [
            'username' => 'required',
            'password' => 'required|min:4'
        ]);
    }

    public function dashboard(){
        return $this->userRepository->enrollCourse(Session::get('user_id'));
    }

    public function loginPassword($username, $password)
    {
        $user = $this->userRepository->findPassword($username);
        
        if (!password_verify($password, $user->password)) {
            return false;
        }
        $password = $user->password;

        return ['password' => $password, 'user' => $user];
    }
    
    public function loginUser($username, $password, $userDetails)
    {        
        if ($this->userRepository->login($username, $password)) {
            Session::put('username', $userDetails['username']);
            Session::put('user_id', $userDetails['user_id']);
            Session::put('email', $userDetails['email']);
            Session::put('registration_number', $userDetails['registration_number']);
            Session::put('phone_number', $userDetails['phone_number']);
            Session::put('password', $userDetails['password']);
            Session::put('user_type', $userDetails['user_type']);
            Session::put('status', $userDetails['status']);
            
            return true;
        }
        return false;
    }
}