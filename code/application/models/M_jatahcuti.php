<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_jatahcuti extends CI_Model {
    protected $pk_col = 'jtc_id';
    protected $table = 'jatah_cuti';
    protected $isDetail = FALSE;

	function __construct()
	{ 
        parent::__construct();
    }


    private function select(){
        if(!$this->isDetail){
            $this->db->select('jtc_id');
            $this->db->select('jtc_validdate');
            $this->db->select('jtc_jumlah');
            $this->db->select('jtc_sisa');
            $this->db->select('jtc_karyawan');
            $this->db->select('jtc_delaystart');
            $this->db->select('jtc_delayend');
            $this->db->select('jtc_status');

            $this->db->select('krw_nama');

        }else if($this->isDetail){
            $this->db->select('jtc_id');
            $this->db->select('jtc_validdate');
            $this->db->select('jtc_jumlah');
            $this->db->select('jtc_sisa');
            $this->db->select('jtc_karyawan');
            $this->db->select('jtc_delaystart');
            $this->db->select('jtc_delayend');
            $this->db->select('jtc_status');

            $this->db->select('krw_nama');

        }
        $this->db->from('jatah_cuti');
        $this->db->join('karyawan','krw_id = jtc_karyawan');
        
    }
    
    public function get($isDetail = FALSE,$where=NULL,$order=NULL,$limit=NULL,$offset=NULL,$escape=NULL){
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

    public function get_datatable(){
        $limit = intval($this->input->post('length'));
        if(!is_exist($limit))
            $limit = 10;
        
        $data = array();

        $result = $this->get(TRUE,NULL,NULL,10,0);
        
        echo json_encode(array(
            'data' => $result,
            'draw' => 0,
            'recordsTotal' => count($result)
        ));
        exit;
    }

    public function get_sisa_cuti($filter = NULL){
        if(!is_exist($filter))
            return 0;

        $jatah_cuti = $this->get(TRUE,implode(" AND ",$filter));
        $sisa_cuti = 0;
        foreach($jatah_cuti as $row){
            $sisa_cuti += intval($row->jtc_sisa);
        }
        return $sisa_cuti;
    }

    public function insert($data){
        $this->db->insert('jatah_cuti', $data);
		return $this->db->insert_id();
    }

    public function update($pk_val,$data){
        return $this->db->update('jatah_cuti', $data, "jtc_id = '$pk_val'");
    }

    public function delete_permanent($pk_val){
        return $this->db->delete('jatah_cuti',array('jtc_id' => $pk_val));
    }


}