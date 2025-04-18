@extends('layout.main')
@push("title")
    <title>Marks Create</title>
@endpush
@section('main')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center fw-bold text-info">{{ $pageName }} for {{ $records['username'] }}</h4>
                </div>
                <div class="card-body bg-light">
                    {{ Form::open(['url' => 'marks/go', 'method' => 'post', 'novalidate' => true]) }}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                {{ Form::label('givenMark', 'Mark', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('givenMark', Input::old('givenMark'), 
                                    [
                                    'class' => 'form-control shadow-lg',
                                    'placeholder' => 'Enter mark out of '. $records['marks'],
                                    'required' => true
                                    ]
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
                                {{ Form::text('courseName', isset($records['courseName']) ? $records['courseName'] : null,
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
                            <div class="col-md-4">
                                {{ Form::label('departmentName', 'Department', ['class' => 'form-label']) }}
                                <span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('departmentName', isset($records['departmentName']) ? $records['departmentName'] : null,
                                    [
                                    'class' => 'form-control shadow-lg fw-bold',
                                    'required' => true,
                                    'readonly' => true
                                    ]
                                )}}
                                <br>
                            </div>
                            <div class="col-md-4">
                                {{ Form::label('semesterName', 'Semester', ['class' => 'form-label']) }}
                                <span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('semesterName', isset($records['semesterName']) ? $records['semesterName'] : null,
                                    [
                                    'class' => 'form-control shadow-lg fw-bold',
                                    'required' => true,
                                    'readonly' => true
                                    ]
                                )}}
                                <br>
                            </div>
                            <div class="col-md-4">
                                {{ Form::label('examTitle', 'Exam Title', ['class' => 'form-label']) }}
                                <span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('examTitle', isset($records['examTitle']) ? $records['examTitle'] : null,
                                    [
                                    'class' => 'form-control shadow-lg fw-bold',
                                    'required' => true,
                                    'readonly' => true
                                    ]
                                )}}
                                <br>
                            </div>
                        </div>                        
                        {{ Form::hidden('studentId', isset($records['studentId']) ? $records['studentId'] : null) }}
                        {{ Form::hidden('courseId', isset($records['courseId']) ? $records['courseId'] : null) }}
                        {{ Form::hidden('examId', isset($records['examId']) ? $records['examId'] : null) }}
                        {{ Form::hidden('semesterId', isset($records['semesterId']) ? $records['semesterId'] : null) }}
                        {{ Form::hidden('totalMarks', isset($records['marks']) ? $records['marks'] : null) }}
                        {{ Form::hidden('username', isset($records['username']) ? $records['username'] : null) }}

                    <div class="d-grid gap-2">
                        {{ Form::submit('Create', 
                            [
                            'class' => 'btn btn-primary btn-block'
                            ]
                        )}}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection