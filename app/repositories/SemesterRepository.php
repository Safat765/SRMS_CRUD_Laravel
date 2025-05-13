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

    public function create($data)
	{
        return DB::table('semesters')->insert($data);
	}

    public function filter($search)
	{
		$semesterCount = Semester::where('name', 'LIKE', '%' . $search . '%');
		$semesterPaginate = $semesterCount->orderBy('semester_id', 'desc')->paginate(5);
        
		return ['semesterPaginate' => $semesterPaginate, 'semesterCount' => $semesterCount];
	}

    public function showAll()
	{
		$semesterCount = Semester::all();
		$semesterPaginate = Semester::orderBy('semester_id', 'desc')->paginate(5);		

		return ['semesterPaginate' => $semesterPaginate, 'semesterCount' => $semesterCount];
	}

    public function find($id)
	{
		return Semester::find($id);
	}
	
	public function update(array $data, $semester_id)
	{		
		return Semester::where('semester_id', $semester_id)->update($data);
	}
	
	public function delete($id)
	{
		return Semester::where('semester_id', $id)->delete();
	}

}