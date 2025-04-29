<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

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
	
	public function searchName($courseId, $departmentID, $semesterID, $examType) {
		$exist = Exam::where('exam_type', 'LIKE', $examType)
		->where('course_id', 'LIKE', $courseId)
		->where('department_id', 'LIKE', $departmentID)
		->where('semester_id', 'LIKE', $semesterID)->exists();
		
		return $exist;
	}
	
	public function createExam(array $data, $createdBy)
	{
		$exam = DB::table('exams')->insert(
			array(
				'course_id' => $data['courseId'], 
				'department_id' => $data['departmentId'], 
				'semester_id' => $data['semesterId'], 
				'exam_title' => $data['examTitle'], 
				'exam_type' => $data['examType'], 
				'credit' => $data['credit'], 
				'marks' => $data['marks'], 
				'instructor_id' => $data['instructorId'], 
				'created_by' => $createdBy
			)
		);
		
		// $exam = new Exam();
		// $exam->course_id = $data['courseId'];
		// $exam->department_id = $data['departmentId'];
		// $exam->semester_id = $data['semesterId'];
		// $exam->exam_title = $data['examTitle'];
		// $exam->exam_type = $data['examType'];
		// $exam->credit = $data['credit'];
		// $exam->marks = $data['marks'];
		// $exam->instructor_id = $data['instructorId'];
		// $exam->created_by = $createdBy;
		// $exam->save();
		
		return $exam;
	}
	
	public function filter($search)
	{
		// echo $search;

		$exams = DB::table('exams')
		->join('courses', 'exams.course_id', '=', 'courses.course_id')
		->join('departments', 'exams.department_id', '=', 'departments.department_id')
		->join('semesters', 'exams.semester_id', '=', 'semesters.semester_id')
		->join('users', 'exams.instructor_id', '=', 'users.user_id') // Fixed: should match users PK
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
			->get();
		// p($exams);
		$totalExams = count($exams);
		// echo $totalExams;
		// $exams = Exam::paginate(5);
		
		$data = compact('exams', 'totalExams');
		return $data;
	}
	
	public function showAll()
	{
		$examCount = Exam::all();
		$totalExams = $examCount->count();
		$exams = Exam::paginate(5);		
		$data = compact('exams', 'totalExams');
		
		return $data;
	}
	
	public function ediFind($id)
	{
		$exam = Exam::find($id);
		return $exam;
	}
	
	public function updateExam(array $data, $exam_id)
	{
		// Find the existing exam
		$exist = $this->ediFind($data['examId']);
		echo $exist;
		
		if (!$exist) {
			return false;
		}

		p($data);
		
		// Update the existing exam's properties
		$exam = DB::table('exams')
			->where('exam_id', $data['examId'])
				->update([
					'course_id' => $data['courseId'],
					'department_id' => $data['departmentId'],
					'semester_id' => $data['semesterId'],
					'exam_title' => $data['examTitle'],
					'credit' => $data['credit'],
					'exam_type' => $data['examType'],
					'marks' => $data['marks'],
					'instructor_id' => $data['instructorId']
        ]);
		
		return $exam;
	}
	
	public function deleteExam($id)
	{
		$exam = $this->ediFind($id);
		
		if (!$exam) {
			return false;
		}
		
		$exam->delete();
		
		return $exam;
	}
	
	public function joinTables()
	{
		$results = DB::table('exams')
		->join('courses', 'exams.course_id', '=', 'courses.course_id')
		->join('departments', 'exams.department_id', '=', 'departments.department_id')
		->join('semesters', 'exams.semester_id', '=', 'semesters.semester_id')
		->join('users', 'exams.instructor_id', '=', 'users.user_id') // Fixed: should match users PK
		->select(
			'users.user_id as instructor_id',
			'users.username',
			'departments.department_id',
			'departments.name as department_name',
			'semesters.semester_id',
			'semesters.name as semester_name',
			'courses.course_id',
			'courses.name as course_name',
			// 'exams.exam_id',
			// 'exams.exam_title',
			// 'exams.semester_id',
			// 'exams.department_id',
			// 'exams.course_id',
			// 'exams.exam_type',
			// 'exams.credit',
			// 'exams.marks'
			'exams.*'
			)->get();
		
		
		$totalExams = count($results);
		// echo $totalExams;
		
		$data = compact('results', 'totalExams');

		return $data;
	}
}