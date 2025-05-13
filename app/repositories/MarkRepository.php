<?php

namespace App\Repositories;

use App\Models\Mark;
use Illuminate\Support\Facades\DB;

class MarkRepository
{
    public function existOrNot($studentId, $examId)
    {
        return Mark::where('student_id', 'LIKE', $studentId)->where('exam_id', 'LIKE', $examId)->exists();
    }

    public function marksExistOrNot($markId)
    {
        return Mark::where('mark_id', 'LIKE', $markId)->exists();
    }

    public function marksExistOrNotForDelete($studentId, $examId)
    {
        return Mark::where('student_id', 'LIKE', $studentId)->where('exam_id', 'LIKE', $examId)->exists();
    }

    public function updateMarks($marksId, $data)
    {
        return DB::table('marks')->where('mark_id', $marksId)->update($data);
    }
	
	public function createMarks($data)
	{
        return DB::table('marks')->insert($data);
    }

    public function deleteMarks($studentId, $examId)
    {
        return DB::table('marks')->where('student_id', $studentId)->where('exam_id', $examId)->delete();
    }

    public function assignedCourses($instructorId)
    {
        return DB::table('exams')
            ->join('courses', 'exams.course_id', '=', 'courses.course_id')
            ->join('departments', 'exams.department_id', '=', 'departments.department_id')
            ->join('profiles', 'exams.semester_id', '=', 'profiles.semester_id')
            ->join('users','users.user_id','=', 'profiles.user_id')
            ->join('semesters', 'exams.semester_id', '=', 'semesters.semester_id')
            ->select(
                'exams.exam_id',
                'courses.name as course_name', 
                'departments.name as department_name', 
                'semesters.name as semester_name',
                'exams.instructor_id',                       
                'semesters.semester_id',
                'courses.course_id'
            )
            ->where('exams.instructor_id', $instructorId)
            ->groupBy('exams.course_id')
            ->orderBy('exams.semester_id', 'asc')
            ->get();
    }

    public function getStudents($instructorId, $semesterId, $courseID)
    {
        return DB::table('exams')
            ->leftJoin('courses', 'exams.course_id', '=', 'courses.course_id')
            ->join('departments', 'exams.department_id', '=', 'departments.department_id')
            ->join('profiles', 'exams.semester_id', '=', 'profiles.semester_id')
            ->join('users','users.user_id','=', 'profiles.user_id')
            ->join('semesters', 'exams.semester_id', '=', 'semesters.semester_id')
            ->select(
                'exams.exam_id',
                'exams.marks',
                'exams.exam_title',
                'profiles.user_id',
                'courses.course_id',
                'courses.name as course_name',
                'users.username',
                'users.registration_number',
                'users.email',
                'users.user_id',
                'departments.name as department_name',
                'semesters.name as semester_name',
                'semesters.semester_id'
            )
            ->where('exams.instructor_id', $instructorId)
            ->where('exams.semester_id', $semesterId)
            ->where('courses.course_id', $courseID)
            ->get();
    }

    public function editMarks($studentId, $examId)
    {
        return DB::table('marks')
                ->join('exams', 'marks.exam_id', '=', 'exams.exam_id')
                ->leftJoin('courses', 'marks.course_id', '=', 'courses.course_id')
                ->join('users','marks.student_id','=', 'users.user_id')
                ->join('semesters', 'marks.semester_id', '=', 'semesters.semester_id')
                ->select(
                    'marks.mark_id',
                    'exams.exam_id',
                    'exams.marks as total_marks',
                    'marks.marks as given_marks',
                    'exams.exam_title',
                    'courses.course_id',
                    'courses.name as course_name',
                    'users.username',
                    'users.registration_number',
                    'users.email',
                    'users.user_id',
                    'semesters.name as semester_name',
                    'semesters.semester_id'
                )
                ->where('marks.student_id', $studentId)
                ->where('marks.exam_id', $examId)
                ->get();
    }

    public function getMarks($studentId, $examId)
    {
        return DB::table('marks')->where('marks.student_id', $studentId)->where('marks.exam_id', $examId)->get();
    }

    public function view($instructorId)
    {
        return DB::table('exams')
            ->leftJoin('courses', 'exams.course_id', '=', 'courses.course_id')
            ->join('departments', 'exams.department_id', '=', 'departments.department_id')
            ->join('profiles', 'exams.semester_id', '=', 'profiles.semester_id')
            ->join('users', 'users.user_id', '=', 'profiles.user_id')
            ->join('semesters', 'exams.semester_id', '=', 'semesters.semester_id')
            ->leftJoin('marks', function($join) {
                $join->on('marks.exam_id', '=', 'exams.exam_id')
                    ->on('marks.student_id', '=', 'profiles.user_id');
            })
            ->select(
                'exams.exam_id',
                'exams.marks as exam_marks',
                'exams.exam_title',
                'profiles.user_id',
                'courses.course_id',
                'courses.name as course_name',
                'users.username',
                'users.registration_number',
                'users.email',
                'users.user_id',
                'departments.name as department_name',
                'semesters.name as semester_name',
                'semesters.semester_id',
                'marks.marks as obtained_marks',
                'marks.gpa'
            )
            ->where('exams.instructor_id', $instructorId)
            ->orderBy('courses.course_id')
            ->orderBy('marks.marks', 'desc')
            ->get();
    }
}