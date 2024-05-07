<div class="modal-header bg-primary">
    <h5 class="modal-title white">Edit Pricing</h5>
    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
</div>

<div class="modal-body">

    <div class="content">
        <div class="list-group">
            <a class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h4 class="mb-1">Request Material</h4>
                    <small><?= time_ago($edit->created) ?></small>
                </div>
                <p class="mb-1">
                    <em>(<?= $edit->name_mt ?>)</em> <?= $edit->description ?>
                </p>

                <hr class="mx-auto" style="width: 80%;">

                <p class="mb-1">
                    <medium>Name: <?= $edit->name_material ?></medium>
                </p>
                <p class="mb-1">
                    <medium>Size: <?= $edit->size ?></medium>
                </p>
                <p class="mb-3">
                    <medium>Quantity: <?= $edit->quantity ?> / <?= $edit->unit ?></medium>
                </p>

                <hr class="mx-auto">

                <p class="mb-1"><small>- Request by <strong><?= $edit->name_user ?></strong> (<?= $edit->created ?>)</small></p>
                <?php if ($edit->id_user_updated_request_material !== null) : ?>
                    <p class="mb-1">
                        <small>- Last Updated by <strong><?= $edit->name_user_updated ?></strong> (<?= $edit->updated ?>)</small>
                    </p>
                <?php endif; ?>
            </a>
        </div>

    </div>

    <form id="edit" method="post">
        <input type="hidden" name="id_mp" value="<?= $edit->id_mp ?>">

        <!-- Input field for Price -->
        <div class="form-group mt-3">
            <label for="price">Price</label>
            <input type="text" name="price" id="price_edit" placeholder="Material Pricing" class="form-control" value="<?= $edit->price ?>">
            <div class="error-text" id="price_error_edit"></div>
        </div>

        <medium class="mt-2 text-success">- Pricing by <strong><?= $edit->name_user_mp ?></strong> on <?= time_ago($edit->created_pricing) ?></medium>

        <?php if ($edit->id_user_updated !== null) : ?>
            <p>
                <medium class="mt-2 text-danger">- Last Updated by <strong><?= $edit->name_user_updated_mp ?></strong> on <?= time_ago($edit->updated_pricing) ?></medium>
            <?php endif; ?>

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
        // Ambil nilai dari input price_edit
        var priceInput = document.getElementById('price_edit').value;

        // Panggil formatRupiah dengan nilai dari input price_edit
        var formattedPrice = formatRupiah(priceInput);

        // Set nilai formattedPrice ke dalam input price_edit
        document.getElementById('price_edit').value = formattedPrice;
    });

    // Fungsi untuk mengubah angka menjadi format rupiah
    function formatRupiah(angka) {
        var number_string = angka.toString();
        var rupiah = number_string.replace(/\D/g, ''); // Mengambil hanya digit
        rupiah = parseInt(rupiah).toLocaleString('id-ID'); // Menggunakan toLocaleString untuk format rupiah
        return 'Rp ' + rupiah;
    }

    // Fungsi untuk mengubah input saat pengguna mengetik
    document.getElementById('price_edit').addEventListener('input', function(e) {
        var input = e.target;
        var value = input.value.replace(/\D/g, ''); // Mengambil hanya digit

        // Validate if the input contains only numbers
        if (value !== input.value) {
            input.value = value; // Set the input value to digits only
        }

        input.value = formatRupiah(value);
    });

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
            url: '<?= base_url('material_pricing/edit') ?>',
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
                        table_material_pricing();
                        $('#edit_material_pricing').modal('hide');
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
                    const errorFields = ['price'];

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