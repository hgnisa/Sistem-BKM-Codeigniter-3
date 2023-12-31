<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('userModel', 'users');
		$this->load->library('session');
    }

	public function login()
	{
		$this->load->view('login');
    }

    public function process_login()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$type = $this->input->post('type');

		$validate = $this->users->validate($username, $password, $type);
		if ($validate) {
			$username 	 = $validate->user_username;
			$roles 		 = $validate->user_type;
			$sessionData = array(
				'id'		=> $validate->user_id,
				'username' 	=> $username,
				'roles' 	=> $type,
				'logged_in' => TRUE,
				'last_login' => time()
			);

			$update = $this->users->update(array('user_lastlogin' => $sessionData["last_login"]), $sessionData["id"]);

            $this->session->set_userdata($sessionData);
			
			setcookie("cook_id",$validate->user_id,0,"/");
            setcookie("cook_type",$type,0,"/");

			if($type == 'admin'){
				redirect('admin');
			}elseif($type == 'mandor'){
				redirect('mandor');
			}
		} else {
			print $this->session->set_flashdata("error-message", "Periksa kembali username atau password anda");
			redirect('auth/login');
		}
    }

	public function logout()
	{
		$roles = $this->session->userdata('roles');
		$this->session->sess_destroy();

		setcookie("cook_id","",time()-1,"/");
		setcookie("cook_username","",time()-1,"/");

		redirect('auth/login');
	}
}
