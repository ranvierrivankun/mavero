<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last mb-3">
                <h3><?= $setting['title_page'] ?></h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Administration Menu</li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url('material_setting') ?>"><?= $setting['title_page']; ?></a></li>
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

                            <div class="col-md-3 col-12">
                                <button type="button" class="btn btn-primary btn-block" id="btnTambahMaterialType">Tambah Material Type</button>
                            </div>

                            <div class="col-md-7 col-12">
                            </div>

                            <div class="btn-group col-md-2 col-12 mt-2 mt-md-0">
                                <button type="button" class="btn btn-light" id="btnRefresh">Refresh</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="card-body">

                            <!-- Table with outer spacing -->
                            <div class="table-responsive">
                                <table id="table_material_type" class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th width="5%">Action</th>
                                            <th>Name</th>
                                            <th>Description</th>
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

<!-- Modal tambah_material_type -->
<div class="modal modal-borderless fade text-left" id="tambah_material_type" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div id="isi_modal_tambah"></div>
        </div>
    </div>
</div>

<!-- Modal edit_material_type -->
<div class="modal modal-borderless fade text-left" id="edit_material_type" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div id="isi_modal_edit"></div>
        </div>
    </div>
</div>

<script>
    // Menangani klik pada tombol "Tambah Material"
    $("#btnTambahMaterialType").click(function() {

        $.ajax({
            url: "<?= site_url('material_setting/tambah_material_type') ?>",
            async: true,
            beforeSend: () => {
                Swal.fire({
                    title: 'Please wait...',
                    text: 'Modal in progress',
                    didOpen: () => {
                        Swal.showLoading();
                    }
                })
            },
            success: function(data) {
                Swal.close();
                $('#tambah_material_type').modal('show');
                $('#isi_modal_tambah').html(data);
            }
        });
    });

    // Menangani klik pada tombol "Edit"
    $('#table_material_type').on('click', '.edit', function(e) {
        e.preventDefault();

        const id_mt = $(this).data('id_mt');
        $.ajax({
            url: "<?= site_url('material_setting/edit_material_type') ?>",
            async: true,
            type: 'post',
            data: {
                id_mt: id_mt,
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
                $('#edit_material_type').modal('show');
                $('#isi_modal_edit').html(data);

            }
        });
    });

    // Menangani klik pada tombol "Delete"
    $('#table_material_type').on('click', '.delete', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Delete Material Type',
            text: "To confirm deletion, please type 'DELETE' below:",
            icon: 'question',
            input: 'text', // Menambahkan input teks
            inputPlaceholder: 'Type "DELETE" here', // Pesan placeholder untuk input
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            inputValidator: (value) => { // Validasi input
                if (value.trim() !== 'DELETE') {
                    return 'Please type "DELETE" to confirm.';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const id_mt = $(this).data('id_mt');

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
                    url: "<?= site_url('material_setting/delete') ?>",
                    data: {
                        id_mt: id_mt,
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
                                table_material_type();
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

    // Menangani klik pada tombol "Refresh"
    $("#btnRefresh").click(function() {
        // Memuat ulang tabel dengan data yang baru
        $('#selectType').val('');
        table_material_type();
    });

    /* Table Request Material */
    function table_material_type() {
        $(document).ready(function() {
            var table_material_type = $('#table_material_type').DataTable({
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
                    url: "<?= site_url('material_setting/table_material_type') ?>",
                    method: "POST",
                    data: {}
                }
            });
        });
    }
    table_material_type();
</script>