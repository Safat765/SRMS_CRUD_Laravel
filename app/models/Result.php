<?php

namespace App\Models;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Result extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'results';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'result_id';

    public function results($studentId)
    {
        $results = DB::table('marks')
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

        return $results;
    }
	public function resultExistonNot($studentId)
    {
        return DB::table('results')->where('student_id', $studentId)->exists();
    }
	public function createResult($studentId, $cgpa)
	{
		$result = DB::table('results')->insert(
			array(
                'student_id'=> $studentId,
				'cgpa' => $cgpa
			)
		);
        
        return $result;
    }
	public function updateResult($studentId, $cgpa)
	{
		$result = DB::table('results')->where('student_id', $studentId)->update(['cgpa' => $cgpa]);
        
        return $result;
    }

    public function showResult($studentId)
    {
        $results = DB::table('results')->where('student_id', $studentId)->first();

        return $results;
    }

    public function getSemester($studentId)
    {
        $result = DB::table('marks')
            ->join('semesters', 'semesters.semester_id', '=', 'marks.semester_id')
            ->select(
                'semesters.semester_id',
                'semesters.name as semester_name'
            )
            ->distinct('marks.semester_id') 
            ->where('marks.student_id', $studentId)
            ->orderBy('semesters.semester_id','asc')
            ->get();

        return $result;
    }
    
    public function getData($studentId, $semesterId)
    {        
        $results = DB::table('results') // Fixed table name (was 'result')
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

        return $results;
    }

    public function getResult($studentId, $semesterId)
    {
        $results = DB::table('results') // Fixed table name (was 'result')
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

        return $results;
    }

    public function updateStudentsSemester($studentId, $semesterId)
    {
        $result = DB::table('profiles')
                ->where('user_id', $studentId)
                ->update([
                    'semester_id'=> $semesterId
                ]);
                
        return $result;
    }

}