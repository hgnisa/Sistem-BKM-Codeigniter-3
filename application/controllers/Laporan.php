<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends MY_Controller {

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

	public function daily()
	{
		$this->load->helper('url');
		$currentURL = current_url();
		$params = $_SERVER['QUERY_STRING'];
		
		if($params){
			$date = str_replace("date=", "", $params);
		}else{
			$date = NULL;
		}

		## data user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		## get kegiatan each date
		$data['date'] = $date ? $date : date("Y-m-d"); 
		$data['kegiatan'] = $this->getKegiatan($data['date']);

		## data profile
		$this->load->view('admin-laporan-harian', $data);
	}

    public function exportDaily()
    {
        $this->load->helper('url');
		$currentURL = current_url();
		$params = $_SERVER['QUERY_STRING'];
		
		if($params){
			$date = str_replace("date=", "", $params);
		}else{
			$date = NULL;
		}

		## get kegiatan each date
		$data['date'] = $date ? $date : date("Y-m-d"); 
		$data['kegiatan'] = $this->getKegiatanByPekerjaan($data['date']);

		## get month name
		$getdate = explode("-", $data['date']);
		$data['month_name'] = $this->monthName($getdate[1]);
		$data['month_name_now'] = $this->monthName(date('m'));

		$this->load->view('admin-export-harian', $data);
    }

    public function detail($id, $date)
    {
        $this->load->helper('url');
		$currentURL = current_url();
		$params = $_SERVER['QUERY_STRING'];

        ## data user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		## get full date
		$data['fulldate'] = $this->showDate($date);

		## get detail kegiatan
		$data['kegiatan'] = $this->findKegiatan($id);

		$data['from'] = $params ? $params : '';
		$data['date'] = $date;

		$this->load->view('admin-detail-kegiatan', $data);
    }

    public function monthly($periode = NULL)
    {
        ## data user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

        ## get all parameter
        $this->load->helper('url');
		$currentURL = current_url();
		$params = $_SERVER['QUERY_STRING'] ? $_SERVER['QUERY_STRING'] : "";
		$data['periode'] = $periode ? $periode : 'start';
		$data['param'] = $params;

        if($params){
            $getparam = explode("&", $params);
			$month = str_replace("m=", "", $getparam[0]);
			$year = str_replace("y=", "", $getparam[1]);
		}else{
			$month = NULL;
			$year = NULL;
		}

		$data['month'] = $month ? $month : date('m');
		$data['year'] = $year ? $year : date('Y');

		## get monthly report
		$data['bulanan'] = $this->getKegiatanByRange($data['periode'], $data['month'], $data['year']);

		## data profile
		$this->load->view('admin-laporan-bulanan', $data);
    }

    public function exportMonthly()
    {
        $this->load->helper('url');
		$currentURL = current_url();
		$params = $_SERVER['QUERY_STRING'] ? $_SERVER['QUERY_STRING'] : "";

        if($params){
            $getparam = explode("&", $params);
			$data['periode'] = str_replace("periode=", "", $getparam[0]);
			$data['month'] = str_replace("month=", "", $getparam[1]);
			$data['year'] = str_replace("year=", "", $getparam[2]);
        }else{
            $data['periode'] = 'awal';
            $data['month'] = date('m');
            $data['year'] = date('Y');
        }
    }

	
	###############################################

	public function getKegiatan($date, $return = [])
    {
		$date = $date ? $date : date('Y-m-d');

		$data = $this->reports->findOrder('', array('keg_date' => $date), 'keg_date', 'DESC');
        
		foreach($data as $value){
            ## get job name
			$name = $this->jobs->find(array('pekerjaan_id' => $value->pekerjaan_id));
			$name = $name->pekerjaan_name;

			## get bhl name
			$bhl = $this->users->find(array('user_id' => $value->user_id));
			$bhl = $bhl->user_name;

            ## get  kavling
			$kavlings = $this->kavs->find(array('kav_id' => $value->kav_id));

			$return[] = [
                'keg_id'     		=> $value->keg_id,
				'pekerjaan_name'   	=> $name,
                'user_name'     	=> $bhl,
                'keg_volume'		=> $value->keg_volume,
                'keg_satuan'		=> strtoupper($value->keg_satuan),
                'keg_cuaca' 		=> $value->keg_cuaca,
                'kav_name' 			=> $kavlings->kav_name,
            ];
        }
		return $return;
	}

	public function getKegiatanByPekerjaan($date, $return = [])
	{
		$data = $this->reports->groupBy(array('keg_date' => $date), 'pekerjaan_id');

		foreach($data as $e){
            ## get job name
			$job = $this->jobs->find(array('pekerjaan_id' => $e->pekerjaan_id));
			$job = $job->pekerjaan_name;

			## get bhl name
			$bhl = $this->users->find(array('user_id' => $e->user_id));
			$bhl = $bhl->user_name;

            ## get total volume
			$volume = $this->reports->sum('keg_volume', array('pekerjaan_id' => $e->pekerjaan_id, 'keg_date' => $e->keg_date));
			$volume = $volume[0]->keg_volume;

            ## get all kavling
			unset($kavlings);
			unset($kav);
			$kavlings = $this->reports->join('kav_name', 'kavling', 'kegiatan.kav_id = kavling.kav_id', array('pekerjaan_id' => $e->pekerjaan_id, 'keg_date' => $e->keg_date));

            foreach($kavlings as $k){
                $kav[] = $k->kav_name;
            }

			$kav = array_unique($kav);
            $return[] = [
                'date'     => $e->keg_date,
                'job'     => $job,
                'bhl'     => $bhl,
                'volume'   => $volume,
                'satuan'   => strtoupper($e->keg_satuan),
                'kavling'  => implode(", ", $kav),
            ];   
        }
		return $return;
	}

    public function findKegiatan($id, $return = [])
	{
		$val = $this->reports->find(array('keg_id' => $id));

		## get job name
		$job = $this->jobs->find(array('pekerjaan_id' => $val->pekerjaan_id));
		$job = $job->pekerjaan_name;

		## get bhl name
		$bhl = $this->users->find(array('user_id' => $val->user_id));
		$bhl = $bhl->user_name;

		## get kavling name
		$kav = $this->kavs->find(array('kav_id' => $val->kav_id));

		$return = [
			'keg_id'     		=> $val->keg_id,
			'user_name'     	=> $bhl,
			'pekerjaan_name'   	=> $job,
			'kav_name' 			=> $kav->kav_name,
			'keg_date'			=> $val->keg_date,
			'keg_timestamp'		=> $val->keg_timestamp,
			'keg_volume'		=> $val->keg_volume,
			'keg_satuan'		=> strtoupper($val->keg_satuan),
			'keg_cuaca' 		=> $val->keg_cuaca,
			'keg_image' 		=> $val->keg_image,
			'keg_unit' 			=> $val->keg_unit,
			'keg_keterangan' 	=> $val->keg_keterangan,
			'keg_status' 		=> $val->keg_status,
		];  
		return $return;
	}

    public function getKegiatanByRange($periode, $month, $year, $return = [])
	{
        if($periode == "start"){
            $start = $year."-".$month."-01";
            $end = $year."-".$month."-15";
        }else{
            $start = $year."-".$month."-16";
            $end = $year."-".$month."-".date("t", strtotime($year."-".$month."-01"));
        }

		$data = $this->reports->groupBy(array('keg_date >=' => $start, 'keg_date <=' => $end), 'pekerjaan_id', 'pekerjaan_id', 'ASC');
		foreach($data as $r){
			## get all data by each pekerjaan_id
			unset($datas);
			$datas = $this->reports->findResult('*', array('pekerjaan_id' => $r->pekerjaan_id, 'keg_date >=' => $start, 'keg_date <=' => $end));

				unset($dates);
				unset($satuan);
				$volume = 0;
				foreach($datas as $val){
					## dates
					$dates[] = date("d", $val->keg_timestamp);

					## volumes
					$volume += $val->keg_volume;

					## satuan
					$satuan[] = $val->keg_satuan;
				}

			## show job name
			$job = $this->jobs->find(array('pekerjaan_id' => $r->pekerjaan_id))->pekerjaan_name;

			## show date
			$date = implode(", ", array_unique($dates));

			## show satuan
			$satuan = implode(", ", array_unique(array_map("strtoupper", $satuan)));

			$return[] = [
				'pekerjaan_name'    => $job,
				'keg_date'   		=> $date,
				'keg_volume'  		=> $volume,
				'keg_satuan'		=> $satuan
			];

		}
		return $return;
	}


    #################################################

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

    public function showDate($date, $return = NULL)
	{
		$getdate = explode("-", $date);

		if($getdate[1] == '1'){
            $month = "Januari";
        }elseif($getdate[1] == '2'){
            $month = "Fabruari";
        }elseif($getdate[1] == '3'){
            $month = "Maret";
        }elseif($getdate[1] == '4'){
            $month = "April";
        }elseif($getdate[1] == '5'){
            $month = "Mei";
        }elseif($getdate[1] == '6'){
            $month = "Juni";
        }elseif($getdate[1] == '7'){
            $month = "Juli";
        }elseif($getdate[1] == '8'){
            $month = "Agustus";
        }elseif($getdate[1] == '9'){
            $month = "September";
        }elseif($getdate[1] == '10'){
            $month = "Oktober";
        }elseif($getdate[1] == '11'){
            $month = "November";
        }elseif($getdate[1] == '12'){
            $month = "Desember";
        }

		$return = $getdate[2]." ".$month." ".$getdate[0];
		return $return;
	}
}

	