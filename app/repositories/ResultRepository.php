<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ResultRepository
{   
    public function results($studentId)
    {
        return DB::table('marks')
		->join('profiles', 'marks.student_id', '=', 'profiles.user_id')
		->join('exams', 'marks.semester_id', '=', 'exams.semester_id')
		->join('courses', 'marks.course_id', '=', 'courses.course_id')
		->join('semesters', 'marks.semester_id', '=', 'semesters.semester_id')
            ->select(
                    'marks.student_id',
                    'marks.mark_id', 
                    'marks.marks', 
                    'marks.gpa',
                    'profiles.first_name', 
                    'profiles.middle_name', 
                    'profiles.last_name',
                    'profiles.session',
                    'courses.name as course_name',
                    'courses.credit',
                    'semesters.name as semester_name'
                    )
            ->where('marks.student_id', '=', $studentId)
            ->groupBy('marks.mark_id')
            ->get();
    }
	public function resultExistonNot($studentId)
    {
        return DB::table('results')->where('student_id', $studentId)->exists();
    }
	public function createResult($result)
	{
        return DB::table('results')->insert($result);
    }
	public function updateResult($studentId, $result)
	{
        return DB::table('results')->where('student_id', $studentId)->update($result);
    }

    public function showResult($studentId)
    {
        return DB::table('results')->where('student_id', $studentId)->first();
    }

    public function getSemester($studentId)
    {
        return DB::table('marks')
            ->join('semesters', 'semesters.semester_id', '=', 'marks.semester_id')
            ->select(
                'semesters.semester_id',
                'semesters.name as semester_name'
            )
            ->distinct('marks.semester_id') 
            ->where('marks.student_id', $studentId)
            ->orderBy('semesters.semester_id','asc')
            ->get();
    }
    
    public function getData($studentId, $semesterId)
    {        
        return DB::table('results')
            ->join('marks', 'results.student_id', '=', 'marks.student_id')
            ->join('semesters', 'semesters.semester_id', '=', 'marks.semester_id')
            ->join('courses', 'courses.course_id', '=', 'marks.course_id')
            ->select(
                'marks.mark_id',
                'marks.marks',
                'marks.gpa as gread',
                'semesters.name as semester_name',
                'courses.name as course_name',
                'courses.credit'
            )
            ->where('results.student_id', $studentId)
            ->where('semesters.semester_id', $semesterId)
            ->get();
    }

    public function getResult($studentId, $semesterId)
    {
        return DB::table('results')
            ->join('marks', 'results.student_id', '=', 'marks.student_id')
            ->join('semesters', 'semesters.semester_id', '=', 'marks.semester_id')
            ->join('courses', 'courses.course_id', '=', 'marks.course_id')
            ->select(
                'marks.marks',
                'marks.gpa',
                'semesters.name as semester_name',
                'courses.name as course_name'
            )
            ->where('results.student_id', $studentId)
            ->where('marks.semester_id', $semesterId)
            ->get();
    }

    public function updateStudentsSemester($studentId, $semesterId)
    {
        return DB::table('profiles')
                ->where('user_id', $studentId)
                ->update([
                    'semester_id'=> $semesterId
                ]);
    }
}
