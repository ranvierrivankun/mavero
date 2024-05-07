<style>
    #table_material_detail {
        margin-bottom: 0;
        /* Menghapus margin bawah untuk mengurangi ruang kosong */
    }

    #table_material_detail th,
    #table_material_detail td {
        padding: 5px 10px;
        /* Mengurangi padding untuk membuat sel tabel lebih kecil */
        font-size: 14px;
        /* Mengurangi ukuran font untuk membuat teks lebih kecil */
    }

    #table_material_detail th {
        background-color: transparent;
        /* Menggunakan latar belakang transparan untuk header kolom */
    }
</style>

<div class="modal-header bg-secondary">
    <h5 class="modal-title white">Detail Group</h5>
    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
</div>

<div class="modal-body">

    <input type="hidden" id="id_group_detail" name="id_group" value="<?= $edit->id_group_db ?>">

    <!-- Input field for Name -->
    <div class="form-group">

        <?php if ($edit->status_group == 'pending') : ?>
            <span class="badge bg-warning mb-2">Pending</span>
        <?php elseif ($edit->status_group == 'sending') : ?>
            <span class="badge bg-success mb-2">Sending</span>
        <?php endif; ?>

        <h3><?= $edit->name_group ?></h3>

    </div>

    <!-- Table with outer spacing -->
    <div class="table-responsive">
        <table id="table_material_detail" class="table table-sm">
            <thead>
                <tr>
                    <th width="5%">No.</th>
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

        <?php if ($edit->id_user_received !== null) : ?>
        <p>
            <medium class="mt-2 text-primary">- Received by <strong><?= $edit->name_received ?></strong> on <?= time_ago($edit->received) ?></medium>
        <?php endif; ?>

</div>

<div class="modal-footer">
    <!-- Close button -->
    <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal">
        Close
    </button>
</div>

<script>
    /* Table Material Pricing */
    function table_material_detail() {
        var id_group = $('#id_group_detail').val();
        var table_material_detail = $('#table_material_detail').DataTable({
            paging: false,
            info: false,
            destroy: true,
            processing: true,
            serverSide: true,
            pageLength: -1,
            searching: false,
            ordering: true,
            ajax: {
                url: "<?= site_url('material_grouping/table_material_detail') ?>",
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
                $('#table_material_detail tfoot').remove();
                // Membuat elemen tfoot baru dengan colspan
                $('#table_material_detail').append('<tfoot><tr><td colspan="5"><strong>Total Price:</strong> ' + data.total_price + '</td><td></td></tr></tfoot>');
            }
        });
    }

    table_material_detail();
</script>