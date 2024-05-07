<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last mb-3">
                <h3><?= $setting['title_page'] ?></h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url('material_grouping') ?>"><?= $setting['title_page']; ?></a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section id="basic-horizontal-layouts">
        <div class="row match-height">
            <div class="col-md-12 col-12">

                <div class="card">

                    <div class="card-header">
                        <div class="row">

                            <div class="col-md-2 col-12">
                                <?php if (userdata('id_role') == 3) : ?>
                                    <button type="button" class="btn btn-primary btn-block" id="btnTambahGroup">Tambah Group</button>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-4 col-12 mt-2 mt-md-0">
                            </div>

                            <div class="col-md-4 col-12 mt-2 mt-md-0">
                                <select class="choices form-select" id="selectStatus">
                                    <option value="">Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="sending">Sending</option>
                                    <option value="received">Received</option>
                                </select>
                            </div>

                            <div class="btn-group col-md-2 col-12 mt-2 mt-md-0">
                                <button type="button" class="btn btn-secondary mr-2" id="btnFilter" onclick="table_material_grouping()">Filter</button>
                                <button type="button" class="btn btn-light" id="btnRefresh">Refresh</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="card-body">

                            <!-- Table with outer spacing -->
                            <div class="table-responsive">
                                <table id="table_material_grouping" class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th width="5%">Action</th>
                                            <th>Name</th>
                                            <th>Procurement</th>
                                            <th>Created</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
</div>

<!-- Modal tambah_group -->
<div class="modal modal-borderless fade text-left" id="tambah_group" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="isi_modal_tambah"></div>
        </div>
    </div>
</div>

<!-- Modal edit_group -->
<div class="modal modal-borderless fade text-left" data-bs-focus="false" id="edit_group" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="isi_modal_edit"></div>
        </div>
    </div>
</div>

<!-- Modal detail_group -->
<div class="modal modal-borderless fade text-left" id="detail_group" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="isi_modal_detail"></div>
        </div>
    </div>
</div>

<script>
    // Menangani klik pada tombol "Detail"
    $('#table_material_grouping').on('click', '.detail', function(e) {
        e.preventDefault();

        const id_group = $(this).data('id_group');
        $.ajax({
            url: "<?= site_url('material_grouping/detail_group') ?>",
            async: true,
            type: 'post',
            data: {
                id_group: id_group,
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Please wait...',
                    text: 'Modal in progress',
                    didOpen: function() {
                        Swal.showLoading();
                    }
                });
            },
            success: function(data) {
                Swal.close();
                $('#detail_group').modal('show');
                $('#isi_modal_detail').html(data);

            }
        });
    });

    // Menangani klik pada tombol "Edit"
    $('#table_material_grouping').on('click', '.edit', function(e) {
        e.preventDefault();

        // Membuat Variabel select untuk Choices
        let choices;

        // Hancurkan variabel choices setiap kali mengklik tambah_material
        if (choices) {
            choices.destroy();
        }

        const id_group = $(this).data('id_group');
        $.ajax({
            url: "<?= site_url('material_grouping/edit_group') ?>",
            async: true,
            type: 'post',
            data: {
                id_group: id_group,
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Please wait...',
                    text: 'Modal in progress',
                    didOpen: function() {
                        Swal.showLoading();
                    }
                });
            },
            success: function(data) {
                Swal.close();
                $('#edit_group').modal('show');
                $('#isi_modal_edit').html(data);

                // Memilih elemen .choices dengan class "multiple-remove"
                choices = document.querySelectorAll('.multiple-remove');

                // Loop melalui elemen-elemen .choices dan inisialisasi Choices.js
                choices.forEach((choice) => {
                    let initChoice;
                    if (choice.classList.contains("multiple-remove")) {
                        initChoice = new Choices(choice, {
                            delimiter: ',',
                            editItems: true,
                            maxItemCount: -1,
                            removeItemButton: true,
                        });
                    } else {
                        initChoice = new Choices(choice);
                    }
                });

            }
        });
    });

    // Menangani klik pada tombol "Tambah Material"
    $("#btnTambahGroup").click(function() {
        // Membuat Variabel select untuk Choices
        let choices;

        // Hancurkan variabel choices setiap kali mengklik tambah_material
        if (choices) {
            choices.destroy();
        }

        $.ajax({
            url: "<?= site_url('material_grouping/tambah_group') ?>",
            async: true,
            beforeSend: () => {
                Swal.fire({
                    title: 'Please wait...',
                    text: 'Modal in progress',
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(data) {
                Swal.close();
                $('#tambah_group').modal('show');
                $('#isi_modal_tambah').html(data);

                // Memilih elemen .choices dengan class "multiple-remove"
                choices = document.querySelectorAll('.multiple-remove');

                // Loop melalui elemen-elemen .choices dan inisialisasi Choices.js
                choices.forEach((choice) => {
                    let initChoice;
                    if (choice.classList.contains("multiple-remove")) {
                        initChoice = new Choices(choice, {
                            delimiter: ',',
                            editItems: true,
                            maxItemCount: -1,
                            removeItemButton: true,
                        });
                    } else {
                        initChoice = new Choices(choice);
                    }
                });
            }
        });
    });

    // Menangani klik pada tombol "Delete"
    $('#table_material_grouping').on('click', '.delete', function(e) {
        e.preventDefault();

        const id_group = $(this).data('id_group');

        Swal.fire({
            title: 'Delete Group',
            text: 'Are you sure you want to delete this group?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Confirm Deletion',
                    text: 'Are you absolutely sure? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel',
                }).then((innerResult) => {
                    if (innerResult.isConfirmed) {
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
                            url: "<?= site_url('material_grouping/delete') ?>",
                            data: {
                                id_group: id_group,
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
            }
        });
    });

    // Menangani klik pada tombol "Refresh"
    $("#btnRefresh").click(function() {
        // Memuat ulang tabel dengan data yang baru
        table_material_grouping();
        $('#selectStatus').val('');
    });

    /* Table Material Pricing */
    function table_material_grouping() {
        $(document).ready(function() {

            var selectStatus = $('#selectStatus').val();
            var table_material_grouping = $('#table_material_grouping').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                ordering: true, // Mengaktifkan fitur pengurutan
                ajax: {
                    url: "<?= site_url('material_grouping/table_material_grouping') ?>",
                    method: "POST",
                    data: {
                        selectStatus: selectStatus
                    }
                }
            });
        });
    }
    table_material_grouping();
</script>