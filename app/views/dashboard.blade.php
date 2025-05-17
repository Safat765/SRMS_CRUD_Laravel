@extends('layout.main')
@push("title")
    <title>Dashboard</title>
@endpush
@section('main')
    @if (Session::get('user_type') == App\Models\User::USER_TYPE_ADMIN)
        <div class="container-fluid" style="background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20230612/pngtree-cartoon-students-celebrating-graduation-image_2900390.jpg'); text-shadow: 2px 2px 4px hsla(0, 9.70%, 93.90%, 0.98); height: 350px;"></div>
            <div>
                <br>
                <div class="row" style="display: flex; gap: 20px;">
                    <div class="col">
                        <div class="card card-body" style="background-color: lightblue;">
                            <h5>Overview</h5>
                            <p>The Student Result Management System (SRMS) is designed to streamline the process of managing and accessing student results. It provides an efficient way for administrators to input, update, and retrieve student grades.</p>
                            <p>SRMS is a comprehensive solution for educational institutions to manage academic records, generate reports, and ensure data accuracy. It is user-friendly and scalable, making it suitable for schools, colleges, and universities.</p>
                            <p>Key objectives of SRMS include:</p>
                            <ul>
                                <li>Centralized result management</li>
                                <li>Real-time data access</li>
                                <li>Improved data security</li>
                                <li>Enhanced communication between stakeholders</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card card-body" style="background-color: lightgreen;">
                            <h5>Features</h5>
                            <p>SRMS offers a wide range of features to simplify result management and improve efficiency:</p>
                            <ul>
                                <li><strong>User Management:</strong> Add, update, and manage user roles (admin, teacher, student).</li>
                                <li><strong>Department Management:</strong> Create and manage departments for better organization.</li>
                                <li><strong>Course Management:</strong> Add and manage courses with ease.</li>
                                <li><strong>Exam Management:</strong> Schedule and manage exams efficiently.</li>
                                <li><strong>Marks Management:</strong> Enter and update student marks securely.</li>
                                <li><strong>Result Generation:</strong> Automatically generate results and reports.</li>
                                <li><strong>Profile Management:</strong> Create and manage student and staff profiles.</li>
                                <li><strong>Data Security:</strong> Role-based access control to ensure data privacy.</li>
                                <li><strong>Real-Time Updates:</strong> Instant updates for students and teachers.</li>
                            </ul>
                        </div>
                    </div>
                        <div class="col">
                            <div class="card card-body" style="background-color: lightcoral;">
                                <h5>Benefits</h5>
                                <p>SRMS provides numerous benefits to educational institutions, administrators, teachers, and students:</p>
                                <ul>
                                    <li><strong>Efficiency:</strong> Automates manual processes, saving time and effort.</li>
                                    <li><strong>Accuracy:</strong> Reduces errors in result calculation and data entry.</li>
                                    <li><strong>Transparency:</strong> Provides real-time access to results and reports.</li>
                                    <li><strong>Accessibility:</strong> Students and teachers can access results anytime, anywhere.</li>
                                    <li><strong>Scalability:</strong> Suitable for small schools to large universities.</li>
                                    <li><strong>Improved Communication:</strong> Enhances communication between administrators, teachers, and students.</li>
                                    <li><strong>Data Security:</strong> Protects sensitive student data with role-based access.</li>
                                    <li><strong>Customizable Reports:</strong> Generate detailed reports for analysis and decision-making.</li>
                                </ul>
                                <p>By implementing SRMS, educational institutions can focus more on academic excellence and less on administrative tasks.</p>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        @elseif (Session::get('user_type') == App\Models\User::USER_TYPE_INSTRUCTOR)
            <div class="container-fluid" style="background-image: url('https://static.vecteezy.com/system/resources/thumbnails/022/093/495/small_2x/teacher-and-students-teaching-in-the-classroom-vector.jpg');
                                     background-size: contain;
                                     background-repeat: no-repeat;
                                     background-position: center;
                                     text-shadow: 2px 2px 4px hsla(0, 9.70%, 93.90%, 0.98);
                                     height: 350px;">
            </div>  
            <br>
            @include('dashboard.instructor', ['results' => $results, 'totalCourse' => $totalCourse, 'marksResults' => $marksResults])
        @else
            <div class="container-fluid" style="background-image: url('https://tspu.ru/images2/eng/Student_life/SL.jpg');
                                     background-size: contain;
                                     background-repeat: no-repeat;
                                     background-position: center;
                                     text-shadow: 2px 2px 4px hsla(0, 9.70%, 93.90%, 0.98);
                                     height: 350px;">
            </div>  
            @include('dashboard.student', ['totalEnrollCourse' => $totalEnrollCourse, 'courses' => $courses])
        @endif
    </div>

@endsection