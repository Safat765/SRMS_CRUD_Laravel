<div class="modal fade" id="updateDepartmentModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <form>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Department</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="errorMsgContainer">

                    </div>
                    <div class="row mb-3">
                            <div class="col-md-12">
                                {{ Form::hidden('departmentId', null, ['class' => 'departmentId', 'id' => 'departmentId']) }}
                                {{ Form::label('depName', 'Department name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('depName', null, 
                                    [
                                    'class' => 'form-control shadow-lg depName',
                                    'id' => 'depName',
                                    'placeholder' => 'Enter Department name',
                                    'required' => true
                                    ]
                                )}}
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary updateDepartment">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $(document).on('click', '.btnEdit', function() {
            var courseId = $(this).data('id');
            var depName = $(this).data('name');

            $('.departmentId').val(courseId);
            $('.depName').val(depName);
        });

        $(document).on('click', '.updateDepartment', function(e) {
            e.preventDefault();
            var departmentId = $('#departmentId').val();
            var depName = $('#depName').val();

            if (!depName) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Fillup the form first!"
                });
                return;
            }
            $('.errorMsgContainer').html("");

            $.ajax({
                url : `/admin/departments/${departmentId}`,
                type : 'PUT',
                data : {id : departmentId ,name : depName},
                success : function (response)
                {
                    if (response.status === 'success') {
                        $("#updateDepartmentModal").modal('hide');
                        $("#updateDepartmentModal").trigger("reset");
                        $('.departmentUpdate').load(location.href + ' .departmentUpdate');
                        Swal.fire({
                            title: "Good job!",
                            text: `'${depName}' Department updated successfully`,
                            icon: "success"
                        });
                    }
                },
                error :function (err)
                {
                    var error = err.responseJSON;
                    $.each(error.errors, function(index, value) {
                        $('.errorMsgContainer').append('<span class="text-danger">'+value+'</span>'+'<br>')
                    });
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message
                    });
                }
            });
        });
        $(document).on('click', '.close', function(e) {
            e.preventDefault();
            $("#exampleModal").trigger("reset");
            $('.errorMsgContainer').text("");
        });
    });
</script>