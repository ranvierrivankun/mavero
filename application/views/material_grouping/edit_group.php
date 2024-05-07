<style>
    #table_material {
        margin-bottom: 0;
        /* Menghapus margin bawah untuk mengurangi ruang kosong */
    }

    #table_material th,
    #table_material td {
        padding: 5px 10px;
        /* Mengurangi padding untuk membuat sel tabel lebih kecil */
        font-size: 14px;
        /* Mengurangi ukuran font untuk membuat teks lebih kecil */
    }

    #table_material th {
        background-color: transparent;
        /* Menggunakan latar belakang transparan untuk header kolom */
    }
</style>

<div class="modal-header bg-primary">
    <h5 class="modal-title white">Edit Group</h5>
    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
</div>

<form id="edit" method="post">
    <div class="modal-body">

        <input type="hidden" id="id_group" name="id_group" value="<?= $edit->id_group_db ?>">

        <!-- Input field for Name -->
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name_group" id="name_group_edit" placeholder="Name Group" class="form-control" value="<?= $edit->name_group ?>">
            <div class="error-text" id="name_group_error_edit"></div>
        </div>

        <!-- Input field for Material -->
        <div class="form-group">
            <label for="name">Add Material</label>
            <select id="material_edit" class="choices form-select" name="material[]" multiple>
                <!-- Opsi-opsi akan ditambahkan di sini oleh JavaScript -->
            </select>
        </div>

        <!-- Table with outer spacing -->
        <div class="table-responsive">
            <table id="table_material" class="table table-sm">
                <thead>
                    <tr>
                        <th width="5%">Action</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <br>

        <medium class="mt-2 text-success">- Created Group by <strong><?= $edit->name ?></strong> on <?= time_ago($edit->created_group) ?></medium>

        <?php if ($edit->id_user_updated !== null) : ?>
            <p>
                <medium class="mt-2 text-danger">- Last Updated by <strong><?= $edit->name_updated ?></strong> on <?= time_ago($edit->updated_group) ?></medium>
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
            url: '<?= base_url('material_grouping/edit') ?>',
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
                        table_material_grouping();
                        table_material();
                        $('#edit_group').modal('hide');
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
                    const errorFields = ['name_group'];

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

    // Menangani klik pada tombol "Delete"
    $('#table_material').on('click', '.delete', function(e) {
        e.preventDefault();

        const id_material = $(this).data('id_material');

        Swal.fire({
            title: 'Delete Material in Group',
            text: 'Are you sure you want to delete this material?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Please wait...',
                    text: 'Delete in progress',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    type: "post",
                    url: "<?= site_url('material_grouping/delete_material') ?>",
                    data: {
                        id_material: id_material,
                    },
                    dataType: "json",
                    success: function(response) {
                        Swal.close();
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                table_material();
                                table_material_grouping();
                            });
                        } else if (response.status === 'error') {
                            Swal.fire({
                                title: 'Error',
                                html: response.message,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire({
                            title: 'Error',
                            text: 'An error occurred. Please try again later.',
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    },
                });
            }
        });
    });

    function updateMaterialSelect() {
        $.ajax({
            url: "<?= site_url('material_grouping/get_material_options') ?>",
            type: "GET",
            dataType: "json",
            success: function(data) {
                // Dapatkan elemen <select> berdasarkan ID
                var selectMaterial = $('#material_edit');

                // Hapus semua opsi yang ada dalam elemen <select>
                selectMaterial.empty();

                // Tambahkan opsi-opsi berdasarkan data material
                $.each(data, function(key, value) {
                    selectMaterial.append('<option value="' + value.id_material + '">' + value.name + ' ' + value.size + ' ' + value.quantity + ' ' + value.unit + '</option>');
                });

                var editData = <?= json_encode($material) ?>;
                selectMaterial.val(editData.id_material);

                var select = new Choices(selectMaterial.get(0), {
                    removeItemButton: true,
                    maxItemCount: 5,
                });
            }
        });
    }
    updateMaterialSelect();

    /* Table Material Pricing */
    function table_material() {
        var id_group = $('#id_group').val();
        var table_material = $('#table_material').DataTable({
            paging: false,
            destroy: true,
            processing: true,
            serverSide: true,
            pageLength: -1,
            searching: false,
            ordering: true,
            ajax: {
                url: "<?= site_url('material_grouping/table_material') ?>",
                method: "POST",
                data: {
                    id_group: id_group
                },
            },
            initComplete: function() {
                // Hapus elemen tfoot lama (jika ada)
                $('#table_material tfoot').remove();
            },
            drawCallback: function() {
                // Memanggil calculateTotalPrice setiap kali tabel diperbarui (termasuk saat searching)
                calculateTotalPrice(id_group);
            },
        });
    }

    function calculateTotalPrice(id_group) {
        // Permintaan AJAX untuk mengambil total price
        $.ajax({
            url: "<?= site_url('material_grouping/calculateTotalPrice') ?>",
            type: "POST",
            data: {
                id_group: id_group
            },
            dataType: "json",
            success: function(data) {
                // Hapus elemen tfoot lama (jika ada)
                $('#table_material tfoot').remove();
                // Membuat elemen tfoot baru dengan colspan
                $('#table_material').append('<tfoot><tr><td colspan="5"><strong>Total Price:</strong> ' + data.total_price + '</td><td></td></tr></tfoot>');
            }
        });
    }

    table_material();
</script>