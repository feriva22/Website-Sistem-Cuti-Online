<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model(array('m_karyawan','m_admin','m_cuti','m_jatahcuti'));
        $this->lang->load('_site');

        is_login();

    }

    public function index(){

        //check as redirector
        $auth = $this->session->userdata('login_data');

        if($auth['is_admin']){
            redirect('dashboard/admin');
        }else if($auth['data']->krw_level == KARYAWAN ){
            redirect('dashboard/karyawan');
        }
        else if($auth['data']->krw_level != KARYAWAN){
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
        
        $this->site_info->set_page_title('Dashboard - Admin');

        $data = array();
        $data['admin'] = $auth['data'];
        $data['tot_karyawan'] = $this->m_karyawan->total("krw_status = ".STATUS_ACTIVE);

        $data['tot_cuti_acc'] = $this->m_cuti->get_total(implode(" AND ",array(
            'cti_appr_sdmstat ='. STATUS_ACTIVE,
            '(cti_appr_atlstat = '. STATUS_ACTIVE . ' OR cti_appr_atlstat = '. STATUS_NOT_USED.')',
            '(cti_appr_attlstat ='.STATUS_ACTIVE . ' or cti_appr_attlstat = '. STATUS_NOT_USED.')'
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
        $data['karyawan'] = $auth['data'];
        $data['status_level'] = $this->lang->line('status_level');

        $data['tot_cuti'] = $this->m_jatahcuti->get_sisa_cuti(array(
            'jtc_karyawan ='. get_login_data()->krw_id,
            'jtc_status =' . STATUS_ACTIVE
        ));
        
        //$data['tot_cuti'] = 0;
        
        $data['tot_cuti_acc'] = $this->m_cuti->get_total(implode(" AND ",array(
            'cti_karyawan ='. get_login_data()->krw_id,
            'cti_appr_sdmstat ='. STATUS_ACTIVE,
            '(cti_appr_atlstat = '. STATUS_ACTIVE . ' OR cti_appr_atlstat = '. STATUS_NOT_USED.')',
            '(cti_appr_attlstat ='.STATUS_ACTIVE . ' or cti_appr_attlstat = '. STATUS_NOT_USED.')'
        )));

        $data['tot_cuti_wait'] = $this->m_cuti->get_total(implode(" AND ",array(
            'cti_karyawan ='. get_login_data()->krw_id,"(".
            implode(" OR ",array(
                'cti_appr_sdmstat ='. STATUS_WAITING,
                'cti_appr_atlstat = '. STATUS_WAITING ,
                'cti_appr_attlstat ='.STATUS_WAITING
            )).")"
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

        redir_karyawan();
        if(check_login_as() == ADMIN){
            redirect('dashboard');
        }

        $this->site_info->set_page_title('Dashboard - Approver');

        $data = array();
        $data['karyawan'] = $auth['data'];
        $data['status_level'] = $this->lang->line('status_level');

        $filter = NULL;
        if($auth['login_as'] == KADIR_SDMO || $auth['login_as'] == KABAG_ADMIN ){
            $filter = "cti_appr_sdmstat = ".STATUS_WAITING;
        }
        else{
            $filter = "cti_appr_atlstat = ".STATUS_WAITING .' OR cti_appr_attlstat = '.STATUS_WAITING;
        }

        $data['tot_cuti'] = $this->m_cuti->get_total($filter);


        $data['tot_cuti_acc'] = $this->m_cuti->get_total(implode(" AND ",array(
            'cti_appr_sdmstat ='. STATUS_ACTIVE,
            '(cti_appr_atlstat = '. STATUS_ACTIVE . ' OR cti_appr_atlstat = '. STATUS_NOT_USED.')',
            '(cti_appr_attlstat ='.STATUS_ACTIVE . ' or cti_appr_attlstat = '. STATUS_NOT_USED.')'
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