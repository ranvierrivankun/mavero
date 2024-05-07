<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Fungsi Check Login disetiap Controller yang membutuhkan Autitenikasi
function check_login()
{
    $ci = get_instance();
    if (!$ci->session->userdata('login_season')) {
        $ci->session->set_flashdata('response_check_login', 'You must be logged in to access this page');
        redirect('auth');
    }
}

// Fungsi mengambil data user yang sedang login
function userdata($field)
{
    $ci = get_instance();
    $ci->load->model('Builder_model');
    $id_user = $ci->session->userdata('login_season')['id_user'];

    $user_data = $ci->Builder_model->get('user_db', ['id_user' => $id_user]);

    if ($user_data) {
        $user_role = $ci->Builder_model->get('role_db', ['id_role' => $user_data['id_role']]);

        if ($user_role) {
            $combined_data = array_merge($user_data, $user_role);
            return $combined_data[$field];
        }
    }

    return null;
}

// Fungsi Check Role di setiap Controller yang membutuhkan peran tertentu
if (!function_exists('check_role')) {
    function check_role($required_roles)
    {
        $ci = get_instance();
        $user_role = userdata('id_role');

        // Memeriksa apakah peran pengguna ada dalam daftar peran yang diizinkan
        if (!in_array($user_role, $required_roles)) {
            redirect('errors/access_denied');
        }
    }
}

// Convert datetime
function time_ago($date)
{
    $currentDate = new DateTime();
    $requestDate = new DateTime($date);
    $timeDifference = $currentDate->diff($requestDate);

    if ($timeDifference->days === 1) {
        return "1 day ago";
    } else if ($timeDifference->days > 1) {
        return $timeDifference->days . " days ago";
    } else {
        return "today";
    }
}

// Format Rupiah
if (!function_exists('rupiah')) {
    function rupiah($angka)
    {
        $rupiah = "Rp " . number_format($angka, 0, ',', '.');
        return $rupiah;
    }
}
