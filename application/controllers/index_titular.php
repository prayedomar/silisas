<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index_titular extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if ($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != 'titular') {
            redirect(base_url() . 'login');
        }
        $data['perfil'] = $this->session->userdata('perfil');        
        $data['base_url'] = base_url();
        $this->parser->parse('index_titular', $data);
    }

}
