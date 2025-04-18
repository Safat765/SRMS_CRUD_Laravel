@extends('layout.main')
@push("title")
    <title>Marks Edit</title>
@endpush
@section('main')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            @foreach ($records as $records)
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center fw-bold text-info">{{ $pageName }} for {{ $records->username }}</h4>
                </div>
                <div class="card-body bg-light">
                    {{ Form::open(['url' => 'marks/'.$records->mark_id, 'method' => 'patch', 'novalidate' => true]) }}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                {{ Form::hidden('totalMarks', isset($records->total_marks) ? $records->total_marks : null) }}
                                {{ Form::hidden('username', isset($records->username) ? $records->username : null) }}
                                {{ Form::hidden('courseName', isset($records->course_name) ? $records->course_name : null) }}

                                {{ Form::label('givenMark', 'Mark', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('givenMark', isset($records->given_marks) ? $records->given_marks : null, 
                                    [
                                    'class' => 'form-control shadow-lg',
                                    'placeholder' => 'Enter mark out of '. $records->total_marks,
                                    'required' => true
                                    ], Input::old('givenMark')
                                )}}
                                @if($errors->has('givenMark'))
                                <span class="text-danger small d-block mt-1">
                                    {{ $errors->first('givenMark') }}
                                </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                {{ Form::label('courseName', 'Course', ['class' => 'form-label']) }}
                                <span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('courseName', isset($records->course_name) ? $records->course_name : null,
                                    [
                                    'class' => 'form-control shadow-lg fw-bold',
                                    'required' => true,
                                    'readonly' => true
                                    ]
                                )}}
                            </div>
                        </div>
                        <br>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                {{ Form::label('semesterName', 'Semester', ['class' => 'form-label']) }}
                                <span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('semesterName', isset($records->semester_name) ? $records->semester_name : null,
                                    [
                                    'class' => 'form-control shadow-lg fw-bold',
                                    'required' => true,
                                    'readonly' => true
                                    ]
                                )}}
                                <br>
                            </div>
                            <div class="col-md-6">
                                {{ Form::label('examTitle', 'Exam Title', ['class' => 'form-label']) }}
                                <span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('examTitle', isset($records->exam_title) ? $records->exam_title : null,
                                    [
                                    'class' => 'form-control shadow-lg fw-bold',
                                    'required' => true,
                                    'readonly' => true
                                    ]
                                )}}
                                <br>
                            </div>
                        </div>
                    <div class="d-grid gap-2">
                        {{ Form::submit('Update', 
                            [
                            'class' => 'btn btn-primary btn-block'
                            ]
                        )}}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>        
            @endforeach
        </div>
    </div>
</div>
@endsection