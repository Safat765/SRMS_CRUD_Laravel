<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Mark extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'marks';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'mark_id';

    public function assignedCourses($instructorId)
    {
        $result = DB::table('exams')
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
            ->groupBy('exams.semester_id')
            ->get();
            
        return $result;
    }

    public function getStudents($instructorId, $semesterId, $courseID)
    {
        $result = DB::table('exams')
            ->leftJoin('courses', 'exams.course_id', '=', 'courses.course_id')
            ->join('departments', 'exams.department_id', '=', 'departments.department_id')
                ->join('profiles', 'exams.semester_id', '=', 'profiles.semester_id')
                    ->join('users','users.user_id','=', 'profiles.user_id')
                    ->join('semesters', 'exams.semester_id', '=', 'semesters.semester_id')
                ->select(
                    'profiles.user_id',
                    'courses.name as course_name',
                    'users.username',
                    'users.registration_number',
                    'users.email',
                    'departments.name as department_name',
                    'semesters.name as semester_name'
                )
                        ->where('exams.instructor_id', $instructorId)
                        ->where('exams.semester_id', $semesterId)
                        ->where('courses.course_id', $courseID)
                        ->get();
        // $result = DB::table('exams')
        //     ->join('courses', 'exams.course_id', '=', 'courses.course_id')
        //     ->join('departments', 'exams.department_id', '=', 'departments.department_id')
        //     ->join('profiles', 'exams.semester_id', '=', 'profiles.semester_id')
        //     ->join('semesters', 'exams.semester_id', '=', 'semesters.semester_id')
        //     ->join('users','users.user_id','=', 'profiles.user_id')
        //     ->select(
        //                 'courses.name as course_name', 
        //                 'departments.name as department_name', 
        //                 'users.user_id', 'users.username', 
        //                 'users.registration_number', 
        //                 'users.email',
        //                 'semesters.name as semester_name',                        
        //                 'semesters.semester_id'
        //             )
        //     ->where('exams.instructor_id', $instructorId)
        //     ->get();
            
        return $result;
    }
}