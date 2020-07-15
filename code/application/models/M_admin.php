<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_admin extends CI_Model {
    protected $pk_col = 'adm_id';
    protected $table = 'admin';
    protected $isDetail = FALSE;

	function __construct()
	{ 
        parent::__construct(); 
    }


    public function select(){
        if(!$this->isDetail){
            $this->db->select('adm_id');
            $this->db->select('adm_username');
            $this->db->select('adm_password');
        }else if($this->isDetail){
            $this->db->select('adm_id');
            $this->db->select('adm_username');
        }

        $this->db->from('admin');
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


}