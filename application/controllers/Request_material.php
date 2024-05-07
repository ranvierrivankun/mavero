<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request_material extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_login();
        check_role([1, 2]);
        $this->load->model('Builder_model');
        $this->load->model('Material_pricing_model');
        $this->load->model('Request_material_model');
    }

    public function index()
    {
        // Mengambil data setting dari database
        $setting = $this->db->select('*')->from('setting')->get()->row();

        // Membentuk judul halaman dengan nama setting
        $title = $setting->name . ' - Request Material';
        $title_page = 'Request Material';

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
        $this->load->view('request_material/index', $data);
        $this->load->view('template/footer');
    }

    // Tampilkan table_request_material
    public function table_request_material()
    {
        // Mengambil informasi yang diperlukan dari permintaan AJAX DataTables
        $start = $this->input->post('start'); // Mulai data
        $length = $this->input->post('length'); // Jumlah data per halaman
        $order = $this->input->post('order'); // Informasi pengurutan dari DataTables

        $column_order = $order[0]['column']; // Index kolom yang diurutkan
        $dir = $order[0]['dir']; // Arah pengurutan (asc atau desc)

        // Ambil data method POST Datatables
        $selectType = $this->input->post('selectType');
        $selectStatus = $this->input->post('selectStatus');

        // Memuat data dari model dengan pengaturan pengurutan
        $table = $this->Request_material_model->table_request_material($start, $length, $column_order, $dir, $selectType, $selectStatus);
        $filter = $this->Request_material_model->filter_table_request_material();
        $total = $this->Request_material_model->total_table_request_material();

        $data = [];

        foreach ($table as $tb) {

            $detail = "<a class='btn btn-sm btn-secondary detail' data-id_material='" . htmlspecialchars($tb->id_material, ENT_QUOTES, 'UTF-8') . "'>Detail</a>";
            $edit = "<a class='btn btn-sm btn-primary edit' data-id_material='" . htmlspecialchars($tb->id_material, ENT_QUOTES, 'UTF-8') . "'>Edit</a>";
            $delete = "<a class='btn btn-sm btn-danger delete' data-id_material='" . htmlspecialchars($tb->id_material, ENT_QUOTES, 'UTF-8') . "'>Delete</a>";
            $pricing = "<a class='btn btn-sm btn-outline-secondary detail' data-id_material='" . htmlspecialchars($tb->id_material, ENT_QUOTES, 'UTF-8') . "'>@pricing</a>";

            // Menentukan button dengan status yang sesuai
            $buttons = '';
            if ($tb->status === 'process') {
                $buttons = '<center><div class="btn-group">' . $edit . ' ' . $delete . '</div></center>';
            } elseif ($tb->status === 'pricing') {
                $buttons = '<center><div class="btn-group">' . $pricing . '</div></center>';
            } elseif ($tb->status === 'rejected') {
                $buttons = '<center><div class="btn-group">' . $detail . ' ' . $delete . '</div></center>';
            }

            // Menentukan status
            $status = '';
            if ($tb->status === 'process') {
                $status = '<span class="badge bg-warning">Process</span>';
            } elseif ($tb->status === 'pricing') {
                $status = '<span class="badge bg-success">Pricing</span>';
            } elseif ($tb->status === 'rejected') {
                $status = '<span class="badge bg-danger">Rejected</span>';
            }

            // Menyiapkan data untuk setiap baris dalam tabel
            $td = [
                $buttons,
                $tb->name_mt,
                $tb->name,
                $tb->size,
                $tb->quantity . ' ' . $tb->unit,
                $status
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

    // Tampilkan modal tambah_material
    public function tambah_material()
    {
        // Memperoleh data type dari model
        $data['type'] = $this->Request_material_model->get_type();

        // Memuat tampilan "tambah_material" dan mengirimkan data
        $this->load->view('request_material/tambah_material', $data);
    }

    // Tampilkan modal edit_material
    public function edit_material()
    {
        // Ambil id_material dan konversi menjadi integer
        $id_material = intval($this->input->post('id_material'));

        // Memperoleh data type dari model
        $data['type'] = $this->Request_material_model->get_type();

        // Query get_request_material
        $data['edit'] = $this->Request_material_model->get_request_material($id_material);

        // Memuat tampilan "edit_material" dan mengirimkan data
        $this->load->view('request_material/edit_material', $data);
    }

    // Tampilkan modal detail_material
    public function detail_material()
    {
        // Ambil id_material dan konversi menjadi integer
        $id_material = intval($this->input->post('id_material'));

        // Query get_request_material
        $data['edit'] = $this->Material_pricing_model->edit_request_material($id_material);

        // Memuat tampilan "detail_material" dan mengirimkan data
        $this->load->view('request_material/detail_material', $data);
    }

    // Tampilkan description
    public function description()
    {
        $id_mt = $this->input->get('id_mt'); // Dapatkan ID yang dikirim melalui permintaan GET

        // Panggil model untuk mendapatkan deskripsi berdasarkan ID
        $query = $this->Builder_model->edit('material_type', 'id_mt', $id_mt)->row();

        if ($query !== false) {
            echo $query->description;
        } else {
            echo "Deskripsi tidak ditemukan";
        }
    }

    // Proses Tambah Material
    public function tambah()
    {
        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax

            // Mendapatkan data dari permintaan POST
            $id_mt = $this->input->post('id_mt', true);
            $name = $this->input->post('name', true);
            $size = $this->input->post('size', true);
            $quantity = $this->input->post('quantity', true);
            $unit = $this->input->post('unit', true);

            // Set validation rules
            $this->form_validation->set_rules('id_mt', 'Material Type', 'required');
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('size', 'Size', 'required');
            $this->form_validation->set_rules('quantity', 'Quantity', 'required');
            $this->form_validation->set_rules('unit', 'Unit', 'required');


            // Validasi data yang harus diisi
            if ($this->form_validation->run() == false) {
                // Jika kosong, mengembalikan pesan error
                $response['status'] = 'error';
                $response['message'] = validation_errors();

                $response['id_mt'] = form_error('id_mt');
                $response['name'] = form_error('name');
                $response['size'] = form_error('size');
                $response['quantity'] = form_error('quantity');
                $response['unit'] = form_error('unit');
            } else {

                // Data yang akan disimpan
                $data = array(
                    'id_user' => userdata('id_user'),
                    'id_mt' => $id_mt,
                    'name' => $name,
                    'size' => $size,
                    'quantity' => $quantity,
                    'unit' => $unit,
                    'status' => 'process',
                    'created' => date('Y-m-d H:i:s')
                );

                // Melakukan insert data pengguna di database request_material
                $this->db->insert('request_material', $data);

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

    // Proses Edit Material
    public function edit()
    {
        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax

            // Mendapatkan data dari permintaan POST
            $id_material = $this->input->post('id_material', true);
            $id_mt = $this->input->post('id_mt', true);
            $name = $this->input->post('name', true);
            $size = $this->input->post('size', true);
            $quantity = $this->input->post('quantity', true);
            $unit = $this->input->post('unit', true);

            // Set validation rules
            $this->form_validation->set_rules('id_mt', 'Material Type', 'required');
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('size', 'Size', 'required');
            $this->form_validation->set_rules('quantity', 'Quantity', 'required');
            $this->form_validation->set_rules('unit', 'Unit', 'required');

            // Validasi data yang harus diisi
            if ($this->form_validation->run() == false) {
                // Jika ada kesalahan validasi, mengembalikan pesan error
                $response['status'] = 'error';
                $response['message'] = validation_errors();

                $response['id_mt'] = form_error('id_mt');
                $response['name'] = form_error('name');
                $response['size'] = form_error('size');
                $response['quantity'] = form_error('quantity');
                $response['unit'] = form_error('unit');
            } else {

                // Data yang akan disimpan
                $data = array(
                    'id_mt' => $id_mt,
                    'name' => $name,
                    'size' => $size,
                    'quantity' => $quantity,
                    'unit' => $unit,

                    'id_user_updated' => userdata('id_user'),
                    'updated' => date('Y-m-d H:i:s')
                );

                // Melakukan update data pengguna di database request_material
                $this->Builder_model->update('request_material', $data, 'id_material', $id_material);

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

    // Proses Delete Request Material
    public function delete()
    {
        // Ambil id_material dan konversi menjadi integer
        $id_material = intval($this->input->post('id_material'));

        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax
            // Validasi apakah id adalah angka yang valid atau tidak
            if ($id_material <= 0) {
                $response = [
                    'status' => 'error',
                    'message' => 'Invalid material ID.'
                ];
            } else {
                // Lanjutkan dengan penghapusan
                $this->Builder_model->delete('request_material', 'id_material', $id_material);

                $response = [
                    'status' => 'success',
                    'message' => 'Request Material successfully deleted.'
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
