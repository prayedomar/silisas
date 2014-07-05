<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
        $nivel_jerarquico_ejecutivo_directo = $this->select_model->t_cargo_id('21')->nivel_jerarquico;
    }

}
