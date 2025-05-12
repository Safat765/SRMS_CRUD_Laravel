<div class="modal fade" id="resultUserModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <form>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-info" id="updateModalLabel"></h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
                        <thead>
                            <tr>
                                <th colspan="3" class="text-center fw-bold fs-5 text-warning" id="rseultStudentName"></th>
                            </tr>
                            <tr>
                                <th scope="col">Course Name</th>
                                <th scope="col">Marks</th>
                                <th scope="col">GPA</th>
                            </tr>
                        </thead>
                        <tbody id="resultModalTableBody"></tbody>
                    </table>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
