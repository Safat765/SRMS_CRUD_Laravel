<div class="row mb-3">
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
            <thead>
                <tr>
                    <th colspan="2">Assign Course ({{ $data['totalCourse'] }})</th>
                </tr>
                <tr>
                    <th scope="col">Course</th>
                    <th scope="col">Department</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['results'] as $result)
                    <tr>
                        <td scope="row">{{$result->course_name}}</td>
                        <td scope="row">{{$result->department_name}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-12"><br></div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
            <thead>
                <tr>
                    <th colspan="3">Recent given marks</th>
                </tr>
                <tr>
                    <th scope="col">Student</th>
                    <th scope="col">Course</th>
                    <th scope="col">Marks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['marksResults'] as $result)
                    @if ($result->obtained_marks)
                        <tr>
                            <td scope="row" class="p-2">{{$result->username}}</td>
                            <td scope="row" class="p-2">{{$result->course_name}}</td>
                            <td scope="row" class="p-2">
                                @if (isset($result->username) && isset($result->obtained_marks))
                                    {{$result->obtained_marks}} / {{$result->exam_marks}}
                                @else
                                    <p>Null</p>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
    