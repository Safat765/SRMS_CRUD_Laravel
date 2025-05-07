@extends('layout.main')
@push("title")
<title>Assigned Course</title>
@section('main')
<div class="table-responsive pt-5" id="marksIndex">
    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
        <thead>
            <tr>
                <td scope="row" colspan="4" class="fw-bold fs-3 text-info">Assigned Course</td>
            </tr>
            <tr>
                <th scope="col">Instructor Name</th>
                <th scope="col">Registration Number</th>
                <th scope="col">Course Name</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($getCourses as $semesterName => $instructor)
            <tr>
                <th colspan="4" class="bg-light text-warning fs-5 p-2 text-center">{{ $semesterName }}</th>
            </tr>
            @foreach ($instructor as $course)
                <tr>
                    <td class="p-2">{{ $course->username }}</td>
                    <td class="p-2">{{ $course->registration_number }}</td>
                    <td class="p-2">{{ $course->course_name }}</td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
</div>

@include('mark.createModal')
@endsection