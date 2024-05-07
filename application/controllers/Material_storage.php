<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Material_storage extends CI_Controller
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
        $title = $setting->name . ' - Material Storage';
        $title_page = 'Material Storage';

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
        $this->load->view('material_storage/index', $data);
        $this->load->view('template/footer');
    }

    // Tampilkan table_material_storage
    public function table_material_storage()
    {
        // Mengambil informasi yang diperlukan dari permintaan AJAX DataTables
        $start = $this->input->post('start'); // Mulai data
        $length = $this->input->post('length'); // Jumlah data per halaman
        $order = $this->input->post('order'); // Informasi pengurutan dari DataTables

        $column_order = $order[0]['column']; // Index kolom yang diurutkan
        $dir = $order[0]['dir']; // Arah pengurutan (asc atau desc)

        // Ambil data method POST Datatables
        $selectType = $this->input->post('selectType');

        // Memuat data dari model dengan pengaturan pengurutan
        $table = $this->Material_storage_model->table_material_storage($start, $length, $column_order, $dir, $selectType);
        $filter = $this->Material_storage_model->filter_table_material_storage();
        $total = $this->Material_storage_model->total_table_material_storage();

        $data = [];

        foreach ($table as $tb) {

            $take = "<a class='btn btn-sm btn-success take' data-id_material='" . htmlspecialchars($tb->id_material, ENT_QUOTES, 'UTF-8') . "'>Take</a>";
            $list = "<a class='btn btn-sm btn-primary list' data-id_material='" . htmlspecialchars($tb->id_material, ENT_QUOTES, 'UTF-8') . "'>List</a>";

            // Menentukan button dengan status yang sesuai
            // $buttons = '<center><div class="btn-group">' . $take . ' ' . $list . '</div></center>';

            // Misalkan Anda memiliki variabel $quantity_mo yang menyimpan nilai quantity_mo
            if ($tb->quantity == 0) {
                $buttons = '<center><div class="btn-group">' . $list . '</div></center>';
            } else {
                $buttons = '<center><div class="btn-group">' . $take . ' ' . $list . '</div></center>';
            }


            // Menyiapkan data untuk setiap baris dalam tabel
            $td = [
                $buttons,
                $tb->name_mt,
                $tb->name,
                $tb->size,
                $tb->quantity . ' ' . $tb->unit,
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

    // Tampilkan table_list_material
    public function table_list_material()
    {
        // Mengambil informasi yang diperlukan dari permintaan AJAX DataTables
        $start = $this->input->post('start'); // Mulai data
        $length = $this->input->post('length'); // Jumlah data per halaman
        $order = $this->input->post('order'); // Informasi pengurutan dari DataTables

        $column_order = $order[0]['column']; // Index kolom yang diurutkan
        $dir = $order[0]['dir']; // Arah pengurutan (asc atau desc)

        // Ambil data method POST Datatables
        $id_material = $this->input->post('id_material');

        // Memuat data dari model dengan pengaturan pengurutan
        $table = $this->Material_storage_model->table_list_material($start, $length, $column_order, $dir, $id_material);
        $filter = $this->Material_storage_model->filter_table_list_material();
        $total = $this->Material_storage_model->total_table_list_material();

        $data = [];

        $counter = 1;

        foreach ($table as $tb) {

            $delete = "<a class='btn btn-sm btn-danger delete' data-id_material='" . htmlspecialchars($tb->id_material, ENT_QUOTES, 'UTF-8') . "'>Delete</a>";

            // Menentukan button dengan status yang sesuai
            $buttons = '<center><div class="btn-group">' . $delete . '</div></center>';

            // Menyiapkan data untuk setiap baris dalam tabel
            $td = [
                $counter++,
                $tb->created_mo,
                $tb->name,
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

    // Tampilkan modal take material
    public function take_material()
    {
        // Ambil id_material dan konversi menjadi integer
        $id_material = intval($this->input->post('id_material'));

        // Query get_material_storage
        $data['edit'] = $this->Material_storage_model->get_request_material($id_material);

        // Memuat tampilan "pricing" dan mengirimkan data
        $this->load->view('material_storage/take_material', $data);
    }

    // Tampilkan modal list material
    public function list_material()
    {
        // Ambil id_material dan konversi menjadi integer
        $id_material = intval($this->input->post('id_material'));

        // Query get_material_storage
        $data['edit'] = $this->Material_storage_model->get_request_material($id_material);

        // Memuat tampilan "pricing" dan mengirimkan data
        $this->load->view('material_storage/list_material', $data);
    }

    public function quantity_check($str, $quantity_old)
    {
        if (!is_numeric($str)) {
            $this->form_validation->set_message('quantity_check', 'The {field} field must contain only numbers.');
            return false;
        } elseif ($str <= 0) {
            $this->form_validation->set_message('quantity_check', 'The {field} field must be greater than 0.');
            return false;
        } elseif ($str > $quantity_old) {
            $this->form_validation->set_message('quantity_check', 'The {field} field cannot exceed the old quantity.');
            return false;
        } else {
            return true;
        }
    }

    // Proses Take Material
    public function take()
    {
        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax

            // Mendapatkan data dari permintaan POST
            $id_material = $this->input->post('id_material', true);
            $quantity = $this->input->post('quantity', true);

            // get_material untuk mengambil quantity old
            $query = $this->Material_storage_model->get_request_material($id_material);
            $quantity_old = $query->quantity;

            // Set validation rules
            $this->form_validation->set_rules('quantity', 'Quantity', 'required|numeric|callback_quantity_check[' . $quantity_old . ']');

            // Validasi data yang harus diisi
            if ($this->form_validation->run() == false) {
                // Jika ada kesalahan validasi, mengembalikan pesan error
                $response['status'] = 'error';
                $response['message'] = validation_errors();

                $response['quantity'] = form_error('quantity');
            } else {

                // Proses pengurangan
                $update_quantity = $quantity_old - $quantity;

                // Data yang akan diupdate
                $data_update = array(
                    'quantity' => $update_quantity
                );

                // Melakukan update data pengguna di tb material_storage
                $this->Builder_model->update('material_storage', $data_update, 'id_material', $id_material);

                // Data yang akan disimpan
                $data = array(
                    'id_material' => $id_material,
                    'quantity_mo' => $quantity,

                    'id_user_mo' => userdata('id_user'),
                    'created_mo' => date('Y-m-d H:i:s')
                );

                // Melakukan insert data pengguna di tb take_material
                $this->Builder_model->save('material_out', $data);

                // Menyiapkan respons JSON
                $response = array();

                // Setelah berhasil update
                $response['status'] = 'success';
                $response['message'] = 'Data inserted successfully';
            }

            // Mengirim respons JSON
            echo json_encode($response);
        } else {
            redirect('errors/not_found');
        }
    }
}
