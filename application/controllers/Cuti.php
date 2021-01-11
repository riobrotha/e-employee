<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Cuti extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $is_login = $this->session->userdata('is_login');

        if ($is_login != true) {
            redirect(base_url());
            return;
        }
    }

    public function index()
    {

        $data['title']          = 'Cuti - Daftar Cuti Pegawai';
        $data['nav_title']      = 'cuti';
        $data['detail_title']   = 'cuti';
        $data['page']           = 'pages/cuti/index';

        $this->view($data);
    }

    public function showFormCuti()
    {
        $data['title']     = 'Form Ajukan Cuti';

        $this->cuti->table = 'pegawai';
        $data['pegawai']   = $this->cuti->get();
        $this->output->set_output(show_my_modal('pages/cuti/modal/modal_add_cuti', 'modal-add-cuti', $data, 'lg'));
    }

    public function insert()
    {
        $nip_pegawai        = $this->input->post('nip_pegawai', true);
        $jenis_cuti         = $this->input->post('jenis_cuti', true);
        $lama_cuti          = $this->input->post('lama_cuti', true);
        $tgl_cuti           = $this->input->post('tgl_cuti', true);
        $alasan_cuti        = $this->input->post('alasan_cuti', true);
        $alamat_cuti        = $this->input->post('alamat_cuti', true);
        $nip_pengganti      = $this->input->post('nip_pengganti', true);
        $jatah_cuti         = $this->session->userdata('jatah_cuti');

        if (!$this->cuti->validate()) {

            $array = array(
                'error'                 => true,
                'statusCode'            => 400,
                'jenis_cuti_error'      => form_error('jenis_cuti'),
                'lama_cuti_error'       => form_error('lama_cuti'),
                'tgl_cuti_error'        => form_error('tgl_cuti'),
                'alasan_cuti_error'     => form_error('alasan_cuti'),
                'alamat_cuti_error'     => form_error('alamat_cuti'),
                'nip_pengganti_error'   => form_error('nip_pengganti'),

            );

            echo json_encode($array);
        } else {
            $data = [
                'id'            => date('YmdHis') . rand(pow(10, 4 - 1), pow(10, 4) - 1),
                'nip_pegawai'   => $nip_pegawai,
                'lama_cuti'     => $lama_cuti,
                'tgl_cuti'      => $tgl_cuti,
                'jenis_cuti'    => $jenis_cuti,
                'alasan_cuti'   => $alasan_cuti,
                'alamat_cuti'   => $alamat_cuti,
                'nip_pengganti' => $nip_pengganti
            ];

            $data_pegawai = [
                'nama'              => $this->session->userdata('name'),
                'alamat'            => $this->session->userdata('alamat'),
                'nohp'              => $this->session->userdata('nohp'),
                'jabatan'           => $this->session->userdata('nama_jabatan'),
                'lama_cuti'         => $lama_cuti,
                'alasan_cuti'       => $alasan_cuti

            ];

            if ($this->cuti->add($data) == true) {

                // $this->cuti->table = 'pegawai';
                // $this->cuti->where('nip', $this->session->userdata('nip'))->update(['jatah_cuti' => $jatah_cuti - $lama_cuti]);

                if ($this->sendMail($this->session->userdata('name'), $this->session->userdata('email'), 'hrd@sorayabedsheet.id', $data_pegawai, $data)) {
                    echo json_encode(array(
                        "statusCode" => 200,

                    ));
                }
            } else {
                echo json_encode(array(
                    "statusCode" => 201,
                ));
            }
        }
    }

    public function sendMail($nama, $fromEmail, $toEmail, $data_arr)
    {
        $config = array(
            'mailtype'  => 'html',
            'protocol'  => 'smtp',
            'smtp_host' => 'smtp.mailtrap.io',
            'smtp_port' =>  2525,
            'smtp_user' => '6ac3b9bcd23b5b',
            'smtp_pass' => '1d60c61c055229',
            'crlf' => "\r\n",
            'newline' => "\r\n"
        );



        $this->load->library('email', $config);
        $this->email->from($fromEmail, $nama);
        $this->email->to($toEmail);
        $this->email->subject('Pengajuan Cuti');
        $this->email->message($this->load->view('email/cuti_notification', $data_arr, true));

        if ($this->email->send()) {
            //echo 'Email berhasil dikirim';
            echo json_encode(array(
                "statusCode" => 200,

            ));
        } else {
            echo 'Email tidak berhasil dikirim';
            echo '<br />';
            echo $this->email->print_debugger();
        }
    }

    public function loadTable()
    {
        $data['content']        = $this->cuti->orderBy('cuti.created_at', 'DESC')
            ->joinPegawai('pegawai')
            ->get();

        //print_r($data['content']);
        $this->load->view('pages/cuti/table_ajax', $data);
    }

    public function tes()
    {
        $this->load->view('email/cuti_notification_to_pegawai');
    }

    public function detail($id)
    {
        $data['title']      = 'Lihat Detail Cuti';
        $data['content']    = $this->cuti->joinPegawai('pegawai')
            ->where('cuti.id', $id)
            ->first();

        $this->cuti->table = 'pegawai';
        $data['pegawai']    = $this->cuti->get();

        //print_r($data['content']);

        $this->output->set_output(show_my_modal('pages/cuti/modal/modal_detail_cuti', 'modal-detail-cuti', $data, 'lg'));
    }

    public function update_status_cuti($id, $status, $nama, $toEmail, $lamaCuti = null, $jatahCuti = null, $nip = null)
    {
        if ($this->input->is_ajax_request()) {
            if ($status == "diterima") {
                if ($this->cuti->where('id', $id)->update(['status_cuti' => 'diterima'])) {

                    //kurangi jatah cuti pegawai
                    $this->cuti->table = 'pegawai';
                    $this->cuti->where('nip', $nip)->update(['jatah_cuti' => $jatahCuti - $lamaCuti]);

                    //set ulang session data jatah cuti

                    $array = array(
                        'jatah_cuti' => $jatahCuti - $lamaCuti
                    );

                    $this->session->set_userdata($array);


                    $this->sendMailToPegawai(urldecode($nama), 'hrd@sorayabedsheet.id', $toEmail, $status);
                    echo json_encode(
                        array(
                            'statusCode' => 200
                        )
                    );
                }
            } else {
                if ($this->cuti->where('id', $id)->update(['status_cuti' => 'ditolak'])) {
                    $this->sendMailToPegawai(urldecode($nama), 'hrd@sorayabedsheet.id', $toEmail, $status);
                    echo json_encode(
                        array(
                            'statusCode' => 200
                        )
                    );
                }
            }
        } else {
            echo "Don't have an access!";
        }
    }

    public function sendMailToPegawai($nama, $fromEmail, $toEmail, $status)
    {
        $config = array(
            'mailtype'  => 'html',
            'protocol'  => 'smtp',
            'smtp_host' => 'smtp.mailtrap.io',
            'smtp_port' =>  2525,
            'smtp_user' => '6ac3b9bcd23b5b',
            'smtp_pass' => '1d60c61c055229',
            'crlf' => "\r\n",
            'newline' => "\r\n"
        );

        $data = [
            'nama' => $nama,
            'status' => $status
        ];

        $this->load->library('email', $config);
        $this->email->from($fromEmail, 'Human Resources');
        $this->email->to($toEmail);
        $this->email->subject('Tindak Lanjut Pengajuan Cuti');
        $this->email->message($this->load->view('email/cuti_notification_to_pegawai', $data, true));

        if ($this->email->send()) {
            //echo 'Email berhasil dikirim';
            echo json_encode(array(
                "statusCode" => 200,

            ));
        } else {
            echo 'Email tidak berhasil dikirim';
            echo '<br />';
            echo $this->email->print_debugger();
        }
    }

    public function potong()
    {

        $data['title']          = 'Potong Cuti - Cuti Pegawai';
        $data['nav_title']      = 'cuti';
        $data['detail_title']   = 'potong_cuti';

        $this->cuti->table      = 'pegawai';
        $data['getPegawai']     = $this->cuti->get();
        $data['page']           = 'pages/cuti/potong/index';

        $this->view($data);
    }

    public function showFormPotongCuti()
    {

        $this->cuti->table      = 'pegawai';
        $data['getPegawai']     = $this->cuti->get();
        echo $this->load->view('pages/cuti/potong/form_potong_cuti', $data, true);
    }

    public function potong_jatah_cuti_pegawai()
    {
        $nip_pegawai = $this->input->post('nip_pegawai', true);
        $jatah_cuti = $this->input->post('jatah_cuti', true);
        $jatah_cuti_from_db = $this->input->post('jatah_cuti_from_db', true);

        if (!$this->cuti->validate2()) {

            $array = array(
                'error' => true,
                'statusCode' => 400,
                'nip_pegawai_error' => form_error('nip_pegawai'),
                'jatah_cuti_error'    => form_error('jatah_cuti')

            );

            echo json_encode($array);
        } else {
            $data = [
                'jatah_cuti' => $jatah_cuti_from_db - $jatah_cuti
            ];

            $this->cuti->table = 'pegawai';

            if ($this->cuti->where('pegawai.nip', $nip_pegawai)->update($data)) {
                echo json_encode(array(
                    "statusCode" => 200,

                ));
            } else {
                echo json_encode(array(
                    "statusCode" => 201,
                ));
            }
        }
    }

    public function history_cuti($nip)
    {
        $data['title']            = 'Riwayat Cuti';
        $data['getCuti']        = $this->cuti->where('nip_pegawai', $nip)->get();
        $this->output->set_output(show_my_modal('pages/cuti/modal/modal_history_cuti', 'modal-history-cuti', $data, 'lg'));
    }

    public function loadTableModal($nip, $status = null)
    {
        if ($status) {
            $data['getCuti']        = $this->cuti->orderBy('created_at', 'DESC')->where('nip_pegawai', $nip)->where('status_cuti', $status)->get();
        } else {
            $data['getCuti']        = $this->cuti->orderBy('created_at', 'DESC')->where('nip_pegawai', $nip)->get();
        }
        $this->load->view('pages/cuti/modal/table_ajax', $data);
    }
}

/* End of file Cuti.php */
