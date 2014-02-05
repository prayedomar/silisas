<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index_jefe_cartera extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        if ($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != 'jefe_cartera') {
            redirect(base_url().'login');
        }
        $this->load->view('index_jefe_cartera');
    }
}