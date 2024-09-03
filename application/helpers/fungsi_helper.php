<?php

function cek_login()
{
    $ci = get_instance();
    if (!$ci->session->has_userdata('login_session')) {
        set_pesan('tidak ada sesi ditemukan, silahkan login.', 'warning');
        redirect('login');
    }
}

function is_admin()
{
    $ci = get_instance();
    $role = $ci->session->userdata('login_session')['role'];

    $status = true;

    if ($role != 'admin') {
        $status = false;
    }

    return $status;
}

function set_pesan($pesan, $tipe = 'success')
{
    $ci = get_instance();
    
    switch ($tipe) {
        case 'warning':
            $ci->session->set_flashdata('pesan', "<div class='alert alert-warning'><strong>Peringatan!</strong> {$pesan} <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>");
          break;
        case 'danger':
            $ci->session->set_flashdata('pesan', "<div class='alert alert-danger'><strong>Kesalahan!</strong> {$pesan} <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>");
          break;
        case 'success':
        default:
            $ci->session->set_flashdata('pesan', "<div class='alert alert-success'><strong>Sukses!</strong> {$pesan} <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>");
      }
}

function userdata($field)
{
    $ci = get_instance();
    $ci->load->model('Admin_model', 'admin');

    $userId = $ci->session->userdata('login_session')['user'];
    return $ci->admin->get('user', ['id_user' => $userId])[$field];
}

function output_json($data)
{
    $ci = get_instance();
    $data = json_encode($data);
    $ci->output->set_content_type('application/json')->set_output($data);
}
