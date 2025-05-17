<div class="modal fade" id="viewMarksModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-info" id="viewMarksModalLabel"></h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="errorMsgContainer"></div>
                    <div class="card-body bg-light">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{ Form::label('givenMark', 'Mark', ['class' => 'form-label']) }}
                            {{ Form::text('givenMark', null, 
                                [
                                'class' => 'form-control shadow-lg fw-bold',
                                'id' => 'viewGivenMark',
                                'required' => true,
                                'readonly' => true
                                ]
                            )}}
                        </div>
                        <div class="col-md-6">
                            {{ Form::label('courseName', 'Course', ['class' => 'form-label']) }}
                            {{ Form::text('courseName', null,
                                [
                                'class' => 'form-control shadow-lg fw-bold',
                                'id' => 'viewCourseName',
                                'required' => true,
                                'readonly' => true
                                ]
                            )}}
                        </div>
                    </div>
                    <br>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{ Form::label('semesterName', 'Semester', ['class' => 'form-label']) }}
                            {{ Form::text('semesterName', null,
                                [
                                'class' => 'form-control shadow-lg fw-bold',
                                'id' => 'viewSemesterName',
                                'required' => true,
                                'readonly' => true
                                ]
                            )}}
                            <br>
                        </div>
                        <div class="col-md-6">
                            {{ Form::label('examTitle', 'Exam Title', ['class' => 'form-label']) }}
                            {{ Form::text('examTitle', null,
                                [
                                'class' => 'form-control shadow-lg fw-bold',
                                'id' => 'viewExamTitle',
                                'required' => true,
                                'readonly' => true
                                ]
                            )}}
                            <br>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>