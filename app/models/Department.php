<?php

namespace App\Models;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Carbon\Carbon;

class Department extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'departments';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'department_id';
	protected $fillable = [
		'name',
        'created_by'
	];

	public function searchName($name)
	{
		$exist = Department::where('name', 'LIKE', $name)->exists();
		
		if ($exist) {
			return true;
		}

		return false;
	}

    public function createDepartment($name)
	{
		$exist = $this->searchName($name);
		
		if ($exist) {
			return false;
		}
		$department = new Department();
		
		$department->name = $name;
		$department->created_by = 1;
		$department->created_at = Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s');
		$department->updated_at = "";
		$department->save();

		return true;
	}

    public function filter($search)
	{
		$departmentCount = Department::where('name', 'LIKE', '%' . $search . '%');
		
		$totalDepartment = $departmentCount->count();
		$department = $departmentCount->paginate(5);
		
		$data = compact('department', 'totalDepartment');
		return $data;
	}

    public function showAll()
	{
		$departmentCount = Department::all();
		$totalDepartment = $departmentCount->count();
		$department = Department::paginate(5);		
		$data = compact('department', 'totalDepartment');

		return $data;
	}

    public function edit($id)
	{
		$department = Department::find($id);
		return $department;
	}
	
	public function updateDepartment(array $data, $department_id)
	{		
		$department = $this->edit($department_id);
		
		if (!$department) {
			return false;
		}
		$department->name = $data['name'];
		$department->updated_at = Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s');
		$department->save();
		
		return $department;
	}
	
	public function deleteDepartment($id)
	{
		$department = $this->edit($id);
		
		if (!$department) {
			return false;
		}
		
		$department->delete();
		
		return $department;
	}

}