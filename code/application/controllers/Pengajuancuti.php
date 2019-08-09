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
        $data['status_level'] = $this->lang->line('status_level');
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

        $template_page = array(
            CUTI_TAHUNAN => 'cuti_tahunan',
            CUTI_BESAR => 'cuti_besar',
            CUTI_SAKIT => 'cuti_sakit',
            CUTI_LAHIR => 'cuti_lahir',
            CUTI_HAJI  => 'cuti_haji',
            CUTI_DISPEN => 'cuti_dispen'
        );
        $cuti_id = $this->input->get('jenis');
        $template_data = isset($template_page[intval($cuti_id)]) ? $template_page[intval($cuti_id)] : NULL;

        $this->load->view('__base/header',$data);
        $this->load->view('__base/sidebar_karyawan',$data);
        $this->load->view('pengajuancuti/' . (isset($template_data) ? $template_data : 'cuti_tahunan' ) ,$data);
        $this->load->view('__base/footer',$data);
    }
    
    /* check overlap tanggal */
	public function check_overlap($ttp_start){
		$start = new DateTime($ttp_start);
		$end = new DateTime($this->input->post('cti_selesai'));
		
		if ($start > $end || $end < $start ){
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
        $this->form_validation->set_rules('cti_jenis'             , 'Jenis Cuti'                 , 'required|integer');

        if($this->input->post('cti_jenis') == CUTI_DISPEN) 
            $this->form_validation->set_rules('cti_upload',         'Upload Berkas',            '');
        else if($this->input->post('cti_jenis') == CUTI_TAHUNAN || $this->input->post('cti_jenis') == CUTI_BESAR)
            $this->form_validation->set_rules('cti_alamat_cuti'     ,'Alamat Cuti',             'required');
        else
            $this->form_validation->set_rules('cti_upload',         'Upload Berkas',            'required');

        if($this->form_validation->run()){
            $krw_id = $auth['data']->krw_id;    //karyawan id now
            $jenis = $this->input->post('cti_jenis');

            $cuti_start = new DateTime($this->input->post('cti_mulai'));
            $cuti_end = new DateTime($this->input->post('cti_selesai'));
            
            $cuti_totobj = $cuti_start->diff($cuti_end);
            $tot_cuti = intval($cuti_totobj->format('%r%a')) + 1; //add 1 for end date if same pick

            $data_cuti = array(
                'cti_karyawan'              => $krw_id,
                'cti_upload'                => NULL,
                'cti_alamat_cuti'           => NULL,
                'cti_hari'                  => $tot_cuti,
                'cti_mulai'                 => $cuti_start->format('Y-m-d'),
                'cti_selesai'               => $cuti_end->format('Y-m-d'),
                'cti_alasan'                => $this->input->post('cti_alasan')
            );

            if($jenis == CUTI_DISPEN){
                $data_cuti['cti_jenis'] = CUTI_DISPEN;
                $data_cuti['cti_appr_attlstat'] = STATUS_NOT_USED;
                
                $result = $this->m_cuti->ajukan_cuti($data_cuti);
                $msg = array(
                    'type' => 'success',
                    'message' => 'Sukses tambah cuti'
                );
                $this->session->set_flashdata('msg',$msg); 
                redirect('historicuti');
            }
            else if ($jenis == CUTI_SAKIT || $jenis == CUTI_LAHIR || $jenis == CUTI_HAJI){
                //atasi file upload 
                $data_cuti['cti_upload'] = NULL; //for sementara
                $data_cuti['cti_jenis'] = $jenis;
                
                if($jenis == CUTI_SAKIT){
                    $data_cuti['cti_appr_atlstat'] = STATUS_NOT_USED;
                    $data_cuti['cti_appr_attlstat'] = STATUS_NOT_USED;
                }
                if($jenis == CUTI_LAHIR || $jenis == CUTI_HAJI ){
                    $data_cuti['cti_appr_attlstat'] = STATUS_NOT_USED;
                }

                $result = $this->m_cuti->ajukan_cuti($data_cuti);
                $msg = array(
                    'type' => 'success',
                    'message' => 'Sukses tambah cuti'
                );
                $this->session->set_flashdata('msg',$msg); 
                redirect('historicuti');
            }
            else if ($jenis == CUTI_TAHUNAN){
                $data_cuti['cti_jenis'] = CUTI_TAHUNAN;
                $data_cuti['cti_alamat_cuti'] = $this->input->post('cti_alamat_cuti');

                //filter jatah cuti tahunan
                $filter_jtcuti = array(
                    'jtc_karyawan = "'.$krw_id.'"', //karyawan id
                    'jtc_status = '.STATUS_ACTIVE,
                    'jtc_jenis = '.CUTI_TAHUNAN
                );
                //get jatah cuti
                $jatah_cuti = $this->m_jatahcuti->get(TRUE,implode(" AND ",$filter_jtcuti),"jtc_validstart asc"); //start dari tahun cuti terkecil

                if(count($jatah_cuti) > 0){
                    $year_used = FALSE;
                    foreach($jatah_cuti as $row){
                        if( check_date_range(
                                array($cuti_start->format('Y-m-d'),$cuti_end->format('Y-m-d')),
                                array($row->jtc_validstart, $row->jtc_validend)
                            ) == PARTIAL && $row->jtc_sisa >= $tot_cuti     //jika permintaan berada di range tersebut
                        ){
                            //ajukan
                            $jtc_id = $row->jtc_id;
                            $jtc_sisa = $row->jtc_sisa;
                            $result = $this->m_cuti->ajukan_cuti($data_cuti,$jtc_id,$jtc_sisa);

                            $year_used = TRUE;
                            $msg = array(
                                'type' => 'success',
                                'message' => 'Sukses tambah cuti'
                            );
                            $this->session->set_flashdata('msg',$msg); 
                            break;
                            exit;
                        }
                    }
                    if(!$year_used){
                        $msg = array(
                            'type' => 'error',
                            'message' => 'Anda belum punya jatah cuti yang valid'
                        );
                        $this->session->set_flashdata('msg',$msg);
                    }
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
            else if($jenis == CUTI_BESAR){
                //jika cuti besar
                $data_cuti['cti_jenis'] = CUTI_BESAR;
                $data_cuti['cti_alamat_cuti'] = $this->input->post('cti_alamat_cuti');

                //filter jatah cuti tahunan
                $filter_jtcuti = array(
                    'jtc_karyawan = "'.$krw_id.'"', //karyawan id
                    'jtc_status = '.STATUS_ACTIVE,
                    'jtc_jenis = '.CUTI_BESAR
                );

                //get jatah cuti
                $jatah_cuti = $this->m_jatahcuti->get(TRUE,implode(" AND ",$filter_jtcuti),"jtc_validstart asc"); //start dari tahun cuti terkecil

                if(count($jatah_cuti) > 0){
                    $year_used = FALSE;
                    foreach($jatah_cuti as $row){
                        if( check_date_range(
                                array($cuti_start->format('Y-m-d'),$cuti_end->format('Y-m-d')),
                                array($row->jtc_validstart, $row->jtc_validend)
                            ) == PARTIAL && $row->jtc_sisa >= $tot_cuti     //jika permintaan berada di range tersebut
                        ){
                            //ajukan
                            $jtc_id = $row->jtc_id;
                            $jtc_sisa = $row->jtc_sisa;
                            $result = $this->m_cuti->ajukan_cuti($data_cuti,$jtc_id,$jtc_sisa);

                            $year_used = TRUE;
                            $msg = array(
                                'type' => 'success',
                                'message' => 'Sukses tambah cuti'
                            );
                            $this->session->set_flashdata('msg',$msg); 
                            break;
                            exit;
                        }
                    }
                    if(!$year_used){
                        $msg = array(
                            'type' => 'error',
                            'message' => 'Anda belum punya jatah cuti yang valid'
                        );
                        $this->session->set_flashdata('msg',$msg);
                    }
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