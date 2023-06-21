<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

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

	public function index()
	{
		## data user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));
		
		## get monthly report
		$data['pengguna'] = $this->users->get();


		## data profile
		$this->load->view('admin-pengguna', $data);
	}

	public function add()
	{
		## data user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		## get action
		$data['action'] = 'add';

		## data profile
		$this->load->view('admin-pengguna-add', $data);
	}

	public function profile($id)
	{
		## data user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		## get detail kegiatan
		$data['user'] = $this->users->find(['user_id' => $id]);


		$this->load->view('admin-modal-profile', $data);
	}

	public function edit($id)
	{
		## data user logged in
		$userdata = $this->session->userdata();
		$data['users'] = $this->users->find(array('user_id' => $userdata["id"]));

		## get detail kegiatan
		$data['user'] = $this->users->find(['user_id' => $id]);

		$this->load->view('admin-pengguna-edit', $data);
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

	####################################

	public function addUser()
	{
		
        $allowedType=array("image/gif","image/png","image/pjpeg","image/jpeg");
		$error = '';
		$data = $this->input->post();

		$name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmpass = $_POST['confirmpass'];
        $type = $_POST['type'];
        $image=$_FILES["mandorimage"];

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
			$dataimg = '';
			$imgfile = '';
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

			if($dataimg){
				$imgfile = $dataimg['file_name'];
			}

			$now = time();
			
			$data = [
				'user_name' => $name,
				'user_username' => $username,
				'user_password' => md5($password),
				'user_type' => $type,
				'user_profile' => $imgfile,
				'user_lastlogin' => $now
			];

			$insert = $this->users->insert($data);
			?>
			<script type="text/javascript">
				localStorage.setItem('error', false);
				location="<?php print base_url();?>admin/user"; 
			</script> 
			<?php 
		}else{
			?>
				<script type="text/javascript">
					var errormsg = "<?php print $error?>";
					console.log(errormsg);
					localStorage.setItem('error', true);
					localStorage.setItem('errormsg', errormsg);					
					location="<?php print base_url();?>admin/user/add"; 
				</script> 
			<?php
		}
	}

	public function updateUser($id)
	{
		$data = $this->input->post();

        $error = '';
		$imgfile = '';
		$dataimg = '';
        $name = $data['name'];
        $username = $data['username'];
        $password = $data['password'];
        $type = $data['type'];
        $image = $_FILES["mandorimage"];

        ## allowed ext file upload
        $allowedType=array("image/gif","image/png","image/pjpeg","image/jpeg");
        if($type == "mandor"){
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
                        }
						
						if($check[0] > 2048 or $check[1] > 2048){
							$error = "File yang dimasukkan lebih besar dari ukuran yang diizinkan";
						}
                    }
                }
            }
        }

		$validate = $this->users->validateUpdate($id, $username, $type);
		if($validate){
            $error="Username sudah digunakan. Silakan coba username lain.";
        }

		if(!$error){
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

			if($dataimg){
				$imgfile = $dataimg['file_name'];
			}

			if(!$error){
				$arr['user_name'] = $name;
				$arr['user_username'] = $username;
				if($password){
					$password = md5($password); 
					$arr['user_password'] = $password;
				}
				$arr['user_type'] = $type;	
				if($type == "admin"){
					$arr['user_profile'] = '';
				}elseif($dataimg){
					$arr['user_profile'] = $imgfile;
				}

				$update = $this->users->update($arr, $id);
				?>
				<script type="text/javascript">
					localStorage.setItem('error', false);
					location="<?php print base_url();?>admin/user"; 
				</script> 
				<?php 
			}else{
				?>
					<script type="text/javascript">
						var id = "<?php print $id?>";
						var errormsg = "<?php print $error?>";
						localStorage.setItem('error', true);
						localStorage.setItem('errormsg', errormsg);					
						location="<?php print base_url();?>admin/user/"+id; 
					</script> 
            	<?php
			}
		}else{
			?>
                <script type="text/javascript">
                    var id = "<?php print $id?>";
                    var errormsg = "<?php print $error?>";
                    localStorage.setItem('error', true);
                    localStorage.setItem('errormsg', errormsg);					
                    location="<?php print base_url();?>admin/user/"+id; 
                </script> 
            <?php
		}
	}

	public function update($id)
	{
		$data = $this->input->post();

        $error = '';
		$imgfile = '';
		$dataimg = '';
        $name = $data['name'];
        $username = $data['username'];
        $password = $data['password'];
        $type = $data['type'];
        $image = $_FILES["mandorimage"];

        ## allowed ext file upload
        $allowedType=array("image/gif","image/png","image/pjpeg","image/jpeg");
        if($type == "mandor"){
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
                        }
						
						if($check[0] > 2048 or $check[1] > 2048){
							$error = "File yang dimasukkan lebih besar dari ukuran yang diizinkan";
						}
                    }
                }
            }
        }

		$validate = $this->users->validateUpdate($id, $username, $type);
		if($validate){
            $error="Username sudah digunakan. Silakan coba username lain.";
        }

		if(!$error){
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

			if($dataimg){
				$imgfile = $dataimg['file_name'];
			}

			if(!$error){
				$arr['user_name'] = $name;
				$arr['user_username'] = $username;
				if($password){
					$password = md5($password); 
					$arr['user_password'] = $password;
				}		
				if($dataimg){
					$arr['user_profile'] = $imgfile;
				}

				$update = $this->users->update($arr, $id);
				?>
				<script type="text/javascript">
					localStorage.setItem('error', false);
					location="<?php print base_url();?>mandor"; 
				</script> 
				<?php 
			}else{
				?>
					<script type="text/javascript">
						var id = "<?php print $id?>";
						var errormsg = "<?php print $error?>";
						localStorage.setItem('error', true);
						localStorage.setItem('errormsg', errormsg);					
						location="<?php print base_url();?>mandor/profile/"+id; 
					</script> 
            	<?php
			}
		}else{
			?>
                <script type="text/javascript">
                    var id = "<?php print $id?>";
                    var errormsg = "<?php print $error?>";
                    localStorage.setItem('error', true);
                    localStorage.setItem('errormsg', errormsg);					
                    location="<?php print base_url();?>mandor/profile/"+id; 
                </script> 
            <?php
		}
	}

	public function deleteUser($id){
		$delete = $this->users->delete($id);
		redirect('admin/user');
	}
}
