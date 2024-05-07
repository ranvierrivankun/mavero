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
                        <li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url('material_pricing') ?>"><?= $setting['title_page']; ?></a></li>
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
                                    <?php if (userdata('id_role' == '1')) : ?>
                                        <option value="rejected">Rejected</option>
                                    <?php endif ?>
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
                                <button type="button" class="btn btn-secondary mr-2" id="btnFilter" onclick="table_material_pricing()">Filter</button>
                                <button type="button" class="btn btn-light" id="btnRefresh">Refresh</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="card-body">

                            <!-- Table with outer spacing -->
                            <div class="table-responsive">
                                <table id="table_material_pricing" class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th width="5%">Action</th>
                                            <th>Type</th>
                                            <th>Name</th>
                                            <th>Size</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tfooter>
                                        <div id="total_price"></div>
                                    </tfooter>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
</div>

<!-- Modal material_pricing -->
<div class="modal modal-borderless fade text-left" id="material_pricing" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="isi_modal_pricing"></div>
        </div>
    </div>
</div>

<!-- Modal edit_material_pricing -->
<div class="modal modal-borderless fade text-left" id="edit_material_pricing" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="isi_modal_edit"></div>
        </div>
    </div>
</div>

<!-- Modal detail_material_pricing -->
<div class="modal modal-borderless fade text-left" id="detail_material_pricing" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="isi_modal_detail"></div>
        </div>
    </div>
</div>

<script>
    // Menangani klik pada tombol "Detail"
    $('#table_material_pricing').on('click', '.detail', function(e) {
        e.preventDefault();

        const id_material = $(this).data('id_material');
        $.ajax({
            url: "<?= site_url('material_pricing/detail_material_pricing') ?>",
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
                $('#detail_material_pricing').modal('show');
                $('#isi_modal_detail').html(data);

            }
        });
    });

    // Menangani klik pada tombol "Edit"
    $('#table_material_pricing').on('click', '.edit', function(e) {
        e.preventDefault();

        const id_material = $(this).data('id_material');
        $.ajax({
            url: "<?= site_url('material_pricing/edit_material_pricing') ?>",
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
                $('#edit_material_pricing').modal('show');
                $('#isi_modal_edit').html(data);

            }
        });
    });

    // Menangani klik pada tombol "Pricing"
    $('#table_material_pricing').on('click', '.pricing', function(e) {
        e.preventDefault();

        const id_material = $(this).data('id_material');
        $.ajax({
            url: "<?= site_url('material_pricing/material_pricing') ?>",
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
                $('#material_pricing').modal('show');
                $('#isi_modal_pricing').html(data);

            }
        });
    });

    // Menangani klik pada tombol "Reject"
    $('#table_material_pricing').on('click', '.reject', function(e) {
        e.preventDefault();

        const id_material = $(this).data('id_material');

        Swal.fire({
            title: 'Reject Request Material',
            text: 'Are you sure you want to reject this request?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, Reject',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Confirm Rejection',
                    text: 'Are you absolutely sure? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, Reject',
                    cancelButtonText: 'Cancel',
                }).then((innerResult) => {
                    if (innerResult.isConfirmed) {
                        Swal.fire({
                            title: 'Please wait...',
                            text: 'Reject in progress',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            type: "post",
                            url: "<?= site_url('material_pricing/reject') ?>",
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
                                        table_material_pricing();
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
        table_material_pricing();
    });

    /* Table Material Pricing */
    function table_material_pricing() {
        $(document).ready(function() {
            var selectType = $('#selectType').val();
            var selectStatus = $('#selectStatus').val();
            var table_material_pricing = $('#table_material_pricing').DataTable({
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
                    url: "<?= site_url('material_pricing/table_material_pricing') ?>",
                    method: "POST",
                    data: {
                        selectType: selectType,
                        selectStatus: selectStatus,
                    }
                },
                initComplete: function() {
                    // Hapus elemen tfoot lama (jika ada)
                    $('#table_material_pricing tfoot').remove();
                },
                footerCallback: function(row, data, start, end, display, settings) {
                    // Setelah tabel selesai dimuat, tampilkan total Rupiah
                    $.ajax({
                        url: "<?= site_url('material_pricing/table_material_pricing') ?>",
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            // Hapus elemen tfoot lama (jika ada)
                            $('#table_material_pricing tfoot').remove();
                            // Membuat elemen tfoot baru dengan colspan
                            $('#table_material_pricing').append('<tfoot><tr><td colspan="5"><strong>Total Price:</strong> ' + data.total_rupiah + '</td><td></td></tr></tfoot>');
                        }
                    });
                },
            });
        });
    }
    table_material_pricing();
</script>