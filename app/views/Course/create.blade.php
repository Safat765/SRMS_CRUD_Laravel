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
                            {{ Form::label('name', 'Course name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('name', Input::old('name'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter Course name',
                                'required' => true
                                ]
                            )}}
                            @if($errors->has('name'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('name') }}
                            </span>
                            @endif
                        </div>                        
                        <div class="col-md-6">
                            {{ Form::label('credit', 'Credit', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('credit', Input::old('credit'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter Credit',
                                'required' => true
                                ]
                            )}}
                            @if($errors->has('email'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('credit') }}
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