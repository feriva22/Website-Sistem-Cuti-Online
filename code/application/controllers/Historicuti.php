<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historicuti extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model(array('m_karyawan','m_cuti','m_jatahcuti'));
        $this->lang->load('_site');

        is_login();
        is_not_karyawan();

    }

    public function index(){
        $auth = $this->session->userdata('login_data');

        $data = array();
        $data['karyawan'] = $this->m_karyawan->get(TRUE,'krw_username = "'.$auth['data']->krw_username.'"',NULL,1);
        $data['status_approve'] = $this->lang->line('status_approve');
        $data['status_level'] = $this->lang->line('status_level');

        $data['jenis_cuti'] = $this->lang->line('jenis_cuti');

        $data['add_js'] = array(
            'plugins/datatables/jquery.dataTables.js',
            'plugins/datatables/dataTables.bootstrap4.js',
            'plugins/fastclick/fastclick.js'
        );
        $data['add_css'] = array( 'plugins/datatables/dataTables.bootstrap4.css');
        $data['add_php_js'] = array(
            'data' => $data,
            'src'  => '__scripts/historicuti'
        );
        
        $this->site_info->set_page_title('Histori Cuti');

        $this->load->view('__base/header',$data);
        if($auth['login_as'] == KARYAWAN)
            $this->load->view('__base/sidebar_karyawan',$data);
        else if($auth['login_as'] !== KARYAWAN)
            $this->load->view('__base/sidebar_approver',$data);
        $this->load->view('historicuti/master',$data);
        $this->load->view('__base/footer',$data);
    }

    public function get_dataajax(){
        if(!$this->input->is_ajax_request()) show_404();

        $auth = $this->session->userdata('login_data');

        $this->m_cuti->get_datatable("cti_karyawan = ". $auth['data']->krw_id,"cti_tglpengajuan desc");
    }
}