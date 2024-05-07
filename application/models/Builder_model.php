<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Builder_model extends CI_Model
{
    public function get_by($table, $where)
    {
        return $this->db->get_where($table, $where)->result_array();
    }

    // Memeriksa apakah username ada dalam database
    public function cek_username($username)
    {
        $data = $this->db->get_where('user_db', ['username' => $username]);
        return $data->num_rows();
    }

    // Mengambil password user berdasarkan username
    public function get_password($username)
    {
        $data = $this->db->get_where('user_db', ['username' => $username])->row_array();
        return $data['password'];
    }

    // Mengambil data user berdasarkan username
    public function userdata($username)
    {
        return $this->db->get_where('user_db', ['username' => $username])->row_array();
    }

    // Fungsi untuk mendapatkan data dengan filter
    public function get($table, $data = null, $where = null)
    {
        if ($data != null) {
            return $this->db->get_where($table, $data)->row_array();
        } elseif ($where != null) {
            return $this->db->get_where($table, $where)->result_array();
        }
        return false;
    }

    // Fungsi untuk mendapatkan data dengan kondisi WHERE
    public function where($table, $field, $id)
    {
        return $this->db->select('*')->from($table)->where($field, $id)->get();
    }

    // Fungsi untuk mendapatkan semua data dengan pengurutan
    public function all($table, $order, $sort)
    {
        return $this->db->select('*')->from($table)->order_by($order, $sort)->get();
    }

    // Fungsi untuk menyimpan data ke dalam tabel
    public function save($table, $object)
    {
        $this->db->insert($table, $object);
    }

    // Fungsi untuk menghapus data berdasarkan kondisi WHERE
    public function delete($table, $field, $id)
    {
        $this->db->where($field, $id);
        return $this->db->delete($table);
    }

    // Fungsi untuk mendapatkan data yang akan diubah
    public function edit($table, $field, $id)
    {
        return $this->db->select('*')->from($table)->where($field, $id)->get();
    }

    // Fungsi untuk melakukan pembaruan data
    public function update($table, $object, $field, $id)
    {
        $this->db->where($field, $id);
        $this->db->update($table, $object);
    }

    // Fungsi untuk mendapatkan detail data dengan kondisi WHERE
    public function detail($table, $field, $id)
    {
        return $this->db->select('*')->from($table)->where($field, $id)->get();
    }

    // Fungsi untuk menghitung total baris dalam tabel
    public function total($table, $id)
    {
        return $this->db->select($id)->from($table)->get()->num_rows();
    }
}
