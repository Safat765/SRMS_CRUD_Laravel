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

}