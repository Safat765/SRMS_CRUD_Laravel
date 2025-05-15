<div class="row mb-3">
    <div class="col-md-6">
        <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
            <thead>
                <tr>
                    <th colspan="2">Enrolled Course ({{ $totalEnrollCourse }})</th>
                </tr>
                <tr>
                    <th scope="col">Course</th>
                    <th scope="col">Credit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($courses as $result)
                    <tr>
                        <td scope="row" class="p-2">{{ $result->name }}</td>
                        <td scope="row" class="p-2">{{ $result->credit }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
    