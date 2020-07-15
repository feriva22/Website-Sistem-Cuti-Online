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

	public function google(){
		if(!$this->input->is_ajax_request()) show_404();

		$id_token = $this->input->post("idtoken");

		if($id_token != NULL || $id_token != ""){
				
			// Include two files from google-php-client library in controller
			require_once APPPATH . "libraries/Google/autoload.php";
			include_once APPPATH . "libraries/Google/Client.php";
			include_once APPPATH . "libraries/Google/Service/Oauth2.php";

			// Create Client Request to access Google API
			$client = new Google_Client();
			$client->setApplicationName("Sistem Cuti UISI Oauth Login");
			$client->setClientId(GOOGLE_CLIENT_ID);
			$client->setClientSecret(GOOGLE_CLIENT_SECRET);
			$client->setRedirectUri(GOOGLE_REDIR_URI);
			$client->setDeveloperKey(GOOGLE_API_KEY);
			$client->addScope("https://www.googleapis.com/auth/userinfo.email");

			// Send Client Request
			$objOAuthService = new Google_Service_Oauth2($client);

			//verify the id token
			$decoded_token = $client->verifyIdToken($id_token);
			
			if($decoded_token){ //if valid id token
				//$payload is parsed jwt token to information account
				$payload = $decoded_token->getAttributes()['payload'];
				$userid = $payload['sub'];
				$email = $payload['email'];

				$krw_filter = array(
					'krw_email = "'.$email.'"',
					'krw_status = '.STATUS_ACTIVE
				);

				//check on my database for current user
				$user_login = $this->m_karyawan->get(FALSE,implode(" AND ",$krw_filter),NULL,1);

				if($user_login != NULL){
					$sessdata['login_data'] = array(
						'is_admin' => FALSE,
						'login_as' => $user_login->krw_level,
						'data' => $user_login
					);

					$this->session->set_userdata($sessdata);

					echo json_encode(array(
						'status' => 'ok',
						'message' => 'Sukses Login','redir' => base_url()."dashboard/"
					));
					exit;
				}
				else{
					echo json_encode(array(
						'status' => 'error',
						'message' => 'User tidak ada di sistem'
					));
					exit;
				}
			}
			else {
				//invalid id token
				echo json_encode(array(
					'status' => 'error',
					'message' => 'Invalid id token'
				));
				exit;
			}
		}
		else{
			//invalid id token
			echo json_encode(array(
				'status' => 'error',
				'message' => 'Invalid id token'
			));
			exit;
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
