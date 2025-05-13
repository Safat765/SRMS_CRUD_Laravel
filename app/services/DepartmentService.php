<?php

namespace App\Services;

use App\Repositories\DepartmentRepository;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class DepartmentService
{
    private $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function getAll($search)
    {
        if ($search != '') {
            $result = $this->departmentRepository->filter($search);
        } else {
			$result = $this->departmentRepository->showAll();
        }
        $totalDepartment = $result['departmentCount']->count();
        $department = $result['departmentPaginate'];

        return [
            'department' => $department,
            'totalDepartment' => $totalDepartment,
            'search' => $search
        ];
    }

    public function checkValidation(array $data)
    {
        return Validator::make($data, [
			'name' => 'required|min:2|unique:departments'
		], [
			'required' => 'The Department field is required.',
			'min' => 'The Department must be at least :min characters.'
		]);
    }

    public function store(array $data)
    {
        $name = $data['name'];

        if ($this->departmentRepository->searchName($name)) {
            return false;
        }

        return $this->departmentRepository->create([
            'name' => $name,
            'created_by' => Session::get('user_id'),
            'created_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s'),
            'updated_at' => ""
        ]);
    }

    public function checkById($id)
    {
        return $this->departmentRepository->find($id);
    }

    public function updateValidation(array $data, $id)
    {
        return Validator::make($data, [
            'name' => 'required|min:3|unique:departments,name,'.$id.',department_id'
        ], [
            'required' => 'The Department field is required.',
            'min' => 'The Department must be at least :min characters.'
        ]);
    }

    public function checkByName($name)
    {
        return $this->departmentRepository->searchName($name);
    }

    public function update(array $data, $id)
    {
        if ($this->departmentRepository->find($id)) {

            return $this->departmentRepository->update([
                'name' => $data['name'],
                'updated_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s')
            ], $id);
        } else {
            return false;
        }
    }

    public function destroy($id)
    {
        if ($this->departmentRepository->find($id)) {
            return $this->departmentRepository->delete($id);
        } else {
            return false;
        }
    }
}
