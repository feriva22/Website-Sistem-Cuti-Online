<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatan extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model(array('m_karyawan','m_admin','m_jabatan'));
        $this->lang->load('_site');

        is_login();
        redir_karyawan();  //jika karyawan redirect ke dashboard
        redir_not_admin(); //jika bukan admin redirect ke dashboard
    }

    public function index(){
        $auth = $this->session->userdata('login_data');

        $data = array();
        if($auth['login_as'] == ADMIN)
            $data['admin'] = $this->m_admin->get(TRUE,'adm_username = "'.$auth['data']->adm_username.'"',NULL,1);
        else if($auth['login_as'] !== KARYAWAN)
            $data['karyawan'] = $this->m_karyawan->get(TRUE,'krw_username = "'.$auth['data']->krw_username.'"',NULL,1);

        $data['status_level'] = $this->lang->line('status_level');

        $data['add_js'] = array(
            'plugins/datatables/jquery.dataTables.js',
            'plugins/datatables/dataTables.bootstrap4.js',
            'plugins/fastclick/fastclick.js'
        );
        $data['add_css'] = array( 'plugins/datatables/dataTables.bootstrap4.css');
        $data['add_php_js'] = array(
                            'data' => $data,
                            'src'  => '__scripts/jabatan'
        );


        $this->site_info->set_page_title('Jabatan');

        $this->load->view('__base/header',$data);

        if($auth['login_as'] == ADMIN)
            $this->load->view('__base/sidebar_admin',$data);
        else if($auth['login_as'] !== KARYAWAN)
            $this->load->view('__base/sidebar_approver',$data);

        $this->load->view('jabatan/master',$data);
        $this->load->view('__base/footer',$data);
    }

    public function get_dataajax(){
        if(!$this->input->is_ajax_request()) show_404();

        $this->m_jabatan->get_datatable();
    }

    public function detail(){
        if(!$this->input->is_ajax_request()) show_404();

		$search_data = $this->input->post('jbt_id');
		if($search_data === NULL)
            redirect('jabatan');
        
        $search_data = intval($search_data);
        $filter = array(
            'jbt_id = '.$search_data
        );

        $result = $this->m_jabatan->get(TRUE,implode(" AND ",$filter),NULL,1);
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
        $this->form_validation->set_rules('jbt_id'                , 'Id Jabatan'                  , 'integer');
        $this->form_validation->set_rules('jbt_nama'              , 'Nama Jabatan'                , 'required');
        
        if($this->form_validation->run()){
            // insert
			if($this->input->post('jbt_id') == ''){
                $jbt_id = $this->m_jabatan->insert($this->input->post());
                echo json_encode(array(
                        'status' => $jbt_id !== "" ? 'ok' : 'error',
                        'msg' => $jbt_id !== "" ? 'Sukses tambah data' : 'Gagal tambah data'
                ));
                exit;
            }
            else { //update
                $pk_id = $this->input->post('jbt_id');
                //check if pk integer
                $pk_id = intval($pk_id);
                
                //check data 
                $edited = $this->m_jabatan->get(TRUE,'jbt_id = '.$pk_id,NULL,1);

                if($edited !== NULL){
                    $this->m_jabatan->update($pk_id,$this->input->post());
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
                'msg' => validation_errors()
            ));
        }
    }

    public function delete(){
        if(!$this->input->is_ajax_request()) show_404();

        if($this->input->post('jbt_id') === NULL) {
            echo json_encode(array(
                'status' => 'error',
                'msg' => 'ID kosong !'
            ));
            exit;
        }
        $all_deleted = array();
		foreach($this->input->post('jbt_id') as $row){
            $row = intval($row);
            $deleted = $this->m_jabatan->get(TRUE,'jbt_id = '.$row,NULL,1);

            if($deleted !== NULL){
                $this->m_jabatan->delete_permanent($row);
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