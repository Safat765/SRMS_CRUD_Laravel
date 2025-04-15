@extends('layout.main')
@push("title")
    <title>User Create</title>
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
                            {{ Form::label('username', 'Username', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('username', Input::old('username'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter username',
                                'required' => true
                                ]
                            )}}
                            @if($errors->has('username'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('username') }}
                            </span>
                            @endif
                        </div>
                        
                        <div class="col-md-4">
                            {{ Form::label('email', 'Email', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('email', Input::old('email'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter email',
                                'required' => true
                                ]
                            )}}
                            @if($errors->has('email'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('email') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            {{ Form::label('password', 'Password', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::password('password', 
                                [
                                'class' => 'form-control',
                                'placeholder' => 'Enter password',
                                'required' => true
                                ]
                            )}}
                            @if($errors->has('password'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('password') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{ Form::label('userType', 'User Type', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('userType', 
                                [
                                    '' => 'Select user type',
                                    $ADMIN => 'Admin', 
                                    $INSTRUCTOR => 'Instructor', 
                                    $STUDENT => 'Student'
                                ], 
                                Input::old('userType'), [
                                    'class' => 'form-control shadow-lg',
                                    'required' => true
                                ],
                                [
                                    '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                ]
                            )}}
                            @if($errors->has('userType'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('userType') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            {{ Form::label('session', 'Session', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> (Only for students)</span>
                            {{ Form::text('session', Input::old('session'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter Session',
                                'required' => true
                                ]) 
                            }}
                            @if($errors->has('session'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('session') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            {{ Form::label('registrationNumber', 'Registration Number', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('registrationNumber', Input::old('registrationNumber'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter registration number',
                                'required' => true
                                ]) 
                            }}
                            @if($errors->has('registrationNumber'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('registrationNumber') }}
                            </span>
                            @endif
                        </div>                        
                        <div class="col-md-4">
                            {{ Form::label('phoneNumber', 'Phone Number', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('phoneNumber', Input::old('phoneNumber'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter phone number',
                                'required' => true
                                ]
                            )}}
                            @if($errors->has('phoneNumber'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('phoneNumber') }}
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
                        <div class="col-md-4">
                            {{ Form::label('firstName', 'First Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('firstName', Input::old('firstName'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter First Name',
                                'required' => true
                                ]) 
                            }}
                            @if($errors->has('firstName'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('firstName') }}
                            </span>
                            @endif
                        </div> 
                        <div class="col-md-4">
                            {{ Form::label('middleName', 'Middle Name', ['class' => 'form-label']) }}
                            {{ Form::text('middleName', Input::old('middleName'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter Middle Name',
                                'required' => true
                                ]) 
                            }}
                            @if($errors->has('middleName'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('middleName') }}
                            </span>
                            @endif
                        </div> 
                        <div class="col-md-4">
                            {{ Form::label('lastName', 'Last Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('lastName', Input::old('lastName'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter First Name',
                                'required' => true
                                ]) 
                            }}
                            @if($errors->has('lastName'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('lastName') }}
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