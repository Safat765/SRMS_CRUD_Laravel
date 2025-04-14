<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Exam extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    const EXAM_TYPE_MID = 1;
    const EXAM_TYPE_QUIZ = 2;
    const EXAM_TYPE_VIVA = 3;
    const EXAM_TYPE_FINAL = 4;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'exams';
    protected $fillable = [
        'course_id', 
        'department_id',
        'semester_id',
        'exam_title',
        'exam_type',
        'credit',
        'marks',
        'instructor_id',
        'created_by'
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'exam_id';

    public function course()
    {
        return $this->belongsTo('Course', 'course_id');
    }

    public function department()
    {
        return $this->belongsTo('Department', 'department_id');
    }

    public function semester()
    {
        return $this->belongsTo('Semester', 'semester_id');
    }

    public function instructor()
    {
        return $this->belongsTo('User', 'instructor_id');
    }

    public function creator()
    {
        return $this->belongsTo('User', 'created_by');
    }

    public function join()
    {
        $allTables = Exam::with(['course', 'department', 'semester', 'user', 'instructor', 'creator'])->get();
        p($allTables);
        foreach ($allTables as $exam) {
           echo $exam->course_name = $exam->course->name;
           echo $exam->department_name = $exam->department->name;
           echo $exam->semester_name = $exam->semester->name;
        }
        // return $allTables;
    }
}