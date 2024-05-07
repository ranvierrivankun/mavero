<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_setting extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_login();
        check_role([1]);
        $this->load->model('User_setting_model');
        $this->load->model('Builder_model');
    }


    public function index()
    {
        // Mengambil data setting dari database
        $setting = $this->db->select('*')->from('setting')->get()->row();

        // Membentuk judul halaman dengan nama setting
        $title = $setting->name . ' - User Setting';
        $title_page = 'User Setting';

        // Ambil data dari model
        $roles = $this->User_setting_model->get_roles();

        // Menyusun data untuk dikirim ke tampilan
        $data['setting'] = [
            'title' => $title,
            'title_page' => $title_page,
            'query' => $setting,
            'roles' => $roles
        ];

        // Memuat tampilan dengan data yang telah disusun
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('template/navbar');
        $this->load->view('user_setting/index', $data);
        $this->load->view('template/footer');
    }

    // Tampilkan table_user_setting
    public function table_user_setting()
    {
        // Mengambil informasi yang diperlukan dari permintaan AJAX DataTables
        $start = $this->input->post('start'); // Mulai data
        $length = $this->input->post('length'); // Jumlah data per halaman
        $order = $this->input->post('order'); // Informasi pengurutan dari DataTables

        $column_order = $order[0]['column']; // Index kolom yang diurutkan
        $dir = $order[0]['dir']; // Arah pengurutan (asc atau desc)

        // Ambil data method POST Datatables
        $selectRole = $this->input->post('selectRole');

        // Memuat data dari model dengan pengaturan pengurutan
        $table = $this->User_setting_model->table_user_setting($start, $length, $column_order, $dir, $selectRole);
        $filter = $this->User_setting_model->filter_table_user_setting();
        $total = $this->User_setting_model->total_table_user_setting();

        $data = [];

        foreach ($table as $tb) {

            $edit = "<a class='btn btn-sm btn-primary edit' data-id_user='" . htmlspecialchars($tb->id_user, ENT_QUOTES, 'UTF-8') . "'>Edit</a>";
            $delete = "<a class='btn btn-sm btn-danger delete' data-id_user='" . htmlspecialchars($tb->id_user, ENT_QUOTES, 'UTF-8') . "'>Delete</a>";

            // Menyiapkan data untuk setiap baris dalam tabel
            $td = [
                '<center><div class="btn-group">' . $edit . ' ' . $delete . '</div></center>', // Kolom Action
                $tb->name_role,
                $tb->name,
                $tb->username,
                $tb->email,
                $tb->mobile,
            ];

            // Menentukan status dengan label yang sesuai
            $status_label = ($tb->status === 'aktif') ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
            $td[] = $status_label;

            $data[] = $td;
        }

        // Menyiapkan output dalam format JSON untuk DataTables
        $output = [
            'draw' => $this->input->post('draw'), // Nomor draw yang digunakan oleh DataTables
            'recordsTotal' => $total, // Jumlah total catatan
            'recordsFiltered' => $filter, // Jumlah catatan setelah diterapkan filter
            'data' => $data, // Data yang akan ditampilkan dalam tabel
        ];

        // Mengirimkan output dalam format JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    // Tampilkan modal tambah_user
    public function tambah_user()
    {
        // Memperoleh data roles dari model
        $data['role'] = $this->User_setting_model->get_roles();

        // Memuat tampilan "tambah_user" dan mengirimkan data
        $this->load->view('user_setting/tambah_user', $data);
    }

    // Tampilkan modal edit_user
    public function edit_user()
    {
        // Ambil id_user dan konversi menjadi integer
        $id_user = intval($this->input->post('id_user'));

        // Memperoleh data roles dari model
        $data['role'] = $this->User_setting_model->get_roles();

        // Query user_db where id_user
        $data['edit'] = $this->Builder_model->edit('user_db', 'id_user', $id_user)->row();

        // Memuat tampilan "tambah_user" dan mengirimkan data
        $this->load->view('user_setting/edit_user', $data);
    }

    // Proses Tambah User
    public function tambah()
    {
        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax

            // Mendapatkan data dari permintaan POST
            $id_role = $this->input->post('id_role', true);
            $username = $this->input->post('username', true);
            $password = $this->input->post('password', true);
            $name = $this->input->post('name', true);
            $email = $this->input->post('email', true);
            $mobile = $this->input->post('mobile', true);

            // Set validation rules
            $this->form_validation->set_rules('id_role', 'Role', 'required');
            $this->form_validation->set_rules('username', 'Username', 'required|is_unique[user_db.username]');
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[user_db.email]');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|numeric');


            // Validasi data yang harus diisi
            if ($this->form_validation->run() == false) {
                // Jika kosong, mengembalikan pesan error
                $response['status'] = 'error';
                $response['message'] = validation_errors();

                $response['id_role'] = form_error('id_role');
                $response['username'] = form_error('username');
                $response['password'] = form_error('password');
                $response['name'] = form_error('name');
                $response['email'] = form_error('email');
                $response['mobile'] = form_error('mobile');
            } else {

                // Hash Password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Data yang akan disimpan
                $data = array(
                    'id_role' => $id_role,
                    'username' => $username,
                    'password' => $hashedPassword,
                    'name' => $name,
                    'email' => $email,
                    'mobile' => $mobile,
                    'status' => 'aktif'
                );

                // Melakukan insert data pengguna di database user_db
                $this->db->insert('user_db', $data);

                // Menyiapkan respons JSON
                $response = array();

                // Setelah berhasil insert
                $response['status'] = 'success';
                $response['message'] = 'Data inserted successfully';
            }

            // Mengirim respons JSON
            echo json_encode($response);
        } else {
            redirect('errors/not_found');
        }
    }

    // Cek Username pada Database
    public function check_unique_username($username, $id_user)
    {
        $existing_user = $this->Builder_model->get('user_db', array('username' => $username));

        if ($existing_user && $existing_user['id_user'] != $id_user) {
            $this->form_validation->set_message('check_unique_username', 'This username is already taken.');
            return false;
        }
        return true;
    }

    // Cek Email pada Database
    public function check_unique_email($email, $id_user)
    {
        $existing_user = $this->Builder_model->get('user_db', array('email' => $email));

        if ($existing_user && $existing_user['id_user'] != $id_user) {
            $this->form_validation->set_message('check_unique_email', 'This email is already taken.');
            return false;
        }
        return true;
    }

    // Proses Edit User
    public function edit()
    {
        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax

            // Mendapatkan data dari permintaan POST
            $id_user = $this->input->post('id_user', true);
            $id_role = $this->input->post('id_role', true);
            $username = $this->input->post('username', true);
            $password = $this->input->post('password', true);
            $name = $this->input->post('name', true);
            $email = $this->input->post('email', true);
            $mobile = $this->input->post('mobile', true);
            $status = $this->input->post('status', true);

            // Set validation rules
            $this->form_validation->set_rules('id_role', 'Role', 'required');
            $this->form_validation->set_rules('username', 'Username', 'required|callback_check_unique_username[' . $id_user . ']');
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_check_unique_email[' . $id_user . ']');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|numeric');
            $this->form_validation->set_rules('status', 'Status', 'required');

            // Jika password diinput, validasi dan hash password
            if (!empty($password)) {
                $this->form_validation->set_rules('password', 'Password', 'required');
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            }

            // Validasi data yang harus diisi
            if ($this->form_validation->run() == false) {
                // Jika ada kesalahan validasi, mengembalikan pesan error
                $response['status'] = 'error';
                $response['message'] = validation_errors();

                $response['username'] = form_error('username');
                $response['name'] = form_error('name');
                $response['email'] = form_error('email');
                $response['mobile'] = form_error('mobile');
            } else {

                // Data yang akan disimpan
                $data = array(
                    'id_role' => $id_role,
                    'username' => $username,
                    'name' => $name,
                    'email' => $email,
                    'mobile' => $mobile,
                    'status' => $status
                );

                // Jika password diinput, tambahkan ke data yang akan disimpan
                if (!empty($password)) {
                    $data['password'] = $hashedPassword;
                }

                // Melakukan update data pengguna di database user_db
                $this->Builder_model->update('user_db', $data, 'id_user', $id_user);

                // Menyiapkan respons JSON
                $response = array();

                // Setelah berhasil update
                $response['status'] = 'success';
                $response['message'] = 'Data updated successfully';
            }

            // Mengirim respons JSON
            echo json_encode($response);
        } else {
            redirect('errors/not_found');
        }
    }

    // Proses Delete User
    public function delete()
    {
        // Ambil id_user dan konversi menjadi integer
        $id_user = intval($this->input->post('id_user'));

        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax
            // Validasi apakah id adalah angka yang valid atau tidak
            if ($id_user <= 0) {
                $response = [
                    'status' => 'error',
                    'message' => 'Invalid user ID.'
                ];
            } else {
                // Lanjutkan dengan penghapusan pengguna
                $this->Builder_model->delete('user_db', 'id_user', $id_user);

                $response = [
                    'status' => 'success',
                    'message' => 'User successfully deleted.'
                ];
            }

            // Mengembalikan respons dalam format JSON
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            redirect('errors/not_found');
        }
    }
}
