<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_Model', 'User_model', TRUE);
        $this->load->library('session');
        $this->load->helper('url');

    }
    public function index()
    {		
        $this->load->view('user/home');
    }
}