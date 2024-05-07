<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Material_setting_model extends CI_Model
{
    public function table_material_type($start, $length, $order_col, $order_dir)
    {
        $search_value = strtolower($this->input->post('search')['value']);

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('name_mt', $search_value);
            $this->db->group_end();
        }

        // Mengambil data
        $this->db->select('*');
        $this->db->from('material_type');

        // Mengatur pengurutan
        if (!empty($order_col) && !empty($order_dir)) {
            $this->db->order_by($order_col, $order_dir);
        } else {
            $this->db->order_by('id_mt', 'DESC'); // Pengurutan default jika tidak ada pengurutan yang ditentukan
        }

        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        return $this->db->get()->result();
    }

    public function filter_table_material_type()
    {
        $search_value = strtolower($this->input->post('search')['value']);

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('name_mt', $search_value);
            $this->db->group_end();
        }

        // Menghitung jumlah baris
        $this->db->from('material_type');
        return $this->db->count_all_results();
    }

    public function total_table_material_type()
    {
        $this->db->from('material_type');
        return $this->db->count_all_results();
    }
}
