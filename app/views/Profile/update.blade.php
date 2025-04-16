@extends('layout.main')
@push("title")
    <title>{{ $title }}</title>
@endpush
@section('main')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <a href="{{ URL::route('editProfile') }}" class="col-md-1 btn btn-danger">Back</a>
            <hr>
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center fw-bold text-info">{{ $pageName }}</h4>
                </div>                
                {{ Form::open(['url' => $url, 'method' => 'patch', 'novalidate' => true]) }}   
                <div class="card-body bg-light">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            {{ Form::label('firstName', 'First Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('firstName', isset($user->first_name) ? $user->first_name : null, 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter First Name',
                                'required' => true
                                ], Input::old('firstName')
                            )}}
                            @if($errors->has('firstName'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('firstName') }}
                            </span>
                            @endif
                        </div> 
                        <div class="col-md-4">
                            {{ Form::label('middleName', 'Middle Name', ['class' => 'form-label']) }}
                            {{ Form::text('middleName', isset($user->middle_name) ? $user->middle_name : null, 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter Middle Name',
                                'required' => true
                                ] , Input::old('middleName')
                            )}}
                            @if($errors->has('middleName'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('middleName') }}
                            </span>
                            @endif
                        </div> 
                        <div class="col-md-4">
                            {{ Form::label('lastName', 'Last Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('lastName', isset($user->last_name) ? $user->last_name : null,  
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter First Name',
                                'required' => true
                                ], Input::old('lastName')
                            )}}
                            @if($errors->has('lastName'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('lastName') }}
                            </span>
                            @endif
                        </div> 
                    </div>                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{ Form::label('registrationNumber', 'Registration Number', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('registrationNumber', isset($user->registration_number) ? $user->registration_number : null, 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter registration number',
                                'readonly' => true,
                                'required' => true
                                ], Input::old('registrationNumber')
                            )}}
                            @if($errors->has('registrationNumber'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('registrationNumber') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            {{ Form::label('session', 'Session', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('session', isset($user->session) ? $user->session : null, 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter Session',
                                'readonly' => true,
                                'required' => true
                                ], Input::old('session')
                            )}}
                            @if($errors->has('session'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('session') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{ Form::label('semesterId', 'Semester', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('semesterId', isset($user->semester_name) ? $user->semester_name : null, 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter registration number',
                                'readonly' => true,
                                'required' => true
                                ], Input::old('semesterId')
                            )}}
                            @if($errors->has('semesterId'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('semesterId') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            {{ Form::label('departmentId', 'Department', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('departmentId', isset($user->department_name) ? $user->department_name : null, 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter Department',
                                'readonly' => true,
                                'required' => true
                                ], Input::old('departmentId')
                            )}}
                            @if($errors->has('departmentId'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('departmentId') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <?php
                        if ($pageName == "Edit Profile") {
                    ?>
                        <div class="row mb-3 col-md-12 text-center">
                            {{ Form::submit('Update', 
                                [
                                'class' => 'btn btn-primary btn-block'
                                ]
                            )}}
                        </div>
                    <?php
                        }
                    ?>
                </div>
            {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection