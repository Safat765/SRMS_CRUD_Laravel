<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class Course extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'courses';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'course_id';
	protected $fillable = [
		'name',
        'status',
        'credit',
        'created_by',
		'created_at', 
		'updated_at'
	];

	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;

	public function searchName($name)
	{
		$exist = Course::where('name', 'LIKE', $name)->exists();
		
		if ($exist) {
			return true;
		}

		return false;
	}

    public function createCourse(array $data)
	{
		$exist = $this->searchName($data['name']);
		
		if ($exist) {
			return false;
		}
		$course = new Course();
		
		$course->name = $data['name'];
        $course->credit = $data['credit'];
		$course->created_by = 1;
		$course->created_at = Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s');
		$course->updated_at = "";
		$course->save();

		return true;
	}

    public function filter($search)
	{
		$courseCount = Course::where('name', 'LIKE', '%' . $search . '%')
							->orWhere('credit', 'LIKE', '%' . $search . '%');
		
		$totalCourse = $courseCount->count();
		$course = $courseCount->paginate(5);
		
		$data = compact('course', 'totalCourse');
		return $data;
	}

    public function showAll()
	{
		$courseCount = Course::all();
		$totalCourse = $courseCount->count();
		$course = Course::paginate(5);		
		$data = compact('course', 'totalCourse');

		return $data;
	}

    public function edit($id)
	{
		$course = Course::find($id);
		return $course;
	}
	
	public function updateCourse(array $data, $course_id)
	{		
		$course = $this->edit($course_id);
		
		if (!$course) {
			return false;
		}
		$course->name = $data['name'];
        $course->credit = $data['credit'];
		$course->updated_at = Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s');
		$course->save();
		
		return $course;
	}
	
	public function deleteCourse($id)
	{
		$course = $this->edit($id);
		
		if (!$course) {
			return false;
		}
		
		$course->delete();
		
		return $course;
	}
	
	public function statusUpdate($id)
	{
		$course = $this->edit($id);
		
		if (!$course) {
			return false;
		}
		
		if ($course->status == self::STATUS_ACTIVE) {
			$course->status = self::STATUS_INACTIVE;
			Session::flash('message', 'Course Inactive successfully');
		} else {
			$course->status = self::STATUS_ACTIVE;
			Session::flash('success', 'Course activated successfully');
		}
		
		$course->save();
		
		return $course;
	}
}