@extends('layout.main')
@push("title")
    <title>Login</title>
@endpush
@section('main')
    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg" style="background-color: rgb(206, 244, 244);">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Login</h3>                        
                        {{ Form::open(['url' => 'login', 'method' => 'post', 'novalidate' => true]) }}                            
                            <div class="mb-3">
                                {{ Form::text('username', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Username',
                                    'required' => true
                                ], Input::old('username')) }}

                                @if($errors->has('username'))
                                    <span class="text-danger small d-block mt-1">
                                        {{ $errors->first('username') }}
                                    </span>
                                @endif
                            </div>                      
                            <div class="mb-3">
                                {{ Form::password('password', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Password',
                                    'required' => true
                                ]) }}
                                @if($errors->has('password'))
                                    <span class="text-danger">
                                        {{ $errors->first('password') }}
                                    </span>
                                @endif
                            </div>                            
                            <div class="d-grid gap-2">
                                {{ Form::submit('Login', [
                                    'class' => 'btn btn-primary btn-block'
                                ]) }}
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection