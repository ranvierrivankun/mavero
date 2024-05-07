<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Material_pricing_model extends CI_Model
{
    // Ambil data dari table material_type
    public function get_type()
    {
        $this->db->select('*');
        $this->db->from('material_type');
        return $this->db->get()->result();
    }

    // Ambil data dari tabel request_material
    public function get_request_material($id_material)
    {
        $this->db->select('a.*, b3.*, a.id_material, a.name AS name_material, b1.name AS name_user, b2.name AS name_user_updated');
        $this->db->from('request_material as a');
        $this->db->join('user_db as b1', 'b1.id_user = a.id_user');
        $this->db->join('user_db as b2', 'b2.id_user = a.id_user_updated', 'left'); // Menggunakan left join
        $this->db->join('material_type as b3', 'b3.id_mt = a.id_mt');
        $this->db->where('a.id_material', $id_material);
        $result = $this->db->get()->row();

        if ($result && $result->id_user_updated === null) {
            // Jika id_user_updated adalah NULL, lakukan sesuatu (misalnya, berikan nilai default)
            $result->name_user_updated = "-"; // Nilai default jika NULL
        }

        return $result;
    }

    // Ambil data dari tabel request_material
    public function edit_request_material($id_material)
    {
        $this->db->select('a.*, b3.*, b4.*, a.id_material, 
        a.name AS name_material, b1.name AS name_user, 
        b2.name AS name_user_updated, 
        b5.name AS name_user_mp, 
        b6.name AS name_user_updated_mp, 
        a.created AS created, 
        b4.created AS created_pricing, 
        a.updated AS updated, 
        a.id_user_updated AS id_user_updated_request_material, 
        b4.updated AS updated_pricing');
        $this->db->from('request_material as a');
        $this->db->join('user_db as b1', 'b1.id_user = a.id_user');
        $this->db->join('user_db as b2', 'b2.id_user = a.id_user_updated', 'left'); // Menggunakan left join
        $this->db->join('material_type as b3', 'b3.id_mt = a.id_mt');
        $this->db->join('material_pricing as b4', 'b4.id_material = a.id_material', 'left'); // Menggunakan left join
        $this->db->join('user_db as b5', 'b5.id_user = b4.id_user', 'left'); // Menggunakan left join
        $this->db->join('user_db as b6', 'b6.id_user = b4.id_user_updated', 'left'); // Menggunakan left join
        $this->db->where('a.id_material', $id_material);
        $result = $this->db->get()->row();

        return $result;
    }

    public function table_request_material($start, $length, $order_col, $order_dir, $selectType, $selectStatus)
    {
        $search_value = strtolower($this->input->post('search')['value']);

        $id_role = userdata('id_role');

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('a.name', $search_value);
            $this->db->or_like('b.name_mt', $search_value);
            $this->db->group_end();
        }

        // Mengambil data
        $this->db->select('a.*, b.name_mt, c.price, c.id_material as id_material_pricing');
        $this->db->from('request_material as a');
        $this->db->join('material_type as b', 'b.id_mt=a.id_mt');
        $this->db->join('material_pricing as c', 'c.id_material=a.id_material', 'left');

        // Menambahkan kondisi jika role '3' tidak dapat melihat status rejected
        if ($id_role == 3) {
            $this->db->where('a.status !=', 'rejected');
        }

        // Menambahkan kondisi jika role '2' tidak dapat melihat status pricing
        // if ($id_role == 2) {
        //     $this->db->where('a.status !=', 'pricing');
        // }

        // Menambahkan kondisi jika selectType tidak kosong
        if (!empty($selectType)) {
            $this->db->where('a.id_mt', $selectType);
        }

        // Menambahkan kondisi jika selectStatus tidak kosong
        if (!empty($selectStatus)) {
            $this->db->where('a.status', $selectStatus);
        }

        // Mengatur pengurutan
        if (!empty($order_col) && !empty($order_dir)) {
            $this->db->order_by($order_col, $order_dir);
        } else {
            $this->db->order_by('a.id_material', 'DESC'); // Pengurutan default jika tidak ada pengurutan yang ditentukan
        }

        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        return $this->db->get()->result();
    }

    public function filter_table_request_material()
    {
        $search_value = strtolower($this->input->post('search')['value']);

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('name', $search_value);
            $this->db->or_like('b.name_mt', $search_value);
            $this->db->group_end();
        }

        // Menghitung jumlah baris
        $this->db->from('request_material as a');
        $this->db->join('material_type as b', 'b.id_mt=a.id_mt');
        $this->db->join('material_pricing as c', 'c.id_material=a.id_material', 'left');
        return $this->db->count_all_results();
    }

    public function total_table_request_material()
    {
        $this->db->from('request_material as a');
        $this->db->join('material_type as b', 'b.id_mt=a.id_mt');
        $this->db->join('material_pricing as c', 'c.id_material=a.id_material', 'left');
        return $this->db->count_all_results();
    }
}
