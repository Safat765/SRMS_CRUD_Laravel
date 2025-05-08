<?php

namespace App\Repositories;
use App\Models\Course;
use Illuminate\Support\Facades\DB;


class CourseRepository
{
    public function getStatusConstants()
    {
        return [
            'ACTIVE' => Course::STATUS_ACTIVE,
            'INACTIVE' => Course::STATUS_INACTIVE,
        ];
    }
    
    public function searchName($name)
	{
		return Course::where('name', 'LIKE', $name)->exists();
	}

    public function createCourse(array $data)
	{
		return DB::table('courses')->insert($data);
	}

    public function filter($search)
	{
		$courseCount = Course::where('name', 'LIKE', '%' . $search . '%')
							->orWhere('credit', 'LIKE', '%' . $search . '%');
		$course = $courseCount->paginate(5);

		return compact('course', 'courseCount');
	}

    public function showAll()
	{
		$courseCount = Course::all();
		$course = Course::paginate(5);	

		return compact('course', 'courseCount');
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