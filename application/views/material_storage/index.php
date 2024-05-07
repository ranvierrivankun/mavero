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
                        <li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url('material_storage') ?>"><?= $setting['title_page']; ?></a></li>
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
                                <button type="button" class="btn btn-secondary mr-2" id="btnFilter" onclick="table_material_storage()">Filter</button>
                                <button type="button" class="btn btn-light" id="btnRefresh">Refresh</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="card-body">

                            <!-- Table with outer spacing -->
                            <div class="table-responsive">
                                <table id="table_material_storage" class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th width="5%">Action</th>
                                            <th>Type</th>
                                            <th>Name</th>
                                            <th>Size</th>
                                            <th>Quantity</th>
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

<!-- Modal take_material -->
<div class="modal modal-borderless fade text-left" id="take_material" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="isi_modal_take"></div>
        </div>
    </div>
</div>

<!-- Modal list_material -->
<div class="modal modal-borderless fade text-left" id="list_material" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="isi_modal_list"></div>
        </div>
    </div>
</div>

<script>
    // Menangani klik pada tombol "list"
    $('#table_material_storage').on('click', '.list', function(e) {
        e.preventDefault();

        const id_material = $(this).data('id_material');
        $.ajax({
            url: "<?= site_url('material_storage/list_material') ?>",
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
                $('#list_material').modal('show');
                $('#isi_modal_list').html(data);

            }
        });
    });

    // Menangani klik pada tombol "take"
    $('#table_material_storage').on('click', '.take', function(e) {
        e.preventDefault();

        const id_material = $(this).data('id_material');
        $.ajax({
            url: "<?= site_url('material_storage/take_material') ?>",
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
                $('#take_material').modal('show');
                $('#isi_modal_take').html(data);

            }
        });
    });

    // Menangani klik pada tombol "Refresh"
    $("#btnRefresh").click(function() {
        // Memuat ulang tabel dengan data yang baru
        $('#selectType').val('');
        table_material_storage();
    });

    /* Table Material Storage */
    function table_material_storage() {
        $(document).ready(function() {
            var selectType = $('#selectType').val();
            var table_material_storage = $('#table_material_storage').DataTable({
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
                    url: "<?= site_url('material_storage/table_material_storage') ?>",
                    method: "POST",
                    data: {
                        selectType: selectType
                    }
                }
            });
        });
    }
    table_material_storage();
</script>