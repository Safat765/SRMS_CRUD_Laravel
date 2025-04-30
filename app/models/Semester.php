<?php

namespace App\Models;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Carbon\Carbon;

class Semester extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'semesters';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'semester_id';
	protected $fillable = [
		'name',
        'created_by'
	];

	public function searchName($name)
	{
		$exist = Semester::where('name', 'LIKE', $name)->exists();
		
		if ($exist) {
			return true;
		}

		return false;
	}

    public function createSemester($name)
	{
		$exist = $this->searchName($name);
		
		if ($exist) {
			return false;
		}
		$semester = new Semester();
		
		$semester->name = $name;
		$semester->created_by = 1;
		$semester->created_at = Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s');
		$semester->updated_at = "";
		$semester->save();

		return true;
	}

    public function filter($search)
	{
		$semesterCount = Semester::where('name', 'LIKE', '%' . $search . '%');
		
		$totalSemester = $semesterCount->count();
		$semester = $semesterCount->paginate(5);
		
		$data = compact('semester', 'totalSemester');
		return $data;
	}

    public function showAll()
	{
		$semesterCount = Semester::all();
		$totalSemester = $semesterCount->count();
		$semester = Semester::paginate(5);		
		$data = compact('semester', 'totalSemester');

		return $data;
	}

    public function edit($id)
	{
		$semester = Semester::find($id);
		return $semester;
	}
	
	public function updateSemester(array $data, $semester_id)
	{		
		$semester = $this->edit($semester_id);
		
		if (!$semester) {
			return false;
		}
		$semester->name = $data['name'];
		$semester->updated_at = Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s');
		$semester->save();
		
		return $semester;
	}
	
	public function deleteSemester($id)
	{
		$semester = $this->edit($id);
		
		if (!$semester) {
			return false;
		}
		
		$semester->delete();
		
		return $semester;
	}

}