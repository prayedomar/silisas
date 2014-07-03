<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
$error2 = $this->update_model->concepto_nomina_estado(6, 3);
$error2 = $this->update_model->concepto_nomina_estado(7, 3);
$error2 = $this->update_model->concepto_nomina_estado(8, 3);
$error2 = $this->update_model->concepto_nomina_estado(9, 3);
$error2 = $this->update_model->concepto_nomina_estado(10, 3);
echo "ok";
    }

}
