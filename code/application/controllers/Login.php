<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model(array('m_karyawan','m_admin'));

	}

	public function index()
	{
		$this->site_info->set_page_title('Login');

		$this->load->view('login/index');
	}

	public function auth()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'Username', 'required|max_length[50]');
		$this->form_validation->set_rules('password', 'Password', 'required|max_length[30]');

		if($this->form_validation->run()){

			$login_result = $this->login(	
										$this->input->post('username'),
										$this->input->post('password')
										);
			$msg = array(
				'type' => $login_result->success ? 'success' : 'error',
				'message' => $login_result->message
			);
			$this->session->set_flashdata('msg',$msg);

			$sessdata = array();

			if($login_result->success){
				$sessdata['login_data'] = array(
												'is_admin' => $login_result->isAdmin,
												'login_as' => $login_result->isAdmin ? ADMIN : intval($login_result->data->krw_level),
												'data' => $login_result->data
				);
				$this->session->set_userdata($sessdata);

				redirect('dashboard');
			}else
				redirect('');
		}

	}

	private function login($username, $password){
		$is_admin = FALSE;
		$result = new stdClass();

		//coba login ke karyawan dulu 
		$dataKaryawan = array(
			'krw_username = "'.$username.'" ',
			'krw_password = "'.md5($password).'" ',
			'krw_status = '.STATUS_ACTIVE
		);

		$user_login = $this->m_karyawan->get(FALSE,implode(" AND ",$dataKaryawan),NULL,1);

		if($user_login == NULL){	//jika data tidak ada pada karyawan , coba di admin
			$dataAdmin = array(
				'adm_username = "'.$username.'" ',
				'adm_password = "'.md5($password).'"'
			);

			$user_login = $this->m_admin->get(FALSE,implode(" AND ",$dataAdmin),NULL,1);

			if($user_login != NULL)
				$is_admin = TRUE;
		}

		if($user_login == NULL){
			$result->success = FALSE;
			$result->message = 'Username atau Password Salah';
			return $result;
		}else{
			$result->success = TRUE;
			$result->message = 'Sukses login';
			$result->isAdmin = $is_admin;
			$result->data = $user_login;
			return $result;
		}
	}

	public function logout(){
		//$this->session->sess_destroy();
		$this->session->unset_userdata('login_data');
		$msg = array(
			'type' => 'success' ,
			'message' => 'Sukses Logout'
		);
		$this->session->set_flashdata('msg',$msg);
		redirect('');
	}
}
