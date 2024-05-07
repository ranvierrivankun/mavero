<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Material_delivery_model extends CI_Model
{
    public function get_material($id_group)
    {
        return $this->db->select('*')
            ->from('material_grouping')
            ->where('request_material.id_group', $id_group)
            ->join('request_material', 'material_grouping.id_material = request_material.id_material')
            ->get()
            ->result();
    }

    public function table_material_delivery($start, $length, $order_col, $order_dir, $selectStatus)
    {
        $search_value = strtolower($this->input->post('search')['value']);

        $id_role = userdata('id_role');

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('a.name_group', $search_value);
            $this->db->or_like('b1.no_resi', $search_value);
            $this->db->or_like('b1.created', $search_value);
            $this->db->group_end();
        }

        // Mengambil data
        $this->db->select('a.*, b1.*, b1.created AS created_delivery, a.id_group as id_group_db');
        $this->db->from('group as a');
        $this->db->join('material_delivery as b1', 'b1.id_group=a.id_group', 'left');

        // Menambahkan kondisi jika role '4' tidak dapat melihat status rejected
        if ($id_role == 4) {
            $this->db->where('a.status_group !=', 'pending');
        }

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

    public function filter_table_material_delivery()
    {
        $search_value = strtolower($this->input->post('search')['value']);

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('a.name_group', $search_value);
            $this->db->or_like('b1.no_resi', $search_value);
            $this->db->or_like('b1.created', $search_value);
            $this->db->group_end();
        }

        // Menghitung jumlah baris
        $this->db->from('group as a');
        $this->db->join('material_delivery as b1', 'b1.id_group=a.id_group', 'left');
        return $this->db->count_all_results();
    }

    public function total_table_material_delivery()
    {
        $this->db->from('group as a');
        $this->db->join('material_delivery as b1', 'b1.id_group=a.id_group', 'left');
        return $this->db->count_all_results();
    }
}
