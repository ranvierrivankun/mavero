<div class="modal-header bg-secondary">
    <h5 class="modal-title white">Detail Material</h5>
    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
</div>

<div class="modal-body">

    <div class="content">
        <div class="list-group">
            <a class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h4 class="mb-1">Request Material</h4>
                    <small><?= time_ago($edit->created) ?></small>
                </div>
                <p class="mb-1">
                    <em>(<?= $edit->name_mt ?>)</em> <?= $edit->description ?>
                </p>

                <hr class="mx-auto" style="width: 80%;">

                <p class="mb-1">
                    <medium>Name: <?= $edit->name_material ?></medium>
                </p>
                <p class="mb-1">
                    <medium>Size: <?= $edit->size ?></medium>
                </p>
                <p class="mb-3">
                    <medium>Quantity: <?= $edit->quantity ?> <?= $edit->unit ?></medium>
                </p>

                <p class="mb-3">
                    <?php if ($edit->status == 'pricing') : ?>
                        <span class="badge bg-success">Pricing</span>
                    <?php elseif ($edit->status == 'rejected') : ?>
                        <span class="badge bg-danger">Rejected</span>
                    <?php endif; ?>
                </p>

                <hr class="mx-auto">

                <p class="mb-1"><small>- Request by <strong><?= $edit->name_user ?></strong> (<?= $edit->created ?>)</small></p>
                <?php if ($edit->id_user_updated !== null) : ?>
                    <p class="mb-1">
                        <small>- Last Updated by <strong><?= $edit->name_user_updated ?></strong> (<?= $edit->updated ?>)</small>
                    </p>
                <?php endif; ?>
            </a>
        </div>

    </div>

    <?php if ($edit->status == 'pricing') : ?>

        <!-- Input field for Price -->
        <div class="form-group mt-3">
            <label for="price">Price</label>
            <input type="text" name="price" id="price_detail" placeholder="Material Pricing" class="form-control" value="<?= $edit->price ?>" disabled>
            <div class="error-text" id="price_error_edit"></div>
        </div>

        <medium class="mt-2 text-success">- Pricing by <strong><?= $edit->name_user_mp ?></strong> on <?= time_ago($edit->created_pricing) ?></medium>

        <?php if ($edit->id_user_updated !== null) : ?>
            <p>
                <medium class="mt-2 text-danger">- Last Updated by <strong><?= $edit->name_user_updated_mp ?></strong> on <?= time_ago($edit->updated_pricing) ?></medium>
            <?php endif; ?>

        <?php endif; ?>

</div>

<div class="modal-footer">
    <!-- Close button -->
    <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal">
        Close
    </button>
</div>

<script>
    $(document).ready(function() {
        // Ambil nilai dari input price_detail
        var priceInput = document.getElementById('price_detail').value;

        // Panggil formatRupiah dengan nilai dari input price_detail
        var formattedPrice = formatRupiah(priceInput);

        // Set nilai formattedPrice ke dalam input price_detail
        document.getElementById('price_detail').value = formattedPrice;
    });

    // Fungsi untuk mengubah angka menjadi format rupiah
    function formatRupiah(angka) {
        var number_string = angka.toString();
        var rupiah = number_string.replace(/\D/g, ''); // Mengambil hanya digit
        rupiah = parseInt(rupiah).toLocaleString('id-ID'); // Menggunakan toLocaleString untuk format rupiah
        return 'Rp ' + rupiah;
    }
</script>