@extends('layout.main')
@push("title")
    <title>Course Create</title>
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
                        <div class="col-md-6">
                            {{ Form::label('oldPassword', 'Previous Password', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('oldPassword', null, 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter Previous Password',
                                'required' => true
                                ], Input::old('oldPassword')
                            )}}
                            @if($errors->has('oldPassword'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('oldPassword') }}
                            </span>
                            @endif
                        </div>                        
                        <div class="col-md-6">
                            {{ Form::label('newPassword', 'New Password', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('newPassword', null, 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter New Password',
                                'required' => true
                                ], Input::old('newPassword')
                            )}}
                            @if($errors->has('newPassword'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('newPassword') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        {{ Form::submit('Confirm', 
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