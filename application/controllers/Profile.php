<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');

        $userId = $this->session->userdata('login_session')['user'];
        $this->user = $this->admin->get('user', ['id_user' => $userId]);
    }

    public function index()
    {
        $data['title'] = "Profile";
        $data['user'] = $this->user;
        $this->template->load('templates/dashboard', 'profile/index', $data);
    }

    private function _validasi()
    {
        $db = $this->admin->get('user', ['id_user' => $this->input->post('id_user', true)]);
        $username = $this->input->post('username', true);
        $email = $this->input->post('email', true);

        $uniq_username = $db['username'] == $username ? '' : '|is_unique[user.username]';
        $uniq_email = $db['email'] == $email ? '' : '|is_unique[user.email]';

        $this->form_validation->set_rules('username', 'Username', 'required|trim|alpha_numeric' . $uniq_username);
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email' . $uniq_email);
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'required|trim|numeric');
    }

    private function _config()
    {
        $config['upload_path'] = './assets/img/avatar/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '2048';
        $config['encrypt_name']     = TRUE;
        $this->load->library('upload', $config);
    }

    public function edit()
    {
        $data['title'] = "Profile";
        $data['user'] = $this->user;
        $this->template->load('templates/dashboard', 'profile/edit', $data);
    }

    public function update()
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            redirect('profile/edit');
        }

        $this->_config();

        $input = $this->input->post();

        $data['photo_name']  = $this->input->post('foto', TRUE);
        $data['photo_thumb'] = $_FILES['foto']['name'];

        // init photos if only available
        if (!empty($data['photo_thumb'])) {
            $old_image = FCPATH . 'assets/img/avatar/' . userdata('foto');
            if (file_exists($old_image)) {
                if (!unlink($old_image)) {
                    set_pesan('gagal hapus foto lama.');
                    redirect('profile/edit');
                }
            }
            if (!$this->upload->do_upload('foto')) {
                $error = array('error' => $this->upload->display_errors());
                set_pesan('terdapat kesalahan mengunggah gambar.');
                die;
            } else {
                $input['foto'] = $this->upload->data('file_name');
            }
        }

        // give messages
        if ($this->admin->update('user', 'id_user', $input['id_user'], $input)) {
            set_pesan('perubahan berhasil disimpan.');
        } else {
            set_pesan('perubahan tidak disimpan.');
        }

        redirect('profile/edit');
    }

    public function ubahpassword()
    {
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required|trim');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|trim|min_length[3]|differs[password_lama]');
        $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'matches[password_baru]');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Ubah Password";
            $this->template->load('templates/dashboard', 'profile/ubahpassword', $data);
        } else {
            $input = $this->input->post(null, true);
            if (password_verify($input['password_lama'], userdata('password'))) {
                $new_pass = ['password' => password_hash($input['password_baru'], PASSWORD_DEFAULT)];
                $query = $this->admin->update('user', 'id_user', userdata('id_user'), $new_pass);

                if ($query) {
                    set_pesan('password berhasil diubah.');
                } else {
                    set_pesan('gagal ubah password', false);
                }
            } else {
                set_pesan('password lama salah.', false);
            }
            redirect('profile/ubahpassword');
        }
    }
}
