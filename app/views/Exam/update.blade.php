@extends('layout.main')
@push("title")
    <title>User Update</title>
@endpush
@section('main')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center fw-bold text-info">{{ $pageName }}</h4>
                </div>
                <div class="card-body bg-light">
                    {{ Form::open(['url' => $url, 'method' => 'patch', 'novalidate' => true]) }}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            {{ Form::label('courseId', 'Course Name', ['class' => 'form-label']) }}
                            <span style="color: red; font-weight: bold;"> *</span>
                            
                            {{ Form::select('courseId', 
                                ['' => 'Select course'] + Course::lists('name', 'course_id'),
                                Input::old('courseId', isset($exams->course_id) ? $exams->course_id : null), 
                                [
                                    'class' => 'form-control shadow-lg',
                                    'required' => 'required',
                                ]
                            ) }}
                            
                            @if($errors->has('courseId'))
                                <span class="text-danger small d-block mt-1">
                                    {{ $errors->first('courseId') }}
                                </span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            {{ Form::label('examTitle', 'Exam Title', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('examTitle', isset($exams->exam_title) ? $exams->exam_title : null,
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter exam Title',
                                'required' => true
                                ],  Input::old('examTitle')
                            )}}
                            @if($errors->has('examTitle'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('examTitle') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            {{ Form::label('departmentId', 'Department Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('departmentId', 
                                ['' => 'Select Department'] + Department::lists('name', 'department_id'),
                                Input::old('departmentId', isset($exams->department_id) ? $exams->department_id : null), [
                                    'class' => 'form-control shadow-lg',
                                    'required' => true
                                ],
                                [
                                    '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                ]
                            )}}
                            @if($errors->has('departmentId'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('departmentId') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{ Form::label('semesterId', 'Semester Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('semesterId', 
                                ['' => 'Select Department'] + Semester::lists('name', 'semester_id'),
                                Input::old('semesterId', isset($exams->semester_id) ? $exams->semester_id : null), [
                                    'class' => 'form-control shadow-lg',
                                    'required' => true
                                ],
                                [
                                    '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                ]
                            )}}
                            @if($errors->has('semesterId'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('semesterId') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            {{ Form::label('credit', 'Credit', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('credit', isset($exams->credit) ? $exams->credit : null, 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter exam Title',
                                'required' => true
                                ], Input::old('credit')
                            )}}
                            @if($errors->has('credit'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('credit') }}
                            </span>
                            @endif
                        </div>
                    </div> 
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{ Form::label('marks', 'Marks', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('marks', isset($exams->marks) ? $exams->marks : null, 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter Total Marks for exam',
                                'required' => true
                                ], Input::old('marks')
                            )}}
                            @if($errors->has('marks'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('marks') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            {{ Form::label('instructorId', 'Instructor Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('instructorId', 
                                ['' => 'Select Department'] + User::where('user_type', 2)->lists('username', 'user_id'),
                                Input::old('instructorId', isset($exams->instructor_id) ? $exams->instructor_id : null), [
                                    'class' => 'form-control shadow-lg',
                                    'required' => true
                                ],
                                [
                                    '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                ]
                            )}}
                            @if($errors->has('instructorId'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('instructorId') }}
                            </span>
                            @endif
                        </div>
                    </div>
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