@extends('layout.main')
@push("title")
    <title>Semester View</title>
@section('main')
<div class="table-responsive pt-5">
    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
        <thead>
            <tr>
                <th scope="col">First Name</th>
                <th scope="col">Middel Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Registration Number</th>
                <?php
                    use Illuminate\Support\Facades\Session;
                    
                    $value = Session::get('user_type');
                    if ($value == 3) {
                ?>
                        <th scope="col">Session</th>
                        <th scope="col">Semester</th>
                <?php
                    }
                ?>
                <th scope="col">Department</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $value = Session::get('user_type');
                if ($value != 3) {
                    $colValue = 6;
                    if (empty($user)) {
                    
            ?>
                    <td scope="row" colspan={{ $colValue }}><h3>Profile has not created yet</h3></tr>
            <?php
                    }
                } else {
            ?>

                <tr scope="row">
                    <td scope="row">{{$user->first_name}}</td>
                    <td scope="row">{{$user->middle_name}}</td>
                    <td scope="row">{{$user->last_name}}</td>
                    <td scope="row">{{$user->registration_number}}</td>
                    <?php                    
                        $value = Session::get('user_type');
                        if ($value == 3) {
                    ?>                        
                            <td scope="row">{{$user->session}}</td>
                            <td scope="row">{{$user->semester_name}}</td>
                    <?php
                        }
                    ?>
                    <td scope="row">{{$user->department_name}}</td>
                    <td>
                        <div class="d-flex gap-2" style="display: inline-block;">
                            {{ Form::open(['url' => 'profiles/' .$user->profile_id, 'method' => 'get']) }}
                                
                                <div class="text-center">
                                    {{ Form::button(HTML::decode('<i class="las la-eye"></i>'), [
                                        'class' => 'btn btn-info btn-sm',
                                        'type' => 'submit'
                                    ])}}
                                </div>
                            {{ Form::close() }}
                            {{ Form::open(['url' => 'profiles/' .$user->profile_id.'/edit', 'method' => 'get']) }}
                                
                                <div class="text-center">
                                    {{ Form::button(HTML::decode('<i class="las la-edit"></i>'), [
                                        'class' => 'btn btn-success btn-sm',
                                        'type' => 'submit'
                                    ])}}
                                </div>
                            {{ Form::close() }}
                        </div>
                    </td>
                </tr>
            <?php
                }
            ?>
        </tbody>
    </table>
</div>

@endsection