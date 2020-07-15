<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Site_info {

    private $CI;
	  private $_site_title = '';
    private $_page_title = '';
    
    public function __construct(){
        $this->CI =& get_instance();
        $this->_site_title = 'Sistem Cuti UISI';
        $this->_page_title = '';
    }

    public function get_page_title($padding_default = FALSE){
		if($this->_page_title == '') return $this->_site_title;
		return $this->_page_title . ($padding_default ? ' - ' . $this->_site_title : '');
    }
    
    public function set_page_title($title){
		$this->_page_title = $title;
	}
}