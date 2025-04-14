@extends('layout.main')
@push("title")
    <title>Exam Create</title>
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
                    {{ Form::open(['url' => $url, 'method' => 'post', 'novalidate' => true]) }}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            {{ Form::label('courseId', 'Course Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('courseId', 
                                ['' => 'Select course'] + Course::lists('name', 'course_id'),
                                Input::old('courseId', ''), [
                                    'class' => 'form-control shadow-lg',
                                    'required' => true
                                ],
                                [
                                    '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                ]
                            )}}
                            @if($errors->has('courseId'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('courseId') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            {{ Form::label('examTitle', 'Exam Title', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('examTitle', Input::old('examTitle'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter exam Title',
                                'required' => true
                                ]
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
                                Input::old('departmentId', ''), [
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
                                Input::old('semesterId', ''), [
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
                            {{ Form::text('credit', Input::old('credit'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter exam Title',
                                'required' => true
                                ]
                            )}}
                            @if($errors->has('credit'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('credit') }}
                            </span>
                            @endif
                        </div>
                    </div> 
                    <div class="row mb-3">
                        <div class="col-md-4">
                            {{ Form::label('examType', 'Exam Type', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('examType', 
                                [
                                    '' => 'Select Exam type',
                                    $examType['Mid'] => 'Mid',
                                    $examType['Quiz'] => 'Quiz',
                                    $examType['Viva'] => 'Viva',
                                    $examType['Final'] => 'Final'
                                ], 
                                Input::old('examType'), [
                                    'class' => 'form-control shadow-lg',
                                    'required' => true
                                ],
                                [
                                    '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                ]
                            )}}
                            @if($errors->has('examType'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('examType') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            {{ Form::label('marks', 'Marks', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('marks', Input::old('marks'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter Total Marks for exam',
                                'required' => true
                                ]
                            )}}
                            @if($errors->has('marks'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('marks') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            {{ Form::label('instructorId', 'Instructor Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('instructorId', 
                                ['' => 'Select Department'] + User::where('user_type', 2)->lists('username', 'user_id'),
                                Input::old('instructorId', ''), [
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