<?php defined('BASEPATH') OR exit('No direct script access allowed');

//helper untuk check empty atau null
if (!function_exists('is_exist')){
    function is_exist($val){
        return (isset($val) || trim($val) !== '');
    }
}

//helper untuk check date range 
if(!function_exists('check_date_range')){
    function check_date_range($range1=NULL,$range2=NULL){
        //check apakah range1 berada pada range 2 termasuk partially sama overlapping
        //range1 adalah array dan range2 adalah array date string dengan 2 value

        $range_min = new DateTime(min($range1));
        $range_max = new DateTime(max($range1));

        $start = new DateTime(min($range2));
        $end = new DateTime(max($range2));

        if ($start >= $range_min && $end <= $range_max) {
            return OVERLAP; //overlaping
        } 
        else if($end > $range_min && $start < $range_max){
            return PARTIAL; //partial
        }
        else {
            return FREE; //free from range 2
        }
    }
}

//validation error in array
if (!function_exists('validation_errors_array')) {

    function validation_errors_array($prefix = '', $suffix = '') {
       if (FALSE === ($OBJ = & _get_validation_object())) {
         return '';
       }
 
       return $OBJ->error_array($prefix, $suffix);
    }
 }


//helper untuk check login atau belum
if (!function_exists('is_login')){
    function is_login(){
        $CI =& get_instance();
        $auth = $CI->session->userdata('login_data');
        if(!is_exist($auth)){
            $msg = array(
				'type' => 'error',
				'message' => 'Silahkan login terlebih dahulu'
			);
			$CI->session->set_flashdata('msg',$msg);
            redirect('');
        }
    }
}

//helper untuk check apakah login karyawan 
if (!function_exists('redir_karyawan')){
    function redir_karyawan($is_ajax=FALSE){
        $CI =& get_instance();
        $auth = $CI->session->userdata('login_data');

        $is_karyawan = true;
        if($auth['is_admin'] || $auth['login_as'] != KARYAWAN){
            if(isset($auth['data']->krw_level) && $auth['data']->krw_level != KARYAWAN){
                $is_karyawan = false;
            }
            $is_karyawan = false;
        }   
        if($is_karyawan){
             //jika admin atau login sebagai bukan karyawan redirect ke dashboard
            $msg = array(
				'type' => 'error',
				'message' => 'Silahkan login sebagai admin atau approver'
			);
            $CI->session->set_flashdata('msg',$msg);
            if($is_ajax){
                echo json_encode(array('status' => 'error', 'msg' => $msg['message']));
                exit;
            }
            else{
                redirect('dashboard');
            }
        }
    }
}

//helper untuk check apakah login sebagai karyawan
if(!function_exists('check_login_as')){
    function check_login_as(){
        $CI =& get_instance();
        $auth = $CI->session->userdata('login_data');

        if(is_exist($auth)){
            return $auth['login_as'];
        }else{
            return NULL;
        }
    }
}

//helper untuk mendapatkan login data
if(!function_exists('get_login_data')){
    function get_login_data(){
        $CI =& get_instance();
        $auth = $CI->session->userdata('login_data');
        if(is_exist($auth)){
            return $auth['data'];
        }else{
            return NULL;
        }
    }
}


//helper untuk check apakah login bukan sebagai karyawan
if(!function_exists('is_not_karyawan')){
    function is_not_karyawan(){
        $CI =& get_instance();
        $auth = $CI->session->userdata('login_data');

        if($auth['login_as'] != KARYAWAN){
            $msg = array(
				'type' => 'error',
				'message' => 'Silahkan login sebagai karyawan'
			);
			$CI->session->set_flashdata('msg',$msg);
            redirect('dashboard');
        }
    }
}

//helper untuk check apakah login sebagai bukan admin
if (!function_exists('redir_not_admin')){
    function redir_not_admin(){
        $CI =& get_instance();
        $auth = $CI->session->userdata('login_data');

        if(!$auth['is_admin']){
            $msg = array(
				'type' => 'error',
				'message' => 'Silahkan login sebagai admin atau approver'
			);
			$CI->session->set_flashdata('msg',$msg);
            redirect('dashboard');
        }
    }
}


