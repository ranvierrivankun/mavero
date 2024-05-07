<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last mb-3">
                <h3><?= $setting['title_page'] ?></h3>
                <!-- <p class="text-subtitle text-muted">Dashboard merupakan halaman utama yang memberikan gambaran umum tentang informasi penting dan aktivitas terkini.</p> -->
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                </nav>
            </div>
        </div>
    </div>

    <div class="page-content">

        <section class="row">
            <div class="col-12 col-lg-12">

                <div class="row">

                    <?php if (userdata('id_role') == 2 || userdata('id_role') == 1) : ?>
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-header p-3 mb-3">
                                    <h3>Request Material</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col col-xxl-2 d-flex justify-content-start">
                                            <div class="stats-icon bg-warning">
                                                <i class="iconly-boldBuy"></i>
                                            </div>
                                        </div>
                                        <div class="col col-xxl-5">
                                            <h6 class="text-muted font-semibold">Request This Month</h6>
                                            <h6 class="font-extrabold mb-0"><?= $TotalRequestMaterialMonth ?></h6>
                                        </div>
                                        <div class="col col-xxl-5">
                                            <h6 class="text-muted font-semibold">Total Request</h6>
                                            <h6 class="font-extrabold mb-0"><?= $TotalRequestMaterial ?></h6>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col col-xxl-4">
                                            <h6 class="text-warning font-semibold">Process</h6>
                                            <h6 class="font-extrabold mb-0"><?= $totalProcess ?></h6>
                                        </div>
                                        <div class="col col-xxl-4">
                                            <h6 class="text-success font-semibold">Pricing</h6>
                                            <h6 class="font-extrabold mb-0"><?= $totalPricing ?></h6>
                                        </div>
                                        <div class="col col-xxl-4">
                                            <h6 class="text-danger font-semibold">Rejected</h6>
                                            <h6 class="font-extrabold mb-0"><?= $totalRejected ?></h6>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col text-end">
                                            <a href="<?= base_url('/request_material') ?>" class="btn btn-secondary btn-sm">
                                                See More
                                            </a>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (userdata('id_role') == 3 || userdata('id_role') == 1) : ?>
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-header p-3 mb-3">
                                    <h3>Material Pricing</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col col-xxl-2 d-flex justify-content-start">
                                            <div class="stats-icon bg-success">
                                                <i class="iconly-boldTick-square"></i>
                                            </div>
                                        </div>
                                        <div class="col col-xxl-5">
                                            <h6 class="text-muted font-semibold">Pricing This Month</h6>
                                            <h6 class="font-extrabold mb-0"><?= rupiah($TotalPricingMaterialMonthRupiah) ?> (<?= $TotalPricingMaterialMonth ?>)</h6>
                                        </div>
                                        <div class="col col-xxl-5">
                                            <h6 class="text-muted font-semibold">Total Pricing</h6>
                                            <h6 class="font-extrabold mb-0"><?= rupiah($TotalPricingMaterialRupiah) ?> (<?= $TotalPricingMaterial ?>)</h6>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col text-end">
                                            <a href="<?= base_url('/material_pricing') ?>" class="btn btn-secondary btn-sm">
                                                See More
                                            </a>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-header p-3 mb-3">
                                    <h3>Material Grouping</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col col-xxl-2 d-flex justify-content-start">
                                            <div class="stats-icon blue">
                                                <i class="iconly-boldBag-2"></i>
                                            </div>
                                        </div>
                                        <div class="col col-xxl-5">
                                            <h6 class="text-muted font-semibold">Grouping This Month</h6>
                                            <h6 class="font-extrabold mb-0"><?= $TotalGroupingMaterialMonth ?></h6>
                                        </div>
                                        <div class="col col-xxl-5">
                                            <h6 class="text-muted font-semibold">Total Grouping</h6>
                                            <h6 class="font-extrabold mb-0"><?= $TotalGroupingMaterial ?></h6>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col col-xxl-4">
                                            <h6 class="text-warning font-semibold">Pending</h6>
                                            <h6 class="font-extrabold mb-0"><?= $totalPending ?></h6>
                                        </div>
                                        <div class="col col-xxl-4">
                                            <h6 class="text-success font-semibold">Sending</h6>
                                            <h6 class="font-extrabold mb-0"><?= $totalSending ?></h6>
                                        </div>
                                        <div class="col col-xxl-4">
                                            <h6 class="text-primary font-semibold">Received</h6>
                                            <h6 class="font-extrabold mb-0"><?= $totalReceived ?></h6>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col text-end">
                                            <a href="<?= base_url('/material_grouping') ?>" class="btn btn-secondary btn-sm">
                                                See More
                                            </a>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-header p-3 mb-3">
                                    <h3>Material Delivery</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col col-xxl-2 d-flex justify-content-start">
                                            <div class="stats-icon bg-primary">
                                                <i class="iconly-boldSend"></i>
                                            </div>
                                        </div>
                                        <div class="col col-xxl-5">
                                            <h6 class="text-muted font-semibold">Delivery This Month</h6>
                                            <h6 class="font-extrabold mb-0"><?= $TotalDeliveryMaterialMonth ?></h6>
                                        </div>
                                        <div class="col col-xxl-5">
                                            <h6 class="text-muted font-semibold">Total Delivery</h6>
                                            <h6 class="font-extrabold mb-0"><?= $TotalDeliveryMaterial ?></h6>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col text-end">
                                            <a href="<?= base_url('/material_delivery') ?>" class="btn btn-secondary btn-sm">
                                                See More
                                            </a>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (userdata('id_role') == 4) { ?>
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-header p-3 mb-3">
                                    <h3>Material Delivery</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col col-xxl-2 d-flex justify-content-start">
                                            <div class="stats-icon bg-primary">
                                                <i class="iconly-boldSend"></i>
                                            </div>
                                        </div>
                                        <div class="col col-xxl-5">
                                            <h6 class="text-muted font-semibold">Delivery This Month</h6>
                                            <h6 class="font-extrabold mb-0"><?= $TotalDeliveryMaterialMonth ?></h6>
                                        </div>
                                        <div class="col col-xxl-5">
                                            <h6 class="text-muted font-semibold">Total Delivery</h6>
                                            <h6 class="font-extrabold mb-0"><?= $TotalDeliveryMaterial ?></h6>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col col-xxl-4">
                                            <h6 class="text-success font-semibold">Sending</h6>
                                            <h6 class="font-extrabold mb-0"><?= $totalSending ?></h6>
                                        </div>
                                        <div class="col col-xxl-4">
                                            <h6 class="text-primary font-semibold">Received</h6>
                                            <h6 class="font-extrabold mb-0"><?= $totalReceived ?></h6>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col text-end">
                                            <a href="<?= base_url('/material_delivery') ?>" class="btn btn-secondary btn-sm">
                                                See More
                                            </a>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-header p-3 mb-3">
                                    <h3>Warehouse</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col col-xxl-2 d-flex justify-content-start">
                                            <div class="stats-icon bg-danger">
                                                <i class="iconly-boldHome"></i>
                                            </div>
                                        </div>
                                        <div class="col col-xxl-5">
                                            <h6 class="text-muted font-semibold">Total Material Storage</h6>
                                            <h6 class="font-extrabold mb-0"><?= $TotalMaterialStorage ?></h6>
                                        </div>
                                        <div class="col col-xxl-5">
                                            <h6 class="text-muted font-semibold">Total Material Out</h6>
                                            <h6 class="font-extrabold mb-0"><?= $TotalMaterialOut ?></h6>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col text-end btn-group">
                                            <a href="<?= base_url('/material_storage') ?>" class="btn btn-secondary btn-sm">
                                                See More Storage
                                            </a>
                                            <a href="<?= base_url('/material_out') ?>" class="btn btn-secondary btn-sm">
                                                See More Out
                                            </a>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                    <?php } else if (userdata('id_role') == 1) { ?>
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-header p-3 mb-3">
                                    <h3>Warehouse</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col col-xxl-2 d-flex justify-content-start">
                                            <div class="stats-icon bg-danger">
                                                <i class="iconly-boldHome"></i>
                                            </div>
                                        </div>
                                        <div class="col col-xxl-5">
                                            <h6 class="text-muted font-semibold">Total Material Storage</h6>
                                            <h6 class="font-extrabold mb-0"><?= $TotalMaterialStorage ?></h6>
                                        </div>
                                        <div class="col col-xxl-5">
                                            <h6 class="text-muted font-semibold">Total Material Out</h6>
                                            <h6 class="font-extrabold mb-0"><?= $TotalMaterialOut ?></h6>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col text-end btn-group">
                                            <a href="<?= base_url('/material_storage') ?>" class="btn btn-secondary btn-sm">
                                                See More Storage
                                            </a>
                                            <a href="<?= base_url('/material_out') ?>" class="btn btn-secondary btn-sm">
                                                See More Out
                                            </a>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>

            </div>
        </section>

    </div>

</div>