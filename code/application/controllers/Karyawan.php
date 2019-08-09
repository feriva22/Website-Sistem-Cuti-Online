<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model(array('m_karyawan','m_admin','m_divisi','m_jabatan','m_jatahcuti'));
        $this->lang->load('_site');

        $auth = $this->session->userdata('login_data');

        is_login();
        redir_karyawan();
        
        if(isset($auth['data']->krw_level) && $auth['data']->krw_level != KARYAWAN && $auth['login_as'] == KARYAWAN)
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
        $data['jabatan'] = $this->m_jabatan->get(TRUE,NULL,"jbt_level asc");

        $data['status_level'] = $this->lang->line('status_level');
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

        $filter = array(
            "krw_status != ".STATUS_DELETED
        );

        if(check_login_as() == ATASAN_LANGSUNG){
            $filter[] = "krw_divisi = ".get_login_data()->krw_divisi;
        }

        $this->m_karyawan->get_datatable(implode(" AND ",$filter),"krw_level desc");
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
        $this->form_validation->set_rules('krw_divisi'            , 'Unit Kerja Karyawan'        , 'integer');
        $this->form_validation->set_rules('krw_jabatan'           , 'Jabatan karyawan'           , 'required|integer');

        if($this->input->post('krw_id') == '')
            $this->form_validation->set_rules('krw_password' , 'Password Karyawan' , 'required|min_length[6]|max_length[30]');
        else
            $this->form_validation->set_rules('krw_password' , 'Password Karyawan' , 'min_length[6]|max_length[30]');
        
        if($this->form_validation->run()){
            $this->load->model(array('m_jabatan','m_divisi'));
            // insert
			if($this->input->post('krw_id') == ''){
                //sementara parsing semua data post
                $data_parse = $this->input->post();
                $data_parse['krw_password'] = md5($data_parse['krw_password']);
                $data_parse['krw_status'] = STATUS_ACTIVE;
                
                //get jabatan untuk mendapat level
                $jabatan = $this->m_jabatan->get(TRUE,"jbt_id = ".$data_parse['krw_jabatan'],NULL,1);
                
                //asumsi jabatan ada karena hasil dari select option
                $data_parse['krw_level'] = $jabatan->jbt_level;
                //check untuk menentukan atasan langsung melalui overide jabatan atasan pk

                if($jabatan->jbt_level == ATASAN_LANGSUNG || $jabatan->jbt_level == KADIR_SDMO){ //jika jbt level atasan langsung ,maka atasannya warek
                     //get divisi untuk ovveride atasan langsung
                    $divisi = $this->m_divisi->get(TRUE,"dvs_id = ".$data_parse['krw_divisi'],NULL,1);

                    //set pk atasan tidak langsung ke overide atasan langsung dengan jabatan pk
                    $data_parse['krw_ovrd_atasanpk'] = $divisi->dvs_attljbt_pk;
                }
                else if($jabatan->jbt_level == KABAG_ADMIN){
                    //jika kabag admin maka overide atasan langsung ke kadir sdmo 
                    $jbt_sdmo = $this->m_jabatan->get(TRUE,"jbt_level = ". KADIR_SDMO ,NULL,1);

                    //set pk atasan tidak langsung ke overide atasan langsung dengan jabatan sdmo
                    $data_parse['krw_ovrd_atasanpk'] = $jbt_sdmo->jbt_id;
                }

                $krw_id = $this->m_karyawan->insert($data_parse);
                /*
                if($krw_id !== ""){
                    //add jatah cuti satu tahun kedepan dari tanggal masuk
                    $tgl_masuk = $this->input->post('krw_tglmasuk');
                    
                    //jatah cuti didapatkan setelah 1 tahun kerja 
                    $cuti_start = date("Y-m-d H:i:s", strtotime("+1 years", strtotime($tgl_masuk)));   
                    //jatah cuti selesai 
                    $cuti_end = date("Y-m-d H:i:s", strtotime($cuti_start." +15 months"));

                    $this->m_jatahcuti->insert(array(
                                'jtc_jumlah'    => 12,  //default beri jatah 12 cuti
                                'jtc_jenis'     => JATAH_TAHUNAN,
                                'jtc_sisa'      => 12,
                                'jtc_karyawan'  => $krw_id,
                                'jtc_validstart'=> $cuti_start,
                                'jtc_validend'  => $cuti_end,
                                'jtc_status'    => STATUS_ACTIVE
                    ));
                    //update value jatah cuti pegawai
                }
                */
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
                    //sementara parsing semua data post
                    $data_parse = $this->input->post();

                    if($data_parse['krw_password'] != ""){
                        $data_parse['krw_password'] = md5($data_parse['krw_password']);
                    }

                    if( $data_parse['krw_divisi'] != $edited->krw_divisi || 
                        $data_parse['krw_jabatan'] != $edited->krw_jabatan){

                        //get jabatan untuk mendapat level
                        $jabatan = $this->m_jabatan->get(TRUE,"jbt_id = ".$data_parse['krw_jabatan'],NULL,1);
                            
                        //asumsi jabatan ada karena hasil dari select option
                        $data_parse['krw_level'] = $jabatan->jbt_level;
                        //check untuk menentukan atasan langsung melalui overide jabatan atasan pk
                            
                        if($jabatan->jbt_level == ATASAN_LANGSUNG || $jabatan->jbt_level == KADIR_SDMO){ //jika jbt level atasan langsung ,maka atasannya warek
                             //get divisi untuk ovveride atasan langsung
                            $divisi = $this->m_divisi->get(TRUE,"dvs_id = ".$data_parse['krw_divisi'],NULL,1);
                        
                            //set pk atasan tidak langsung ke overide atasan langsung dengan jabatan pk
                            $data_parse['krw_ovrd_atasanpk'] = $divisi->dvs_attljbt_pk;
                        }
                        else if($jabatan->jbt_level == KABAG_ADMIN){
                            //jika kabag admin maka overide atasan langsung ke kadir sdmo 
                            $jbt_sdmo = $this->m_jabatan->get(TRUE,"jbt_level = ". KADIR_SDMO ,NULL,1);
                        
                            //set pk atasan tidak langsung ke overide atasan langsung dengan jabatan sdmo
                            $data_parse['krw_ovrd_atasanpk'] = $jbt_sdmo->jbt_id;
                        }
                    
                    }

                    $this->m_karyawan->update($pk_id,$data_parse);
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
                //$this->m_karyawan->delete_permanent($row);
                $this->m_karyawan->delete_soft($row);
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