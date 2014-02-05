<?php

class Consultar_sede extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $data=array();
        $this->loadData($data);
           $this->load->view("consultar_sede",$data);
    }

    private function loadData(&$data) {
        $data["tab"]="consultar_sede";
    }

}
