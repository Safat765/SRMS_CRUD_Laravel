@extends('layout.main')
@push("title")
<title>Student List</title>
@section('main')
<div class="table-responsive pt-5">
        <div class="bg-warning  text-black text-center mx-5">
            <h5>Total Students : {{ $totalStudent }}</h5>
        </div>
        <a href="{{ URL::route('marks.index') }}" class="col-md-1 btn btn-danger">Back</a>
        <hr>
        <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Registration Number</th>
                    <th scope="col">Email</th>
                    <th scope="col">Department</th>
                    <th scope="col">Email</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                    @foreach ($results as $result)
                        <tr>
                            <td scope="row">{{$result->username}}</td>
                            <td scope="row">{{$result->registration_number}}</td>
                            <td scope="row">{{$result->email}}</td>                            
                            <td scope="row">{{$result->department_name}}</td>
                            <td scope="row">{{$result->semester_name}}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2" style="display: inline-block;">
                                    {{ Form::open(['url' => '/marks/'.$result->user_id, 'method' => 'get']) }}
                                        
                                        <div class="text-center">
                                            {{ Form::button(HTML::decode('<i class="las la-eye"></i>'), [
                                                'class' => 'btn btn-info btn-sm',
                                                'type' => 'submit'
                                            ])}}
                                        </div>
                                    {{ Form::close() }}                                    
                                    {{ Form::open(['url' => '/marks/abc', 'method' => 'post']) }}
                                        {{ Form::hidden('studentId', $result->user_id, [
                                            'class' => 'form-control shadow-lg'  
                                        ]) }}
                                        <div class="text-center">
                                            {{ Form::button(HTML::decode('<i class="las la-plus"></i>'), [
                                                'class' => 'btn btn-success btn-sm',
                                                'type' => 'submit'
                                            ])}}
                                        </div>
                                    {{ Form::close() }}
                                    {{ Form::open(['url' => '/marks/'.$result->user_id, 'method' => 'get']) }}
                                        
                                        <div class="text-center">
                                            {{ Form::button(HTML::decode('<i class="las la-edit"></i>'), [
                                                'class' => 'btn btn-warning btn-sm',
                                                'type' => 'submit'
                                            ])}}
                                        </div>
                                    {{ Form::close() }}                                    
                                    {{ Form::open(['url' => '/marks/'.$result->user_id, 'method' => 'get']) }}
                                        
                                        <div class="text-center">
                                            {{ Form::button(HTML::decode('<i class="las la-trash-alt"></i>'), [
                                                'class' => 'btn btn-danger btn-sm',
                                                'type' => 'submit'
                                            ])}}
                                        </div>
                                    {{ Form::close() }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
            </tbody>
        </table>
</div>

@endsection