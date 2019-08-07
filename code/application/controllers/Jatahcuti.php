<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jatahcuti extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model(array('m_karyawan','m_admin','m_jatahcuti'));
        $this->lang->load('_site');

        is_login();
        is_karyawan();  //jika karyawan redirect ke dashboard
    }

    public function index(){
        $auth = $this->session->userdata('login_data');

        $data = array();
        if($auth['login_as'] == ADMIN)
            $data['admin'] = $this->m_admin->get(TRUE,'adm_username = "'.$auth['data']->adm_username.'"',NULL,1);
        else if($auth['login_as'] !== KARYAWAN)
            $data['karyawan'] = $this->m_karyawan->get(TRUE,'krw_username = "'.$auth['data']->krw_username.'"',NULL,1);

        $data['jatahcuti'] = $this->m_jatahcuti->get(TRUE);
        $data['allkaryawan'] = $this->m_karyawan->get(TRUE,NULL,"krw_tglmasuk desc");

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
                            'src'  => '__scripts/jatahcuti'
        );

        $this->site_info->set_page_title('Jatah Cuti');

        $this->load->view('__base/header',$data);

        if($auth['login_as'] == ADMIN)
            $this->load->view('__base/sidebar_admin',$data);
        else if($auth['login_as'] !== KARYAWAN)
            $this->load->view('__base/sidebar_approver',$data);

        $this->load->view('jatahcuti/master');
        $this->load->view('__base/footer',$data);
    }

    public function get_dataajax(){
        if(!$this->input->is_ajax_request()) show_404();

        $this->m_jatahcuti->get_datatable();
    }

    public function detail(){
        if(!$this->input->is_ajax_request()) show_404();

		$search_data = $this->input->post('jtc_id');
		if($search_data === NULL)
            redirect('jatahcuti');
        
        $search_data = intval($search_data);
        $filter = array(
            'jtc_id = '.$search_data
        );

        $result = $this->m_jatahcuti->get(TRUE,implode(" AND ",$filter),NULL,1);
        if($result !== NULL) 
            echo json_encode(array('status' => 'ok','data' => $result));
        else 
            echo json_encode(array('status' => 'error', 'msg' => $this->lang->line('error_not_found')));
        exit;
    }


    public function add(){
        if(!$this->input->is_ajax_request()) show_404();
        
		$this->save();
    }
    
    public function edit(){
		if(!$this->input->is_ajax_request()) show_404();

		$this->save();
    }
    
    private function save(){
        if(!$this->input->is_ajax_request()) show_404();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('jtc_id'                , 'Id Jatah Cuti'                  , 'integer');
        $this->form_validation->set_rules('jtc_validdate'          , 'Tanggal Valid Jatah cuti'       , 'required');
        $this->form_validation->set_rules('jtc_jumlah'            , 'Jumlah Jatah Cuti'              , 'required|integer');
        $this->form_validation->set_rules('jtc_karyawan'          , 'Karyawan'                       , 'required');
        $this->form_validation->set_rules('jtc_delaystart'        , 'Tanggal Mulai Delay'            , 'required');
        $this->form_validation->set_rules('jtc_delayend'          , 'Tanggal Selesai Delay'          , 'required');
        $this->form_validation->set_rules('jtc_status'            , 'Status Jatah Cuti'              , 'required|integer');

        if($this->input->post('jtc_id') == ''){
            $this->form_validation->set_rules('jtc_sisa'              , 'Sisa Jatah Cuti'                , 'integer');
        }else{
            $this->form_validation->set_rules('jtc_sisa'              , 'Sisa Jatah Cuti'                , 'required|integer');
        }
        
        if($this->form_validation->run()){
            // insert
			if($this->input->post('jtc_id') == ''){
                $jtc_id = $this->m_jatahcuti->insert($this->input->post());
                echo json_encode(array(
                        'status' => $jtc_id !== "" ? 'ok' : 'error',
                        'msg' => $jtc_id !== "" ? 'Sukses tambah data' : 'Gagal tambah data'
                ));
                exit;
            }
            else { //update
                $pk_id = $this->input->post('jtc_id');
                //check if pk integer
                $pk_id = intval($pk_id);
                
                //check data 
                $edited = $this->m_jatahcuti->get(TRUE,'jtc_id = '.$pk_id,NULL,1);

                if($edited !== NULL){
                    $this->m_jatahcuti->update($pk_id,$this->input->post());
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

        if($this->input->post('jtc_id') === NULL) {
            echo json_encode(array(
                'status' => 'error',
                'msg' => 'ID kosong !'
            ));
            exit;
        }
        $all_deleted = array();
		foreach($this->input->post('jtc_id') as $row){
            $row = intval($row);
            $deleted = $this->m_jatahcuti->get(TRUE,'jtc_id = '.$row,NULL,1);

            if($deleted !== NULL){
                $this->m_jatahcuti->delete_permanent($row);
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