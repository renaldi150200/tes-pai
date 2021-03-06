<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in_mahasiswa();
        $this->load->model('Kelas_model');
        $this->load->model('Pelajar_model');
    }


    public function index()
    {
        $data['email'] = $this->session->userdata('email');
        $email = $this->session->userdata('email');
        $data['kelas'] = $this->db->get_where('kelas', ['email_pengajar' => $email])->result_array();

        $this->load->view('templates/header/home_header', $data);
        $this->load->view('home/index', $data);
        $this->load->view('templates/footer/home_footer');
        $this->load->view('templates/landing_script');
    }
    /* Absensi Amalan Yaumiyah */
    public function amalan()
    {
        /*------------------get id Mahasiswa---------------------------*/
        $email = $this->session->userdata('email');
        $id = $this->db->query("SELECT id FROM mahasiswa
            WHERE email='" . $email . "'")->result_array();
        $id_mahasiswa = $id[0]['id'];
        /*-------------------------------------------------------------*/

        /*----------Data - data yang di pakai di Halaman ini----------*/
        $data['max'] = $this->Pelajar_model->getJamMax($id_mahasiswa);

        $data['kelas'] = $this->db->get_where('kelas', ['email_pengajar' => $email])->result_array();
        $data['title'] = 'Home';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $tanggal = date('Y-m-d');
        $data['tanggal'] = tanggal_indo($tanggal, true);
        /*-------------------------------------------------------------*/
        $this->load->view('templates/header/amalan_header');
        $this->load->view('home/amalan', $data);
        $this->load->view('templates/footer/home_footer');
        $this->load->view('templates/landing_script');
    }

    public function amalanPekanan()
    {

        /* Get Datetime now*/
        $tanggal = date('Y-m-d');
        $tanggal_ini = tanggal_($tanggal, true);
        $data['tanggal'] = tanggal_($tanggal, true);
        /*----------------*/
        /* Get Data User by Class */
        $email = $this->session->userdata('email');
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $id = $this->db->query("SELECT id FROM mahasiswa
            WHERE email='" . $email . "'")->result_array();

        $id_mahasiswa = $id[0]['id'];

        $idkelas = $this->db->query("SELECT id_kelas FROM anggota_kelas
            WHERE id_mahasiswa='" . $id_mahasiswa . "'")->result_array();

        $id_kelas = $idkelas[0]['id_kelas'];
        $data['max'] = $this->Pelajar_model->getJamMax($id_mahasiswa);
        /*-----------------------*/
        if (!$id_kelas) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Anda harus join salah satu kelas dulu untuk bisa mengisi Amalan Yaumiyah</div>');
            redirect('home/amalan');
        } else {
            $pertemuan = $this->db->query("SELECT jenis FROM pengumuman
            WHERE id_kelas='" . $id_kelas . "' AND jenis='pertemuan'")->result_array();

            $pertemuan_ = $pertemuan[0]['jenis'];

            if (!$pertemuan_) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Anda belum bisa mengisi Amalan Yaumiyah karena pertemuan mentoring belum di mulai</div>');
                redirect('home/amalan');
            } else {
                $this->form_validation->set_rules('shalatWajib', 'Shalat Wajib', 'required', ['required' => 'Wajib di isi']);
                $this->form_validation->set_rules('shalatDhuha', 'Shalat Dhuha', 'required', ['required' => 'Wajib di isi']);
                $this->form_validation->set_rules('tilawah', 'Tilawah', 'required', ['required' => 'Wajib di isi']);
                if ($this->form_validation->run() == false) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Isi Amalan Yaumiyah dengan benar</div>');

                    $this->load->view('templates/header/amalan_header');
                    $this->load->view('home/amalan', $data);
                    $this->load->view('templates/footer/home_footer');
                    $this->load->view('templates/landing_script');
                } else {
                    $amalanHarian = $this->db->query("SELECT * FROM amalan_yaumiyah
                WHERE id_mahasiswa='" . $id_mahasiswa . "' AND date = '" . $tanggal . "'")->result_array();

                    if (!$amalanHarian) {

                        $this->Pelajar_model->input_amalanPekanan($id_mahasiswa, $id_kelas);
                        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Amalan Harian Berhasil Di Update</div>');
                        redirect('home/amalan');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Anda Sudah Mengisi Amalan Yaumiyah</div>');
                        redirect('home/amalan');
                    }
                }
            }
        }
    }
    /* End  Absensi Amalan Yaumiyah */

    /* Praktikum PAI */
    public function praktikum()
    {
        /*----------Data - data yang di pakai di Halaman ini----------*/
        $email = $this->session->userdata('email');
        $data['kelas'] = $this->db->get_where('kelas', ['email_pengajar' => $email])->result_array();
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        /*-------------------------------------------------------------*/

        $this->load->view('templates/header/amalan_header');
        $this->load->view('home/praktikum');
        $this->load->view('templates/footer/home_footer');
        $this->load->view('templates/landing_script');
    }

    public function sholat()
    {
        $email = $this->session->userdata('email');
        $data['kelas'] = $this->db->get_where('kelas', ['email_pengajar' => $email])->result_array();
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        // $this->load->view('templates/header/amalan_header');
        $this->load->view('home/praktikum/sholat');
        // $this->load->view('templates/footer/home_footer');
        // $this->load->view('templates/landing_script');

    }

    public function pose()
    {
        $this->load->view('templates/pose');
    }
    public function sholatPose()
    {
        $email = $this->session->userdata('email');
        $data['kelas'] = $this->db->get_where('kelas', ['email_pengajar' => $email])->result_array();
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('home/praktikum/sholat_pose');
    }
    /* End Of Praktikum PAI */

    /* Evaluasi */
    public function evaluasi()
    {
        /*----------Data - data yang di pakai di Halaman ini----------*/
        $email = $this->session->userdata('email');
        $data['kelas'] = $this->db->get_where('kelas', ['email_pengajar' => $email])->result_array();
        $id_mhs = $this->db->query("SELECT id FROM mahasiswa WHERE email='" . $email . "'")->result_array();
        $id_mahasiswa = $id_mhs[0]['id'];
        $anggota = $this->db->get_where('anggota_kelas', ['id_mahasiswa' => $id_mahasiswa])->result_array();
        /*-------------------------------------------------------------*/

        if (!$anggota) {
            $data['anggota'] = 'false';
        } else {
            /*----------Data - data yang di pakai di Halaman ini----------*/
            $data['anggota'] = 'true';

            $kode = $this->db->query("SELECT id_kelas FROM anggota_kelas WHERE id_mahasiswa='" . $id_mahasiswa . "'")->result_array();
            $kode_kelas = $kode[0]['id_kelas'];

            $email_pengajar = $this->db->query("SELECT email_pengajar FROM kelas WHERE id='" . $kode_kelas . "'")->result_array();
            $email_mentor = $email_pengajar[0]['email_pengajar'];

            $data['data_pengumuman'] = $this->db->get_where('pengumuman', ['id_kelas' => $kode_kelas])->result_array();
            $data['data_kelas'] = $this->db->get_where('kelas', ['id' => $kode_kelas])->result_array();
            $data['data_absen'] = $this->db->get_where('absen', ['id_mahasiswa' => $id_mahasiswa])->result_array();
            $data['data_mentor'] =  $this->db->query("SELECT name FROM user WHERE email='" . $email_mentor . "'")->result_array();
            /*-------------------------------------------------------------*/
        }


        $this->form_validation->set_rules('absen', 'absen', 'required');
        if ($this->form_validation->run() == false) {

            $this->load->view('templates/header/amalan_header', $data);
            $this->load->view('templates/tanggal/selisih_tanggal', $data);
            $this->load->view('home/evaluasi', $data);
            $this->load->view('templates/footer/home_footer');
            $this->load->view('templates/landing_script');
        } else {
            $this->Pelajar_model->update_absen($id_mahasiswa);
            redirect('home/evaluasi');
        }
    }

    public function cariKelas()
    {
        /*----------Data - data yang di pakai di Halaman ini----------*/
        $email = $this->session->userdata('email');
        $data['kelas'] = $this->db->get_where('kelas', ['email_pengajar' => $email])->result_array();
        $kode_kelas = $this->input->post('kode_kelas', true);
        $kelas = $this->db->get_where('kelas', ['kode_kelas' => $kode_kelas])->result_array();
        /*-------------------------------------------------------------*/

        if (!$kelas) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Kelas yang anda cari tidak ada harap cek kembali kode kelas yang anda cari</div>');
            // redirect('home/cariKelas');
            $this->load->view('templates/header/amalan_header');
            $this->load->view('home/cari_kelas', $data);
            $this->load->view('templates/footer/home_footer');
            $this->load->view('templates/landing_script');
        } else {

            $data['kelas'] = $this->db->get_where('kelas', ['kode_kelas' => $kode_kelas])->result_array();
            $this->load->view('templates/header/amalan_header');
            $this->load->view('home/cari_kelas', $data);
            $this->load->view('templates/footer/home_footer');
            $this->load->view('templates/landing_script');
        }
    }
    public function daftarKelas()
    {
        /*----------Data - data yang di pakai di Halaman ini----------*/
        $email = $this->session->userdata('email');
        $data['mahasiswa'] = $this->db->get_where('mahasiswa', ['email' => $this->session->userdata('email')])->row_array();
        $id_mhs = $this->db->query("SELECT id FROM mahasiswa WHERE email='" . $email . "'")->result_array();
        $id_mahasiswa = $id_mhs[0]['id'];
        /*-------------------------------------------------------------*/

        $this->Pelajar_model->daftarKelas($id_mahasiswa);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Berhasil Masuk Kelas</div>');
        redirect('home/evaluasi');
    }
    /* End Of Evaluasi */

    /* Materi */
    public function materi()
    {
        /*----------Data - data yang di pakai di Halaman ini----------*/
        $email = $this->session->userdata('email');
        $data['kelas'] = $this->db->get_where('kelas', ['email_pengajar' => $email])->result_array();
        $data['title'] = 'Home';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        /*-------------------------------------------------------------*/

        $this->load->view('templates/header/materi_header');
        $this->load->view('home/materi');
        $this->load->view('templates/landing_script');
    }

    public function materiTajwid()
    {
        /*----------Data - data yang di pakai di Halaman ini----------*/
        $email = $this->session->userdata('email');
        $data['kelas'] = $this->db->get_where('kelas', ['email_pengajar' => $email])->result_array();
        $data['title'] = 'Home';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        /*-------------------------------------------------------------*/

        $this->load->view('templates/header/materi_header');
        $this->load->view('home/materiTajwid');
        $this->load->view('templates/landing_script');
    }

    public function materiJanaiz()
    {
        /*----------Data - data yang di pakai di Halaman ini----------*/
        $email = $this->session->userdata('email');
        $data['kelas'] = $this->db->get_where('kelas', ['email_pengajar' => $email])->result_array();
        $data['title'] = 'Home';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        /*-------------------------------------------------------------*/

        $this->load->view('templates/header/materi_header');
        $this->load->view('home/materiJanaiz');
        $this->load->view('templates/landing_script');
    }

    public function materiWudhu()
    {
        /*----------Data - data yang di pakai di Halaman ini----------*/
        $email = $this->session->userdata('email');
        $data['kelas'] = $this->db->get_where('kelas', ['email_pengajar' => $email])->result_array();
        $data['title'] = 'Home';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        /*-------------------------------------------------------------*/

        $this->load->view('templates/header/materi_header');
        $this->load->view('home/materiWudhu');
        $this->load->view('templates/landing_script');
    }

    /* End of Materi */
}
