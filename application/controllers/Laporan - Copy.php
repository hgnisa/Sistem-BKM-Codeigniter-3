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

	/**
	 * ADMIN CONTROLLER
	*/

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

			
			$admin = $this->input->post();
			$adminname = $admin['adminname'];

			## get kegiatan each date
			$data['date'] = $date ? $date : date("Y-m-d"); 
			$data['kegiatan'] = $this->getKegiatanByPekerjaan($data['date']);

			## get month name
			$getdate = explode("-", $data['date']);
			$data['month_name'] = $this->monthName($getdate[1]);
			$data['month_name_now'] = $this->monthName(date('m'));
			$data['admin_name'] = $adminname ? $adminname : "";

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

			# get kegiatan each date
			$data['kegiatan'] = $this->getKegiatanByRange($data['periode'], $data['month'], $data['year']);
			$data['totalKegiatan'] = $this->getKegiatanByDate($data['periode'], $data['month'], $data['year']);

			## get month name
			$data['month_name'] = $this->monthName($data['month']);
			$data['month_name_now'] = $this->monthName(date('m'));

			$this->load->view('admin-export-bulanan', $data);
		}

	/**
	 * MANDOR CONTROLLER
	*/

		public function recap()
		{
			## data user logged in
			$userdata = $this->session->userdata();
			$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

			## notification
			$data['notify'] = $this->reports->groupBy(array('keg_status' => 'p'), 'keg_date');

			## get all parameter
			$this->load->helper('url');
			$currentURL = current_url();
			$params = $_SERVER['QUERY_STRING'] ? $_SERVER['QUERY_STRING'] : "";
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
			$data['rekap'] = $this->getKegiatanRecap($data['month'], $data['year']);

			## data profile
			$this->load->view('mandor-rekap-laporan', $data);
		}

		public function report()
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

			## notification
			$data['notify'] = $this->reports->groupBy(array('keg_status' => 'p'), 'keg_date');

			## get kegiatan each date
			$data['date'] = $date ? $date : date("Y-m-d"); 
			$data['kegiatan'] = $this->getKegiatan($data['date']);

			## data profile
			$this->load->view('mandor-kegiatan', $data);
		}

		public function verify($date)
		{
			## data user logged in
			$userdata = $this->session->userdata();
			$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

			## notification
			$data['notify'] = $this->reports->groupBy(array('keg_status' => 'p'), 'keg_date');

			## get month
			$data['fulldate'] = $this->showDate($date);

			$data['rekap'] = $this->getKegiatan($date);
			$data['date'] = $date;

			$this->load->view('mandor-verifikasi', $data);
		}

		public function detailReport($id, $date = NULL, $page = NULL)
		{
			## data user logged in
			$userdata = $this->session->userdata();
			$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));
	
			## notification
			$data['notify'] = $this->reports->groupBy(array('keg_status' => 'p'), 'keg_date');
	
			## get full date
			$data['fulldate'] = $this->showDate($date);
	
			## get detail kegiatan
			$data['kegiatan'] = $this->findKegiatan($id);
			$data['from'] = $page;
			$data['date'] = $date;
	
			$this->load->view('mandor-detail-kegiatan', $data);
		}

		public function exportReport()
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

			$this->load->view('mandor-export-harian', $data);
		}

		public function attendance()
		{
			$this->load->helper('url');
			$currentURL = current_url();
			$params = $_SERVER['QUERY_STRING'];

			if($params){
				$getparams = explode("&", $params);
				$month = str_replace("m=", "", $getparams[0]);
				$year = str_replace("y=", "", $getparams[1]);
			}else{
				$month = NULL;
				$year = NULL;
			}
				
			## data user logged in
			$userdata = $this->session->userdata();
			$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

			## notification
			$data['notify'] = $this->reports->groupBy(array('keg_status' => 'p'), 'keg_date');

			## get absensi
			$data['month'] = $month ? $month : date('m'); 
			$data['year'] = $year ? $year : date('Y');
			$data['absensi'] = $this->getAbsensi($data['month'], $data['year']);

			## data profile
			$this->load->view('mandor-absensi', $data);
		}

		public function detailAttendance($id)
		{
			$data['profile'] = $this->reports->find(array('keg_id' => $id));
			$this->load->view('mandor-modal-kegiatan', $data);
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
			$date = array_unique($dates);

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

    public function getKegiatanByDate($periode, $month, $year, $return = [])
	{
        $t = date("t", strtotime($year."-".$month."-01"));

        $data = $this->getKegiatanByRange($periode, $month, $year);
        foreach($data as $val){
			
            ## get pekerjaan_id
            $pekerjaan_id = $this->jobs->find(['pekerjaan_name' => $val['pekerjaan_name']])->pekerjaan_id;
            if($periode == "start"){
				
                for($i=1;$i<=15;$i++) {
                    if($i < 10){
                        $date = $year."-".$month."-0".$i;
                    }else{
                        $date = $year."-".$month."-".$i;
                    }
    
                    $sum = $this->reports->sum('keg_volume', ['pekerjaan_id' => $pekerjaan_id, 'keg_date' => $date])[0]->keg_volume;
    
                    if($sum > 0){
                        $return[] = [
                            'date' => $i,
                            'amount' => $sum,
							'pekerjaan' => $val['pekerjaan_name']
                        ];
                    }
                }
            }else{
                for($i=16;$i<=$t;$i++) {
                    $date = $year."-".$month."-".$i;
        
                    $sum = $this->reports->sum('keg_volume', ['pekerjaan_id' => $pekerjaan_id, 'keg_date' => $date])[0]->keg_volume;

                    if($sum > 0){
                        $return[] = [
                            'date' => $i,
                            'amount' => $sum,
							'pekerjaan' => $val['pekerjaan_name']
                        ];
                    }
                }
            }
        }      
		return $return;
	}

	public function getKegiatanRecap($month, $year, $return = [])
	{
        $start = $year."-".$month."-01";
        $end = $year."-".$month."-".date("t", strtotime($year."-".$month."-01"));
		
		$data = $this->reports->groupBy(array('keg_date >=' => $start, 'keg_date <=' => $end), 'keg_date', 'keg_date', 'DESC');

        foreach($data as $r){
			## get all data by each keg_date
			unset($datas);
			$datas = $this->reports->findResult('*', array('keg_date' => $r->keg_date));

			print "<pre>";
			print_r($r);
			print "</pre>";
			
				unset($job);
				unset($kavling);
				$volume = 0;
				foreach($datas as $val){
					## jobs
					$job[] = $this->jobs->find(array('pekerjaan_id' =>$val->pekerjaan_id))->pekerjaan_name;

					## volumes
					$volume += $val->keg_volume;

					## kavlings
					$kavling[] = $val->kav_id;
				}

			## show jobs name
			$job = implode(", ", array_unique($job));

			## show kavlings name
			unset($kavname);
			foreach($kavling as $k){
				$kavname[] = $this->kavs->find(array('kav_id' => $k))->kav_name;
			}
			$kav = implode(", ", array_unique($kavname));

            $return[] = [
                'keg_date'     		=> $r->keg_date,
                'pekerjaan_name'    => $job,
                'keg_volume'   		=> $volume,
                'kav_name'  		=> $kav,
                'keg_status'   		=> $r->keg_status
            ];
            
        }
        return $return;
    }

	public function verifyReport($status, $date)
	{
		$update = $this->reports->verifyKegiatan($status, $date);
		redirect('mandor/recap');
	}

	public function getAbsensi($month, $year, $return = [])
	{
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
			$return[] = [
                'keg_id'     		=> $value->keg_id,
                'keg_date'     		=> $value->keg_date,
                'user_name'     	=> $bhl,
                'pekerjaan_name'   	=> $name,
                'keg_cuaca' 		=> $value->keg_cuaca,
                'kav_name' 			=> implode(", ", $kav),
                'keg_timestamp'   	=> $value->keg_timestamp,
                'keg_image'   		=> $value->keg_image
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

	