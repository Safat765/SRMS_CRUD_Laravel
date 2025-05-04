<?php

namespace App\Models;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class User extends Eloquent implements UserInterface, RemindableInterface {
	
	use UserTrait, RemindableTrait;
	
	/**
	* The database table used by the model.
	*
	* @var string
	*/
	protected $table = 'users';
	
	/**
	* The attributes excluded from the model's JSON form.
	*
	* @var array
	*/
	protected $hidden = array('password', 'remember_token');
	protected $primaryKey = 'user_id';
	protected $fillable = [
		'username', 
		'email', 
		'password',
		'user_type',
		'status',
		'registration_number', 
		'phone_number',
		'created_at', 
		'updated_at',
        'first_name',
        'middle_name',
        'last_name',
        'session',
        'department_id'
	];
	
	const USER_TYPE_ADMIN = 1;
	const USER_TYPE_INSTRUCTOR = 2;
	const USER_TYPE_STUDENT = 3;
	
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;
	
	public function setPasswordAttribute($value)
	{
		$this->attributes['password'] = Hash::make($value);
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
		$totalUsers = $userCount->count();
		$users = $userCount->paginate(5);
		$data = compact('users', 'totalUsers');
		
		return $data;
	}
	
	public function showAll()
	{
		$userCount = User::all();
		$totalUsers = $userCount->count();
		$users = User::orderBy('user_id', 'desc')->paginate(5);		
		$data = compact('users', 'totalUsers');

		return $data;
	}
	
	public function edit($id)
	{
		$user = User::find($id);
		return $user;
	}
	
	public function updateUser(array $data, $user_id)
	{		
		$user = $this->edit($user_id);
		
		if (!$user) {
			return false;
		}
		$user->username = $data['username'];
		$user->email = $data['email'];
		$user->registration_number = $data['registrationNumber'];
		$user->user_type = $data['userType'];
		$user->phone_number = $data['phoneNumber'];
		$user->updated_at = Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s');
		$user->save();
		
		return $user;
	}
	
	public function deleteUser($id)
	{
		$user = $this->edit($id);
		
		if (!$user) {
			return false;
		}
		$user->delete();
		
		return $user;
	}
	
	public function statusUpdate($id)
	{
		$user = $this->edit($id);
		
		if (!$user) {
			return false;
		}
		
		if ($user->status == self::STATUS_ACTIVE) {
			$user->status = self::STATUS_INACTIVE;
			Session::flash('message', 'User Inactive successfully');
		} else {
			$user->status = self::STATUS_ACTIVE;
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
}
