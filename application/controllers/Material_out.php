<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Material_out extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_login();
        check_role([1, 4]);
        $this->load->model('Builder_model');
        $this->load->model('Request_material_model');
        $this->load->model('Material_storage_model');
    }

    public function index()
    {
        // Mengambil data setting dari database
        $setting = $this->db->select('*')->from('setting')->get()->row();

        // Membentuk judul halaman dengan nama setting
        $title = $setting->name . ' - Material Out';
        $title_page = 'Material Out';

        // Ambil data dari model
        $type = $this->Request_material_model->get_type();

        // Menyusun data untuk dikirim ke tampilan
        $data['setting'] = [
            'title' => $title,
            'title_page' => $title_page,
            'type' => $type
        ];

        // Memuat tampilan dengan data yang telah disusun
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('template/navbar');
        $this->load->view('material_out/index', $data);
        $this->load->view('template/footer');
    }

    // Tampilkan table_material_out
    public function table_material_out()
    {
        // Mengambil informasi yang diperlukan dari permintaan AJAX DataTables
        $start = $this->input->post('start'); // Mulai data
        $length = $this->input->post('length'); // Jumlah data per halaman
        $order = $this->input->post('order'); // Informasi pengurutan dari DataTables

        $column_order = $order[0]['column']; // Index kolom yang diurutkan
        $dir = $order[0]['dir']; // Arah pengurutan (asc atau desc)

        // Ambil data method POST Datatables
        $tgl1 = $this->input->post('tgl1');
        $tgl2 = $this->input->post('tgl2');
        $selectType = $this->input->post('selectType');

        // Memuat data dari model dengan pengaturan pengurutan
        $table = $this->Material_storage_model->table_material_out($start, $length, $column_order, $dir, $selectType, $tgl1, $tgl2);
        $filter = $this->Material_storage_model->filter_table_material_out();
        $total = $this->Material_storage_model->total_table_material_out();

        $data = [];

        foreach ($table as $tb) {

            // Menentukan button dengan status yang sesuai
            $buttons = '<center><div class="btn-group"></div></center>';

            // Menyiapkan data untuk setiap baris dalam tabel
            $td = [
                $tb->created_mo,
                $tb->name_mt,
                $tb->name,
                $tb->size,
                $tb->quantity_mo . ' ' . $tb->unit,
            ];

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
}
