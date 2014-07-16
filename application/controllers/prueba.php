<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
        $concepto_base = $this->select_model->concepto_base_nomina_empleado('1128478351', $dni_empleado, $id_t_concepto);
        var_dump($sede_ppal);
    }

}
