<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Material_setting extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_login();
        check_role([1]);
        $this->load->model('Builder_model');
        $this->load->model('Material_setting_model');
    }


    public function index()
    {
        // Mengambil data setting dari database
        $setting = $this->db->select('*')->from('setting')->get()->row();

        // Membentuk judul halaman dengan nama setting
        $title = $setting->name . ' - Material Setting';
        $title_page = 'Material Setting';

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
        $this->load->view('material_setting/index', $data);
        $this->load->view('template/footer');
    }

    // Tampilkan table_material_type
    public function table_material_type()
    {
        // Mengambil informasi yang diperlukan dari permintaan AJAX DataTables
        $start = $this->input->post('start'); // Mulai data
        $length = $this->input->post('length'); // Jumlah data per halaman
        $order = $this->input->post('order'); // Informasi pengurutan dari DataTables

        $column_order = $order[0]['column']; // Index kolom yang diurutkan
        $dir = $order[0]['dir']; // Arah pengurutan (asc atau desc)

        // Memuat data dari model dengan pengaturan pengurutan
        $table = $this->Material_setting_model->table_material_type($start, $length, $column_order, $dir);
        $filter = $this->Material_setting_model->filter_table_material_type();
        $total = $this->Material_setting_model->total_table_material_type();

        $data = [];

        foreach ($table as $tb) {

            $edit = "<a class='btn btn-sm btn-primary edit' data-id_mt='" . htmlspecialchars($tb->id_mt, ENT_QUOTES, 'UTF-8') . "'>Edit</a>";
            $delete = "<a class='btn btn-sm btn-danger delete' data-id_mt='" . htmlspecialchars($tb->id_mt, ENT_QUOTES, 'UTF-8') . "'>Delete</a>";

            // Menyiapkan data untuk setiap baris dalam tabel
            $td = [
                '<center><div class="btn-group">' . $edit . ' ' . $delete . '</div></center>', // Kolom Action
                $tb->name_mt,
                $tb->description
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

    // Tampilkan modal tambah_material_type
    public function tambah_material_type()
    {
        // Memuat tampilan "tambah_material_type" dan mengirimkan data
        $this->load->view('material_setting/tambah_material_type', false);
    }

    // Tampilkan modal edit_material_type
    public function edit_material_type()
    {
        // Ambil id_mt dan konversi menjadi integer
        $id_mt = intval($this->input->post('id_mt'));

        // Query material_type
        $data['edit'] = $this->Builder_model->edit('material_type', 'id_mt', $id_mt)->row();

        // Memuat tampilan "edit_material_type" dan mengirimkan data
        $this->load->view('material_setting/edit_material_type', $data);
    }

    // Proses Tambah Material
    public function tambah()
    {
        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax

            // Mendapatkan data dari permintaan POST
            $name_mt = $this->input->post('name_mt', true);
            $description = $this->input->post('description', true);

            // Set validation rules
            $this->form_validation->set_rules('name_mt', 'Name Material Type', 'required|is_unique[material_type.name_mt]');
            $this->form_validation->set_rules('description', 'Description Material Type', 'required');

            // Validasi data yang harus diisi
            if ($this->form_validation->run() == false) {
                // Jika kosong, mengembalikan pesan error
                $response['status'] = 'error';
                $response['message'] = validation_errors();

                $response['name_mt'] = form_error('name_mt');
                $response['description'] = form_error('description');
            } else {

                // Data yang akan disimpan
                $data = array(
                    'name_mt' => $name_mt,
                    'description' => $description
                );

                // Melakukan insert data pengguna di database request_material
                $this->db->insert('material_type', $data);

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

    // Cek Name Material Type pada Database
    public function check_unique_name_mt($name_mt, $id_mt)
    {
        $existing = $this->Builder_model->get('material_type', array('name_mt' => $name_mt));

        if ($existing && $existing['id_mt'] != $id_mt) {
            $this->form_validation->set_message('check_unique_name_mt', 'This Name Material Type is already taken.');
            return false;
        }
        return true;
    }

    // Proses Edit Material Type
    public function edit()
    {
        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax

            // Mendapatkan data dari permintaan POST
            $id_mt = $this->input->post('id_mt', true);
            $name_mt = $this->input->post('name_mt', true);
            $description = $this->input->post('description', true);

            // Set validation rules
            $this->form_validation->set_rules('name_mt', 'Name Material Type', 'required|callback_check_unique_name_mt[' . $id_mt . ']');
            $this->form_validation->set_rules('description', 'Description Material Type', 'required');

            // Validasi data yang harus diisi
            if ($this->form_validation->run() == false) {
                // Jika ada kesalahan validasi, mengembalikan pesan error
                $response['status'] = 'error';
                $response['message'] = validation_errors();

                $response['name_mt'] = form_error('name_mt');
                $response['description'] = form_error('description');
            } else {

                // Data yang akan disimpan
                $data = array(
                    'name_mt' => $name_mt,
                    'description' => $description
                );

                // Melakukan update data pengguna di database material_type
                $this->Builder_model->update('material_type', $data, 'id_mt', $id_mt);

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

    // Proses Delete Material Type
    public function delete()
    {
        // Ambil id_mt dan konversi menjadi integer
        $id_mt = intval($this->input->post('id_mt'));

        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax
            // Validasi apakah id adalah angka yang valid atau tidak
            if ($id_mt <= 0) {
                $response = [
                    'status' => 'error',
                    'message' => 'Invalid material ID.'
                ];
            } else {
                // Lanjutkan dengan penghapusan
                $this->Builder_model->delete('material_type', 'id_mt', $id_mt);

                $response = [
                    'status' => 'success',
                    'message' => 'Material Type successfully deleted.'
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
