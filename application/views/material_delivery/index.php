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
                        <li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url('material_delivery') ?>"><?= $setting['title_page']; ?></a></li>
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

                            <div class="col-md-2 col-12 mt-2 mt-md-0">
                            </div>

                            <div class="col-md-4 col-12 mt-2 mt-md-0">
                            </div>

                            <div class="col-md-4 col-12 mt-2 mt-md-0">
                                <select class="choices form-select" id="selectStatus">
                                    <option value="">Select Status</option>
                                    <?php if (userdata('id_role') == '1' || userdata('id_role') == '3') : ?>
                                        <option value="pending">Pending</option>
                                    <?php endif ?>
                                    <option value="sending">Sending</option>
                                    <option value="received">Received</option>
                                </select>
                            </div>

                            <div class="btn-group col-md-2 col-12 mt-2 mt-md-0">
                                <button type="button" class="btn btn-secondary mr-2" id="btnFilter" onclick="table_material_delivery()">Filter</button>
                                <button type="button" class="btn btn-light" id="btnRefresh">Refresh</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="card-body">

                            <!-- Table with outer spacing -->
                            <div class="table-responsive">
                                <table id="table_material_delivery" class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th width="5%">Action</th>
                                            <th>No. Resi</th>
                                            <th>Created</th>
                                            <th>Group</th>
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

<!-- Modal Send -->
<div class="modal modal-borderless fade text-left" data-bs-focus="false" id="send_material" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="isi_modal_send"></div>
        </div>
    </div>
</div>

<!-- Modal edit_send -->
<div class="modal modal-borderless fade text-left" data-bs-focus="false" id="edit_send" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="isi_modal_edit"></div>
        </div>
    </div>
</div>

<!-- Modal detail_send -->
<div class="modal modal-borderless fade text-left" data-bs-focus="false" id="detail_send" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="isi_modal_detail"></div>
        </div>
    </div>
</div>

<script>
    // Menangani klik pada tombol "Accept"
    $('#table_material_delivery').on('click', '.accept', function(e) {
        e.preventDefault();

        const id_group = $(this).data('id_group_db');

        Swal.fire({
            title: 'Accept Material Delivery',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            confirmButtonText: 'Yes, Accept',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Please wait...',
                    text: 'Accept in progress',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    type: "post",
                    url: "<?= site_url('material_delivery/accept') ?>",
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
                                table_material_delivery();
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

    // Menangani klik pada tombol "Detail"
    $('#table_material_delivery').on('click', '.detail', function(e) {
        e.preventDefault();

        const id_group = $(this).data('id_group_db');
        $.ajax({
            url: "<?= site_url('material_delivery/detail_send') ?>",
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
                $('#detail_send').modal('show');
                $('#isi_modal_detail').html(data);

            }
        });
    });

    // Menangani klik pada tombol "Edit"
    $('#table_material_delivery').on('click', '.edit', function(e) {
        e.preventDefault();

        const id_group = $(this).data('id_group_db');
        $.ajax({
            url: "<?= site_url('material_delivery/edit_send') ?>",
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
                $('#edit_send').modal('show');
                $('#isi_modal_edit').html(data);

            }
        });
    });

    // Menangani klik pada tombol "Send"
    $('#table_material_delivery').on('click', '.send', function(e) {
        e.preventDefault();

        const id_group = $(this).data('id_group_db');
        $.ajax({
            url: "<?= site_url('material_delivery/send_material') ?>",
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
                $('#send_material').modal('show');
                $('#isi_modal_send').html(data);
            }
        });
    });

    // Menangani klik pada tombol "Refresh"
    $("#btnRefresh").click(function() {
        // Memuat ulang tabel dengan data yang baru
        table_material_delivery();
        $('#selectStatus').val('');
    });

    /* Table Material Pricing */
    function table_material_delivery() {
        $(document).ready(function() {

            var selectStatus = $('#selectStatus').val();
            var table_material_delivery = $('#table_material_delivery').DataTable({
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
                    url: "<?= site_url('material_delivery/table_material_delivery') ?>",
                    method: "POST",
                    data: {
                        selectStatus: selectStatus
                    }
                }
            });
        });
    }
    table_material_delivery();
</script>