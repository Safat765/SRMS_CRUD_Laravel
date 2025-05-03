<div class="modal fade" id="updateSemesterModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    {{ Form::open(['url' => '/semesters', 'method' => 'post', 'novalidate' => true, 'id' => 'semesterUpdate']) }}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Course</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="errorMsgContainer">

                    </div>
                    <div class="row mb-3">
                            <div class="col-md-12">
                                {{ Form::hidden('semesterId', null, ['class' => 'semesterId', 'id' => 'semesterId']) }}
                                {{ Form::label('semesterName', 'Semester Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('semesterName', null, 
                                    [
                                    'class' => 'form-control shadow-lg semesterName',
                                    'placeholder' => 'Enter Semester semester name',
                                    'required' => true
                                    ]
                                )}}
                            </div>    
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary updateSemester">Update</button>
                </div>
            </div>
        </div>
    {{ Form::close() }}
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $(document).on('click', '.btnEdit', function() {
            let semesterId = $(this).data('id');
            let semesterName = $(this).data('name');

            $('.semesterId').val(semesterId);
            $('.semesterName').val(semesterName);
        });

        $(document).on('click', '.updateSemester', function(e) {
            e.preventDefault();
            let semesterId = $('.semesterId').val();
            let semesterName = $('.semesterName').val();
            $.ajax({
                url : `/semesters/${semesterId}`,
                type : 'PUT',
                data : {id : semesterId, name : semesterName},
                success : function (response)
                {
                    if (response.status === 'success') {
                        $('.semesterUpdate').load(location.href + ' .semesterUpdate')
                        $("#updateSemesterModal").modal('hide');
                        $("#semesterUpdate").trigger("reset");
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Your work has been saved",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error :function (err)
                {
                    let error = err.responseJSON;
                    $.each(error.errors, function(index, value) {
                        $('.errorMsgContainer').append('<span class="text-danger">'+value+'</span>'+'<br>')
                    });
                }
            });
        });
        $(document).on('click', '.close', function(e) {
            e.preventDefault();
            $("#semesterUpdate").trigger("reset");
            $('.errorMsgContainer').text("");
        });
    });
</script>