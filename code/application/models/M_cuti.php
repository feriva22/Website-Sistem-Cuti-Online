<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_cuti extends CI_Model {
    protected $pk_col = 'cti_id';
    protected $table = 'cuti';
    protected $isDetail = FALSE;

	function __construct()
	{ 
        parent::__construct();
        $this->load->model('m_jatahcuti');

    }


    private function select(){
        if(!$this->isDetail){
            $this->db->select('cti_id');
            $this->db->select('cti_karyawan');
            $this->db->select('cti_tglpengajuan');
            $this->db->select('cti_hari');
            $this->db->select('cti_mulai');
            $this->db->select('cti_selesai');
            $this->db->select('cti_alasan');
            $this->db->select('cti_appr_sdmstat');
            $this->db->select('cti_appr_sdmpk');
            $this->db->select('cti_appr_sdmnote');
            $this->db->select('cti_appr_sdmdate');
            $this->db->select('cti_appr_atlstat');
            $this->db->select('cti_appr_atlpk');
            $this->db->select('cti_appr_atlnote');
            $this->db->select('cti_appr_atldate');
            $this->db->select('cti_appr_attlstat');
            $this->db->select('cti_appr_attlpk');
            $this->db->select('cti_appr_attlnote');
            $this->db->select('cti_appr_attldate');

            $this->db->select('krw_nama');

        }else if($this->isDetail){
            $this->db->select('cti_id');
            $this->db->select('cti_karyawan');
            $this->db->select('cti_tglpengajuan');
            $this->db->select('cti_hari');
            $this->db->select('cti_mulai');
            $this->db->select('cti_selesai');
            $this->db->select('cti_alasan');
            $this->db->select('cti_appr_sdmstat');
            $this->db->select('cti_appr_sdmpk');
            $this->db->select('cti_appr_sdmnote');
            $this->db->select('cti_appr_sdmdate');
            $this->db->select('cti_appr_atlstat');
            $this->db->select('cti_appr_atlpk');
            $this->db->select('cti_appr_atlnote');
            $this->db->select('cti_appr_atldate');
            $this->db->select('cti_appr_attlstat');
            $this->db->select('cti_appr_attlpk');
            $this->db->select('cti_appr_attlnote');
            $this->db->select('cti_appr_attldate');

            $this->db->select('krw_nama');
        }

        $this->db->from('cuti');
        $this->db->join('karyawan','krw_id = cti_karyawan');
        
    }
    
    public function get($isDetail = FALSE,$where="",$order="",$limit=NULL,$offset=NULL,$escape=NULL){
        $this->isDetail = $isDetail;

        $this->select();

        if(is_exist($where))  $this->db->where($where, NULL, $escape);

        if(is_exist($order)) $this->db->order_by($order, '', $escape);

        if(is_exist($limit) && is_exist($offset)) 
            $this->db->limit($limit, $offset);
        else if(is_exist($limit)) 
            $this->db->limit($limit);
        
        $query = $this->db->get();
        $result = $query->result();
        if($limit === 1)
			return count($result) == 0 ? NULL : $result[0];
        
        return $result;
    }

    public function get_datatable($filter=NULL){
        $limit = intval($this->input->post('length'));
        if(!is_exist($limit))
            $limit = 10;
        
        $data = array();

        $result = $this->get(TRUE,$filter,NULL,10,0);
        
        echo json_encode(array(
            'data' => $result,
            'draw' => 0,
            'recordsTotal' => count($result)
        ));
        exit;
    }

    public function get_total($filter=NULL){
        $result = $this->get(TRUE,$filter);
        return count($result);
    }

    private function insert($karyawan_id,$data){
        $karyawan = $this->m_karyawan->get(TRUE,"krw_id = ".$karyawan_id,NULL,1);
        $stat_atl = STATUS_WAITING;
        $stat_sdm = STATUS_WAITING;
        $stat_attl = STATUS_WAITING;
        $atasan_pk = NULL;
        if(is_exist($karyawan)){
            if(intval($karyawan->krw_level) == ATASAN_LANGSUNG && $karyawan->krw_ovrd_atasanpk != NULL){
//                $stat_atl = STATUS_ACCEPT;
                $atasan_pk = $karyawan->krw_ovrd_atasanpk;

            }
            /*else if(intval($karyawan->krw_level) == SDM){
                $stat_atl = STATUS_ACCEPT;
                $stat_sdm = STATUS_ACCEPT;
            }*/
            /*if(intval($karyawan->krw_level) == ATASAN_TDK_LANGSUNG ){
                //jika ATASAN TIDAK LANGSUNG yang mengajukan
                //$stat_attl = STATUS_ACCEPT; 
            }*/
        }
        $data = array(
            'cti_karyawan'          => $karyawan_id,
            'cti_tglpengajuan'      => date('Y-m-d H:i:s'),
            'cti_hari'              => $data['cti_hari'],
            'cti_mulai'             => $data['cti_mulai'],
            'cti_selesai'           => $data['cti_selesai'],
            'cti_alasan'            => $data['cti_alasan'],
            'cti_appr_sdmstat'      => $stat_sdm,
            'cti_appr_sdmpk'        => NULL,
            'cti_appr_sdmnote'      => NULL,
            'cti_appr_sdmdate'      => NULL,
            'cti_appr_atlstat'      => $stat_atl,
            'cti_appr_atlpk'        => NULL,
            'cti_appr_atlnote'      => NULL,
            'cti_appr_atldate'      => NULL,
            'cti_appr_attlstat'     => $stat_attl,
            'cti_appr_attlpk'       => NULL,
            'cti_appr_attlnote'     => NULL,
            'cti_appr_attldate'     => NULL,
            'cti_ovrd_atasanpk'     => $atasan_pk
        );

        $this->db->insert('cuti', $data);
		return $this->db->insert_id();
    }

    public function update($pk_val,$data){
        return $this->db->update('cuti', $data, "cti_id = '$pk_val'");
    }


    
    public function ajukan_cuti($karyawan_id,$data,$jtc_pk,$jtc_jumlah){

        $this->load->model('m_karyawan');

        $result = $this->insert($karyawan_id,$data);
        
        $this->m_jatahcuti->update($jtc_pk,array(   //decrement jumlah cuti
            'jtc_sisa' => intval($jtc_jumlah)-intval($data['cti_hari'])
        ));

        $filter = array(
            'jtc_karyawan' => $karyawan_id,
            'jtc_status' => STATUS_ACTIVE
        );
        $sisa_cuti = $this->m_jatahcuti->get_sisa_cuti($filter);
        //update sisa cuti karyawan
        $krw_id = $this->m_karyawan->update($karyawan_id,array( 'krw_jatahcuti' => $sisa_cuti));

        
        return $result;
    }



}