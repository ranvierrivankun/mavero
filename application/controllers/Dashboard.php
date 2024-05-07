<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dashboard_model');
        check_login();
    }


    public function index()
    {
        // Mengambil data setting dari database
        $setting = $this->db->select('*')->from('setting')->get()->row();

        // Membentuk judul halaman dengan nama setting
        $title = $setting->name . ' - Dashboard';
        $title_page = 'Dashboard';

        // Menyusun data untuk dikirim ke tampilan
        $data['setting'] = [
            'title' => $title,
            'title_page' => $title_page
        ];

        $TotalRequestMaterial = $this->Dashboard_model->TotalRequestMaterial();
        $TotalRequestMaterialMonth = $this->Dashboard_model->TotalRequestMaterialMonth();
        $totalProcess = $this->Dashboard_model->TotalRequestMaterialByStatus('process');
        $totalPricing = $this->Dashboard_model->TotalRequestMaterialByStatus('pricing');
        $totalRejected = $this->Dashboard_model->TotalRequestMaterialByStatus('rejected');

        $TotalPricingMaterial = $this->Dashboard_model->TotalPricingMaterial();
        $TotalPricingMaterialMonth = $this->Dashboard_model->TotalPricingMaterialMonth();
        $TotalPricingMaterialRupiah = $this->Dashboard_model->TotalPricingMaterialRupiah();
        $TotalPricingMaterialMonthRupiah = $this->Dashboard_model->TotalPricingMaterialMonthRupiah();

        $TotalGroupingMaterial = $this->Dashboard_model->TotalGroupingMaterial();
        $TotalGroupingMaterialMonth = $this->Dashboard_model->TotalGroupingMaterialMonth();
        $totalPending = $this->Dashboard_model->TotalGroupingMaterialByStatus('pending');
        $totalSending = $this->Dashboard_model->TotalGroupingMaterialByStatus('sending');
        $totalReceived = $this->Dashboard_model->TotalGroupingMaterialByStatus('received');

        $TotalDeliveryMaterial = $this->Dashboard_model->TotalDeliveryMaterial();
        $TotalDeliveryMaterialMonth = $this->Dashboard_model->TotalDeliveryMaterialMonth();

        $TotalMaterialStorage = $this->Dashboard_model->TotalMaterialStorage();
        $TotalMaterialOut = $this->Dashboard_model->TotalMaterialOut();

        // Menyusun data untuk dikirim ke tampilan Dashboard
        $dashboard = [
            'TotalRequestMaterial' => $TotalRequestMaterial,
            'TotalRequestMaterialMonth' => $TotalRequestMaterialMonth,
            'totalProcess' => $totalProcess,
            'totalPricing' => $totalPricing,
            'totalRejected' => $totalRejected,

            'TotalPricingMaterial' => $TotalPricingMaterial,
            'TotalPricingMaterialMonth' => $TotalPricingMaterialMonth,
            'TotalPricingMaterialRupiah' => $TotalPricingMaterialRupiah,
            'TotalPricingMaterialMonthRupiah' => $TotalPricingMaterialMonthRupiah,

            'TotalGroupingMaterial' => $TotalGroupingMaterial,
            'TotalGroupingMaterialMonth' => $TotalGroupingMaterialMonth,
            'totalPending' => $totalPending,
            'totalSending' => $totalSending,
            'totalReceived' => $totalReceived,

            'TotalDeliveryMaterial' => $TotalDeliveryMaterial,
            'TotalDeliveryMaterialMonth' => $TotalDeliveryMaterialMonth,

            'TotalMaterialStorage' => $TotalMaterialStorage,
            'TotalMaterialOut' => $TotalMaterialOut
        ];

        // Memuat tampilan dengan data yang telah disusun
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('template/navbar');
        $this->load->view('dashboard/index', array_merge($data, $dashboard));
        $this->load->view('template/footer');
    }
}
