<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model(array('m_karyawan','m_admin','m_divisi','m_jabatan','m_jatahcuti'));
        $this->lang->load('_site');

        $auth = $this->session->userdata('login_data');

        is_login();

        if(!$auth['is_admin'] && $auth['data']->krw_level == KARYAWAN)
            redirect('dashboard');
        if(isset($auth['data']->krw_level) && $auth['data']->krw_level != KARYAWAN && $auth['login_as'] == KARYAWAN)
            redirect('dashboard');
        if($auth['login_as'] == ATASAN_LANGSUNG || $auth['login_as'] == ATASAN_TDK_LANGSUNG)
            redirect('dashboard');

    }

    public function index(){
        $auth = $this->session->userdata('login_data');

        $data = array();
        if($auth['login_as'] == ADMIN)
            $data['admin'] = $this->m_admin->get(TRUE,'adm_username = "'.$auth['data']->adm_username.'"',NULL,1);
        else if($auth['login_as'] !== KARYAWAN)
            $data['karyawan'] = $this->m_karyawan->get(TRUE,'krw_username = "'.$auth['data']->krw_username.'"',NULL,1);

        $data['divisi'] = $this->m_divisi->get(TRUE);
        $data['jabatan'] = $this->m_jabatan->get(TRUE);

        $data['level_karyawan'] = $this->lang->line('level_karyawan');
        $data['data_agama'] = $this->lang->line('data_agama');

        $data['add_js'] = array(
            'plugins/datatables/jquery.dataTables.js',
            'plugins/datatables/dataTables.bootstrap4.js',
            'plugins/fastclick/fastclick.js',
            'plugins/moment/moment.min.js',
            'plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js'
        );
        $data['add_css'] = array( 
            'plugins/datatables/dataTables.bootstrap4.css',
            'plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css'
        );
        $data['add_php_js'] = array(
                            'data' => $data,
                            'src'  => '__scripts/karyawan'
        );

        $data['atasan_karyawan'] = $this->m_karyawan->get(TRUE,"krw_level = ". ATASAN_TDK_LANGSUNG);

        $this->site_info->set_page_title('Karyawan');

        $this->load->view('__base/header',$data);
        if($auth['login_as'] == ADMIN)
            $this->load->view('__base/sidebar_admin',$data);
        else if($auth['login_as'] !== KARYAWAN)
            $this->load->view('__base/sidebar_approver',$data);
        $this->load->view('karyawan/master');
        $this->load->view('__base/footer',$data);
    }

    public function get_dataajax(){
        if(!$this->input->is_ajax_request()) show_404();

        $auth = $this->session->userdata('login_data');
        if(!is_exist($auth)){
            $msg = array(
				'type' => 'error',
				'message' => 'Silahkan login terlebih dahulu'
			);
			$this->session->set_flashdata('msg',$msg);
            redirect('');
        }

        $this->m_karyawan->get_datatable();
    }

    public function detail(){
        if(!$this->input->is_ajax_request()) show_404();
        $auth = $this->session->userdata('login_data');

		$search_data = $this->input->post('krw_id');
		if($search_data === NULL)
            redirect('karyawan');
        
        $search_data = intval($search_data);
        $filter = array(
            'krw_id = '.$search_data
        );

        $result = $this->m_karyawan->get(TRUE,implode(" AND ",$filter),NULL,1);
        if($result !== NULL) 
            echo json_encode(array('status' => 'ok','data' => $result));
        else 
            echo json_encode(array('status' => 'error', 'msg' => $this->lang->line('error_not_found')));
        exit;
    }


    public function add(){
		if(!$this->input->is_ajax_request()) show_404();
        $auth = $this->session->userdata('login_data');

		$this->save();
    }
    
    public function edit(){
		if(!$this->input->is_ajax_request()) show_404();
        $auth = $this->session->userdata('login_data');
		$this->save();
    }
    
    private function save(){
        if(!$this->input->is_ajax_request()) show_404();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('krw_id'                , 'Id karyawan'                , 'integer');
        $this->form_validation->set_rules('krw_username'          , 'Username karyawan'          , 'required');
        $this->form_validation->set_rules('krw_email'             , 'Email karyawan'             , 'required');
        $this->form_validation->set_rules('krw_nama'              , 'Nama karyawan'              , 'required');
        $this->form_validation->set_rules('krw_nik'               , 'NIk karyawan'               , 'required');
        $this->form_validation->set_rules('krw_tgllahir'          , 'Tanggal Lahir karyawan'     , 'required');
        $this->form_validation->set_rules('krw_jeniskelamin'      , 'Jenis Kelaming karyawan'    , 'required|integer');
        $this->form_validation->set_rules('krw_alamat'            , 'Alamat karyawan'            , 'required');
        $this->form_validation->set_rules('krw_agama'             , 'Agama karyawan'             , 'required|integer');
        $this->form_validation->set_rules('krw_tglmasuk'          , 'Tanggal Masuk karyawan'     , 'required');
        $this->form_validation->set_rules('krw_divisi'            , 'Divisi karyawan'            , 'required|integer');
        $this->form_validation->set_rules('krw_jabatan'           , 'Jabatan karyawan'           , 'required|integer');
        $this->form_validation->set_rules('krw_level'             , 'Level karyawan'             , 'required|integer');

        if($this->input->post('krw_id') == '')
            $this->form_validation->set_rules('krw_password' , 'Password Karyawan' , 'required|min_length[6]|max_length[30]');
        else
            $this->form_validation->set_rules('krw_password' , 'Password Karyawan' , 'min_length[6]|max_length[30]');
        
        if($this->form_validation->run()){
            // insert
			if($this->input->post('krw_id') == ''){
                $krw_id = $this->m_karyawan->insert($this->input->post());
                if($krw_id !== ""){
                    //add jatah cuti satu tahun kedepan dari tanggal masuk
                    $tgl_masuk = $this->input->post('krw_tglmasuk');
                    
                    //jatah cuti didapatkan setelah 1 tahun kerja 
                    $cuti_start = date("Y-m-d H:i:s", strtotime("+1 years", strtotime($tgl_masuk)));   
                    //jatah cuti selesai 
                    $cuti_end = date("Y-m-d H:i:s", strtotime($cuti_start." +1 years"));
                    //jatah cuti mulai delay
                    $cuti_delay_start = date("Y-m-d H:i:s", strtotime($cuti_end." +1 days"));
                    //jatah cuti delay berakhir
                    $cuti_delay_end = date("Y-m-d H:i:s", strtotime($cuti_delay_start." +3 months"));

                    $this->m_jatahcuti->insert(array(
                        'jtc_validdate' => $cuti_start,
                        'jtc_jumlah'    => 12,  //default beri jatah 12 cuti
                        'jtc_sisa'      => 12,
                        'jtc_karyawan'  => $krw_id,
                        'jtc_delaystart'=> $cuti_delay_start,
                        'jtc_delayend'  => $cuti_delay_end,
                        'jtc_status'    => STATUS_ACTIVE
                    ));
                    //update value jatah cuti pegawai
                    $this->m_karyawan->update($krw_id,array(
                        'krw_jatahcuti' => 12
                    ));
                }
                echo json_encode(array(
                        'status' => $krw_id !== "" ? 'ok' : 'error',
                        'msg' => $krw_id !== "" ? 'Sukses tambah data' : 'Gagal tambah data'
                ));
                exit;
            }
            else { //update
                $pk_id = $this->input->post('krw_id');
                //check if pk integer
                $pk_id = intval($pk_id);
                
                //check data 
                $edited = $this->m_karyawan->get(TRUE,'krw_id = '.$pk_id,NULL,1);

                if($edited !== NULL){
                    $this->m_karyawan->update($pk_id,$this->input->post());
                    echo json_encode(array(
                        'status' => 'ok',
                        'msg' => 'Sukses edit data' 
                    ));
                    exit;
                }
                else{
                    echo json_encode(array(
                        'status' => 'error',
                        'msg' => 'Data tidak ada' 
                    ));
                    exit;
                }
            }
        }
        else {
            echo json_encode(array(
                'status' => 'error',
                'msg' => validation_errors_array()
            ));
        }
    }

    public function delete(){
        if(!$this->input->is_ajax_request()) show_404();

        $auth = $this->session->userdata('login_data');
 
        if($this->input->post('krw_id') === NULL) {
            echo json_encode(array(
                'status' => 'error',
                'msg' => 'ID kosong !'
            ));
            exit;
        }
        $all_deleted = array();
		foreach($this->input->post('krw_id') as $row){
            $row = intval($row);
            $deleted = $this->m_karyawan->get(TRUE,'krw_id = '.$row,NULL,1);

            if($deleted !== NULL){
                $this->m_karyawan->delete_permanent($row);
                $all_deleted[] = 'deleted';
            }
        }
        if(count($all_deleted) > 0){}
            //do something
        
        echo json_encode(array(
            'status' => 'ok',
            'msg' => 'Sukses hapus'
        ));
        exit;
        
    }




}