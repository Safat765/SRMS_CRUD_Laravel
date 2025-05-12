<?php

namespace App\Repositories;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class SemesterRepository
{
    public function searchName($name)
	{
		return Semester::where('name', 'LIKE', $name)->exists();
	}

    public function createSemester($data)
	{
        return DB::table('semesters')->insert($data);
	}

    public function filter($search)
	{
		$semesterCount = Semester::where('name', 'LIKE', '%' . $search . '%');
		$semester = $semesterCount->orderBy('semester_id', 'desc')->paginate(5);
        
		return compact('semester', 'semesterCount');
	}

    public function showAll()
	{
		$semesterCount = Semester::all();
		$semester = Semester::orderBy('semester_id', 'desc')->paginate(5);		

		return compact('semester', 'semesterCount');
	}

    public function find($id)
	{
		return Semester::find($id);
	}
	
	public function updateSemester(array $data, $semester_id)
	{		
		return Semester::where('semester_id', $semester_id)->update($data);
	}
	
	public function deleteSemester($id)
	{
		return Semester::where('semester_id', $id)->delete();
	}

}