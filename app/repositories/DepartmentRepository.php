<?php

namespace App\Repositories;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DepartmentRepository
{   
	public function searchName($name)
	{
		return Department::where('name', 'LIKE', $name)->exists();
	}

    public function createDepartment(array $data)
	{
		return DB::table('departments')->insert($data);
	}

    public function filter($search)
	{
		$departmentCount = Department::where('name', 'LIKE', '%' . $search . '%');
		$department = $departmentCount->paginate(5);
		$data = compact('department', 'departmentCount');

		return $data;
	}

    public function showAll()
	{
		$departmentCount = Department::all();
		$department = Department::paginate(5);

		return compact('department', 'departmentCount');
	}

    public function find($id)
	{
		return Department::find($id);
	}
	
	public function updateDepartment(array $data, $department_id)
	{
		return DB::table('departments')->where('department_id', $department_id)->update($data);
	}
	
	public function deleteDepartment($id)
	{
		return DB::table('departments')->where('department_id', $id)->delete();
	}
}
