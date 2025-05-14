@extends('layout.main')
@push("title")
<title>Student Enrolled Course</title>
@section('main')
<div class="table-responsive pt-5" id="marksIndex">
    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
        <thead>
            <tr>
                <td scope="row" colspan="2" class="fw-bold fs-3 text-info">{{ ucwords(Illuminate\Support\Facades\Session::get('username')) }}'s Enrolled Course</td>
            </tr>
            <tr>
                <th scope="col">Course</th>
                <th scope="col">Credit</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($groupedResults as $semesterName => $students)
            <tr>
                <th colspan="2" class="bg-light text-warning fs-5 text-center">{{ $semesterName }}</th>
            </tr>
            @foreach ($students as $result)
                <tr>
                    <td class="p-2">{{ $result->course_name }}</td>
                    <td class="p-2">{{ $result->credit }}</td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
</div>
@include('mark.createModal')
@endsection
