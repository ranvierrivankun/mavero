<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_setting_model extends CI_Model
{

    // Ambil data dari table role_db
    public function get_roles()
    {
        $this->db->select('*');
        $this->db->from('role_db');
        $this->db->where('id_role !=', 1); // Mengkecualikan id=1
        return $this->db->get()->result();
    }

    public function table_user_setting($start, $length, $order_col, $order_dir, $selectRole)
    {
        $search_value = strtolower($this->input->post('search')['value']);

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('username', $search_value);
            $this->db->or_like('name', $search_value);
            $this->db->or_like('email', $search_value);
            $this->db->or_like('mobile', $search_value);
            $this->db->or_like('b.name_role', $search_value);
            $this->db->group_end();
        }

        // Mengambil data
        $this->db->select('*');
        $this->db->from('user_db as a');
        $this->db->join('role_db as b', 'b.id_role=a.id_role');
        $this->db->where('a.id_role !=', 1); // Mengkecualikan id_role=1

        // Menambahkan kondisi jika selectRole tidak kosong
        if (!empty($selectRole)) {
            $this->db->where('a.id_role', $selectRole);
        }

        // Mengatur pengurutan
        if (!empty($order_col) && !empty($order_dir)) {
            $this->db->order_by($order_col, $order_dir);
        } else {
            $this->db->order_by('a.id_user', 'DESC'); // Pengurutan default jika tidak ada pengurutan yang ditentukan
        }

        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        return $this->db->get()->result();
    }

    public function filter_table_user_setting()
    {
        $search_value = strtolower($this->input->post('search')['value']);

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('username', $search_value);
            $this->db->or_like('name', $search_value);
            $this->db->or_like('email', $search_value);
            $this->db->or_like('mobile', $search_value);
            $this->db->or_like('b.name_role', $search_value);
            $this->db->group_end();
        }

        // Menghitung jumlah baris
        $this->db->from('user_db as a');
        $this->db->join('role_db as b', 'b.id_role=a.id_role');
        $this->db->where('a.id_role !=', 1); // Mengkecualikan id_role=1
        return $this->db->count_all_results();
    }

    public function total_table_user_setting()
    {
        $this->db->from('user_db as a');
        $this->db->join('role_db as b', 'b.id_role=a.id_role');
        $this->db->where('a.id_role !=', 1); // Mengkecualikan id_role=1
        return $this->db->count_all_results();
    }
}
