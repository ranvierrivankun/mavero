<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Material_storage_model extends CI_Model
{
    // Ambil data dari tabel request_material
    public function get_request_material($id_material)
    {
        $this->db->select('*');
        $this->db->from('material_storage as a');
        $this->db->join('material_type as b', 'b.id_mt=a.id_mt');
        $this->db->where('a.id_material', $id_material);
        $result = $this->db->get()->row();

        return $result;
    }

    public function table_material_storage($start, $length, $order_col, $order_dir, $selectType)
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
        $this->db->select('*');
        $this->db->from('material_storage as a');
        $this->db->join('material_type as b', 'b.id_mt=a.id_mt');
        $this->db->where('a.quantity >', 0);

        // Menambahkan kondisi jika selectType tidak kosong
        if (!empty($selectType)) {
            $this->db->where('a.id_mt', $selectType);
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

    public function filter_table_material_storage()
    {
        $search_value = strtolower($this->input->post('search')['value']);

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('a.name', $search_value);
            $this->db->or_like('b.name_mt', $search_value);
            $this->db->group_end();
        }

        // Menghitung jumlah baris
        $this->db->from('material_storage as a');
        $this->db->join('material_type as b', 'b.id_mt=a.id_mt');
        $this->db->where('a.quantity >', 0);
        return $this->db->count_all_results();
    }

    public function total_table_material_storage()
    {
        $this->db->from('material_storage as a');
        $this->db->join('material_type as b', 'b.id_mt=a.id_mt');
        $this->db->where('a.quantity >', 0);
        return $this->db->count_all_results();
    }

    public function table_material_out($start, $length, $order_col, $order_dir, $selectType, $tgl1, $tgl2)
    {
        $search_value = strtolower($this->input->post('search')['value']);

        $id_role = userdata('id_role');

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('c.name', $search_value);
            $this->db->or_like('b.name_mt', $search_value);
            $this->db->or_like('a.created_mo', $search_value);
            $this->db->group_end();
        }

        // Mengambil data
        $this->db->select('*');
        $this->db->from('material_out as a');
        $this->db->join('material_storage as c', 'c.id_material=a.id_material');
        $this->db->join('material_type as b', 'b.id_mt=c.id_mt');
        $this->db->where('a.quantity_mo >', 0);

        if ($tgl1 != "" && $tgl2 != "") {
            $this->db->where("CAST(a.created_mo AS DATE) >= '$tgl1' AND CAST(a.created_mo AS DATE) <= '$tgl2'");
        } elseif ($tgl1 != "" && $tgl2 == "") {
            $this->db->where("CAST(a.created_mo AS DATE) = '$tgl1'");
        } else {
            // Handle case where both tgl1 and tgl2 are empty, if needed.
        }

        // Menambahkan kondisi jika selectType tidak kosong
        if (!empty($selectType)) {
            $this->db->where('c.id_mt', $selectType);
        }

        // Mengatur pengurutan
        if (!empty($order_col) && !empty($order_dir)) {
            $this->db->order_by($order_col, $order_dir);
        } else {
            $this->db->order_by('a.id_mo', 'DESC'); // Pengurutan default jika tidak ada pengurutan yang ditentukan
        }

        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        return $this->db->get()->result();
    }

    public function filter_table_material_out()
    {
        $search_value = strtolower($this->input->post('search')['value']);

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('c.name', $search_value);
            $this->db->or_like('b.name_mt', $search_value);
            $this->db->or_like('a.created_mo', $search_value);
            $this->db->group_end();
        }

        // Menghitung jumlah baris
        $this->db->from('material_out as a');
        $this->db->join('material_storage as c', 'c.id_material=a.id_material');
        $this->db->join('material_type as b', 'b.id_mt=c.id_mt');
        $this->db->where('a.quantity_mo >', 0);
        return $this->db->count_all_results();
    }

    public function total_table_material_out()
    {
        $this->db->from('material_out as a');
        $this->db->join('material_storage as c', 'c.id_material=a.id_material');
        $this->db->join('material_type as b', 'b.id_mt=c.id_mt');
        $this->db->where('a.quantity_mo >', 0);
        return $this->db->count_all_results();
    }

    public function table_list_material($start, $length, $order_col, $order_dir, $id_material)
    {
        $search_value = strtolower($this->input->post('search')['value']);

        $id_role = userdata('id_role');

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('c.name', $search_value);
            $this->db->or_like('b.name_mt', $search_value);
            $this->db->group_end();
        }

        // Mengambil data
        $this->db->select('*');
        $this->db->from('material_out as a');
        $this->db->join('material_storage as c', 'c.id_material=a.id_material');
        $this->db->where('a.quantity_mo >', 0);
        $this->db->where('a.id_material', $id_material);

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

    public function filter_table_list_material()
    {
        $search_value = strtolower($this->input->post('search')['value']);

        // Kondisi pencarian
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('c.name', $search_value);
            $this->db->or_like('b.name_mt', $search_value);
            $this->db->group_end();
        }

        // Menghitung jumlah baris
        $this->db->from('material_out as a');
        $this->db->join('material_storage as c', 'c.id_material=a.id_material');
        $this->db->where('a.quantity_mo >', 0);
        return $this->db->count_all_results();
    }

    public function total_table_list_material()
    {
        $this->db->from('material_out as a');
        $this->db->join('material_storage as c', 'c.id_material=a.id_material');
        $this->db->where('a.quantity_mo >', 0);
        return $this->db->count_all_results();
    }
}
