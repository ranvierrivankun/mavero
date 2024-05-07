<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Builder_model');
    }

    // Cek login_season
    public function _has_login()
    {
        if ($this->session->has_userdata('login_season')) {
            redirect('dashboard');
        }
    }

    // Tampilan View auth/index
    public function index()
    {
        $this->_has_login();

        // Mengambil data setting dari database
        $setting = $this->db->select('*')->from('setting')->get()->row();

        // Membentuk judul halaman dengan nama setting
        // $title = $setting->name . ' - Login Mavero';

        // Menyusun data untuk dikirim ke tampilan
        $data['setting'] = $setting;

        // Memuat tampilan dengan data yang telah disusun
        $this->load->view('auth/index', $data);
    }

    // Proses Autentikasi
    public function proses()
    {
        // Mendapatkan data input username dan password dari form
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);

        // Memeriksa apakah input username dan password kosong
        if (empty($username) || empty($password)) {
            // Jika kosong, mengembalikan pesan error
            $response = array('status' => 'error', 'message' => 'Username and password must be filled');
        } else {
            // Memeriksa apakah username ada dalam database
            $cek_username = $this->Builder_model->cek_username($username);

            if ($cek_username > 0) {
                // Mendapatkan data user berdasarkan username
                $user = $this->Builder_model->userdata($username);

                if ($user['status'] == 'aktif') {
                    // Memeriksa kesesuaian password yang diinput dengan yang ada di database
                    $cek_password = $this->Builder_model->get_password($username);
                    if (password_verify($password, $cek_password)) {
                        // Jika password cocok, menyimpan data user dalam sesi dan mengirim pesan sukses
                        $user_db = $this->Builder_model->userdata($username);
                        $userdata = [
                            'id_user'   => $user_db['id_user']
                        ];
                        $this->session->set_userdata('login_season', $userdata);
                        $response = array('status' => 'success', 'message' => 'Login successful');
                    } else {
                        // Jika password tidak cocok, mengirim pesan error
                        $response = array('status' => 'error', 'message' => 'Wrong Username or Password');
                    }
                } else {
                    // Jika status user tidak aktif, mengirim pesan error
                    $response = array('status' => 'error', 'message' => 'Inactive user');
                }
            } else {
                // Jika username tidak ditemukan, mengirim pesan error
                $response = array('status' => 'error', 'message' => 'Wrong Username or Password');
            }
        }

        // Mengirimkan pesan dalam format JSON
        echo json_encode($response);
    }

    // Proses Logout
    public function logout()
    {
        $this->session->unset_userdata('login_season');
        $this->session->set_flashdata('response_logout', 'Logout successful');
        redirect('auth');
    }
}
