<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $CI = & get_instance();
        $CI->load->library('session');
        $CI->load->helper('url');

        if ( !$this->session->userdata('logged_in')){
            redirect('auth/login');
        }

        ## get user logged in
		$userdata = $this->session->userdata();
		$currentURL = current_url(); 

        $checkadm = strpos($currentURL, "admin");
        $checkmdr = strpos($currentURL, "mandor");

        if($checkadm or $checkmdr){
            $check = strpos($currentURL, $userdata['roles']);
            if ($check === false or $check == 0) {
                redirect(base_url().$userdata['roles']);
            } 
        }
            
    }
}