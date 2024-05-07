<style>
    .error-text {
        animation: error-text 1s ease infinite;
        -moz-animation: error-text 1s ease infinite;
        -webkit-animation: error-text 1s ease infinite;
    }

    @keyframes error-text {

        0%,
        100% {
            color: red;
        }

        50% {
            color: #806914;
        }
    }

    .swal2-container {
        z-index: 10000;
    }

    body {
        padding-right: 0 !important;
    }
</style>

<!-- Memuat library jQuery -->
<script src="<?= base_url() ?>/assets/extensions/jquery/jquery.min.js"></script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $setting['title']; ?></title>

    <!-- Base Template -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/main/app.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/main/app-dark.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/shared/iconly.css">

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/extensions/bootstrap-icons/font/bootstrap-icons.min.css">

    <!-- Sweetalert2 -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/extensions/sweetalert2/sweetalert2.min.css">
    <link rel="shortcut icon" href="<?= base_url() ?>/assets/images/logo/logo.png" type="image/png">

    <!-- Datatables -->
    <!-- <link rel="stylesheet" href="<?= base_url() ?>/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/pages/datatables.css"> -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/extensions/DataTables/datatables.min.css">

    <!-- Choices -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/extensions/choices.js/public/assets/styles/choices.css">

    <!-- Flatpickr -->
    <link rel="stylesheet" href="<?= base_url('') ?>/assets/extensions/flatpickr/flatpickr.css">

</head>