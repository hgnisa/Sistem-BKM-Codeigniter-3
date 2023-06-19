<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengguna extends MY_Controller {

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
	}

	public function index($action = NULL)
	{
		var_dump("sasasas"); exit;
		if(!$action){
			## data user logged in
			$userdata = $this->session->userdata();
			$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));
			
			## get monthly report
			$data['pengguna'] =$this->getPengguna();

			## data profile
			$this->load->view('admin-pengguna', $data);
		}elseif($action == "add"){
			## data user logged in
			$userdata = $this->session->userdata();
			$data['users'] = $this->users->findUser(array('user_id' => $userdata["id"]));

			## get action
			$data['action'] = 'add';

			## data profile
			$this->load->view('admin-pengguna-add', $data);
		}
		
	}


	##########################################################


	public function getPengguna($return = [])
	{
		## get all user
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

	public function addPengguna()
	{
		$error = '';
		$data = $this->input->post();

		$name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmpass = $_POST['confirmpass'];
        $type = $_POST['type'];
        $mandorimage=$_FILES["mandorimage"];

		if($type == "mandor" or $type == "bhl"){
            if($image['name']){
                $fname=$image['name'];
                $ftype=$image['type'];

                if($fname){
                    if (!in_array($ftype,$allowedType)){
                        $error="Gambar yang dimasukkan tidak valid. Silakan coba upload file lain.";
                    }else{
                        $target_dir = base_url()."img/profile/";
                        $target_file = $target_dir . basename($fname);

                        $uploadOk = 1;
                        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                        
                        $check = getimagesize($image["tmp_name"]);

                        if($check == false) {
                            $error = "Gambar yang dimasukkan tidak valid. Silahkan coba upload file lain.";
                        }else{
                        }
                    }
                }
            }
        }

		$validate = $this->users->validateAdd($username, $type);
		if($validate){
			$error="Username sudah digunakan. Silakan coba username lain.";
        }

		if($password != $confirmpass){
            $error="Password tidak cocok. Silakan coba lagi.";
        }

		if(!$error){
			$fname = "";
            if($image['name']){
                $fname=$image['name'];
                $fname=rand()."-".$fname;
                $target_dir = base_url()."img/profile/";
                $target_file = $target_dir . basename($fname);
                $insertimg = $user->upload_profile($image['tmp_name'], $target_file);
            }
			if($image['name']){
                $config['upload_path']= './img/profile';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size'] = 2048;
				$config['max_width'] = 2048;
				$config['max_height'] = 2048;
				$config['encrypt_name'] = TRUE;

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('mandorimage')) {
					$error="Update dan upload gambar profile gagal.";
				} else {
					$dataimg = $this->upload->data();
				}
            }
			
			$data[
				'user_name' => $name,
				'user_username' => $username,
				'user_password' => $password,
				'user_type' => $type,
				'user_profile' =>	$
				'user_lastlogin' =>
			]
			$insert = $this->users->insert($data);
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
}
