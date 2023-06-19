<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kavling extends MY_Controller {

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

		## get data pekerjaan
		$data['kavling'] = $this->kavs->get();

		## load data to page
		$this->load->view('admin-kavling', $data);
	}

	public function add()
	{
		## get user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		## load data to page
		$this->load->view('admin-kavling-add', $data);
	}

	public function edit($id)
	{
		## data user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		## get detail kegiatan
		$data['kavling'] = $this->kavs->find(['kav_id' => $id]);

		$this->load->view('admin-kavling-edit', $data);
	}

	###############################################

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
				location="<?php print base_url();?>admin/kav"; 
			</script> 
			<?php 
		}else{
			?>
				<script type="text/javascript">
					var errormsg = "<?php print $error?>";
					console.log(errormsg);
					localStorage.setItem('error', true);
					localStorage.setItem('errormsg', errormsg);					
					location="<?php print base_url();?>admin/kav/add"; 
				</script> 
			<?php
		}
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
				location="<?php print base_url();?>admin/kav"; 
			</script> 
			<?php 
		}else{
			?>
				<script type="text/javascript">
					var id = "<?php print $id?>";
					var errormsg = "<?php print $error?>";
					localStorage.setItem('error', true);
					localStorage.setItem('errormsg', errormsg);					
					location="<?php print base_url();?>admin/kav/edit/"+id; 
				</script> 
			<?php
		}
	}

	public function deleteKavling($id){
		$delete = $this->kavs->delete($id);
		redirect('admin/kav');
	}

}

	