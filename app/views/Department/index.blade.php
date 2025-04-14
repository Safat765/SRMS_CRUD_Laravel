@extends('layout.main')
@push("title")
<title>Department View</title>
@section('main')
<div class="table-responsive">
    <div class="form-group d-flex justify-content-between align-items-start">
        <div class="d-flex">
            <div class="p-1">            
                <div class="d-flex justify-content-start mb-3">
                    <a href="{{url('/departments/create')}}" class="btn btn-primary m-2">
                        Create Department
                    </a>
                </div>
            </div>
            <div class="p-1">                       
                <div class="d-flex justify-content-start mb-3">
                    <a href="{{url('/departments')}}" class="btn btn-secondary m-2">
                        Reset
                    </a>
                </div>
            </div>  
        </div>
        
        <div class="flex-grow-1" style="min-width: 250px; max-width: 500px;">
            {{ Form::open([URL::route('departments.index'), 'method' => 'get']) }}
            <div class="form-group d-flex">
                <div class="form-group p-1 col-10">
                    {{ Form::text('search', $search, [
                    'class' => 'form-control',
                    'placeholder' => 'Search by Department name',
                    'required' => true
                    ])}}
                </div>                                            
                <div class="p-1">
                    {{ Form::submit('Search', [
                    'class' => 'btn btn-primary btn-block'
                    ]) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>     
    </div>
    <div class="bg-warning  text-black text-center mx-5">
        <h5>Total Department : {{ $totalDepartment }}</h5>
    </div>
    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
        <thead>
            <tr>
                <th scope="col">Department Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($department as $departments)
            <tr>
                <td scope="row">{{$departments->name}}</td>
                <td class="d-flex justify-content-center gap-2">
                    <div class="d-flex gap-2" style="display: inline-block;">
                        {{ Form::open(['url' => 'departments/' .$departments->department_id.'/edit', 'method' => 'get']) }}
                        
                        <div class="text-center">
                            {{ Form::submit('Edit', ['class' => 'btn btn-success btn-sm'])}}
                        </div>
                        {{ Form::close() }}

                        {{ Form::open(['url' => 'departments/' .$departments->department_id, 'method' => 'delete']) }}
                        
                        <div class="text-center">
                            {{ Form::submit('Delete', ['class' => 'btn btn-danger btn-sm'])}}
                        </div>
                        {{ Form::close() }}
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {{ $department->links() }}
    </div>
</div>

@endsection