<div class="modal-header bg-primary">
    <h5 class="modal-title white">Tambah User</h5>
    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
</div>

<form id="tambah" method="post">
    <div class="modal-body">
        <!-- Input field for Role -->
        <div class="form-group">
            <label for="id_role">Role</label>
            <select class="form-select" name="id_role" id="id_role">
                <option value="">Select Role Type</option>
                <?php foreach ($role as $roleItem) : ?>
                    <option value="<?= $roleItem->id_role ?>"><?= $roleItem->name_role ?></option>
                <?php endforeach; ?>
            </select>
            <div class="error-text" id="id_role_error"></div>
        </div>

        <!-- Input field for Username -->
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="Username User" class="form-control">
            <div class="error-text" id="username_error"></div>
        </div>

        <!-- Input field for Name -->
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" placeholder="Name User" class="form-control">
            <div class="error-text" id="name_error"></div>
        </div>

        <!-- Input field for Email -->
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Email User" class="form-control">
            <div class="error-text" id="email_error"></div>
        </div>

        <!-- Input field for Mobile -->
        <div class="form-group">
            <label for="mobile">Mobile</label>
            <input type="text" name="mobile" id="mobile" placeholder="Mobile User" class="form-control">
            <div class="error-text" id="mobile_error"></div>
        </div>

        <!-- Input field for Password -->
        <label for="password">Password</label>
        <div class="input-group mb-2">
            <input type="password" name="password" id="password" placeholder="Password User" class="form-control">
            <span class="input-group-text">
                <div class="form-control-icon password-toggle" id="password_toggle" onclick="togglePassword('password')">
                    <i class="bi bi-eye"></i>
                </div>
            </span>
        </div>
        <div class="error-text" id="password_error"></div>
    </div>

    <div class="modal-footer">
        <!-- Close button -->
        <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal">
            Close
        </button>

        <!-- Submit button -->
        <button type="button" id="submit" class="btn btn-primary ml-1">
            Submit
        </button>
    </div>
</form>

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

    // Menambahkan event listener pada tombol submit
    document.getElementById('submit').addEventListener('click', function() {
        // Menampilkan SweetAlert dengan indikator proses
        Swal.fire({
            title: 'Please wait...',
            text: 'Submit in progress',
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Mengirim permintaan AJAX untuk proses submit
        $.ajax({
            type: 'POST',
            url: '<?= base_url('user_setting/tambah') ?>',
            data: $('#tambah').serialize(),
            dataType: 'json',
            beforeSend: function() {
                Swal.fire({
                    title: 'Please wait...',
                    text: 'Submit in progress',
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
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
                        table_user_setting();
                        $('#tambah_user').modal('hide');
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
                    const errorFields = ['id_role', 'username', 'password', 'name', 'email', 'mobile'];

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