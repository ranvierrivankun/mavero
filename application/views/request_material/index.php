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
                        <li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url('request_material') ?>"><?= $setting['title_page']; ?></a></li>
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
                                <?php if (userdata('id_role') == 2) : ?>
                                    <button type="button" class="btn btn-primary btn-block" id="btnTambahMaterial">Tambah Material</button>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-4 col-12 mt-2 mt-md-0">
                                <select class="choices form-select" id="selectStatus">
                                    <option value="">Select Status</option>
                                    <option value="process">Process</option>
                                    <option value="pricing">Pricing</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>

                            <div class="col-md-4 col-12 mt-2 mt-md-0">
                                <select class="choices form-select" id="selectType">
                                    <option value="">Select Material Type</option>
                                    <?php foreach ($setting['type'] as $type) : ?>
                                        <option value="<?= $type->id_mt ?>"><?= $type->name_mt ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="btn-group col-md-2 col-12 mt-2 mt-md-0">
                                <button type="button" class="btn btn-secondary mr-2" id="btnFilter" onclick="table_request_material()">Filter</button>
                                <button type="button" class="btn btn-light" onclick="table_request_material()">Refresh</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="card-body">

                            <!-- Table with outer spacing -->
                            <div class="table-responsive">
                                <table id="table_request_material" class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th width="5%">Action</th>
                                            <th>Type</th>
                                            <th>Name</th>
                                            <th>Size</th>
                                            <th>Quantity</th>
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

<!-- Modal tambah_material -->
<div class="modal modal-borderless fade text-left" id="tambah_material" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="isi_modal_tambah"></div>
        </div>
    </div>
</div>

<!-- Modal edit_material -->
<div class="modal modal-borderless fade text-left" id="edit_material" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="isi_modal_edit"></div>
        </div>
    </div>
</div>

<!-- Modal detail_material -->
<div class="modal modal-borderless fade text-left" id="detail_material" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="isi_modal_detail"></div>
        </div>
    </div>
</div>

<script>
    // Menangani klik pada tombol "Detail"
    $('#table_request_material').on('click', '.detail', function(e) {
        e.preventDefault();

        const id_material = $(this).data('id_material');
        $.ajax({
            url: "<?= site_url('request_material/detail_material') ?>",
            async: true,
            type: 'post',
            data: {
                id_material: id_material,
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
                $('#detail_material').modal('show');
                $('#isi_modal_detail').html(data);

            }
        });
    });

    // Menangani klik pada tombol "Tambah Material"
    $("#btnTambahMaterial").click(function() {

        // Membuat Variabel select untuk Choices
        let select;

        // Hancurkan variabel select setiap kali mengklik tambah_material
        if (select) {
            select.destroy();
        }

        $.ajax({
            url: "<?= site_url('request_material/tambah_material') ?>",
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
                $('#tambah_material').modal('show');
                $('#isi_modal_tambah').html(data);

                // Inisialisasi kembali objek Choices setelah modal ditampilkan
                select = new Choices('.choices-mt', {
                    searchEnabled: true,
                });
            }
        });
    });

    // Menangani klik pada tombol "Edit"
    $('#table_request_material').on('click', '.edit', function(e) {
        e.preventDefault();

        // Membuat Variabel select untuk Choices
        let select;

        // Hancurkan variabel select setiap kali mengklik tambah_material
        if (select) {
            select.destroy();
        }

        const id_material = $(this).data('id_material');
        $.ajax({
            url: "<?= site_url('request_material/edit_material') ?>",
            async: true,
            type: 'post',
            data: {
                id_material: id_material,
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
                $('#edit_material').modal('show');
                $('#isi_modal_edit').html(data);

                // Inisialisasi kembali objek Choices setelah modal ditampilkan
                select = new Choices('.choices-mt-edit', {
                    searchEnabled: true,
                });
            }
        });
    });

    // Menangani klik pada tombol "Delete"
    $('#table_request_material').on('click', '.delete', function(e) {
        e.preventDefault();

        const id_material = $(this).data('id_material');

        Swal.fire({
            title: 'Delete Request Material',
            text: 'Are you sure you want to delete this item?',
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
                            url: "<?= site_url('request_material/delete') ?>",
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
                                        table_request_material();
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
        $('#selectType').val('');
        $('#selectStatus').val('');
        table_request_material();
    });

    /* Table Request Material */
    function table_request_material() {
        $(document).ready(function() {
            var selectType = $('#selectType').val();
            var selectStatus = $('#selectStatus').val();
            var table_request_material = $('#table_request_material').DataTable({
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
                    url: "<?= site_url('request_material/table_request_material') ?>",
                    method: "POST",
                    data: {
                        selectType: selectType,
                        selectStatus: selectStatus
                    }
                }
            });
        });
    }
    table_request_material();
</script>