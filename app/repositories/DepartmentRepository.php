<?php

namespace App\Repositories;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentRepository
{   
	public function searchName($name)
	{
		return Department::where('name', 'LIKE', $name)->exists();
	}

    public function create(array $data)
	{
		return DB::table('departments')->insert($data);
	}

    public function filter($search)
	{
		$departmentCount = Department::where('name', 'LIKE', '%' . $search . '%');
		$departmentPaginate = $departmentCount->orderBy('department_id', 'desc')->paginate(5);

		return ['departmentPaginate' => $departmentPaginate, 'departmentCount' => $departmentCount];
	}

    public function showAll()
	{
		$departmentCount = Department::all();
		$departmentPaginate = Department::orderBy('department_id', 'desc')->paginate(5);

		return ['departmentPaginate' => $departmentPaginate, 'departmentCount' => $departmentCount];
	}

    public function find($id)
	{
		return Department::find($id);
	}
	
	public function update(array $data, $department_id)
	{
		return DB::table('departments')->where('department_id', $department_id)->update($data);
	}
	
	public function delete($id)
	{
		return DB::table('departments')->where('department_id', $id)->delete();
	}
}
