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
		$data['notify'] = $this->reports->groupBy(array('keg_status' => 'p'), 'keg_date');	

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
		$data['notify'] = $this->reports->groupBy(array('keg_status' => 'p'), 'keg_date');

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
            ## get job name
			$name = $this->jobs->find(array('pekerjaan_id' => $r->pekerjaan_id));
			$name = $name->pekerjaan_name;

            ## get total volume
			$volume = $this->reports->sum('keg_volume', array('keg_date' => $r->keg_date));
			$volume = $volume[0]->keg_volume;

            ## get all kavling
			unset($kavlings);
			$kavlings = $this->reports->join('kav_name', 'kavling', 'kegiatan.kav_id = kavling.kav_id', array('keg_date' => $r->keg_date));

            foreach($kavlings as $k){
                $kav[] = $k->kav_name;
            }

			$kav = array_unique($kav);
            $return[] = [
                'keg_date'     		=> $r->keg_date,
                'pekerjaan_name'    => $name,
                'keg_volume'   		=> $volume,
                'kav_name'  		=> implode(", ", $kav),
                'keg_status'   		=> $r->keg_status
            ];
            
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
