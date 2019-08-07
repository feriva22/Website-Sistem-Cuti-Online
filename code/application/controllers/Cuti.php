<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuti extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model(array('m_karyawan','m_admin','m_cuti'));
        $this->lang->load('_site');

        is_login();
        is_karyawan();  //jika karyawan redirect ke dashboard
    }

    public function index(){
        $auth = $this->session->userdata('login_data');

        $data = array();
        $data['login_as'] = $auth['login_as'];
        if($auth['login_as'] == ADMIN)
            $data['admin'] = $this->m_admin->get(TRUE,'adm_username = "'.$auth['data']->adm_username.'"',NULL,1);
        else if($auth['login_as'] !== KARYAWAN)
            $data['karyawan'] = $this->m_karyawan->get(TRUE,'krw_username = "'.$auth['data']->krw_username.'"',NULL,1);

        $data['status_approve'] = $this->lang->line('status_approve');
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
                            'src'  => '__scripts/cuti'
        );

        $data['allkaryawan'] = $this->m_karyawan->get(TRUE,NULL,"krw_tglmasuk desc");

        $this->site_info->set_page_title('Cuti');

        
        $this->load->view('__base/header',$data);
        if($auth['login_as'] == ADMIN)
            $this->load->view('__base/sidebar_admin',$data);
        else if($auth['login_as'] !== KARYAWAN)
            $this->load->view('__base/sidebar_approver',$data);
        $this->load->view('cuti/master',$data);
        $this->load->view('__base/footer',$data);
    }

    public function get_dataajax(){
        if(!$this->input->is_ajax_request()) show_404();
        
        $auth = $this->session->userdata('login_data');
        if($auth['login_as'] == ADMIN){
            $this->m_cuti->get_datatable();
        }
        else if($auth['login_as'] == ATASAN_LANGSUNG){
            $this->m_cuti->get_datatable();
        }
        else if($auth['login_as'] == SDM){
            $this->m_cuti->get_datatable("cti_appr_atlstat = ".STATUS_ACCEPT);
        }
        else if($auth['login_as'] == ATASAN_TDK_LANGSUNG){
            $this->m_cuti->get_datatable("cti_appr_sdmstat = ".STATUS_ACCEPT. " OR cti_ovrd_atasanpk = ".$auth['data']->krw_id);
        }
    }

    public function set_approve(){
        if(!$this->input->is_ajax_request()) show_404();

        $pk_val = $this->input->post('cti_id');

        $auth = $this->session->userdata('login_data');
        if($auth['login_as'] != ADMIN || $auth['login_as'] != KARYAWAN){
            if($auth['login_as'] == SDM){
                $data = array(
                    'cti_appr_sdmstat' => STATUS_ACCEPT,
                    'cti_appr_sdmpk' => $auth['data']->krw_id,
                    'cti_appr_sdmdate' => date('Y-m-d H:i:s'),
                    'cti_appr_sdmnote' => "" //sementara kosong
                );

                $this->m_cuti->update($pk_val,$data);
                
            }
            else if($auth['login_as'] == ATASAN_LANGSUNG){
                $data = array(
                    'cti_appr_atlstat' => STATUS_ACCEPT,
                    'cti_appr_atlpk' => $auth['data']->krw_id,
                    'cti_appr_atldate' => date('Y-m-d H:i:s'),
                    'cti_appr_atlnote' => "" //sementara kosong
                );

                $this->m_cuti->update($pk_val,$data);
            }
            else if($auth['login_as'] == ATASAN_TDK_LANGSUNG){
                $data = array(
                    'cti_appr_atlstat' => STATUS_ACCEPT,    //AUTO ACCEPT FOR ATASAN LANGSUNG
                    'cti_appr_attlstat' => STATUS_ACCEPT,
                    'cti_appr_attlpk' => $auth['data']->krw_id,
                    'cti_appr_attldate' => date('Y-m-d H:i:s'),
                    'cti_appr_attlnote' => "" //sementara kosong
                );

                $this->m_cuti->update($pk_val,$data);
            }

            echo json_encode(array(
                'status' => 'ok',
                'msg' => 'Status menjadi Accept'
            ));
            exit;
        }
        else {
            echo json_encode(array(
                'status' => 'ok',
                'msg' => 'Login sebagai Approver'
            ));
            exit;
        }
    }

    public function set_reject(){
        if(!$this->input->is_ajax_request()) show_404();

        $pk_val = $this->input->post('cti_id');

        $auth = $this->session->userdata('login_data');
        if($auth['login_as'] != ADMIN || $auth['login_as'] != KARYAWAN){
            if($auth['login_as'] == SDM){
                $data = array(
                    'cti_appr_sdmstat' => STATUS_REJECT,
                    'cti_appr_sdmpk' => $auth['data']->krw_id,
                    'cti_appr_sdmdate' => date('Y-m-d H:i:s'),
                    'cti_appr_sdmnote' => "" //sementara kosong
                );

                $this->m_cuti->update($pk_val,$data);
                
            }
            else if($auth['login_as'] == ATASAN_LANGSUNG){
                $data = array(
                    'cti_appr_atlstat' => STATUS_REJECT,
                    'cti_appr_atlpk' => $auth['data']->krw_id,
                    'cti_appr_atldate' => date('Y-m-d H:i:s'),
                    'cti_appr_atlnote' => "" //sementara kosong
                );

                $this->m_cuti->update($pk_val,$data);
            }
            else if($auth['login_as'] == ATASAN_TDK_LANGSUNG){
                $is_atasan = STATUS_ACCEPT;
                $cuti = $this->m_cuti->get(TRUE,"cti_id = ".$pk_val." AND cti_ovrd_atasanpk IS NOT NULL",NULL,1);
                if($cuti != NULL){
                    $is_atasan = STATUS_REJECT;
                }
                $data = array(
                    'cti_appr_atlstat' => $is_atasan,
                    'cti_appr_attlstat' => STATUS_REJECT,
                    'cti_appr_attlpk' => $auth['data']->krw_id,
                    'cti_appr_attldate' => date('Y-m-d H:i:s'),
                    'cti_appr_attlnote' => "" //sementara kosong
                );

                $this->m_cuti->update($pk_val,$data);
            }

            echo json_encode(array(
                'status' => 'ok',
                'msg' => 'Status menjadi Ditolak'
            ));
            exit;
        }
        else {
            echo json_encode(array(
                'status' => 'ok',
                'msg' => 'Login sebagai Approver'
            ));
            exit;
        }
    }
}