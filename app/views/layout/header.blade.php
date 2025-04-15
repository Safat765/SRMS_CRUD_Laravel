<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="https://img.icons8.com/?size=100&id=AMAhOJecr9gE&format=png&color=000000" type="image/x-icon">
    @stack('title')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" >
    <link rel="stylesheet" href="/css/pagination.css">
</head>
<body>
    <div>        
        <?php
            use Illuminate\Support\Facades\Session;
            
            $value = Session::get('user_id');
            if (isset($value)) {
        ?>
        <nav class="navbar navbar-expand-lg container-fluid py-3 pb-3" style="background-color: rgb(56, 56, 56);">
            <div class="container-fluid">
                <a class="navbar-brand text-white" href="{{URL::route('login.index')}}"><b>Navbar</b></a>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="nav nav-pills">
                        {{-- <li class="nav-item">
                            <a class="nav-link text-white" href="{{url('/')}}">Home</a>
                        </li> --}}
                        <li class="nav-item dropdown">
                            <a class="btn btn-dark dropdown-toggle me-2" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 14px;">
                                User
                            </a>
                            <ul class="dropdown-menu dropdown-menu-start dropdown-menu-dark" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item py-0" href="{{URL::route('users.index')}}" style="font-size: 13px;">View</a></li>
                                <hr>
                                <li><a class="dropdown-item py-0" href="{{URL::route('users.create')}}" style="font-size: 13px;">Create</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="btn btn-dark dropdown-toggle me-2" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 14px;">
                                Department
                            </a>
                            <ul class="dropdown-menu dropdown-menu-start dropdown-menu-dark" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item py-0" href="{{URL::route('departments.index')}}" style="font-size: 13px;">View</a></li>
                                <hr>
                                <li><a class="dropdown-item py-0" href="{{URL::route('departments.create')}}" style="font-size: 13px;">Create</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="btn btn-dark dropdown-toggle me-2" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 14px;">
                                Semester
                            </a>
                            <ul class="dropdown-menu dropdown-menu-start dropdown-menu-dark" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item py-0" href="{{URL::route('semesters.index')}}" style="font-size: 13px;">View</a></li>
                                <hr>
                                <li><a class="dropdown-item py-0" href="{{URL::route('semesters.create')}}" style="font-size: 13px;">Create</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="btn btn-dark dropdown-toggle me-2" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 14px;">
                                Course
                            </a>
                            <ul class="dropdown-menu dropdown-menu-start dropdown-menu-dark" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item py-0" href="{{URL::route('courses.index')}}" style="font-size: 13px;">View</a></li>
                                <hr>
                                <li><a class="dropdown-item py-0" href="{{URL::route('courses.create')}}" style="font-size: 13px;">Create</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="btn btn-dark dropdown-toggle me-2" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 14px;">
                                Exam
                            </a>
                            <ul class="dropdown-menu dropdown-menu-start dropdown-menu-dark" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item py-0" href="{{URL::route('exams.index')}}" style="font-size: 13px;">View</a></li>
                                <hr>
                                <li><a class="dropdown-item py-0" href="{{URL::route('exams.create')}}" style="font-size: 13px;">Create</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown" style="padding-left: 860px; padding-top: 1px;">
                            <a class="btn btn-dark dropdown-toggle me-2"  style="font-size: 16px;" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 14px;">
                                <i class="las la-user"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item py-0" href="#" style="font-size: 13px;"><i class="las la-edit" style="font-size: 24px;"></i> Edit profile</a></li>
                                <hr>
                                <li><a class="dropdown-item py-0" href="{{ URL::route('profiles.create') }}" style="font-size: 13px;"><i class="las la-exchange-alt" style="font-size: 24px;"></i> Change Password</a></li>
                                <hr>
                                <li class="text-center"><a class="dropdown-item py-0" href="{{ url('/logout') }}"><button class="btn btn-danger" style="font-size: 13px;"><i class="las la-sign-out-alt" style="font-size: 24px;"></i></button></a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <?php
        } else {
    ?>
    <h1 class="container-fluid text-center p-3" style="background-color: darkgrey;">Welcome To SRMS</h1>
    <?php
        }
    ?>
    