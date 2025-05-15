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
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.19.1/dist/sweetalert2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/pagination.css">
  <link rel="stylesheet" href="/css/sideNavBar.css">
</head>
<body>
  <?php
    $value = Illuminate\Support\Facades\Session::get('user_id');
  ?>
  @if (isset($value))
  <nav class="navbar" style="background-color: #383838;">
    <div class="container-fluid justify-content-between align-items-center">
      <button id="playSidebar" class="btn btn-outline-dark playSidebar">
        <h5 class="text-center fs-2 fw-bold fst-italic text-light">
          <i class="las la-bars"></i>
        </h5>
      </button>
      <div class="text-start flex-grow-1 ms-3">
        @if (Illuminate\Support\Facades\Session::get('user_type') == App\Models\User::USER_TYPE_ADMIN)
        <a href="{{ url('/admin/dashboard') }}" class="text-decoration-none">
          <h5 class="fs-1 fw-bold fst-italic text-info">SRMS</h5>
        </a>
        @elseif (Illuminate\Support\Facades\Session::get('user_type') == App\Models\User::USER_TYPE_INSTRUCTOR)
        <a href="{{ url('/instructor/dashboard') }}" class="text-decoration-none">
          <h5 class="fs-1 fw-bold fst-italic text-info">SRMS</h5>
        </a>
        @elseif (Illuminate\Support\Facades\Session::get('user_type') == App\Models\User::USER_TYPE_STUDENT)
        <a href="{{ url('/students/dashboard') }}" class="text-decoration-none">
          <h5 class="fs-1 fw-bold fst-italic text-info">SRMS</h5>
        @endif
      </div>
      <div class="dropdown pe-3">
        <button class="btn btn-dark dropdown-toggle" type="button" id="profileDropdown">
          <i class="las la-user-edit me-1 fs-4"></i> <span class="text-danger fw-bold">{{ strtoupper(Session::get('username')) }}</span>
        </button>
        <?php
          $userType = Illuminate\Support\Facades\Session::get('user_type');
          $urlMap = [
              App\Models\User::USER_TYPE_ADMIN => 'admin',
              App\Models\User::USER_TYPE_INSTRUCTOR => 'instructor',
              App\Models\User::USER_TYPE_STUDENT => 'students',
          ];
          $url = isset($urlMap[$userType]) ? $urlMap[$userType] : '';
        ?>
        <ul class="dropdown-menu dropdown-menu-dark mt-2" id="profileDropdownMenu" style="display: none;">
          <li>
            <a class="dropdown-item fs-6" href="{{ url('/'.$url . '/profiles/'.Illuminate\Support\Facades\Session::get('user_id')) }}">
              <i class="las la-edit"></i> Edit Profile
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item fs-6" href="{{ URL::route($url.'.profiles.create') }}">
              <i class="las la-exchange-alt"></i> Change Password
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li class="text-center"><a href="{{ url('/logout') }}" class="btn btn-danger text-center fs-6"><i class="las la-sign-out-alt">  Logout</i></a></li>
        </ul>
      </div>
    </div>
  </nav>
  @else
    <h1 class="container-fluid text-center p-3" style="background-color: darkgrey;">Welcome To SRMS</h1>
  @endif

  <div class="d-flex">       
    <?php
      $value = Illuminate\Support\Facades\Session::get('user_id');
    ?>
    @if (isset($value))
      <div class="sidebar position-relative pt-4 p-3 a" style="width: 230px;" id="sidebar">          
          @if (Illuminate\Support\Facades\Session::get('user_type') == App\Models\User::USER_TYPE_ADMIN)
            <button class="btn btn-dark mb-3" data-bs-toggle="collapse" data-bs-target="#userMenu"><i class="las la-user me-1 fw-bold fs-4"></i> User</button>
            <div class="collapse ps-4 w-100" id="userMenu">
                <a href="{{ URL::route('admin.users.index') }}" class="btn btn-outline-secondary fs-6">View</a>
                <hr>
            </div>
            <button class="btn btn-dark mb-3" data-bs-toggle="collapse" data-bs-target="#departmentMenu"><i class="las la-list fs-4"></i> Department</button>
            <div class="collapse ps-4 w-100" id="departmentMenu">
                <a href="{{ URL::route('admin.departments.index') }}" class="btn btn-outline-secondary fs-6">View</a>
                <hr>
            </div>
            <button class="btn btn-dark mb-3" data-bs-toggle="collapse" data-bs-target="#semesterMenu"><i class="las la-stream fs-4"></i> Semester</button>
            <div class="collapse ps-4 w-100" id="semesterMenu">
                <a href="{{ URL::route('admin.semesters.index') }}" class="btn btn-outline-secondary fs-6">View</a>
                <hr>
            </div>
            <button class="btn btn-dark mb-3" data-bs-toggle="collapse" data-bs-target="#courseMenu"><i class="lab la-discourse fs-4"></i> Course</button>
            <div class="collapse ps-4 pb-2 w-100" id="courseMenu">
                <a href="{{ URL::route('admin.courses.index') }}" class="btn btn-outline-secondary fs-6">View</a>
                <hr>
                <a href="{{url('/admin/courses/assigned/all')}}" class="btn btn-outline-secondary fs-6">Assigned Course</a>
            </div>
            <button class="btn btn-dark mb-3" data-bs-toggle="collapse" data-bs-target="#examMenu"><i class="las la-clipboard-list fs-4"></i> Exam</button>
            <div class="collapse ps-4 w-100" id="examMenu">
                <a href="{{ URL::route('admin.exams.index') }}" class="btn btn-outline-secondary fs-6">View</a>
                <hr>
            </div>
            <a href="{{url('/admin/results/all')}}" class="btn btn-outline-secondary fs-6 mb-3"><i class="las la-poll fs-4"></i>  Results</a>
          @endif
          
          @if (Illuminate\Support\Facades\Session::get('user_type') == App\Models\User::USER_TYPE_INSTRUCTOR)
            <div>
                <a href="{{ url('/instructor/marks/assigned/courses') }}" class="btn btn-outline-secondary fs-6 mb-3">Assigned Course</a>
                <hr>
                <a href="{{ URL::route('instructor.marks.index') }}" class="btn btn-outline-secondary fs-6 mb-3">Add marks</a>
                <hr>
                <a href="{{url('/instructor/marks/all/students')}}" class="btn btn-outline-secondary fs-6 mb-3">View marks</a>
            </div>
          @endif
          @if (Illuminate\Support\Facades\Session::get('user_type') == App\Models\User::USER_TYPE_STUDENT)
            <button class="btn btn-dark mb-3" data-bs-toggle="collapse" data-bs-target="#resultMenu"><i class="las la-poll fs-4"></i> Result</button>
            <div class="collapse ps-4 w-100" id="resultMenu">
                <a href="{{ URL::route('students.results.index') }}" class="btn btn-outline-secondary fs-6 mb-3">View</a>
                <hr>
            </div>
            <a href="{{url('/students/results/ecrolled')}}" class="btn btn-outline-secondary fs-6 mb-3"><i class="las la-school fs-4"></i>  Enroll Courses</a>
          @endif
      </div>
    @endif

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
  $(document).ready(function() {
    $('#profileDropdown').click(function(e) {
      e.preventDefault();
      $('#profileDropdownMenu').slideToggle();
    });
  });
</script>
