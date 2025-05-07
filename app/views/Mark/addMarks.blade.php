@extends('layout.main')
@push("title")
<title>Course List</title>
@section('main')
    <div class="table-responsive pt-5">
        <div class="bg-warning  text-black text-center mx-5">
            <h5>Total Course : {{ $totalCourse }}</h5>
        </div>
        <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
            <thead>
                <tr>
                    <td scope="row" colspan="4" class="fw-bold fs-3 text-info">Courses Assigned for marks</td>
                </tr>
                <tr>
                    <th scope="col">Course</th>
                    <th scope="col">Department</th>
                    <th scope="col">Semester</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($userType != 2) {
                ?>
                        <tr>
                            <td scope="row" colspan="3">You are not authorized to view this page</td>
                        </tr>
                <?php
                    } else {
                ?>
                    @foreach ($results as $result)
                        <tr>
                            <td scope="row">{{$result->course_name}}</td>
                            <td scope="row">{{$result->department_name}}</td>
                            <td scope="row">{{$result->semester_name}}</td>
                            <td>
                                <div class="d-flex justify-content-center" style="display: inline-block;">
                                    {{ Form::open(['url' => URL::to('/instructor/marks/students/' . $result->course_id . '/' . $result->semester_id), 'method' => 'get']) }}
                                        <div class="text-center">
                                            {{ Form::button(HTML::decode('<i class="las la-eye"></i>'), [
                                                'class' => 'btn btn-info btn-sm',
                                                'type' => 'submit'
                                            ])}}
                                        </div>
                                    {{ Form::close() }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
@endsection