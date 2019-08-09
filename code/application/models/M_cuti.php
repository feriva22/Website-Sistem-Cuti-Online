<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_cuti extends CI_Model {
    protected $pk_col = 'cti_id';
    protected $table = 'cuti';
    protected $isDetail = FALSE;

	function __construct()
	{ 
        parent::__construct();

    }


    private function select(){
        if(!$this->isDetail){
            $this->db->select('cti_id');
            $this->db->select('cti_karyawan');
            $this->db->select('cti_jenis');
            $this->db->select('cti_upload');
            $this->db->select('cti_alamat_cuti');
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
            $this->db->select('krw_divisi');

        }else if($this->isDetail){
            $this->db->select('cti_id');
            $this->db->select('cti_karyawan');
            $this->db->select('cti_jenis');
            $this->db->select('cti_upload');
            $this->db->select('cti_alamat_cuti');
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
            $this->db->select('krw_divisi');

        }

        $this->db->from('cuti');
        //$this->db->from('divisi');
        $this->db->join('karyawan','krw_id = cti_karyawan');
        
    }
    
    public function get($isDetail = FALSE,$where=NULL,$order=NULL,$limit=NULL,$offset=NULL,$escape=NULL,$table=NULL){
        $this->isDetail = $isDetail;

        $this->select();

        if(is_exist($table)) $this->db->from($table);

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

    public function get_datatable($filter=NULL,$order=NULL,$table=NULL){
        $limit = intval($this->input->post('length'));
        if(!is_exist($limit))
            $limit = 10;
        
        $data = array();

        $result = $this->get(TRUE,$filter,$order,NULL,NULL,NULL,$table);
        
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

    private function insert($data){
        $this->load->model('m_karyawan');
        $karyawan = $this->m_karyawan->get(TRUE,"krw_id = ".$data['cti_karyawan'],NULL,1);
        $stat_atl = isset($data['cti_appr_atlstat']) ? $data['cti_appr_atlstat'] : STATUS_WAITING;
        $stat_sdm = isset($data['cti_appr_sdmstat']) ? $data['cti_appr_sdmstat'] : STATUS_WAITING;
        $stat_attl = isset($data['cti_appr_attlstat']) ? $data['cti_appr_attlstat'] : STATUS_WAITING;
        $data = array(
            'cti_karyawan'          => $data['cti_karyawan'],
            'cti_jenis'             => $data['cti_jenis'],
            'cti_upload'            => $data['cti_upload'],
            'cti_alamat_cuti'       => $data['cti_alamat_cuti'],
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


    
    public function ajukan_cuti($data,$jtc_pk=NULL,$jtc_jumlah=NULL){

        $this->load->model('m_jatahcuti');

        $result = $this->insert($data);
        
        if(is_exist($jtc_pk) && is_exist($jtc_jumlah)){
            $this->m_jatahcuti->update($jtc_pk,array(   //decrement jumlah cuti
                'jtc_sisa' => intval($jtc_jumlah)-intval($data['cti_hari'])
            ));
        }
        
        return $result;
    }



}