<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $setting->name ?> - Login Mavero</title>
    <!-- Memuat stylesheet untuk tampilan -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/main/app.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/pages/auth.css">
    <!-- Memuat favicon untuk halaman -->
    <link rel="shortcut icon" href="<?= base_url() ?>/assets/images/logo/logo.png" type="image/png">
</head>

<body>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="col-md-12 mb-5 text-center">
                        <h3 class="auth-title">MAVERO</h3>
                        <hr>
                        <p>Your Material Inventory Project Solution</p>
                    </div>
                    <h1>Log in.</h1>
                    <p class="mb-5">Log in to access the application.</p>
                    <form id="loginForm" method="post">

                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <div class="form-control-icon">
                                    <i class="bi bi-person"></i>
                                </div>
                            </span>
                            <input type="text" class="form-control" name="username" placeholder="Username">
                        </div>


                        <div class="input-group">
                            <span class="input-group-text">
                                <div class="form-control-icon">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                            </span>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                            <span class="input-group-text">
                                <div class="password-toggle" id="password_toggle" onclick="togglePassword('password')">
                                    <i class="bi bi-eye"></i>
                                </div>
                            </span>
                        </div>

                        <!-- Tombol login dengan id "loginButton" -->
                        <button type="button" id="loginButton" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
                    </form>

                    <p class="mt-5 text-center"><?= $setting->email ?> / <?= $setting->mobile ?></p>
                    <hr>

                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                </div>
            </div>
        </div>
    </div>

    <!-- Memuat library jQuery -->
    <script src="<?= base_url() ?>/assets/extensions/jquery/jquery.min.js"></script>
    <!-- Memuat library SweetAlert2 -->
    <script src="<?= base_url() ?>/assets/extensions/sweetalert2/sweetalert2.all.min.js"></script>

    <script>
        // Tampilkan type password ke type text
        function togglePassword(inputId) {
            var input = document.getElementById(inputId);
            var toggleButton = document.getElementById(inputId + '_toggle');

            if (input.type === "password") {
                input.type = "text";
                toggleButton.innerHTML = '<i class="bi bi-eye-slash"></i>';
            } else {
                input.type = "password";
                toggleButton.innerHTML = '<i class="bi bi-eye"></i>';
            }
        }

        // Tombol Login dapat di Enter
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("loginForm");
            const loginButton = document.getElementById("loginButton");

            form.addEventListener("keydown", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    loginButton.click(); // Klik Tombol Login
                }
            });
        });

        // Menambahkan event listener pada tombol masuk
        document.getElementById('loginButton').addEventListener('click', function() {
            // Menampilkan SweetAlert dengan indikator proses
            Swal.fire({
                title: 'Please wait...',
                text: 'Logging in progress',
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Mengirim permintaan AJAX untuk proses masuk
            $.ajax({
                type: 'POST',
                url: '<?= base_url('auth/proses') ?>',
                data: $('#loginForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Jika masuk berhasil, arahkan ke halaman dasbord
                        window.location.href = '<?= base_url('dashboard') ?>';
                    } else {
                        // Jika terjadi kesalahan, tampilkan pesan error menggunakan SweetAlert
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                },
                error: function() {
                    // Jika terjadi kesalahan akibat masalah server atau basis data
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred. Please try again later.',
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });

        });
    </script>

    <!-- Periksa adanya data flash dari sesi logout dan tampilkan SweetAlert -->
    <?php if ($this->session->flashdata('response_logout')) : ?>
        <script>
            Swal.fire({
                title: '<?= $this->session->flashdata('response_logout') ?>',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    <?php elseif ($this->session->flashdata('response_check_login')) : ?>
        <script>
            Swal.fire({
                title: '<?= $this->session->flashdata('response_check_login') ?>',
                icon: 'error',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    <?php endif; ?>


</body>

</html>