<style>
    .btn-custom {
        margin-top: 15px;
    }
</style>

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
                        <li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url('material_out') ?>"><?= $setting['title_page']; ?></a></li>
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
                                <input type="text" class="range form-control" id="date_range" placeholder="Select Date" readonly>
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
                                <button type="button" class="btn btn-secondary mr-2" id="btnFilter" onclick="table_material_out()">Filter</button>
                                <button type="button" class="btn btn-light" id="btnRefresh">Refresh</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="card-body">

                            <!-- Table with outer spacing -->
                            <div class="table-responsive">
                                <table id="table_material_out" class="table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
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

<script>
    // Menangani klik pada tombol "Refresh"
    $("#btnRefresh").click(function() {
        // Memuat ulang tabel dengan data yang baru
        $('#date_range').val(null);
        $('#selectType').val('');
        table_material_out();
    });

    /* Table Material Storage */
    function table_material_out() {
        $(document).ready(function() {
            var tanggal = $('#date_range').val();
            var tanggalArray = tanggal.split(' to ');

            var tgl1 = tanggalArray[0];
            var tgl2 = tanggalArray[1];

            var selectType = $('#selectType').val();

            var currentDate = new Date().toLocaleDateString();

            var table_material_out = $('#table_material_out').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                dom: 'lf<"custom-buttons"B>rtip',
                buttons: [{
                        extend: 'print',
                        text: '<i class="bi bi-printer"></i> Print', // Simbol kustom untuk print
                        className: 'btn-custom',
                        title: 'Material Out', // Judul kustom untuk mencetak
                        messageTop: 'Tanggal: ' + currentDate // Tambahkan tanggal di atas tabel
                    },
                    {
                        extend: 'excel',
                        text: '<i class="bi bi-file-earmark-excel"></i> Excel', // Simbol kustom untuk Excel
                        className: 'btn-custom',
                        title: 'Material Out', // Judul kustom untuk ekspor Excel
                        messageTop: 'Tanggal: ' + currentDate, // Tambahkan tanggal di atas tabel
                        filename: 'Material_Out_' + currentDate // Tambahkan tanggal ke dalam nama berkas Excel
                    }
                ],
                ordering: true, // Mengaktifkan fitur pengurutan
                ajax: {
                    url: "<?= site_url('material_out/table_material_out') ?>",
                    method: "POST",
                    data: {
                        selectType: selectType,
                        tgl1: tgl1,
                        tgl2: tgl2,
                    }
                }
            });
        });
    }
    table_material_out();
</script>