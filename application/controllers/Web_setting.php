<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Web_setting extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_login();
        check_role([1]);
    }


    public function index()
    {
        // Mengambil data setting dari database
        $setting = $this->db->select('*')->from('setting')->get()->row();

        // Membentuk judul halaman dengan nama setting
        $title = $setting->name . ' - Web Setting';
        $title_page = 'Web Setting';

        // Menyusun data untuk dikirim ke tampilan
        $data['setting'] = [
            'title' => $title,
            'title_page' => $title_page,
            'query' => $setting
        ];

        // Memuat tampilan dengan data yang telah disusun
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('template/navbar');
        $this->load->view('web_setting/index', $data);
        $this->load->view('template/footer');
    }

    // Proses Edit Information
    public function EditForm()
    {
        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax

            // Mendapatkan data dari permintaan POST
            $name = $this->input->post('name', true);
            $email = $this->input->post('email', true);
            $mobile = $this->input->post('mobile', true);

            // Set validation rules
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required');

            // Validasi data yang harus diisi
            if ($this->form_validation->run() == false) {
                // Jika kosong, mengembalikan pesan error
                $response['status'] = 'error';
                $response['message'] = validation_errors();

                // Menambahkan pesan kesalahan ke dalam respons
                $response['name'] = form_error('name');
                $response['email'] = form_error('email');
                $response['mobile'] = form_error('mobile');
            } else {
                // Data yang akan disimpan
                $data = array(
                    'name' => $name,
                    'email' => $email,
                    'mobile' => $mobile
                );

                // Melakukan update data pengguna di database user_db
                $this->db->update('setting', $data);

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
}
