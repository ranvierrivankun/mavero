<div class="modal-header bg-primary">
    <h5 class="modal-title white">Edit Material Type</h5>
    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
</div>

<form id="edit" method="post">

    <input type="hidden" name="id_mt" value="<?= $edit->id_mt ?>">

    <div class="modal-body">

        <!-- Input field for Name -->
        <div class="form-group">
            <label for="name_mt">Name</label>
            <input type="text" name="name_mt" id="name_mt_edit" placeholder="Name Material Type" class="form-control" value="<?= $edit->name_mt ?>">
            <div class="error-text" id="name_mt_error_edit"></div>
        </div>

        <!-- Input field for Description -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea type="text" name="description" id="description_edit" placeholder="Description Material Type" class="form-control"><?= $edit->description ?></textarea>
            <div class="error-text" id="description_error_edit"></div>
        </div>

    </div>

    <div class="modal-footer">
        <!-- Close button -->
        <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal">
            Close
        </button>

        <!-- Submit button -->
        <button type="button" id="submit_edit" class="btn btn-primary ml-1">
            Submit
        </button>
    </div>
</form>

<script>
    // Menambahkan event listener pada tombol submit
    document.getElementById('submit_edit').addEventListener('click', function() {

        // Menampilkan SweetAlert dengan indikator proses
        Swal.fire({
            title: 'Please wait...',
            text: 'Submit in progress',
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Mengirim permintaan AJAX untuk proses submit
        $.ajax({
            type: 'POST',
            url: '<?= base_url('material_setting/edit') ?>',
            data: $('#edit').serialize(),
            dataType: 'json',

            beforeSend: function() {
                Swal.fire({
                    title: 'Please wait...',
                    text: 'Submit in progress',
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },

            success: function(response) {
                if (response.status === 'success') {
                    // Jika submit berhasil
                    Swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        table_material_type();
                        $('#edit_material_type').modal('hide');
                    });

                } else if (response.status === 'error') {
                    // Jika terjadi kesalahan, tampilkan pesan error menggunakan SweetAlert
                    Swal.fire({
                        title: 'Error',
                        html: response.message,
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    // Tampilkan kesalahan validasi pada input form edit
                    const errorFields = ['name_mt', 'description'];

                    errorFields.forEach(function(fieldName) {
                        const errorElement = document.getElementById(fieldName + '_error_edit');
                        const inputElement = document.getElementById(fieldName + '_edit');
                        const errorMessage = response[fieldName];

                        if (errorMessage) {
                            errorElement.innerHTML = errorMessage;
                            inputElement.classList.add('is-invalid');
                        } else {
                            errorElement.innerHTML = '';
                            inputElement.classList.remove('is-invalid');
                        }
                    });

                }
            },
            error: function() {
                // Jika terjadi kesalahan akibat masalah server atau basis data
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred. Please try again later.',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    });
</script>