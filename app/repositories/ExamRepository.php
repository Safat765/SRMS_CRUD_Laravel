<?php

namespace App\Repositories;

use App\Models\Exam;
use App\Models\Course;
use App\Models\Department;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ExamRepository
{
    public function getCourseList()
    {
        return Course::lists('name', 'course_id');
    }

    public function getDepartmentList()
    {
        return Department::lists('name', 'department_id');
    }

    public function getSemesterList()
    {
        return Semester::lists('name', 'semester_id');
    }

    public function getInstructorList()
    {
        return User::where('user_type', 2)->lists('username', 'user_id');
    }
    
	public function searchName($courseId, $departmentID, $semesterID, $examType) {
		return Exam::where('exam_type', 'LIKE', $examType)
			->where('course_id', 'LIKE', $courseId)
			->where('department_id', 'LIKE', $departmentID)
			->where('semester_id', 'LIKE', $semesterID)->exists();
	}
	
	public function create(array $data)
	{
		return DB::table('exams')->insert($data);
	}
	
	public function filter($search)
	{
		return  DB::table('exams')
			->join('courses', 'exams.course_id', '=', 'courses.course_id')
			->join('departments', 'exams.department_id', '=', 'departments.department_id')
			->join('semesters', 'exams.semester_id', '=', 'semesters.semester_id')
			->join('users', 'exams.instructor_id', '=', 'users.user_id')
			->select(
				'users.user_id as instructor_id',
				'users.username',
				'departments.department_id',
				'departments.name as department_name',
				'semesters.semester_id',
				'semesters.name as semester_name',
				'courses.course_id',
				'courses.name as course_name',
				'exams.exam_id',
				'exams.exam_title',
				'exams.exam_type',
				'exams.credit',
				'exams.marks'
			) 
			->where('exams.exam_title', 'LIKE', $search)
			->orWhere('exams.credit', 'LIKE', $search)
			->orderBy('exams.exam_id', 'desc')
			->paginate(5);
	}
	
	public function find($id)
	{
		return Exam::find($id);
	}
	
	public function update(array $data, $id)
	{
		return DB::table('exams')->where('exam_id', $id)->update($data);
	}
	
	public function delete($id)
	{
		return DB::table('exams')->where('exam_id', $id)->delete();
	}
	
	public function joinTables()
	{
		return  DB::table('exams')
		->join('courses', 'exams.course_id', '=', 'courses.course_id')
		->join('departments', 'exams.department_id', '=', 'departments.department_id')
		->join('semesters', 'exams.semester_id', '=', 'semesters.semester_id')
		->join('users', 'exams.instructor_id', '=', 'users.user_id')
		->select(
			'users.user_id as instructor_id',
			'users.username',
			'departments.department_id',
			'departments.name as department_name',
			'semesters.semester_id',
			'semesters.name as semester_name',
			'courses.course_id',
			'courses.name as course_name',
			'exams.*'
		)
		->orderBy('exams.exam_id', 'desc')
		->paginate(5);
	}
}
