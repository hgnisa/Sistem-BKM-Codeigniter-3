<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

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
		## get user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		## get month and year
		$data['month'] = date('m'); 
		$data['year'] = date('Y');
		$data['monthname'] = $this->monthName($data['month']);

		## get daily report
		$data['harian'] = $this->getHarian($data['month'], $data['year']);

		## get monthly report
		$data['bulanan'] = $this->getBulanan($data['month'], $data['year']);
	
		## load data to page
		$this->load->view('admin-index', $data);
	}

	public function pekerjaan($action = NULL, $id = NULL)
	{ 
		if(!$action){
			## get user logged in
			$userdata = $this->session->userdata();
			$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

			## get data pekerjaan
			$data['pekerjaan'] = $this->getPekerjaan();

			## load data to page
			$this->load->view('admin-pekerjaan', $data);
		}elseif($action == "edit" and $id){
			## get user logged in
			$userdata = $this->session->userdata();
			$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

			## id pekerjaan
			$data['id'] = $id;
			$data['pekerjaan'] = $this->jobs->find(array('pekerjaan_id' => $id));

			$this->load->view('admin-pekerjaan-edit', $data);
		}elseif($action == "add"){
			## get user logged in
			$userdata = $this->session->userdata();
			$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

			$this->load->view('admin-pekerjaan-add', $data);
		}
		
	}

	public function kavling($action = NULL, $id = NULL)
	{ 
		if(!$action){
			## get user logged in
			$userdata = $this->session->userdata();
			$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

			## get data pekerjaan
			$data['kavling'] = $this->getKavling();

			## load data to page
			$this->load->view('admin-kavling', $data);
		}elseif($action == "edit" and $id){
			## get user logged in
			$userdata = $this->session->userdata();
			$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

			## id pekerjaan
			$data['id'] = $id;
			$data['kavling'] = $this->kavs->find(array('kav_id' => $id));

			$this->load->view('admin-kavling-edit', $data);
		}elseif($action == "add"){
			## get user logged in
			$userdata = $this->session->userdata();
			$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

			$this->load->view('admin-kavling-add', $data);
		}
		
	}

	public function harian()
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

	public function bulanan()
	{
		## data user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		$data['periode'] = 'awal';
		$data['month'] = date('m');
		$data['year'] = date('Y');

		
		## get monthly report
		$data['bulanan'] = $this->getBulanan($data['month'], $data['year']);

		## data profile
		$this->load->view('admin-laporan-bulanan', $data);
	}

	public function export()
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
		$data['kegiatan'] = $this->getExport($data['date']);

		## get month name
		$getdate = explode("-", $data['date']);
		$data['month_name'] = $this->monthName($getdate[1]);
		$data['month_name_now'] = $this->monthName(date('m'));

		$this->load->view('admin-export-harian', $data);

	}

	public function getExport($date, $return = [])
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

	##########################################################

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

	public function getHarian($month, $year, $return = [])
	{
        $month = $month ? $month : date('m');
        $year = $year ? $year : date('Y');

        $start = $year."-".$month."-01";
        $end = $year."-".$month."-".date("t", strtotime($year."-".$month."-01"));
		
		$data = $this->reports->groupBy(array('keg_date >=' => $start, 'keg_date <=' => $end), 'keg_date', 'keg_date', 'DESC');

        foreach($data as $r){
			## get all data by each keg_date
			unset($datas);
			$datas = $this->reports->findResult('*', array('keg_date' => $r->keg_date));

				unset($job);
				unset($kavling);
				$volume = 0;
				foreach($datas as $val){
					## jobs
					$job[] = $this->jobs->find(array('pekerjaan_id' =>$val->pekerjaan_id))->pekerjaan_name;

					## volumes
					$volume += $val->keg_volume;

					## kavlings
					// $kavling[] = $this->kavs->find(array('kav_id' => $val->kav_id));
					$kavling[] = $val->kav_id;
				}

			## show jobs name
			$job = implode(", ", array_unique($job));

			## show kavlings name
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

	public function detail($id, $date = NULL, $page = NULL)
	{
		## data user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		## get full date
		$data['fulldate'] = $this->showDate($date);

		## get detail kegiatan
		$data['kegiatan'] = $this->findKegiatan($id);
		$data['from'] = $page;
		$data['date'] = $date;

		$this->load->view('admin-detail-kegiatan', $data);
	}

	public function getBulanan($month, $year, $return = [])
	{
		$month = $month ? $month : date('m');
        $year = $year ? $year : date('Y');

        $start = $year."-".$month."-01";
        $end = $year."-".$month."-".date("t", strtotime($year."-".$month."-01"));


		
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

	public function getPekerjaan($return = [])
	{
		## get all pekerjaan
		$data = $this->jobs->get();


		foreach($data as $p){
			$return[] = [
				'pekerjaan_id'    => $p->pekerjaan_id,
				'pekerjaan_name'  => $p->pekerjaan_name
			];
		}
		return $return;
	}

	public function updatePekerjaan($id)
	{
		$error = '';
		$data = $this->input->post();

		$validate = $this->jobs->validateEdit($id, $data['pekerjaan_name']);
		if($validate){
			$error="Pekerjaan sudah ditambahkan";
        }

		if(!$error){
			$update = $this->jobs->update($data, $id);
			?>
			<script type="text/javascript">
				localStorage.setItem('error', false);
				location="<?php print base_url();?>admin/pekerjaan"; 
			</script> 
			<?php 
		}else{
			?>
				<script type="text/javascript">
					var id = "<?php print $id?>";
					var errormsg = "<?php print $error?>";
					localStorage.setItem('error', true);
					localStorage.setItem('errormsg', errormsg);					
					location="<?php print base_url();?>admin/pekerjaan/edit/"+id; 
				</script> 
			<?php
		}
	}

	public function addPekerjaan()
	{
		$error = '';
		$data = $this->input->post();

		$validate = $this->jobs->validate($data['pekerjaan_name']);
		if($validate){
			$error="Pekerjaan sudah ditambahkan";
        }


		if(!$error){
			$insert = $this->jobs->insert($data);
			?>
			<script type="text/javascript">
				localStorage.setItem('error', false);
				location="<?php print base_url();?>admin/pekerjaan"; 
			</script> 
			<?php 
		}else{
			?>
				<script type="text/javascript">
					var errormsg = "<?php print $error?>";
					console.log(errormsg);
					localStorage.setItem('error', true);
					localStorage.setItem('errormsg', errormsg);					
					location="<?php print base_url();?>admin/pekerjaan/add"; 
				</script> 
			<?php
		}
	}

	public function delPekerjaan($id)
	{
		$delete = $this->jobs->delete($id);
		redirect('admin/pekerjaan');
	}

	public function getKavling($return = [])
	{
		## get all pekerjaan
		$data = $this->kavs->get();


		foreach($data as $p){
			$return[] = [
				'kav_id'    	=> $p->kav_id,
				'kav_name'  	=> $p->kav_name,
				'kav_shm'  		=> $p->kav_shm,
				'kav_lokasi'  	=> $p->kav_lokasi,
				'kav_luas'  	=> $p->kav_luas
			];
		}
		return $return;
	}

	public function updateKavling($id)
	{
		$error = '';
		$data = $this->input->post();

		$validate = $this->kavs->validateUpdate($id, $data['kav_name'], $data['kav_shm']);
		if($validate){
			$error="Kavling sudah ada";
        }

		if(!$error){
			$update = $this->kavs->update($data, $id);
			?>
			<script type="text/javascript">
				localStorage.setItem('error', false);
				location="<?php print base_url();?>admin/kavling"; 
			</script> 
			<?php 
		}else{
			?>
				<script type="text/javascript">
					var id = "<?php print $id?>";
					var errormsg = "<?php print $error?>";
					localStorage.setItem('error', true);
					localStorage.setItem('errormsg', errormsg);					
					location="<?php print base_url();?>admin/kavling/edit/"+id; 
				</script> 
			<?php
		}
	}

	public function addKavling()
	{
		$error = '';
		$data = $this->input->post();

		$validate = $this->kavs->validate($data['kav_name'], $data['kav_shm']);
		if($validate){
			$error="Kavling sudah ditambahkan";
        }


		if(!$error){
			$insert = $this->kavs->insert($data);
			?>
			<script type="text/javascript">
				localStorage.setItem('error', false);
				location="<?php print base_url();?>admin/kavling"; 
			</script> 
			<?php 
		}else{
			?>
				<script type="text/javascript">
					var errormsg = "<?php print $error?>";
					console.log(errormsg);
					localStorage.setItem('error', true);
					localStorage.setItem('errormsg', errormsg);					
					location="<?php print base_url();?>admin/kavling/add"; 
				</script> 
			<?php
		}
	}

	public function delKavling($id)
	{
		$delete = $this->kavs->delete($id);
		redirect('admin/kavling');
	}

	public function getKegiatan($date, $return = [])
	{
		$date = $date ? $date : date('Y-m-d');

		$data = $this->reports->findOrder('', array('keg_date' => $date), 'keg_date', 'DESC');
		foreach($data as $value){
            ## get job name
			$name = $this->jobs->find(array('pekerjaan_id' => $value->pekerjaan_id));
			$name = $name->pekerjaan_name;

			## get job name
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

	// public function pengguna()
	// {
	// 	## data user logged in
	// 	$userdata = $this->session->userdata();
	// 	$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));
		
	// 	## get monthly report
	// 	$data['pengguna'] =$this->getPengguna();

	// 	## data profile
	// 	$this->load->view('admin-pengguna', $data);
	// }

	public function getPengguna($return = [])
	{
		## get all pekerjaan
		$data = $this->users->get();


		foreach($data as $p){
			$return[] = [
				'user_id'    => $p->user_id,
				'user_name'  => $p->user_name,
				'user_username'  => $p->user_username,
				'user_type'  => $p->user_type,
				'user_profile'  => $p->user_profile,
				'user_lastlogin'  => $p->user_lastlogin
			];
		}
		return $return;
	}
























































	// public function pengguna(){
		
	// }
}

	