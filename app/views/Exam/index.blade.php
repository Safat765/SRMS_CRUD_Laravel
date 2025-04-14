@extends('layout.main')
@push("title")
<title>User View</title>
@section('main')
<div class="table-responsive">
    <div class="form-group d-flex justify-content-between align-items-start">
        <div class="d-flex">
            <div class="p-1">            
                <div class="d-flex justify-content-start mb-3">
                    <a href="{{url('/users/create')}}" class="btn btn-primary m-2">
                        Create User
                    </a>
                </div>
            </div>
            <div class="p-1">                       
                <div class="d-flex justify-content-start mb-3">
                    <a href="{{url('/users')}}" class="btn btn-secondary m-2">
                        Reset
                    </a>
                </div>
            </div>  
            <div class="p-1">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">                   
                    Create User
                </button>
                
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex-grow-1" style="min-width: 250px; max-width: 500px;">
            {{ Form::open([URL::route('users.index'), 'method' => 'get']) }}
            <div class="form-group d-flex">
                <div class="form-group p-1 col-10">
                    {{ Form::text('search', $search, [
                    'class' => 'form-control',
                    'placeholder' => 'Search by Username or Email',
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
        <h5>Total User : {{ $totalUsers }}</h5>
    </div>
    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
        <thead>
            <tr>
                <th scope="col">Username</th>
                <th scope="col">Email</th>                
                <th scope="col">User Type</th>
                <th scope="col">Registration Number</th>
                <th scope="col">Phone Number</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr @if($user->status == 0) class="disabled-row" style="opacity: 0.3;" @endif>
                <td scope="row">{{$user->username}}</td>
                <td scope="row">{{$user->email}}</td>
                <td scope="row"> 
                    {{ $user->user_type == 1 ? 'Admin' : ($user->user_type == 2 ? 'Instructor' : 'Student') }}</td>
                </td>
                <td scope="row">{{$user->registration_number}}</td>
                <td scope="row">{{$user->phone_number}}</td>
                <td scope="row">
                    @if ($user->status == 1)
                    <a href="/users/status/{{$user->user_id}}">
                        <span class="badge bg-success">Active</span>
                    </a>
                    @else
                    <a href="/users/status/{{$user->user_id}}">
                        <span class="badge bg-danger">Inactive</span>
                    </a>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-2" style="display: inline-block;">
                        @if($user->status == 0) 
                            {{ Form::open(['url' => 'users/' .$user->user_id.'/edit', 'method' => 'get']) }}
                            
                            <div class="text-center">
                                {{ Form::button(HTML::decode('<i class="las la-edit"></i>'), [
                                    'class' => 'btn btn-success btn-sm',
                                    'type' => 'submit',
                                    'disabled' => 'disabled'
                                ])}}
                            </div>
                            {{ Form::close() }}
                        @else
                            {{ Form::open(['url' => 'users/' .$user->user_id.'/edit', 'method' => 'get']) }}
                            
                            <div class="text-center">
                                {{ Form::button(HTML::decode('<i class="las la-edit"></i>'), [
                                    'class' => 'btn btn-success btn-sm',
                                    'type' => 'submit'
                                ])}}
                            </div>
                            {{ Form::close() }}
                        @endif
                        
                        @if($user->status == 0) 
                            {{ Form::open(['url' => 'users/' .$user->user_id, 'method' => 'delete']) }}
                            
                            <div class="text-center">
                                {{ Form::button(HTML::decode('<i class="las la-trash-alt"></i>'), [
                                    'class' => 'btn btn-danger btn-sm',
                                    'type' => 'submit',
                                    'disabled' => 'disabled'
                                ])}}
                            </div>
                            {{ Form::close() }}
                        @else
                            {{ Form::open(['url' => 'users/' .$user->user_id, 'method' => 'delete']) }}                            
                            <div class="text-center">
                                {{ Form::button(HTML::decode('<i class="las la-trash-alt"></i>'), [
                                    'class' => 'btn btn-danger btn-sm',
                                    'type' => 'submit'
                                ])}}
                            </div>
                            {{ Form::close() }}
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="text-center">
        {{ $users->links() }}
    </div>
</div>

@endsection