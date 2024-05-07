<style>
    #table_list_material {
        margin-bottom: 0;
        /* Menghapus margin bawah untuk mengurangi ruang kosong */
    }

    #table_list_material th,
    #table_list_material td {
        padding: 5px 10px;
        /* Mengurangi padding untuk membuat sel tabel lebih kecil */
        font-size: 14px;
        /* Mengurangi ukuran font untuk membuat teks lebih kecil */
    }

    #table_list_material th {
        background-color: transparent;
        /* Menggunakan latar belakang transparan untuk header kolom */
    }
</style>

<div class="modal-header bg-primary">
    <h5 class="modal-title white">List Material Out</h5>
    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
</div>

<div class="modal-body">

    <input type="hidden" id="id_material" name="id_material" value="<?= $edit->id_material ?>">

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

        <!-- Table with outer spacing -->
        <div class="table-responsive">
            <table id="table_list_material" class="table table-sm">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>

</div>

<div class="modal-footer">
    <!-- Close button -->
    <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal">
        Close
    </button>

    <script>
        /* Table Material Pricing */
        function table_list_material() {
            var id_material = $('#id_material').val();
            var table_list_material = $('#table_list_material').DataTable({
                paging: false,
                info: false,
                destroy: true,
                processing: true,
                serverSide: true,
                pageLength: -1,
                searching: false,
                ordering: true,
                ajax: {
                    url: "<?= site_url('material_storage/table_list_material') ?>",
                    method: "POST",
                    data: {
                        id_material: id_material
                    },
                }
            });
        }
        table_list_material()
    </script>