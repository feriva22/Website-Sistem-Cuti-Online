<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_jabatan extends CI_Model {
    protected $pk_col = 'jbt_id';
    protected $table = 'jabatan';
    protected $isDetail = FALSE;

	function __construct()
	{ 
        parent::__construct();
    }


    private function select(){
        if(!$this->isDetail){
            $this->db->select('jbt_id');
            $this->db->select('jbt_nama');
        }else if($this->isDetail){
            $this->db->select('jbt_id');
            $this->db->select('jbt_nama');
        }

        $this->db->from('jabatan');
        
    }
    
    public function get($isDetail = FALSE,$where=NULL,$order="",$limit=NULL,$offset=NULL,$escape=NULL){
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

    public function insert($data){
        $this->db->insert('jabatan', $data);
		return $this->db->insert_id();
    }

    public function update($pk_val,$data){
        return $this->db->update('jabatan', $data, "jbt_id = '$pk_val'");
    }

    public function delete_permanent($pk_val){
        return $this->db->delete('jabatan',array('jbt_id' => $pk_val));
    }


}