<?php

class Prueba2 extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
        $data['base_url'] = base_url();
        $this->load->view('testV');
    }

}
