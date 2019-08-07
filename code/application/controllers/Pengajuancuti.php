<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuancuti extends CI_Controller {

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
        $data['level_karyawan'] = $this->lang->line('level_karyawan');

        $data['add_js'] = array(
            'plugins/moment/moment.min.js',
            'plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js'
        );
        $data['add_css'] = array(
            'plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css'
        );
        $data['add_php_js'] = array(
            'data' => $data,
            'src'  => '__scripts/pengajuancuti'
        );

        $this->site_info->set_page_title('Pengajuan Cuti Baru');


        $this->load->view('__base/header',$data);
        $this->load->view('__base/sidebar_karyawan',$data);
        $this->load->view('pengajuancuti/master',$data);
        $this->load->view('__base/footer',$data);
    }
    
    /* check overlap tanggal */
	public function check_overlap($ttp_start){
		$start = new DateTime($ttp_start);
		$end = new DateTime($this->input->post('cti_selesai'));
		
		if ($start > $end || $end < $start || $start == $end){
			$this->form_validation->set_message('check_overlap', 'Tanggal Overlap');
			return false;
		}else{
			return true;
		}
	}

    public function add(){

        $auth = $this->session->userdata('login_data');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cti_mulai'             , 'Mulai Cuti'                 , 'required|callback_check_overlap');
        $this->form_validation->set_rules('cti_selesai'           , 'Selesai Cuti'               , 'required');
        $this->form_validation->set_rules('cti_alasan'            , 'Alasan CUti'                , 'required');

        if($this->form_validation->run()){
            $krw_id = $auth['data']->krw_id;    //karyawan id now
            $cuti_start = new DateTime($this->input->post('cti_mulai'));
            $cuti_end = new DateTime($this->input->post('cti_selesai'));
            
            $cuti_totobj = $cuti_start->diff($cuti_end);
            $tot_cuti = intval($cuti_totobj->format('%r%a'));

            //filter jatah cuti
            $filter_jtcuti = array(
                'jtc_karyawan = "'.$krw_id.'"', //karyawan id
                'jtc_status = '.STATUS_ACTIVE
            );

            $jatah_cuti = $this->m_jatahcuti->get(TRUE,implode(" AND ",$filter_jtcuti),"jtc_validdate asc"); //start dari tahun cuti terkecil

            if(count($jatah_cuti) > 0 ){
                $year_used = FALSE;
                foreach($jatah_cuti as $row){
                    if( $row->jtc_validdate <= $cuti_start->format('Y-m-d') && 
                        $row->jtc_sisa >= $tot_cuti                         && 
                        $row->jtc_sisa != 0             
                    )
                    {
                        //check if date 
                        if(!check_date_range(
                                array($cuti_start->format('Y-m-d'),$cuti_end->format('Y-m-d')),
                                array($row->jtc_delaystart, $row->jtc_delayend)
                            )
                        )
                        {
                            $data_cuti = array(
                                'cti_hari' => $tot_cuti,
                                'cti_mulai' => $cuti_start->format('Y-m-d'),
                                'cti_selesai' => $cuti_end->format('Y-m-d'),
                                'cti_alasan' => $this->input->post('cti_alasan')
                            );
    
                            $result = $this->m_cuti->ajukan_cuti($krw_id,$data_cuti,$row->jtc_id,$row->jtc_sisa);
    
                            $year_used = TRUE;
                            $msg = array(
                                'type' => 'success',
                                'message' => 'Sukses tambah cuti'
                            );
                            $this->session->set_flashdata('msg',$msg); 
                            break;
                        }
                    }
                }
                if(!$year_used){
                    $msg = array(
                        'type' => 'error',
                        'message' => 'Anda belum punya jatah cuti yang valid'
                    );
                    $this->session->set_flashdata('msg',$msg);
                }
                redirect('pengajuancuti');
            }
            else{
                $msg = array(
                    'type' => 'error',
                    'message' => 'Anda belum punya jatah cuti'
                );
                $this->session->set_flashdata('msg',$msg);
            }
            redirect('pengajuancuti');
        }
        else {
            $msg = array(
				'type' => 'error',
				'message' => 'Cek inputan anda'
			);
            $this->session->set_flashdata('msg',$msg);
            redirect('pengajuancuti');
        }
    }
}