<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Material_delivery extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_login();
        check_role([1, 3, 4]);
        $this->load->model('Builder_model');
        $this->load->model('Material_delivery_model');
        $this->load->model('Material_grouping_model');
    }

    public function index()
    {
        // Mengambil data setting dari database
        $setting = $this->db->select('*')->from('setting')->get()->row();

        // Membentuk judul halaman dengan nama setting
        $title = $setting->name . ' - Material Delivery';
        $title_page = 'Material Delivery';

        // Menyusun data untuk dikirim ke tampilan
        $data['setting'] = [
            'title' => $title,
            'title_page' => $title_page
        ];

        // Memuat tampilan dengan data yang telah disusun
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('template/navbar');
        $this->load->view('material_delivery/index', $data);
        $this->load->view('template/footer');
    }

    // Tampilkan table_material_delivery
    public function table_material_delivery()
    {
        // Mengambil informasi yang diperlukan dari permintaan AJAX DataTables
        $start = $this->input->post('start'); // Mulai data
        $length = $this->input->post('length'); // Jumlah data per halaman
        $order = $this->input->post('order'); // Informasi pengurutan dari DataTables

        $column_order = $order[0]['column']; // Index kolom yang diurutkan
        $dir = $order[0]['dir']; // Arah pengurutan (asc atau desc)

        $selectStatus = $this->input->post('selectStatus');

        // Memuat data dari model dengan pengaturan pengurutan
        $table = $this->Material_delivery_model->table_material_delivery($start, $length, $column_order, $dir, $selectStatus);
        $filter = $this->Material_delivery_model->filter_table_material_delivery();
        $total = $this->Material_delivery_model->total_table_material_delivery();

        $data = [];

        foreach ($table as $tb) {

            $send = "<a class='btn btn-sm btn-success send' data-id_group_db='" . htmlspecialchars($tb->id_group_db, ENT_QUOTES, 'UTF-8') . "'>Send</a>";
            $edit = "<a class='btn btn-sm btn-primary edit' data-id_group_db='" . htmlspecialchars($tb->id_group_db, ENT_QUOTES, 'UTF-8') . "'>Edit</a>";
            $detail = "<a class='btn btn-sm btn-secondary detail' data-id_group_db='" . htmlspecialchars($tb->id_group_db, ENT_QUOTES, 'UTF-8') . "'>Detail</a>";
            $accept = "<a class='btn btn-sm btn-success accept' data-id_group_db='" . htmlspecialchars($tb->id_group_db, ENT_QUOTES, 'UTF-8') . "'>Accept</a>";
            $received = "<a class='btn btn-sm btn-outline-secondary detail' data-id_group_db='" . htmlspecialchars($tb->id_group_db, ENT_QUOTES, 'UTF-8') . "'>@received</a>";

            // Menentukan button dengan status yang sesuai
            $buttons = '';
            if ($tb->status_group === 'pending') {

                if (userdata('id_role') === '4') {
                    $buttons = '<center><div class="btn-group">' . $detail . '</div></center>';
                } else {
                    $buttons = '<center><div class="btn-group">' . $send . '</div></center>';
                }
            } elseif ($tb->status_group === 'sending') {

                if (userdata('id_role') === '4') {
                    $buttons = '<center><div class="btn-group">' . $detail . ' ' . $accept . '</div></center>';
                } else if (userdata('id_role') === '1') {
                    $buttons = '<center><div class="btn-group">' . $detail . ' ' . $edit . ' ' . $accept . '</div></center>';
                } else {
                    $buttons = '<center><div class="btn-group">' . $detail . ' ' . $edit . '</div></center>';
                }
            } elseif ($tb->status_group === 'received') {

                if (userdata('id_role') === '4') {
                    $buttons = '<center><div class="btn-group">' . $received . '</div></center>';
                } else {
                    $buttons = '<center><div class="btn-group">' . $received . '</div></center>';
                }
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

            // Kondisi no_resi
            if ($tb->no_resi === null) {
                $no_resi = '-';
            } else {
                $no_resi = $tb->no_resi;
            }

            // Kondisi created_delivery
            if ($tb->created_delivery === null) {
                $created_delivery = '-';
            } else {
                $created_delivery = $tb->created_delivery;
            }

            // Menyiapkan data untuk setiap baris dalam tabel
            $row = [
                $buttons,
                $no_resi,
                $created_delivery,
                $tb->name_group,
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

    // Tampilkan modal send_material
    public function send_material()
    {
        // Ambil id_group dan konversi menjadi integer
        $id_group = intval($this->input->post('id_group'));

        // Query get_group_db
        $data['edit'] = $this->Material_grouping_model->get_group_db($id_group);

        // Memuat tampilan "send_material" dan mengirimkan data
        $this->load->view('material_delivery/send_material', $data);
    }

    // Tampilkan modal edit_send
    public function edit_send()
    {
        // Ambil id_group dan konversi menjadi integer
        $id_group = intval($this->input->post('id_group'));

        // Query get_group_db
        $data['edit'] = $this->Material_grouping_model->get_group_db($id_group);

        // Memuat tampilan "edit_send" dan mengirimkan data
        $this->load->view('material_delivery/edit_send', $data);
    }

    // Tampilkan modal detail_send
    public function detail_send()
    {
        // Ambil id_group dan konversi menjadi integer
        $id_group = intval($this->input->post('id_group'));

        // Query get_group_db
        $data['edit'] = $this->Material_grouping_model->get_group_db($id_group);

        // Memuat tampilan "edit_send" dan mengirimkan data
        $this->load->view('material_delivery/detail_send', $data);
    }

    // Proses Material Send
    public function send()
    {
        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax

            // Mendapatkan data dari permintaan POST
            $id_group = $this->input->post('id_group', true);
            $no_resi = $this->input->post('no_resi', true);

            // Set validation rules
            $this->form_validation->set_rules('no_resi', 'No. Resi', 'required|is_unique[material_delivery.no_resi]');

            // Validasi data yang harus diisi
            if ($this->form_validation->run() == false) {
                // Jika ada kesalahan validasi, mengembalikan pesan error
                $response['status'] = 'error';
                $response['message'] = validation_errors();

                $response['no_resi'] = form_error('no_resi');
            } else {

                // Data yang akan disimpan
                $data = array(
                    'id_group' => $id_group,
                    'no_resi' => $no_resi,

                    'id_user' => userdata('id_user'),
                    'created' => date('Y-m-d H:i:s')
                );

                // Data yang akan diupdate
                $data_update = array(
                    'status_group' => 'sending'
                );

                // Melakukan insert data pengguna di database material_delivery
                $this->Builder_model->save('material_delivery', $data);

                // Melakukan update data pengguna di database group
                $this->Builder_model->update('group', $data_update, 'id_group', $id_group);

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

    // Cek Name Material Type pada Database
    public function check_unique_no_resi($no_resi, $id_group)
    {
        $existing = $this->Builder_model->get('material_delivery', array('no_resi' => $no_resi));

        if ($existing && $existing['id_group'] != $id_group) {
            $this->form_validation->set_message('check_unique_no_resi', 'This No. Resi is already taken.');
            return false;
        }
        return true;
    }

    // Edit Material Send
    public function edit()
    {
        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax

            // Mendapatkan data dari permintaan POST
            $id_group = $this->input->post('id_group', true);
            $no_resi = $this->input->post('no_resi', true);

            // Set validation rules
            $this->form_validation->set_rules('no_resi', 'No. Resi', 'required|callback_check_unique_no_resi[' . $id_group . ']');

            // Validasi data yang harus diisi
            if ($this->form_validation->run() == false) {
                // Jika ada kesalahan validasi, mengembalikan pesan error
                $response['status'] = 'error';
                $response['message'] = validation_errors();

                $response['no_resi'] = form_error('no_resi');
            } else {

                // Data yang akan disimpan
                $data = array(
                    'no_resi' => $no_resi,

                    'id_user_updated' => userdata('id_user'),
                    'updated' => date('Y-m-d H:i:s')
                );

                // Melakukan update data pengguna di database material_delivery
                $this->Builder_model->update('material_delivery', $data, 'id_group', $id_group);

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

    // Proses Accept Request Material
    public function accept()
    {
        // Ambil id_group dan konversi menjadi integer
        $id_group = intval($this->input->post('id_group'));

        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax
            // Validasi apakah id adalah angka yang valid atau tidak
            if ($id_group <= 0) {
                $response = [
                    'status' => 'error',
                    'message' => 'Invalid group ID.'
                ];
            } else {

                $get_material = $this->Material_delivery_model->get_material($id_group);

                // Loop melalui hasil query dan masukkan data ke tabel material_storage
                foreach ($get_material as $gm) {

                    // Cek apakah material dengan atribut yang sama sudah ada di tabel material_storage
                    $existing_material = $this->Builder_model->get_by(
                        'material_storage',
                        array(
                            'id_mt' => $gm->id_mt,
                            'name' => $gm->name,
                            'size' => $gm->size,
                            'unit' => $gm->unit
                        )
                    );

                    if ($existing_material) {
                        // Jika material sudah ada, lakukan update quantity
                        $existing_data = $existing_material[0];
                        $new_quantity = $existing_data['quantity'] + $gm->quantity;

                        // Update quantity material
                        $this->Builder_model->update(
                            'material_storage',
                            array('quantity' => $new_quantity),
                            'id_material', // Attribut kunci untuk menemukan entri yang sesuai
                            $existing_data['id_material'] // Nilai kunci untuk mencari entri yang sesuai
                        );
                    } else {
                        // Jika material belum ada, lakukan insert
                        $data_material = array(
                            'id_material' => $gm->id_material,
                            'id_mt' => $gm->id_mt,
                            'id_group' => $id_group,
                            'name' => $gm->name,
                            'size' => $gm->size,
                            'quantity' => $gm->quantity,
                            'unit' => $gm->unit,
                        );

                        $this->Builder_model->save('material_storage', $data_material);
                    }
                }

                // Data yang akan diupdate
                $data = array(
                    'status_group' => 'received',
                    'id_user_received' => userdata('id_user'),
                    'received' => date('Y-m-d H:i:s')
                );

                // Melakukan update data pengguna di database request_material
                $this->Builder_model->update('group', $data, 'id_group', $id_group);

                $response = [
                    'status' => 'success',
                    'message' => 'Material Delivery successfully.'
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
