<?php

namespace App\Repositories;
use App\Models\Course;
use Illuminate\Support\Facades\DB;


class CourseRepository
{   
    public function searchName($name)
	{
		return Course::where('name', 'LIKE', $name)->exists();
	}

    public function search($name, $credit)
	{
		return Course::where('name', 'LIKE', $name)->where('credit', 'LIKE', $credit)->exists();
	}

    public function createCourse(array $data)
	{
		return DB::table('courses')->insert($data);
	}

    public function filter($search)
	{
		$courseCount = Course::where('name', 'LIKE', '%' . $search . '%')
							->orWhere('credit', 'LIKE', '%' . $search . '%');
		$coursePaginate = $courseCount->orderBy('course_id', 'desc')->paginate(5);

		return ['coursePaginate' => $coursePaginate, 'courseCount' => $courseCount];
	}

    public function showAll()
	{
		$courseCount = Course::all();
		$coursePaginate = Course::orderBy('course_id', 'desc')->paginate(5);	

		return ['coursePaginate' => $coursePaginate, 'courseCount' => $courseCount];
	}

    public function find($id)
	{
		return Course::find($id);
	}
	
	public function updateCourse(array $data, $course_id)
	{
		return DB::table('courses')->where('course_id', $course_id)->update($data);
	}
	
	public function deleteCourse($id)
	{
		return DB::table('courses')->where('course_id', $id)->delete();
	}
	
	public function status($id, $status)
	{
		return DB::table('courses')->where('course_id', $id)->update(['status' => $status]);
	}

	public function assignedCourse()
	{
		return DB::table('exams')
			->join('users', 'exams.instructor_id', '=', 'users.user_id')
			->join('courses', 'exams.course_id', '=', 'courses.course_id')
			->join('semesters', 'exams.semester_id', '=', 'semesters.semester_id')
			->select([
				'users.username',
				'users.registration_number',
				'semesters.name as semester_name',
				'courses.name as course_name'
			])
			->orderBy('semesters.semester_id', 'asc')
			->get();
	}
}