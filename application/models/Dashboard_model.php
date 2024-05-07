<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{

    public function TotalRequestMaterialMonth()
    {
        $currentMonthYear = date('Y-m');

        $this->db->where("DATE_FORMAT(created, '%Y-%m') =", $currentMonthYear);
        return $this->db->count_all_results('request_material');
    }

    public function TotalRequestMaterial()
    {
        return $this->db->count_all('request_material');
    }

    public function TotalRequestMaterialByStatus($status)
    {
        $this->db->where('status', $status);
        return $this->db->count_all_results('request_material');
    }

    // ==========================================================

    public function TotalPricingMaterialMonth()
    {
        $currentMonthYear = date('Y-m');

        $this->db->where("DATE_FORMAT(created, '%Y-%m') =", $currentMonthYear);
        return $this->db->count_all_results('material_pricing');
    }

    public function TotalPricingMaterial()
    {
        return $this->db->count_all('material_pricing');
    }

    public function TotalPricingMaterialMonthRupiah()
    {
        $currentMonthYear = date('Y-m');

        $this->db->select_sum('price'); // Menggunakan SUM untuk menghitung harga
        $this->db->where("DATE_FORMAT(created, '%Y-%m') =", $currentMonthYear);

        $query = $this->db->get('material_pricing');
        $result = $query->row();

        if ($result) {
            return $result->price; // Mengembalikan jumlah total harga
        } else {
            return 0; // Mengembalikan 0 jika tidak ada data
        }
    }

    public function TotalPricingMaterialRupiah()
    {
        $this->db->select_sum('price'); // Menggunakan SUM untuk menghitung harga

        $query = $this->db->get('material_pricing');
        $result = $query->row();

        if ($result) {
            return $result->price; // Mengembalikan jumlah total harga
        } else {
            return 0; // Mengembalikan 0 jika tidak ada data
        }
    }

    // ==========================================================

    public function TotalGroupingMaterialMonth()
    {
        $currentMonthYear = date('Y-m');

        $this->db->where("DATE_FORMAT(created, '%Y-%m') =", $currentMonthYear);
        return $this->db->count_all_results('group');
    }

    public function TotalGroupingMaterial()
    {
        return $this->db->count_all('group');
    }

    public function TotalGroupingMaterialByStatus($status)
    {
        $this->db->where('status_group', $status);
        return $this->db->count_all_results('group');
    }

    // ==========================================================

    public function TotalDeliveryMaterialMonth()
    {
        $currentMonthYear = date('Y-m');

        $this->db->where("DATE_FORMAT(created, '%Y-%m') =", $currentMonthYear);
        return $this->db->count_all_results('material_delivery');
    }

    public function TotalDeliveryMaterial()
    {
        return $this->db->count_all('material_delivery');
    }

    // ==========================================================

    public function TotalMaterialStorage()
    {
        return $this->db->count_all('material_storage');
    }

    public function TotalMaterialOut()
    {
        return $this->db->count_all('material_out');
    }
}
