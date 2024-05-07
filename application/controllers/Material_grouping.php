<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Material_grouping extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_login();
        check_role([1, 3]);
        $this->load->model('Builder_model');
        $this->load->model('Material_grouping_model');
    }

    public function index()
    {
        // Mengambil data setting dari database
        $setting = $this->db->select('*')->from('setting')->get()->row();

        // Membentuk judul halaman dengan nama setting
        $title = $setting->name . ' - Material Grouping';
        $title_page = 'Material Grouping';

        // Menyusun data untuk dikirim ke tampilan
        $data['setting'] = [
            'title' => $title,
            'title_page' => $title_page
        ];

        // Memuat tampilan dengan data yang telah disusun
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('template/navbar');
        $this->load->view('material_grouping/index', $data);
        $this->load->view('template/footer');
    }

    // Tampilkan table_material_grouping
    public function table_material_grouping()
    {
        // Mengambil informasi yang diperlukan dari permintaan AJAX DataTables
        $start = $this->input->post('start'); // Mulai data
        $length = $this->input->post('length'); // Jumlah data per halaman
        $order = $this->input->post('order'); // Informasi pengurutan dari DataTables

        $column_order = $order[0]['column']; // Index kolom yang diurutkan
        $dir = $order[0]['dir']; // Arah pengurutan (asc atau desc)

        $selectStatus = $this->input->post('selectStatus');

        // Memuat data dari model dengan pengaturan pengurutan
        $table = $this->Material_grouping_model->table_material_grouping($start, $length, $column_order, $dir, $selectStatus);
        $filter = $this->Material_grouping_model->filter_table_material_grouping();
        $total = $this->Material_grouping_model->total_table_material_grouping();

        $data = [];

        foreach ($table as $tb) {


            $detail = "<a class='btn btn-sm btn-secondary detail' data-id_group='" . htmlspecialchars($tb->id_group, ENT_QUOTES, 'UTF-8') . "'>Detail</a>";
            $edit = "<a class='btn btn-sm btn-primary edit' data-id_group='" . htmlspecialchars($tb->id_group, ENT_QUOTES, 'UTF-8') . "'>Edit</a>";
            $delete = "<a class='btn btn-sm btn-danger delete' data-id_group='" . htmlspecialchars($tb->id_group, ENT_QUOTES, 'UTF-8') . "'>Delete</a>";
            $delivery = "<a class='btn btn-sm btn-outline-secondary detail' data-id_group='" . htmlspecialchars($tb->id_group, ENT_QUOTES, 'UTF-8') . "'>@delivery</a>";

            // Menentukan button dengan status yang sesuai
            $buttons = '';
            if ($tb->status_group === 'pending') {
                $buttons = '<center><div class="btn-group">' . $detail . ' ' . $edit . ' ' . $delete . '</div></center>';
            } elseif ($tb->status_group === 'sending') {
                $buttons = '<center><div class="btn-group">' . $detail . ' ' . $edit . '</div></center>';
            } elseif ($tb->status_group === 'received') {
                $buttons = '<center><div class="btn-group">' . $delivery . '</div></center>';
            }

            // Menentukan status
            $status = '';
            if ($tb->status_group === 'pending') {
                $status = '<span class="badge bg-warning">Pending</span>';
            } elseif ($tb->status_group === 'sending') {
                $status = '<span class="badge bg-success">Sending</span>';
            } elseif ($tb->status_group === 'received') {
                $status = '<span class="badge bg-primary">Received</span>';
            }


            // Menyiapkan data untuk setiap baris dalam tabel
            $row = [
                $buttons,
                $tb->name_group,
                $tb->name,
                $tb->created_group,
                $status,
            ];

            $data[] = $row;
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

    public function calculateTotalPrice()
    {
        $id_group = $this->input->post('id_group', true);
        $total_price = $this->Material_grouping_model->calculateTotalPrice($id_group);

        $output = [
            'total_price' => rupiah($total_price), // Format total harga sebagai Rupiah
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    // Tampilkan table_material
    public function table_material()
    {
        // Mengambil informasi yang diperlukan dari permintaan AJAX DataTables
        $start = $this->input->post('start'); // Mulai data
        $length = $this->input->post('length'); // Jumlah data per halaman
        $order = $this->input->post('order'); // Informasi pengurutan dari DataTables

        $id_group = $this->input->post('id_group', true);

        $column_order = $order[0]['column']; // Index kolom yang diurutkan
        $dir = $order[0]['dir']; // Arah pengurutan (asc atau desc)

        // Memuat data dari model dengan pengaturan pengurutan
        $table = $this->Material_grouping_model->table_material($start, $length, $column_order, $dir, $id_group);
        $filter = $this->Material_grouping_model->filter_table_material($id_group);
        $total = $this->Material_grouping_model->total_table_material($id_group);

        $data = [];

        foreach ($table as $tb) {
            $delete = "<a class='btn btn-sm text-white bg-danger delete' data-id_material='" . htmlspecialchars($tb->id_material, ENT_QUOTES, 'UTF-8') . "'>Delete</a>";

            $buttons = '<center><div class="btn-group">' . $delete . '</div></center>';

            // Menyiapkan data untuk setiap baris dalam tabel
            $row = [
                $buttons,
                $tb->name_mt,
                $tb->name,
                $tb->size,
                $tb->quantity . ' ' . $tb->unit,
                rupiah($tb->price),
            ];

            $data[] = $row;
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

    // Tampilkan table_material
    public function table_material_detail()
    {
        // Mengambil informasi yang diperlukan dari permintaan AJAX DataTables
        $start = $this->input->post('start'); // Mulai data
        $length = $this->input->post('length'); // Jumlah data per halaman
        $order = $this->input->post('order'); // Informasi pengurutan dari DataTables

        $id_group = $this->input->post('id_group', true);

        $column_order = $order[0]['column']; // Index kolom yang diurutkan
        $dir = $order[0]['dir']; // Arah pengurutan (asc atau desc)

        // Memuat data dari model dengan pengaturan pengurutan
        $table = $this->Material_grouping_model->table_material($start, $length, $column_order, $dir, $id_group);
        $filter = $this->Material_grouping_model->filter_table_material($id_group);
        $total = $this->Material_grouping_model->total_table_material($id_group);

        $data = [];

        $counter = 1;

        foreach ($table as $tb) {

            // Menyiapkan data untuk setiap baris dalam tabel
            $row = [
                $counter++,
                $tb->name_mt,
                $tb->name,
                $tb->size,
                $tb->quantity . ' ' . $tb->unit,
                rupiah($tb->price),
            ];

            $data[] = $row;
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

    // Tampilkan modal tambah_group
    public function tambah_group()
    {
        // Memperoleh data type dari model
        $data['material'] = $this->Material_grouping_model->getMaterial();

        // Memuat tampilan "tambah_group" dan mengirimkan data
        $this->load->view('material_grouping/tambah_group', $data);
    }

    // ambil data material
    public function get_material_options()
    {
        $material = $this->Material_grouping_model->getMaterial();
        echo json_encode($material);
    }

    // Tampilkan modal edit_group
    public function edit_group()
    {
        // Ambil id_group dan konversi menjadi integer
        $id_group = intval($this->input->post('id_group'));

        // Memperoleh data material dari model
        $data['material'] = $this->Material_grouping_model->getMaterial();

        // Query get_group
        $data['edit'] = $this->Material_grouping_model->get_group_db($id_group);

        // Memuat tampilan "edit_group" dan mengirimkan data
        $this->load->view('material_grouping/edit_group', $data);
    }

    // Tampilkan modal detail_group
    public function detail_group()
    {
        // Ambil id_group dan konversi menjadi integer
        $id_group = intval($this->input->post('id_group'));

        // Query get_group
        $data['edit'] = $this->Material_grouping_model->get_group_db($id_group);

        // Memuat tampilan "detail_group" dan mengirimkan data
        $this->load->view('material_grouping/detail_group', $data);
    }

    // Proses Tambah Group
    public function tambah()
    {
        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax

            // Membuat id_group
            // Mengambil nilai maksimum dari kolom id_group dalam tabel group
            $this->db->select_max('id_group');
            $query = $this->db->get('group')->row_array();

            // Inisialisasi id_group dengan nilai maksimum yang diambil
            $id_group = $query['id_group'];

            // Jika tidak ada data dalam tabel, id_group diinisialisasi dengan 1
            if ($id_group === null) {
                $id_group = 1;
            } else {
                // Jika ada data dalam tabel, tambahkan 1 ke id_group
                $id_group++;
            }

            // Mendapatkan data dari permintaan POST
            $name_group = $this->input->post('name_group', true);
            $material = $this->input->post('material[]', true);

            // Set validation rules
            $this->form_validation->set_rules('name_group', 'Name', 'required|is_unique[group.name_group]');
            $this->form_validation->set_rules('material[]', 'Material', 'required');

            // Validasi data yang harus diisi
            if ($this->form_validation->run() == false) {
                // Jika kosong, mengembalikan pesan error
                $response['status'] = 'error';
                $response['message'] = validation_errors();

                $response['name_group'] = form_error('name_group');
                $response['material'] = form_error('material[]');
            } else {

                // Data yang akan disimpan
                $data = array(
                    'id_group' => $id_group,
                    'id_user' => userdata('id_user'),
                    'name_group' => $name_group,
                    'status_group' => 'pending',
                    'created' => date('Y-m-d H:i:s')
                );

                // Melakukan insert data pengguna di database request_material
                $this->Builder_model->save('group', $data);

                /*Proses Insert Select Material*/
                $select = count($material);
                for ($i = 0; $i < $select; $i++) {
                    $material_grouping[$i] = array(
                        'id_group' => $id_group,
                        'id_material' => $this->input->post('material[' . $i . ']')
                    );

                    $this->Builder_model->save('material_grouping', $material_grouping[$i]);
                }

                /*Proses Update status_group Material*/
                if (!empty($material)) {
                    foreach ($material as $material) {
                        $data_update = array('id_group' => $id_group);
                        $this->Builder_model->update('request_material', $data_update, 'id_material', $material);
                    }
                }

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
    public function check_unique_name_group($name_group, $id_group)
    {
        $existing = $this->Builder_model->get('group', array('name_group' => $name_group));

        if ($existing && $existing['id_group'] != $id_group) {
            $this->form_validation->set_message('check_unique_name_group', 'This Name Group is already taken.');
            return false;
        }
        return true;
    }

    // Proses Edit Group
    public function edit()
    {
        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax

            // Mendapatkan data dari permintaan POST
            $id_group = $this->input->post('id_group', true);
            $name_group = $this->input->post('name_group', true);
            $material = $this->input->post('material[]', true);


            // Set validation rules
            $this->form_validation->set_rules('name_group', 'Name', 'required|callback_check_unique_name_group[' . $id_group . ']');

            // Validasi data yang harus diisi
            if ($this->form_validation->run() == false) {
                // Jika ada kesalahan validasi, mengembalikan pesan error
                $response['status'] = 'error';
                $response['message'] = validation_errors();

                $response['name_group'] = form_error('name_group');
            } else {

                /// Data yang akan disimpan
                $data = array(
                    'name_group' => $name_group,
                    'id_user_updated' => userdata('id_user'),
                    'updated' => date('Y-m-d H:i:s')
                );

                // Melakukan update data pengguna di database request_material
                $this->Builder_model->update('group', $data, 'id_group', $id_group);

                /*Proses Insert Select Material*/
                if (is_array($material) || is_object($material)) {
                    $select = count($material);
                    for ($i = 0; $i < $select; $i++) {
                        $material_grouping[$i] = array(
                            'id_group' => $id_group,
                            'id_material' => $this->input->post('material[' . $i . ']')
                        );

                        $this->Builder_model->save('material_grouping', $material_grouping[$i]);
                    }
                } else {
                    // Handle the case where $material is not an array or countable object.
                    // You may want to log an error or take appropriate action here.
                }

                /*Proses Update status_group Material*/
                if (!empty($material)) {
                    foreach ($material as $material) {
                        $data_update = array('id_group' => $id_group);
                        $this->Builder_model->update('request_material', $data_update, 'id_material', $material);
                    }
                }

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

    // Proses Delete Group
    public function delete()
    {
        // Ambil id_material dan konversi menjadi integer
        $id_group = intval($this->input->post('id_group'));

        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax
            // Validasi apakah id adalah angka yang valid atau tidak
            if ($id_group <= 0) {
                $response = [
                    'status' => 'error',
                    'message' => 'Invalid group ID.'
                ];
            } else {
                // Lanjutkan dengan penghapusan
                $this->Builder_model->delete('group', 'id_group', $id_group);
                $this->Builder_model->delete('material_grouping', 'id_group', $id_group);
                $this->Builder_model->update('request_material', ['id_group' => NULL], 'id_group', $id_group);

                $response = [
                    'status' => 'success',
                    'message' => 'Group successfully deleted.'
                ];
            }

            // Mengembalikan respons dalam format JSON
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            redirect('errors/not_found');
        }
    }

    // Proses Delete Material in Group
    public function delete_material()
    {
        // Ambil id_material dan konversi menjadi integer
        $id_material = intval($this->input->post('id_material'));

        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax
            // Validasi apakah id adalah angka yang valid atau tidak
            if ($id_material <= 0) {
                $response = [
                    'status' => 'error',
                    'message' => 'Invalid group ID.'
                ];
            } else {
                // Lanjutkan dengan penghapusan
                $this->Builder_model->delete('material_grouping', 'id_material', $id_material);
                $this->Builder_model->update('request_material', ['id_group' => NULL], 'id_material', $id_material);

                $response = [
                    'status' => 'success',
                    'message' => 'Material in Group successfully deleted.'
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
