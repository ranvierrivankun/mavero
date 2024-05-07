</div>

</div>
</div>

<!-- Base Template -->
<script src="<?= base_url() ?>/assets/js/bootstrap.js"></script>
<script src="<?= base_url() ?>/assets/js/app.js"></script>

<!-- Sweetalert2 -->
<script src="<?= base_url() ?>/assets/extensions/sweetalert2/sweetalert2.all.min.js"></script>

<!-- Datatables -->
<!-- <script src="<?= base_url() ?>/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script> -->
<script src="<?= base_url() ?>/assets/extensions/DataTables/datatables.min.js"></script>

<!-- Choices -->
<script src="<?= base_url() ?>/assets/extensions/choices.js/public/assets/scripts/choices.js"></script>
<script src="<?= base_url() ?>/assets/js/pages/form-element-select.js"></script>

<!-- Flatpickr -->
<script src="<?= base_url('') ?>/assets/extensions/flatpickr/flatpickr.js"></script>

</body>

</html>

<script type="text/javascript">
    $('.range').flatpickr({
        altInput: true,
        altFormat: "d/m/Y",
        dateFormat: "Y-m-d",
        disableMobile: "true",
        mode: "range"
    })
</script>