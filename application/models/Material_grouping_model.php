<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Material_grouping_model extends CI_Model
{
    // get request_material
    public function getMaterial()
    {
        $this->db->select('*');
        $this->db->from('request_material');
        $this->db->where('status', 'pricing');
        $this->db->where('id_group IS NULL', null, false); // false untuk menghindari pengamanan
        return $this->db->get()->result();
    }

    // get group
    public function get_group_db($id_group)
    {
        $this->db->select('a.*, b3.*, 
        b1.name AS name, 
        b2.name AS name_updated, 
        a.created AS created_group, 
        a.updated AS updated_group,
        b3.created AS created_delivery, 
        b3.updated AS updated_delivery,
        c1.name AS name_delivery, 
        c2.name AS name_delivery_updated, 
        d1.name AS name_received, 
        a.id_group AS id_group_db');
        $this->db->from('group as a');
        $this->db->join('user_db as b1', 'b1.id_user=a.id_user', 'left');
        $this->db->join('user_db as b2', 'b2.id_user=a.id_user_updated', 'left');
        $this->db->join('material_delivery as b3', 'b3.id_group=a.id_group', 'left');
        $this->db->join('user_db as c1', 'c1.id_user=b3.id_user', 'left');
        $this->db->join('user_db as c2', 'c2.id_user=b3.id_user_updated', 'left');
        $this->db->join('user_db as d1', 'd1.id_user=a.id_user_received', 'left');
        $this->db->where('a.id_group', $id_group);
        return $this->db->get()->row();
    }

    // get_request_material
    public function get_request_material($id_group)
    {
        $this->db->select('*');
        $this->db->from('request_material as a');
        $this->db->join('material_type as b', 'b.id_mt=a.id_mt');
        $this->db->join('material_pricing as c', 'c.id_material=a.id_material');
        $this->db->where('a.id_group', $id_group);
        return $this->db->get()->result();
    }

    public function table_material_grouping($start, $length, $order_col, $order_dir, $selectStatus)
    {
        $search_value = strtolower($this->input->post('search')['value']);

        $id_role = userdata('id_role');

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('a.name_group', $search_value);
            $this->db->or_like('a.created', $search_value);
            $this->db->or_like('b1.name', $search_value);
            $this->db->group_end();
        }

        // Mengambil data
        $this->db->select('a.*, b1.*, a.created AS created_group');
        $this->db->from('group as a');
        $this->db->join('user_db as b1', 'b1.id_user=a.id_user');
        // $this->db->where('a.status_group !=', 'received');

        // Menambahkan kondisi jika selectStatus tidak kosong
        if (!empty($selectStatus)) {
            $this->db->where('a.status_group', $selectStatus);
        }

        // Mengatur pengurutan
        if (!empty($order_col) && !empty($order_dir)) {
            $this->db->order_by($order_col, $order_dir);
        } else {
            $this->db->order_by('a.id_group', 'DESC'); // Pengurutan default jika tidak ada pengurutan yang ditentukan
        }

        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        return $this->db->get()->result();
    }

    public function filter_table_material_grouping()
    {
        $search_value = strtolower($this->input->post('search')['value']);

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('a.name_group', $search_value);
            $this->db->or_like('a.created', $search_value);
            $this->db->or_like('b1.name', $search_value);
            $this->db->group_end();
        }

        // Menghitung jumlah baris
        $this->db->from('group as a');
        $this->db->join('user_db as b1', 'b1.id_user=a.id_user');
        // $this->db->where('a.status_group !=', 'received');
        return $this->db->count_all_results();
    }

    public function total_table_material_grouping()
    {
        $this->db->from('group as a');
        $this->db->join('user_db as b1', 'b1.id_user=a.id_user');
        // $this->db->where('a.status_group !=', 'received');
        return $this->db->count_all_results();
    }

    public function calculateTotalPrice($id_group)
    {
        // Lakukan query atau perhitungan total harga berdasarkan $id_group
        $query = $this->db->select_sum('b.price')
            ->from('material_grouping as a')
            ->join('material_pricing as b', 'b.id_material=a.id_material')
            ->where('a.id_group', $id_group)
            ->get();

        return $query->row()->price;
    }

    public function table_material($start, $length, $order_col, $order_dir, $id_group)
    {
        $search_value = strtolower($this->input->post('search')['value']);

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('b1.name', $search_value);
            $this->db->group_end();
        }

        // Mengambil data
        $this->db->select('a.*, b2.name_mt, b1.name, b1.size, b1.quantity, b1.unit, b3.price');
        $this->db->from('material_grouping as a');
        $this->db->where('a.id_group', $id_group);
        $this->db->join('request_material as b1', 'b1.id_material=a.id_material');
        $this->db->join('material_type as b2', 'b2.id_mt=b1.id_mt');
        $this->db->join('material_pricing as b3', 'b3.id_material=a.id_material');


        // Mengatur pengurutan
        if (!empty($order_col) && !empty($order_dir)) {
            $this->db->order_by($order_col, $order_dir);
        } else {
            $this->db->order_by('a.id_group', 'DESC'); // Pengurutan default jika tidak ada pengurutan yang ditentukan
        }

        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        return $this->db->get()->result();
    }

    public function filter_table_material($id_group)
    {
        $search_value = strtolower($this->input->post('search')['value']);

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('b1.name', $search_value);
            $this->db->group_end();
        }

        // Menghitung jumlah baris
        $this->db->from('material_grouping as a');
        $this->db->where('a.id_group', $id_group);
        $this->db->join('request_material as b1', 'b1.id_material=a.id_material');
        $this->db->join('material_type as b2', 'b2.id_mt=b1.id_mt');
        $this->db->join('material_pricing as b3', 'b3.id_material=a.id_material');
        return $this->db->count_all_results();
    }

    public function total_table_material($id_group)
    {
        $this->db->from('material_grouping as a');
        $this->db->where('a.id_group', $id_group);
        $this->db->join('request_material as b1', 'b1.id_material=a.id_material');
        $this->db->join('material_type as b2', 'b2.id_mt=b1.id_mt');
        $this->db->join('material_pricing as b3', 'b3.id_material=a.id_material');
        return $this->db->count_all_results();
    }
}
