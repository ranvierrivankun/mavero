<div class="modal-header bg-success">
    <h5 class="modal-title white">Sending Material</h5>
    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
</div>

<form id="tambah" method="post">
    <div class="modal-body">

        <input type="hidden" id="id_group" name="id_group" value="<?= $edit->id_group_db ?>">

        <!-- Input field for Name -->
        <div class="form-group">

            <?php if ($edit->status_group == 'pending') : ?>
                <span class="badge bg-warning mb-2">Pending</span>
            <?php elseif ($edit->status_group == 'sending') : ?>
                <span class="badge bg-success mb-2">Sending</span>
            <?php endif; ?>

            <h3><?= $edit->name_group ?></h3>

        </div>

        <!-- Input field for Name -->
        <div class="form-group">
            <label for="no_resi">No. Resi</label>
            <input type="text" name="no_resi" id="no_resi" placeholder="No. Resi Delivery" class="form-control">
            <div class="error-text" id="no_resi_error"></div>
        </div>

    </div>

    <div class="modal-footer">
        <!-- Close button -->
        <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal">
            Close
        </button>

        <!-- Submit button -->
        <button type="button" id="submit_send" class="btn btn-success ml-1">
            Submit
        </button>
    </div>
</form>

<script>
    // Menambahkan event listener pada tombol submit
    document.getElementById('submit_send').addEventListener('click', function() {
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
            url: '<?= base_url('material_delivery/send') ?>',
            data: $('#tambah').serialize(),
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
                        table_material_delivery();
                        $('#send_material').modal('hide');
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
                    const errorFields = ['no_resi'];

                    errorFields.forEach(function(fieldName) {
                        const errorElement = document.getElementById(fieldName + '_error');
                        const inputElement = document.getElementById(fieldName);
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