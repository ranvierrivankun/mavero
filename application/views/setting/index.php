<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last mb-3">
                <h3><?= $setting['title_page']; ?></h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Menu</li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url('setting') ?>"><?= $setting['title_page']; ?></a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section id="basic-horizontal-layouts">
        <div class="row match-height">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Security</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="SecurityForm" method="post">

                                <input type="hidden" name="id_user" value="<?= userdata('id_user') ?>">

                                <div class="form-body">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <label>Old Password</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-shield-lock"></i>
                                                    </div>
                                                </span>
                                                <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Old Password">
                                                <span class="input-group-text">
                                                    <div class="form-control-icon password-toggle" id="old_password_toggle" onclick="togglePassword('old_password')">
                                                        <i class="bi bi-eye"></i>
                                                    </div>
                                                </span>
                                            </div>
                                            <div class="error-text" id="old_password_error"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label>New Password</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-shield-lock"></i>
                                                    </div>
                                                </span>
                                                <input type="password" name="password" id="password" class="form-control" placeholder="New Password">
                                                <span class="input-group-text">
                                                    <div class="form-control-icon password-toggle" id="password_toggle" onclick="togglePassword('password')">
                                                        <i class="bi bi-eye"></i>
                                                    </div>
                                                </span>
                                            </div>
                                            <div class="error-text" id="password_error"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Verify New Password</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-shield-lock"></i>
                                                    </div>
                                                </span>
                                                <input type="password" name="verify_password" id="verify_password" class="form-control" placeholder="Verify New Password">
                                                <span class="input-group-text">
                                                    <div class="form-control-icon password-toggle" id="verify_password_toggle" onclick="togglePassword('verify_password')">
                                                        <i class="bi bi-eye"></i>
                                                    </div>
                                                </span>
                                            </div>
                                            <div class="error-text" id="verify_password_error"></div>
                                        </div>

                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="button" id="SecurityButton" class="btn btn-primary me-1 mb-1">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

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

    // Menambahkan event listener pada tombol submit SecurityButton
    document.getElementById('SecurityButton').addEventListener('click', function() {
        var newPassword = document.getElementsByName('password')[0].value;
        var verifyPassword = document.getElementsByName('verify_password')[0].value;

        // Memeriksa apakah password baru dan verifikasi password baru sama
        if (newPassword !== verifyPassword) {
            // Menampilkan pesan error jika tidak sama
            Swal.fire({
                title: 'Error',
                text: 'New password and verify password must match.',
                icon: 'error',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }

        // Menampilkan SweetAlert dengan indikator proses
        Swal.fire({
            title: 'Please wait...',
            text: 'Submit in progress',
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Mengirim permintaan AJAX untuk proses submit SecurityButton
        $.ajax({
            type: 'POST',
            url: '<?= base_url('setting/SecurityForm') ?>',
            data: $('#SecurityForm').serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Jika submit berhasil
                    Swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = '<?= base_url('auth/logout') ?>';
                    });

                } else if (response.status === 'error') {
                    // Jika terjadi kesalahan, tampilkan pesan error menggunakan SweetAlert
                    Swal.fire({
                        title: 'Error',
                        html: response.message,
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    // Tampilkan kesalahan validasi pada input form tambah
                    const errorFields = ['old_password', 'password', 'verify_password'];

                    errorFields.forEach(function(fieldName) {
                        const errorElement = document.getElementById(fieldName + '_error');
                        const inputElement = document.getElementById(fieldName);

                        if (response[fieldName]) {
                            errorElement.innerHTML = response[fieldName];
                            inputElement.classList.add('is-invalid');
                        } else {
                            errorElement.innerHTML = '';
                            inputElement.classList.remove('is-invalid');
                        }
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