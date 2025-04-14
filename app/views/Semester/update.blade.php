@extends('layout.main')
@push("title")
    <title>Semester Update</title>
@endpush
@section('main')
<div class="container mt-5 pt-4 d-flex justify-content-center">
    <div class="row">
        <div class="col-md-12" style="width: 700px;">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center fw-bold text-info">{{ $pageName }}</h4>
                </div>
                <div class="card-body bg-light">
                    {{ Form::open(['url' => $url, 'method' => 'patch', 'novalidate' => true]) }}   
                        <div class="row mb-3">
                            <div class="col-md-12">
                                {{ Form::label('name', 'Semester Name', ['class' => 'form-label']) }}
                                <span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('name', isset($semester->name) ? $semester->name : null,
                                    [
                                    'class' => 'form-control shadow-lg',
                                    'placeholder' => 'Enter Semester Name',
                                    'required' => true
                                    ], Input::old('name')
                                )}}
                                @if($errors->has('name'))
                                <span class="text-danger small d-block mt-1">
                                    {{ $errors->first('name') }}
                                </span>
                                @endif
                                <br>
                            </div>
                            <div class="col-md-12 text-center">
                                {{ Form::submit('Create', 
                                    [
                                    'class' => 'btn btn-primary btn-block'
                                    ]
                                )}}
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection