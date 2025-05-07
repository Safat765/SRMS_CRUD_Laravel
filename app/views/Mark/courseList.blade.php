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
                    <td scope="row" colspan="4" class="fw-bold fs-3 text-info">All Assigned Courses</td>
                </tr>
                <tr>
                    <th scope="col">Course</th>
                    <th scope="col">Department</th>
                    <th scope="col">Semester</th>
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
                            <td scope="row" class="p-3">{{$result->course_name}}</td>
                            <td scope="row" class="p-3">{{$result->department_name}}</td>
                            <td scope="row" class="p-3">{{$result->semester_name}}</td>
                        </tr>
                    @endforeach
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
@endsection