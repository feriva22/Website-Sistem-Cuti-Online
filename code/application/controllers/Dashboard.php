<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model(array('m_karyawan','m_admin','m_jatahcuti','m_cuti'));
        $this->lang->load('_site');

        is_login();

    }

    public function index(){

        //check as redirector
        $auth = $this->session->userdata('login_data');

        if($auth['is_admin']){
            redirect('dashboard/admin');
        }else if(intval($auth['data']->krw_level) === KARYAWAN ){
            redirect('dashboard/karyawan');
        }
        else if(intval($auth['data']->krw_level) !== KARYAWAN){
            if($auth['login_as'] == KARYAWAN)
                redirect('dashboard/karyawan');
            else if($auth['login_as'] != KARYAWAN)
                redirect('dashboard/approver');
        }  //login as approver
        
    }

    public function admin(){
        $auth = $this->session->userdata('login_data');

        if(!$auth['is_admin'])
            redirect('dashboard');
        
        $this->site_info->set_page_title('Dashboard - Karyawan');

        $data = array();
        $data['admin'] = $this->m_admin->get(TRUE,'adm_username = "'.$auth['data']->adm_username.'"',NULL,1);
        $data['tot_karyawan'] = $this->m_karyawan->total();

        $data['tot_cuti_acc'] = $this->m_cuti->get_total(implode(" AND ",array(
            'cti_appr_sdmstat ='. STATUS_ACTIVE,
            'cti_appr_atlstat ='. STATUS_ACTIVE,
            'cti_appr_attlstat ='.STATUS_ACTIVE
        )));

        $data['tot_cuti_wait'] = $this->m_cuti->get_total(
            implode(" OR ",array(
                'cti_appr_sdmstat ='. STATUS_WAITING,
                'cti_appr_atlstat ='. STATUS_WAITING,
                'cti_appr_attlstat ='.STATUS_WAITING
            )
        ));

        $data['tot_cuti_reject'] = $this->m_cuti->get_total(
            implode(" OR ",array(
                'cti_appr_sdmstat ='. STATUS_REJECT,
                'cti_appr_atlstat ='. STATUS_REJECT,
                'cti_appr_attlstat ='.STATUS_REJECT
            )
        ));

        $this->load->view('__base/header');
        $this->load->view('__base/sidebar_admin',$data);
        $this->load->view('dashboard/admin',$data);
        $this->load->view('__base/footer');

    }

    public function karyawan(){
        $auth = $this->session->userdata('login_data');

        if($auth['is_admin'])
            redirect('dashboard');
        if($auth['login_as'] != KARYAWAN)
            redirect('dashboard/approver');
        
        $this->site_info->set_page_title('Dashboard - Karyawan');

        $data = array();
        $data['karyawan'] = $this->m_karyawan->get(TRUE,'krw_username = "'.$auth['data']->krw_username.'"',NULL,1); 
        $data['level_karyawan'] = $this->lang->line('level_karyawan');

        $data['tot_cuti'] = $this->m_jatahcuti->get_sisa_cuti(array(
            'jtc_karyawan ='. $auth['data']->krw_id,
            'jtc_status =' . STATUS_ACTIVE
        ));
        
        $data['tot_cuti_acc'] = $this->m_cuti->get_total(implode(" AND ",array(
            'cti_karyawan ='. $auth['data']->krw_id,
            'cti_appr_sdmstat ='. STATUS_ACTIVE,
            'cti_appr_atlstat ='. STATUS_ACTIVE,
            'cti_appr_attlstat ='.STATUS_ACTIVE
        )));

        $data['tot_cuti_wait'] = $this->m_cuti->get_total(implode(" AND ",array(
            'cti_karyawan ='. $auth['data']->krw_id,
            implode(" OR ",array(
                'cti_appr_sdmstat ='. STATUS_WAITING,
                'cti_appr_atlstat ='. STATUS_WAITING,
                'cti_appr_attlstat ='.STATUS_WAITING
            ))
        )));

        $data['tot_cuti_reject'] = $this->m_cuti->get_total(implode(" AND ",array(
            'cti_karyawan ='. $auth['data']->krw_id,
            implode(" OR ",array(
                'cti_appr_sdmstat ='. STATUS_REJECT,
                'cti_appr_atlstat ='. STATUS_REJECT,
                'cti_appr_attlstat ='.STATUS_REJECT
            ))
        )));


        $this->load->view('__base/header');
        $this->load->view('__base/sidebar_karyawan',$data);
        $this->load->view('dashboard/karyawan',$data);
        $this->load->view('__base/footer');
    }

    public function approver(){
        $auth = $this->session->userdata('login_data');

        if(intval($auth['data']->krw_level) == KARYAWAN || $auth['is_admin'])
            redirect('dashboard');
        if($auth['login_as'] === KARYAWAN)
            redirect('dashboard/karyawan');

        $this->site_info->set_page_title('Dashboard - Approver');

        $data = array();
        $data['karyawan'] = $this->m_karyawan->get(TRUE,'krw_username = "'.$auth['data']->krw_username.'"',NULL,1);
        $data['level_karyawan'] = $this->lang->line('level_karyawan');
        $data['login_as'] = $auth['login_as'];

        $filter = NULL;
        if($auth['login_as'] == SDM){
            $filter = "cti_appr_atlstat = ".STATUS_ACCEPT;
        }
        else if($auth['login_as'] == ATASAN_TDK_LANGSUNG){
            $filter = "cti_appr_sdmstat = ".STATUS_ACCEPT;
        }

        $data['tot_cuti'] = $this->m_cuti->get_total($filter);

        $data['tot_cuti_acc'] = $this->m_cuti->get_total(implode(" AND ",array(
            'cti_appr_sdmstat ='. STATUS_ACTIVE,
            'cti_appr_atlstat ='. STATUS_ACTIVE,
            'cti_appr_attlstat ='.STATUS_ACTIVE
        )));

        $data['tot_cuti_wait'] = $this->m_cuti->get_total(
            implode(" OR ",array(
                'cti_appr_sdmstat ='. STATUS_WAITING,
                'cti_appr_atlstat ='. STATUS_WAITING,
                'cti_appr_attlstat ='.STATUS_WAITING
            )
        ));

        $data['tot_cuti_reject'] = $this->m_cuti->get_total(
            implode(" OR ",array(
                'cti_appr_sdmstat ='. STATUS_REJECT,
                'cti_appr_atlstat ='. STATUS_REJECT,
                'cti_appr_attlstat ='.STATUS_REJECT
            )
        ));

        $this->load->view('__base/header');   
        $this->load->view('__base/sidebar_approver',$data);
        $this->load->view('dashboard/approver',$data);
        $this->load->view('__base/footer');
    }

    public function change_login(){
        $auth = $this->session->userdata('login_data');

        if($auth['is_admin'])
            redirect('dashboard');
        else if($auth['login_as'] !== KARYAWAN){
            $auth['login_as'] = KARYAWAN;
            $this->session->set_userdata('login_data',$auth);
            redirect('dashboard/karyawan');
        }
        else if($auth['login_as'] === KARYAWAN){
            $auth['login_as'] = intval($auth['data']->krw_level);
            $this->session->set_userdata('login_data',$auth);
            redirect('dashboard/approver');
        }
    }



    

}