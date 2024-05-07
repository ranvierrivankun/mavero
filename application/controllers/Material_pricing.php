<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Material_pricing extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_login();
        check_role([1, 3]);
        $this->load->model('Builder_model');
        $this->load->model('Material_pricing_model');
    }

    public function index()
    {
        // Mengambil data setting dari database
        $setting = $this->db->select('*')->from('setting')->get()->row();

        // Membentuk judul halaman dengan nama setting
        $title = $setting->name . ' - Material Pricing';
        $title_page = 'Material Pricing';

        // Ambil data dari model
        $type = $this->Material_pricing_model->get_type();

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
        $this->load->view('material_pricing/index', $data);
        $this->load->view('template/footer');
    }

    // Total Price
    public function total_price()
    {
        // Ambil nilai selectType dan selectStatus dari permintaan POST
        $selectType = $this->input->post('selectType');
        $selectStatus = $this->input->post('selectStatus');

        // Query ke database untuk mengambil harga sesuai dengan selectType dan selectStatus
        $this->db->select('SUM(a.price) as total_rupiah');
        $this->db->from('material_pricing as a'); // Gantilah 'your_table_name' dengan nama tabel Anda
        $this->db->join('request_material as b', 'a.id_material=b.id_material');
        $this->db->where('b.type', $selectType);
        $this->db->where('b.status', $selectStatus);
        $query = $this->db->get();

        // Mengembalikan hasil dalam format JSON
        $result = $query->row();
        echo json_encode($result);
    }


    // Tampilkan table_material_pricing
    public function table_material_pricing()
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
        $table = $this->Material_pricing_model->table_request_material($start, $length, $column_order, $dir, $selectType, $selectStatus);
        $filter = $this->Material_pricing_model->filter_table_request_material();
        $total = $this->Material_pricing_model->total_table_request_material();

        $data = [];

        $total_price = 0;

        foreach ($table as $tb) {
            $pricing = "<a class='btn btn-sm btn-success pricing' data-id_material='" . htmlspecialchars($tb->id_material, ENT_QUOTES, 'UTF-8') . "'>Pricing</a>";
            $reject = "<a class='btn btn-sm btn-danger reject' data-id_material='" . htmlspecialchars($tb->id_material, ENT_QUOTES, 'UTF-8') . "'>Reject</a>";

            $detail = "<a class='btn btn-sm btn-secondary detail' data-id_material='" . htmlspecialchars($tb->id_material, ENT_QUOTES, 'UTF-8') . "'>Detail</a>";
            $edit = "<a class='btn btn-sm btn-primary edit' data-id_material='" . htmlspecialchars($tb->id_material, ENT_QUOTES, 'UTF-8') . "'>Edit</a>";
            $group = "<a class='btn btn-sm btn-outline-secondary detail' data-id_material='" . htmlspecialchars($tb->id_material, ENT_QUOTES, 'UTF-8') . "'>@group</a>";


            // Menentukan button dengan status yang sesuai
            $buttons = '';

            if ($tb->status === 'process') {
                $buttons = '<center><div class="btn-group">' . $pricing . ' ' . $reject . '</div></center>';
            } elseif ($tb->status === 'pricing') {
                if ($tb->id_group === NULL) {
                    $buttons = '<center><div class="btn-group">' . $detail . ' ' . $edit . '</div></center>';
                } else {
                    $buttons = '<center>' . $group . '</center>';
                }
            } elseif ($tb->status === 'rejected') {
                $buttons = '<center><div class="btn-group">' . $detail . '</div></center>';
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
            $row = [
                $buttons,
                $tb->name_mt,
                $tb->name,
                $tb->size,
                $tb->quantity . ' ' . $tb->unit,
                rupiah($tb->price),
                $status
            ];

            $total_price += $tb->price;

            $data[] = $row;
        }

        // Menyiapkan output dalam format JSON untuk DataTables
        $output = [
            'draw' => $this->input->post('draw'), // Nomor draw yang digunakan oleh DataTables
            'recordsTotal' => $total, // Jumlah total catatan
            'recordsFiltered' => $filter, // Jumlah catatan setelah diterapkan filter
            'data' => $data, // Data yang akan ditampilkan dalam tabel
            'total_rupiah' => rupiah($total_price), // Total Rupiah
        ];

        // Mengirimkan output dalam format JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    // Tampilkan modal pricing
    public function material_pricing()
    {
        // Ambil id_material dan konversi menjadi integer
        $id_material = intval($this->input->post('id_material'));

        // Query get_request_material
        $data['edit'] = $this->Material_pricing_model->get_request_material($id_material);

        // Memuat tampilan "pricing" dan mengirimkan data
        $this->load->view('material_pricing/pricing', $data);
    }

    // Tampilkan modal edit_material_pricing
    public function edit_material_pricing()
    {
        // Ambil id_material dan konversi menjadi integer
        $id_material = intval($this->input->post('id_material'));

        // Query get_request_material
        $data['edit'] = $this->Material_pricing_model->edit_request_material($id_material);

        // Memuat tampilan "edit_material_pricing" dan mengirimkan data
        $this->load->view('material_pricing/edit_material_pricing', $data);
    }

    // Tampilkan modal detail_material_pricing
    public function detail_material_pricing()
    {
        // Ambil id_material dan konversi menjadi integer
        $id_material = intval($this->input->post('id_material'));

        // Query get_request_material
        $data['edit'] = $this->Material_pricing_model->edit_request_material($id_material);

        // Memuat tampilan "detail_material_pricing" dan mengirimkan data
        $this->load->view('material_pricing/detail_material_pricing', $data);
    }

    // Proses Material Pricing
    public function pricing()
    {
        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax

            // Mendapatkan data dari permintaan POST
            $id_material = $this->input->post('id_material', true);
            $price = $this->input->post('price', true);

            // Set validation rules
            $this->form_validation->set_rules('price', 'Price', 'required');

            // Validasi data yang harus diisi
            if ($this->form_validation->run() == false) {
                // Jika ada kesalahan validasi, mengembalikan pesan error
                $response['status'] = 'error';
                $response['message'] = validation_errors();

                $response['price'] = form_error('price');
            } else {

                // Hapus tanda koma dan simbol lainnya dari harga
                $price = preg_replace('/[^0-9]/', '', $price);

                // Validasi jika $price adalah null
                if ($price === '') {
                    $response['status'] = 'error';
                    $response['message'] = 'Price cannot be null';
                } else {
                    // Data yang akan disimpan
                    $data = array(
                        'id_material' => $id_material,
                        'price' => $price,

                        'id_user' => userdata('id_user'),
                        'created' => date('Y-m-d H:i:s')
                    );

                    // Data yang akan diupdate
                    $data_update = array(
                        'status' => 'pricing'
                    );

                    // Melakukan insert data pengguna di database material_pricing
                    $this->Builder_model->save('material_pricing', $data);

                    // Melakukan update data pengguna di database request_material
                    $this->Builder_model->update('request_material', $data_update, 'id_material', $id_material);

                    // Menyiapkan respons JSON
                    $response = array();

                    // Setelah berhasil update
                    $response['status'] = 'success';
                    $response['message'] = 'Data inserted successfully';
                }
            }

            // Mengirim respons JSON
            echo json_encode($response);
        } else {
            redirect('errors/not_found');
        }
    }

    // Proses Edit Material Pricing
    public function edit()
    {
        if ($this->input->is_ajax_request()) { // Pastikan ini adalah permintaan Ajax

            // Mendapatkan data dari permintaan POST
            $id_mp = $this->input->post('id_mp', true);
            $price = $this->input->post('price', true);

            // Set validation rules
            $this->form_validation->set_rules('price', 'Price', 'required');

            // Validasi data yang harus diisi
            if ($this->form_validation->run() == false) {
                // Jika ada kesalahan validasi, mengembalikan pesan error
                $response['status'] = 'error';
                $response['message'] = validation_errors();

                $response['price'] = form_error('price');
            } else {

                // Hapus tanda koma dan simbol lainnya dari harga
                $price = preg_replace('/[^0-9]/', '', $price);

                if ($price === '') {
                    $response['status'] = 'error';
                    $response['message'] = 'Price cannot be null';
                } else {
                    // Data yang akan disimpan
                    $data = array(
                        'price' => $price,

                        'id_user_updated' => userdata('id_user'),
                        'updated' => date('Y-m-d H:i:s')
                    );

                    // Melakukan update data pengguna di database material_pricing
                    $this->Builder_model->update('material_pricing', $data, 'id_mp', $id_mp);

                    // Menyiapkan respons JSON
                    $response = array();

                    // Setelah berhasil update
                    $response['status'] = 'success';
                    $response['message'] = 'Data updated successfully';
                }
            }

            // Mengirim respons JSON
            echo json_encode($response);
        } else {
            redirect('errors/not_found');
        }
    }

    // Proses Reject Request Material
    public function reject()
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

                // Data yang akan diupdate
                $data = array(
                    'status' => 'rejected'
                );

                // Melakukan update data pengguna di database request_material
                $this->Builder_model->update('request_material', $data, 'id_material', $id_material);

                $response = [
                    'status' => 'success',
                    'message' => 'Request Material successfully rejected.'
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
