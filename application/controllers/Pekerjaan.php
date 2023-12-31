<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pekerjaan extends MY_Controller {

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
		$data['pekerjaan'] = $this->jobs->get();

		## load data to page
		$this->load->view('admin-pekerjaan', $data);
	}

	public function add()
	{
		## get user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		## load data to page
		$this->load->view('admin-pekerjaan-add', $data);
	}

	public function edit($id)
	{
		## data user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		## get detail kegiatan
		$data['pekerjaan'] = $this->jobs->find(['pekerjaan_id' => $id]);

		$this->load->view('admin-pekerjaan-edit', $data);
	}

	###############################################

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
				location="<?php print base_url();?>admin/job"; 
			</script> 
			<?php 
		}else{
			?>
				<script type="text/javascript">
					var errormsg = "<?php print $error?>";
					console.log(errormsg);
					localStorage.setItem('error', true);
					localStorage.setItem('errormsg', errormsg);					
					location="<?php print base_url();?>admin/job/add"; 
				</script> 
			<?php
		}
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
				location="<?php print base_url();?>admin/job"; 
			</script> 
			<?php 
		}else{
			?>
				<script type="text/javascript">
					var id = "<?php print $id?>";
					var errormsg = "<?php print $error?>";
					localStorage.setItem('error', true);
					localStorage.setItem('errormsg', errormsg);					
					location="<?php print base_url();?>admin/job/edit/"+id; 
				</script> 
			<?php
		}
	}

	public function deletePekerjaan($id){
		$delete = $this->jobs->delete($id);
		redirect('admin/job');
	}

}

	