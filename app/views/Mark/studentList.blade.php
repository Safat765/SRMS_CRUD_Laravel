@extends('layout.main')

@push("title")
<title>Student List</title>
@section('main')

<div class="table-responsive pt-5">
    <div class="bg-warning text-black text-center mx-5">
        <h5>Total Students : {{ $totalStudent }}</h5>
    </div>

    <a href="{{ URL::route('marks.index') }}" class="col-md-1 btn btn-danger">Back</a>
    <hr>

    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
        <thead>
            <tr class="bg-info text-white">
                <td scope="row" colspan="6" class="fw-bold fs-3 text-center text-info">
                    Students of Course :
                    @foreach ($results as $result)
                        {{ $result->course_name }}
                        <?php break; ?>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>Name</th>
                <th>Registration Number</th>
                <th>Email</th>
                <th>Department</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results as $result)
                <tr>
                    <td>{{ $result->username }}</td>
                    <td>{{ $result->registration_number }}</td>
                    <td>{{ $result->email }}</td>
                    <td>{{ $result->department_name }}</td>
                    <td>{{ $result->semester_name }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2" style="display: inline-block;">
                            @if (!empty($marks[$result->user_id]))
                                {{-- View --}}
                                {{ Form::open(['url' => '/marks/'.$result->user_id, 'method' => 'get']) }}
                                {{ Form::hidden('examId', $result->exam_id) }}
                                    <div class="text-center">
                                        {{ Form::button(HTML::decode('<i class="las la-eye"></i>'), [
                                            'class' => 'btn btn-info btn-sm',
                                            'type' => 'submit'
                                        ])}}
                                    </div>
                                {{ Form::close() }}

                                {{-- Add --}}
                                <div class="text-center">
                                    {{ Form::button('<i class="las la-plus"></i>', [
                                        'class' => 'btn btn-success btn-sm',
                                        'disabled' => 'disabled'
                                    ]) }}
                                </div>

                                {{-- Edit --}}
                                {{ Form::open(['url' => '/marks/'.$result->user_id.'/edit', 'method' => 'get']) }}
                                    {{ Form::hidden('examId', $result->exam_id) }}
                                    <div class="text-center">
                                        {{ Form::button(HTML::decode('<i class="las la-edit"></i>'), [
                                            'class' => 'btn btn-warning btn-sm',
                                            'type' => 'submit'
                                        ])}}
                                    </div>
                                {{ Form::close() }}

                                {{-- Delete --}}
                                {{ Form::open(['url' => '/marks/'.$result->user_id, 'method' => 'delete']) }}
                                {{ Form::hidden('username', isset($result->username) ? $result->username : null) }}
                                {{ Form::hidden('courseName', isset($result->course_name) ? $result->course_name : null) }}
                                {{ Form::hidden('examId', isset($result->exam_id) ? $result->exam_id : null) }}
                                    <div class="text-center">
                                        {{ Form::button(HTML::decode('<i class="las la-trash-alt"></i>'), [
                                            'class' => 'btn btn-danger btn-sm',
                                            'type' => 'submit'
                                        ])}}
                                    </div>
                                {{ Form::close() }}
                            @else
                                {{-- Disabled buttons --}}
                                <div class="text-center">
                                    {{ Form::button('<i class="las la-eye"></i>', [
                                        'class' => 'btn btn-info btn-sm',
                                        'disabled' => 'disabled'
                                    ]) }}
                                </div>
                                
                                {{ Form::open(['url' => '/marks/add', 'method' => 'post']) }}
                                    {{ Form::hidden('studentId', $result->user_id) }}
                                    {{ Form::hidden('username', $result->username) }}
                                    {{ Form::hidden('courseId', $result->course_id) }}
                                    {{ Form::hidden('courseName', $result->course_name) }}
                                    {{ Form::hidden('departmentName', $result->department_name) }}
                                    {{ Form::hidden('examId', $result->exam_id) }}
                                    {{ Form::hidden('examTitle', $result->exam_title) }}
                                    {{ Form::hidden('semesterId', $result->semester_id) }}
                                    {{ Form::hidden('semesterName', $result->semester_name) }}
                                    {{ Form::hidden('marks', $result->marks) }}
                                    <div class="text-center">
                                        {{ Form::button(HTML::decode('<i class="las la-plus"></i>'), [
                                            'class' => 'btn btn-success btn-sm',
                                            'type' => 'submit'
                                        ])}}
                                    </div>
                                {{ Form::close() }}
                                <div class="text-center">
                                    {{ Form::button('<i class="las la-edit"></i>', [
                                        'class' => 'btn btn-warning btn-sm',
                                        'disabled' => 'disabled'
                                    ]) }}
                                </div>
                                <div class="text-center">
                                    {{ Form::button('<i class="las la-trash-alt"></i>', [
                                        'class' => 'btn btn-danger btn-sm',
                                        'disabled' => 'disabled'
                                    ]) }}
                                </div>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<br><hr><hr><br><br>
@endsection
