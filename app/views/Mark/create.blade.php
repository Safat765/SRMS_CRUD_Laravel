@extends('layout.main')
@push("title")
    <title>Marks Create</title>
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
                        <div class="col-md-6">
                            {{ Form::label('mark', 'Mark', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('mark', Input::old('mark'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter mark',
                                'required' => true
                                ]
                            )}}
                            @if($errors->has('mark'))
                            <span class="text-danger small d-block mt-1">
                                {{ $errors->first('mark') }}
                            </span>
                            @endif
                        </div>
                        <br>
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