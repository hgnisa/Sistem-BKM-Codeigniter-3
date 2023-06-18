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

	// public function getLapHarian($date, $result = [])
	// {

	// }

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
}

	