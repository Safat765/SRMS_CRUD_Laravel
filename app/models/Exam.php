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

	public function searchName($search, $semester)
	{
		$exist = Semester::where('exam_title', 'LIKE', $search)
							->where('semester', 'LIKE', $semester)->exists();
		
		if ($exist) {
			return true;
		}

		return false;
	}

    public function createExam(array $data, $createdBy)
    {
		$exam = DB::table('exams')->insert(
			array('course_id' => $data['courseId'], 'department_id' => $data['departmentId'], 'semester_id' => $data['semesterId'], 'exam_title' => $data['examTitle'], 'exam_type' => $data['examType'], 'credit' => $data['credit'], 'marks' => $data['marks'], 'instructor_id' => $data['instructorId'], 'created_by' => $createdBy)
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
		$examCount = Exam::where('examTitle', 'LIKE', '%' . $search . '%')
		->orWhere('credit', 'LIKE', '%' . $search . '%');
		
		$totalExams = $examCount->count();
		$exams = $examCount->paginate(5);
		
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
	
	public function edit($id)
	{
		$exam = Exam::find($id);
		return $exam;
	}
	
	public function updateExam(array $data, $exam_id)
	{		
		$exam = $this->edit($exam_id);
		
		if (!$exam) {
			return false;
		}
        $exam->fill($data);
		$exam->save();
		
		return $exam;
	}
	
	public function deleteExam($id)
	{
		$exam = $this->edit($id);
		
		if (!$exam) {
			return false;
		}
		
		$exam->delete();
		
		return $exam;
	}


}