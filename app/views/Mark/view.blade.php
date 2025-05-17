@extends('layout.main')
@push("title")
    <title>Marks View</title>
@endpush
@section('main')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            @foreach ($records as $record)
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center fw-bold text-info">{{ $pageName }} for {{ $record->username }}</h4>
                </div>
                <div class="card-body bg-light">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{ Form::label('givenMark', 'Mark', ['class' => 'form-label']) }}
                            {{ Form::text('givenMark', isset($record->given_marks) ? $record->given_marks : null, 
                                [
                                'class' => 'form-control shadow-lg fw-bold',
                                'required' => true,
                                'readonly' => true
                                ]
                            )}}
                        </div>
                        <div class="col-md-6">
                            {{ Form::label('courseName', 'Course', ['class' => 'form-label']) }}
                            {{ Form::text('courseName', isset($record->course_name) ? $record->course_name : null,
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
                            {{ Form::text('semesterName', isset($record->semester_name) ? $record->semester_name : null,
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
                            {{ Form::text('examTitle', isset($record->exam_title) ? $record->exam_title : null,
                                [
                                'class' => 'form-control shadow-lg fw-bold',
                                'required' => true,
                                'readonly' => true
                                ]
                            )}}
                            <br>
                        </div>
                    </div>
                </div>
            </div>        
            @endforeach
        </div>
    </div>
</div>
@endsection