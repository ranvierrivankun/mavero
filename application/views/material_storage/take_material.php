<div class="modal-header bg-success">
    <h5 class="modal-title white">Take Material</h5>
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
                    <h4 class="mb-1"><?= $edit->name ?></h4>
                </div>
                <p class="mb-1">
                    <medium>Type: <?= $edit->name_mt ?></medium>
                </p>
                <p class="mb-1">
                    <medium>Size: <?= $edit->size ?></medium>
                </p>
                <p class="mb-3">
                    <medium>Quantity: <?= $edit->quantity ?> <?= $edit->unit ?></medium>
                </p>
            </a>
        </div>

    </div>

    <form id="tambah" method="post">
        <input type="hidden" name="id_material" value="<?= $edit->id_material ?>">

        <!-- Input field for Quantity -->
        <div class="form-group mt-3">
            <label for="quantity">Quantity <span>/ <?= $edit->unit ?></span></label>
            <input type="number" name="quantity" id="quantity" placeholder="Enter the quantity of material taken" class="form-control" max="<?= $edit->quantity ?>">
            <div class="error-text" id="quantity_error"></div>
        </div>


</div>

<div class="modal-footer">
    <!-- Close button -->
    <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal">
        Close
    </button>

    <!-- Submit button -->
    <button type="button" id="submit_take" class="btn btn-success ml-1">
        Submit
    </button>
</div>
</form>

<script>
    // Mendapatkan elemen input quantity
    var quantityInput = document.getElementById('quantity');

    // Mendengarkan perubahan pada input quantity
    quantityInput.addEventListener('input', function() {
        var enteredValue = this.value.trim(); // Menghapus spasi di awal dan akhir

        // Mendapatkan nilai maksimum dari atribut max
        var maxQuantity = parseInt(quantityInput.getAttribute('max'));

        // Menghapus semua karakter selain angka
        enteredValue = enteredValue.replace(/[^0-9]/g, '');

        // Validasi agar tidak melebihi nilai maksimum atau kurang dari 1
        if (enteredValue === '') {
            this.value = ''; // Biarkan kosong jika tidak ada angka
        } else {
            enteredValue = parseInt(enteredValue);

            if (enteredValue > maxQuantity) {
                this.value = maxQuantity; // Atur nilai input ke nilai maksimum
            } else if (enteredValue < 1) {
                this.value = 1; // Atur nilai input ke minimum 1 jika kurang dari itu
            } else {
                this.value = enteredValue; // Set nilai yang telah difilter
            }
        }
    });

    // Menambahkan event listener pada tombol submit
    document.getElementById('submit_take').addEventListener('click', function() {
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
            url: '<?= base_url('material_storage/take') ?>',
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
                        table_material_storage();
                        $('#take_material').modal('hide');
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
                    const errorFields = ['quantity'];

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