<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_login();
        $this->load->model('Builder_model');
    }

    // Tampilan View setting/index
    public function index()
    {
        // Mengambil data setting dari database
        $setting = $this->db->select('*')->from('setting')->get()->row();

        // Membentuk judul halaman dengan nama setting
        $title = $setting->name . ' - Setting Account';
        $title_page = 'Setting Account';

        // Menyusun data untuk dikirim ke tampilan
        $data['setting'] = [
            'title' => $title,
            'title_page' => $title_page
        ];

        // Memuat tampilan dengan data yang telah disusun
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('template/navbar');
        $this->load->view('setting/index', $data);
        $this->load->view('template/footer');
    }

    // Cek old_pasword pada Database
    public function check_old_password($oldPassword, $id_user)
    {
        $user = $this->db->get_where('user_db', ['id_user' => $id_user])->row_array();

        if (password_verify($oldPassword, $user['password'])) {
            return true;
        } else {
            $this->form_validation->set_message('check_old_password', 'The old password is incorrect.');
            return false;
        }
    }

    // Cek Password baru sama dengan password lama
    public function check_different_passwords($password)
    {
        $old_password = $this->input->post('old_password');
        if ($password === $old_password) {
            $this->form_validation->set_message('check_different_passwords', 'The New Password field must be different from the Old Password field.');
            return false;
        }
        return true;
    }

    // Proses Security Account}
    public function SecurityForm()
    {
        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax

            // Mendapatkan data dari permintaan POST
            $id_user = $this->input->post('id_user', true);
            $password = $this->input->post('password', true);

            // Set validation rules
            $this->form_validation->set_rules('old_password', 'Old Password', 'required|callback_check_old_password[' . $id_user . ']');
            $this->form_validation->set_rules('password', 'New Password', 'required|matches[verify_password]|callback_check_different_passwords');
            $this->form_validation->set_rules('verify_password', 'Verify Password', 'required|matches[password]|callback_check_different_passwords');

            // Run validation
            if ($this->form_validation->run() == false) {
                // Jika ada error validasi
                $response['status'] = 'error';
                $response['message'] = validation_errors();

                // Menambahkan pesan kesalahan ke dalam respons
                $response['old_password'] = form_error('old_password');
                $response['password'] = form_error('password');
                $response['verify_password'] = form_error('verify_password');
            } else {
                // Hash Password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Data yang akan disimpan
                $data = array(
                    'password' => $hashedPassword
                );

                // Melakukan update data pengguna di database user_db
                $this->Builder_model->update('user_db', $data, 'id_user', $id_user);

                // Setelah berhasil update
                $response['status'] = 'success';
                $response['message'] = 'Password updated successfully';
            }

            // Mengirim respons JSON
            echo json_encode($response);
        } else {
            redirect('errors/not_found');
        }
    }
}
