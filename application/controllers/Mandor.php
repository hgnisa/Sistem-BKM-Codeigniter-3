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
		$this->load->model('userModel', 'users');
		$this->load->model('kegiatanModel', 'reports');
		$this->load->model('pekerjaanModel', 'jobs');
		$this->load->model('kavlingModel', 'kavs');
	}

	public function index()
	{
		## data user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		## notification
		$data['notify'] = $this->reports->groupBy(array('keg_status' => 'p'), 'keg_date, user_id');	

		## data report this month
		$data['month'] = date('m'); 
		$data['year'] = date('Y');
		$data['month_name'] = $this->monthName($data['month']);
		$data['rekap'] = $this->getRekap($data['month'], $data['year']);
	
		## data index
		$this->load->view('mandor-index', $data);
	}

	public function profile($id)
	{
		## data user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		## notification
		$data['notify'] = $this->reports->groupBy(array('keg_status' => 'p'), 'keg_date, user_id');	

		## data profile
		$this->load->view('mandor-edit-profile', $data);
	}

 	public function getRekap($month, $year, $return = [])
	{
		$month = $month ? $month : date('m');
        $year = $year ? $year : date('Y');

        $start = $year."-".$month."-01";
        $end = $year."-".$month."-".date("t", strtotime($year."-".$month."-01"));

		$data = $this->reports->groupBy(array('keg_date >=' => $start, 'keg_date <=' => $end), 'keg_date', 'keg_date', 'DESC');

        foreach($data as $r){
			## get all user by date
			$getusersbydate = $this->reports->groupBy(array('keg_date =' => $r->keg_date), 'user_id', 'user_id', 'ASC');
			foreach($getusersbydate as $vals){
				unset($datas);	
				$datas = $this->reports->findResult('*', array('keg_date' => $r->keg_date, 'user_id' => $vals->user_id));

				unset($jobs);
				unset($kavling);
				unset($kavling);
				$volume = 0;
				foreach($datas as $val){
					## jobs
					$jobs[] = $this->jobs->find(array('pekerjaan_id' =>$val->pekerjaan_id))->pekerjaan_name;
	
					## volumes
					$volume += $val->keg_volume;
	
					## kavlings
					$kavling[] = $val->kav_id;
				}	
				
				## show jobs name
				$jobs = implode(", ", array_unique($jobs));

				## show kavlings name
				unset($kavname);
				foreach($kavling as $k){
					$kavname[] = $this->kavs->find(array('kav_id' => $k))->kav_name;
				}
				$kav = implode(", ", array_unique($kavname));

				$return[$r->keg_date][] = [
					'user_id'			=> $datas[0]->user_id,
					'user_name'     	=> $this->users->find(array('user_id' => $datas[0]->user_id))->user_name,
					'keg_date'     		=> $datas[0]->keg_date,
					'pekerjaan_name'	=> $jobs,
					'keg_volume'   		=> $volume,
					'kav_name'  		=> $kav,
					'keg_status'   		=> $datas[0]->keg_status
				];
			}
        }

        return $return;
    }
	
	public function monthName($month, $monthname = NULL)
	{
		if($month == '1'){
            $monthname = "Januari";
        }elseif($month == '2'){
            $monthname = "Fabruari";
        }elseif($month == '3'){
            $monthname = "Maret";
        }elseif($month == '4'){
            $monthname = "April";
        }elseif($month == '5'){
            $monthname = "Mei";
        }elseif($month == '6'){
            $monthname = "Juni";
        }elseif($month == '7'){
            $monthname = "Juli";
        }elseif($month == '8'){
            $monthname = "Agustus";
        }elseif($month == '9'){
            $monthname = "September";
        }elseif($month == '10'){
            $monthname = "Oktober";
        }elseif($month == '11'){
            $monthname = "November";
        }elseif($month == '12'){
            $monthname = "Desember";
        }

		return $monthname;
	}
}
