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
                        <div class="col-md-6">
                            {{ Form::label('username', 'Username', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('username', isset($users->username) ? $users->username : null, 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter username',
                                'readonly' => true,
                                'required' => true
                                ], Input::old('username')
                            )}}
                            @if($errors->has('username'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('username') }}
                            </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            {{ Form::label('email', 'Email', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('email', isset($users->email) ? $users->email : null,
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter email',
                                'readonly' => true,
                                'required' => true
                                ], Input::old('email')
                            )}}
                            @if($errors->has('email'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('email') }}
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
                                    '1' => 'Admin', 
                                    '2' => 'Instructor', 
                                    '3' => 'Student'
                                ], 
                                isset($users->user_type) ? $users->user_type : null, [
                                    'class' => 'form-control shadow-lg',
                                    'required' => true
                                ],
                                [
                                    '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                ], Input::old('userType')
                            )}}
                            @if($errors->has('userType'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('userType') }}
                            </span>
                            @endif
                        </div>                    
                        <div class="col-md-6">
                            {{ Form::label('status', 'Status', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('status', 
                                [
                                    '' => 'Select user status',
                                    '0' => 'Inactive', 
                                    '1' => 'Active'
                                ], 
                                isset($users->status) ? $users->status : null, [
                                    'class' => 'form-control shadow-lg',
                                    'required' => true
                                ],
                                [
                                    '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                ] , Input::old('status')
                            )}}
                            @if($errors->has('status'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('status') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{ Form::label('registrationNumber', 'Registration Number', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('registrationNumber', isset($users->registration_number) ? $users->registration_number : null, 
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
                            {{ Form::label('phoneNumber', 'Phone Number', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('phoneNumber', isset($users->phone_number) ? $users->phone_number : null, 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter phone number',
                                'required' => true
                                ], Input::old('phoneNumber')
                            )}}
                            @if($errors->has('phoneNumber'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('phoneNumber') }}
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