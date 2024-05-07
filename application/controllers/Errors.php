<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Errors extends CI_Controller
{
    public function index()
    {
        echo 'Ranvier Rivan';
    }

    public function not_found()
    {
        // Membentuk judul halaman dengan nama
        $title = 'Mavero  - Not Found';

        // Menyusun data untuk dikirim ke tampilan
        $data['setting'] = [
            'title' => $title,
            'response' => 'Not Found'
        ];

        $this->load->view('mavero_errors/mavero_not_found', $data);
    }

    public function access_denied()
    {
        // Membentuk judul halaman dengan nama
        $title = 'Mavero  - Access Denied';

        // Menyusun data untuk dikirim ke tampilan
        $data['setting'] = [
            'title' => $title,
            'response' => 'Access Denied'
        ];

        $this->load->view('mavero_errors/mavero_access_denied', $data);
    }
}
