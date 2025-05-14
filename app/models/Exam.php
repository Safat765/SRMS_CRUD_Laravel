<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Exam extends Eloquent
{
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
	
    public static function getExamTypeConstants()
    {
        return [
            'Mid' => self::EXAM_TYPE_MID,
            'Quiz' => self::EXAM_TYPE_QUIZ,
            'Viva' => self::EXAM_TYPE_VIVA,
            'Final' => self::EXAM_TYPE_FINAL
        ];
    }
}