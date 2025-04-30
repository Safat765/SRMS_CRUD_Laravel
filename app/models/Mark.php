<?php

namespace App\Models;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function existOrNot($studentId, $examId)
    {
        $exist = Mark::where('student_id', 'LIKE', $studentId)
                        ->where('exam_id', 'LIKE', $examId)
                        ->exists();
        
        return $exist;
    }

    public function marksExistOrNot($markId)
    {
        $exist = Mark::where('mark_id', 'LIKE', $markId)
                        ->exists();
        
        return $exist;
    }

    public function marksExistOrNotForDelete($studentId, $examId)
    {
        $exist = Mark::where('student_id', 'LIKE', $studentId)
                        ->where('exam_id', 'LIKE', $examId)
                        ->exists();
        
        return $exist;
    }

    public function updateMarks($marksId, $marks, $gpa)
    {
        $result = DB::table('marks')
            ->where('mark_id', $marksId)
            ->update(
                array(
                    'marks' => $marks, 
                    'gpa' => $gpa,
                    'updated_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s')
                )
            );
        
        return $result;
    }
	
	public function createMarks(array $data, $gpa)
	{
		$result = DB::table('marks')->insert(
			array(
                'student_id'=> $data['studentId'],
                'exam_id' => $data['examId'],
				'course_id' => $data['courseId'],
				'semester_id' => $data['semesterId'],
				'marks' => $data['givenMark'], 
				'gpa' => $gpa,
                'created_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s'),
                'updated_at' => ''
			)
		);
        
        return $result;
    }

    public function deleteMarks($studentId, $examId)
    {
        $result = DB::table('marks')
            ->where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->delete();
        
        return $result;
    }

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
            ->groupBy('exams.course_id')
            ->orderBy('exams.semester_id', 'asc')
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
            
        return $result;
    }

    public function editMarks($studentId, $examId)
    {
        $result = DB::table('marks')
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
            
        return $result;
    }

    public function getMarks(array $studentIds, $examId)
    {
        $results = [];
        foreach ($studentIds as $studentId) {
            $result = DB::table('marks')
                    ->where('marks.student_id', $studentId)
                    ->where('marks.exam_id', $examId)
                    ->get();
            $results[$studentId] = $result;
        }
        return $results;
    }

    public function viewMarks($instructorId)
    {
        $result = DB::table('exams')
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
                    'exams.marks as exam_marks', // exam marks (total marks maybe)
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
                    'marks.marks as obtained_marks', // student's marks
                    'marks.gpa'
                )
                ->where('exams.instructor_id', $instructorId)
                ->orderBy('courses.course_id')
                ->get();

        return $result;
    }


}