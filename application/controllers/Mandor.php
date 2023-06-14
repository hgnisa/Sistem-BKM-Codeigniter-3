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
	}

	public function index()
	{
		## data user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		## notification
		$data['notify'] = $this->getnotify();	

		## data report this month
		$data['month'] = date('m'); 
		$data['year'] = date('Y');
		$data['month_name'] = $this->monthName($data['month']);
		$data['rekap'] = $this->getRekap($data['month'], $data['year']);

		## date detail reportss
		$data['detail_rekap'] = $this->showRekap($data['month'], $data['year']);
	
		## data index
		$this->load->view('mandor-index', $data);
	}

	public function profile()
	{
		## data user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		## notification
		$data['notify'] = $this->getnotify();

		## data profile
		$this->load->view('mandor-edit-profile', $data);
	}

	public function absensi()
	{
		## data user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		## notification
		$data['notify'] = $this->getnotify();

		## get absensi
		$data['month'] = date('m'); 
		$data['year'] = date('Y');
		$data['absensi'] = $this->getAbsensi($data['month'], $data['year']);

		## data profile
		$this->load->view('mandor-absensi', $data);
	}

	###############################################################

	public function getNotify($data = [])
	{		
		$data = $this->reports->groupBy(array('keg_status' => 'p'), 'keg_date');
        return $data;
	}

	public function monthName($data) 
	{
        if($data == '1'){
            $result = "Januari";
        }elseif($data == '2'){
            $result = "Fabruari";
        }elseif($data == '3'){
            $result = "Maret";
        }elseif($data == '4'){
            $result = "April";
        }elseif($data == '5'){
            $result = "Mei";
        }elseif($data == '6'){
            $result = "Juni";
        }elseif($data == '7'){
            $result = "Juli";
        }elseif($data == '8'){
            $result = "Agustus";
        }elseif($data == '9'){
            $result = "September";
        }elseif($data == '10'){
            $result = "Oktober";
        }elseif($data == '11'){
            $result = "November";
        }elseif($data == '12'){
            $result = "Desember";
        }
        return $result;
	}

	public function getRekap($month, $year, $data = [])
	{
        $month = $month ? $month : date('m');
        $year = $year ? $year : date('Y');

        $start = $year."-".$month."-01";
        $end = $year."-".$month."-".date("t", strtotime($year."-".$month."-01"));
		
		$data = $this->reports->groupBy(array('keg_date >=' => $start, 'keg_date <=' => $end), 'keg_date', 'keg_date', 'DESC');

        return $data;
    }
 
	public function showRekap($month, $year)
	{
        $rekap = $this->getrekap($month, $year, '');
		
        foreach($rekap as $r){
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
            $datarekap[] = [
                'date'     => $r->keg_date,
                'name'     => $name,
                'volume'   => $volume,
                'kavling'  => implode(", ", $kav),
                'status'   => $r->keg_status
            ];
            
        }
        return $datarekap;
    }

	public function getAbsensi($month, $year, $data = []){
		$month = $month ? $month : date('m');
        $year = $year ? $year : date('Y');

        $start = $year."-".$month."-01";
        $end = $year."-".$month."-".date("t", strtotime($year."-".$month."-01"));
		
		$data = $this->reports->findOrder('*', array('keg_date >=' => $start, 'keg_date <=' => $end), 'keg_date', 'DESC');

		foreach($data as $value){
            ## get job name
			$name = $this->jobs->find(array('pekerjaan_id' => $value->pekerjaan_id));
			$name = $name->pekerjaan_name;

			## get job name
			$bhl = $this->users->find(array('user_id' => $value->user_id));
			$bhl = $bhl->user_name;

            ## get total volume
			$volume = $this->reports->sum('keg_volume', array('keg_date' => $value->keg_date));
			$volume = $volume[0]->keg_volume;

            ## get all kavling
			unset($kavlings);
			$kavlings = $this->reports->join('kav_name', 'kavling', 'kegiatan.kav_id = kavling.kav_id', array('keg_date' => $value->keg_date));

            foreach($kavlings as $k){
                $kav[] = $k->kav_name;
            }

			$kav = array_unique($kav);
			$databsensi[] = [
                'id'     		=> $value->keg_id,
                'date'     		=> $value->keg_date,
                'name'     		=> $bhl,
                'pekerjaan'   	=> $name,
                'cuaca' 		=> $value->keg_cuaca,
                'kavling' 		=> implode(", ", $kav),
                'waktu'   		=> $value->keg_timestamp,
                'image'   		=> $value->keg_image
            ];
            
        }
        return $databsensi;
	}

	// public function showAbsensi($month, $year)
	// {
    //     $absensi = $this->getAbsensi($month, $year, '');
		
    //     foreach($rekap as $r){
    //         ## get job name
	// 		$name = $this->jobs->find(array('pekerjaan_id' => $r->pekerjaan_id));
	// 		$name = $name->pekerjaan_name;

    //         ## get total volume
	// 		$volume = $this->reports->sum('keg_volume', array('keg_date' => $r->keg_date));
	// 		$volume = $volume[0]->keg_volume;

    //         ## get all kavling
	// 		unset($kavlings);
	// 		$kavlings = $this->reports->join('kav_name', 'kavling', 'kegiatan.kav_id = kavling.kav_id', array('keg_date' => $r->keg_date));

    //         foreach($kavlings as $k){
    //             $kav[] = $k->kav_name;
    //         }

	// 		$kav = array_unique($kav);
    //         $datarekap[] = [
    //             'date'     => $r->keg_date,
    //             'name'     => $name,
    //             'volume'   => $volume,
    //             'kavling'  => implode(", ", $kav),
    //             'status'   => $r->keg_status
    //         ];
            
    //     }
    //     return $datarekap;
    // }
}
