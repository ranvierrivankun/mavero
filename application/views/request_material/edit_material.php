<div class="modal-header bg-primary">
    <h5 class="modal-title white">Edit Material</h5>
    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
</div>

<form id="edit" method="post">

    <input type="hidden" name="id_material" value="<?= $edit->id_material ?>">

    <div class="modal-body">
        <!-- Input field for Material Type -->
        <div class="form-group">
            <label for="id_mt">Material Type</label>
            <select class="choices-mt-edit form-select" name="id_mt" id="id_mt_edit" onchange="showDescription()">
                <option value="" disabled selected>Select Material Type</option>
                <?php foreach ($type as $type) : ?>
                    <option value="<?= $type->id_mt ?>" <?= ($type->id_mt == $edit->id_mt) ? 'selected' : '' ?>>
                        <?= $type->name_mt ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="error-text" id="id_mt_error_edit"></div>
        </div>

        <!-- Textarea for displaying description -->
        <div class="form-group">
            <label for="description_edit">Description Material Type:</label>
            <div class="mb-3" id="description_edit">
                </p>
            </div>

            <!-- Input field for Name -->
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name_edit" placeholder="Name Material" class="form-control" value="<?= $edit->name_material ?>">
                <div class="error-text" id="name_error_edit"></div>
            </div>

            <!-- Input field for Size -->
            <div class="form-group">
                <label for="size">Size</label>
                <input type="text" name="size" id="size_edit" placeholder="Size Material" class="form-control" value="<?= $edit->size ?>">
                <div class="error-text" id="size_error_edit"></div>
            </div>

            <!-- Input field for Quantity -->
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" id="quantity_edit" placeholder="Quantity Material" class="form-control" value="<?= $edit->quantity ?>">
                <div class="error-text" id="quantity_error_edit"></div>
            </div>

            <!-- Input field for Unit -->
            <div class="form-group">
                <label for="unit">Unit</label>
                <input type="text" name="unit" id="unit_edit" placeholder="Unit / Satuan" class="form-control" value="<?= $edit->unit ?>">
                <div class="error-text" id="unit_error_edit"></div>
            </div>

            <medium class="mt-2 text-success">- Request by <strong><?= $edit->name_user ?></strong> on <?= time_ago($edit->created) ?></medium>

            <?php if ($edit->id_user_updated !== null) : ?>
                <p>
                    <medium class="mt-2 text-danger">- Last Updated by <strong><?= $edit->name_user_updated ?></strong> on <?= time_ago($edit->updated) ?></medium>
                <?php endif; ?>

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
    $(document).ready(function() {
        // Panggil showDescription() saat halaman dimuat
        showDescription();
    });

    // Tampilkan Deskripsi Material Type
    function showDescription() {
        var id_mt = $("#id_mt_edit").val();

        $.ajax({
            url: "<?= base_url('request_material/description') ?>",
            type: "GET",
            data: {
                id_mt: id_mt
            },
            success: function(response) {
                $("#description_edit").html(response);
            },
            error: function(xhr, status, error) {
                console.error("Terjadi kesalahan: " + error);
            }
        });
    }

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
            url: '<?= base_url('request_material/edit') ?>',
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
                        table_request_material();
                        $('#edit_material').modal('hide');
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
                    const errorFields = ['id_mt', 'name', 'size', 'quantity', 'unit'];

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