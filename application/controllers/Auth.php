<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function index()
    {
		
    }
    
    public function logout()
    {
        $this->session->set_userdata(array('id' => '', 'firstname' => '', 'status' => ''));
        $this->session->sess_destroy();
        redirect('');
    }
}
