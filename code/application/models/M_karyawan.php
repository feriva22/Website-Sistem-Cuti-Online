<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_karyawan extends CI_Model {
    protected $pk_col = 'krw_id';
    protected $table = 'karyawan';
    protected $isDetail = FALSE;

	function __construct()
	{ 
        parent::__construct();
    }


    private function select(){
        if(!$this->isDetail){
            $this->db->select('krw_id');
            $this->db->select('krw_username');
            $this->db->select('krw_password');
            $this->db->select('krw_email');
            $this->db->select('krw_nama');
            $this->db->select('krw_nik');
            $this->db->select('krw_tgllahir');
            $this->db->select('krw_jeniskelamin');
            $this->db->select('krw_alamat');
            $this->db->select('krw_agama');
            $this->db->select('krw_foto');
            $this->db->select('krw_tglmasuk');
            $this->db->select('krw_divisi');
            $this->db->select('krw_jabatan');
            $this->db->select('krw_ovrd_atasanpk');
            $this->db->select('krw_level');


            $this->db->select('dvs_nama'); //data divisi
            $this->db->select('jbt_nama '); //data jabatan
            $this->db->select('dvs_attljbt_pk'); //jabatan atasan tidak langsung siapa

        }else if($this->isDetail){
            $this->db->select('krw_id');
            $this->db->select('krw_username');
            $this->db->select('krw_email');
            $this->db->select('krw_nama');
            $this->db->select('krw_nik');
            $this->db->select('krw_tgllahir');
            $this->db->select('krw_jeniskelamin');
            $this->db->select('krw_alamat');
            $this->db->select('krw_agama');
            $this->db->select('krw_foto');
            $this->db->select('krw_tglmasuk');
            $this->db->select('krw_divisi');
            $this->db->select('krw_jabatan');
            $this->db->select('krw_ovrd_atasanpk');
            $this->db->select('krw_level');

            $this->db->select('dvs_nama'); //data divisi
            $this->db->select('jbt_nama'); //data jabatan
            $this->db->select('dvs_attljbt_pk'); //jabatan atasan tidak langsung siapa

            
        }

        $this->db->from('karyawan');
        $this->db->join('divisi','dvs_id = krw_divisi','left');
        $this->db->join('jabatan', 'jbt_id = krw_jabatan');
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


    public function get_datatable($where=NULL,$order=NULL){
        
        $data = array();

        $result = $this->get(TRUE,$where,$order);
        
        echo json_encode(array(
            'data' => $result,
            'draw' => 0,
            'recordsTotal' => count($result)
        ));
        exit;
    }

    public function total($where=NULL){
        $result = $this->get(TRUE,is_exist($where) ? $where : NULL);

        return count($result);
    }

    public function insert($data){

        $this->db->insert('karyawan', $data);
		return $this->db->insert_id();
    }

    public function update($pk_val,$data){
        if(isset($data['krw_password']) && $data['krw_password'] !== "")
            $data['krw_password'] = md5($data['krw_password']);
        else
            unset($data['krw_password']);

        return $this->db->update('karyawan', $data, "krw_id = '$pk_val'");
    }

    public function delete_soft($pk_val){
        return $this->update($pk_val,array('krw_status ' => STATUS_DELETED));
    }

    public function delete_permanent($pk_val){
        return $this->db->delete('karyawan',array('krw_id' => $pk_val));
    }


}