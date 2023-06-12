<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mandor extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */

	function __construct(){
        parent::__construct();
		$this->load->model('user', 'users');
		$this->load->model('kegiatan', 'reports');
		$this->load->model('pekerjaan', 'reports');
	}

	public function index()
	{
		## data user logged in
		$userdata = $this->session->userdata();
		$userarr = [
			'user_id' => $userdata["id"]
		];
		$data['users'] = $this->users->find($userarr);

		## data report this month
		$data['month'] = date('m'); 
		$data['year'] = date('Y');
		$data['month_name'] = $this->reports->month($data['month']);
		$data['rekap'] = $this->reports->getrekap($data['month'], $data['year']);

		## date detail reportss
		$data['detail_rekap'] = $this->reports->showrekap($data['month'], $data['year']);
		exit;	

		## notification
		$data['notify'] = $this->reports->getnotify();		

		$this->load->view('mandor-index', $data);




	}
}
