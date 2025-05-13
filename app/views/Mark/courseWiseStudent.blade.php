@extends('layout.main')
@push("title")
<title>Course List</title>
@section('main')
<div class="table-responsive pt-5" id="marksIndex">
    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
        <thead>
            <tr>
                <td scope="row" colspan="5" class="fw-bold fs-3 text-info">View Marks according to Course</td>
            </tr>
            <tr>
                <th scope="col">Students</th>
                <th scope="col">Registration Number</th>
                <th scope="col">Department Name</th>
                <th scope="col">Semester</th>
                <th scope="col">Marks</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($data['groupedResults'] as $courseName => $students)
            <tr>
                <th colspan="5" class="bg-light text-danger fs-4">{{ $courseName }}</th>
            </tr>
            @foreach ($students as $result)
                <tr>
                    <td class="p-2">{{ $result->username }}</td>
                    <td class="p-2">{{ $result->registration_number }}</td>
                    <td class="p-2">{{ $result->department_name }}</td>
                    <td class="p-2">{{ $result->semester_name }}</td>
                    <td class="p-2">
                        @if (isset($result->username) && isset($result->obtained_marks))
                            {{ $result->obtained_marks }} / {{ $result->exam_marks }}
                        @else
                            <div class="text-center">
                                {{ Form::button(HTML::decode('<i class="las la-plus"></i>'), [
                                    'class' => 'btn btn-success btn-sm',
                                    'type' => 'button',
                                    'id' => 'marksCreate',
                                    'data-bs-toggle' => 'modal',
                                    'data-bs-target' => '#createMarksModal',
                                    'data-studentid' => $result->user_id,
                                    'data-username' => $result->username,
                                    'data-courseid' => $result->course_id,
                                    'data-cname' => $result->course_name,
                                    'data-depName' => $result->department_name,
                                    'data-examid' => $result->exam_id,
                                    'data-etitle' => $result->exam_title,
                                    'data-semesterid' => $result->semester_id,
                                    'data-semname' => $result->semester_name,
                                    'data-marks' => $result->exam_marks,
                                ]) }}
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
</div>

@include('mark.createModal')
@endsection