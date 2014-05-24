<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
        $this->insert_model->cuenta_x_sede("999999999999", 1, 0);
        $this->insert_model->cuenta_x_sede_x_empleado_ingresar("999999999999", 1, 1, 1, 1);
        $this->insert_model->cuenta_x_sede_x_empleado_retirar("999999999999", 1, 1, 1, 1);
        $this->insert_model->cuenta_x_sede_x_empleado_consultar("999999999999", 1, 1, 1, 1);

        $this->insert_model->cuenta_x_sede("238478762342", 1, 0);
        $this->insert_model->cuenta_x_sede_x_empleado_ingresar("238478762342", 1, 1, 1, 1);
        $this->insert_model->cuenta_x_sede_x_empleado_retirar("238478762342", 1, 1, 1, 1);
        $this->insert_model->cuenta_x_sede_x_empleado_consultar("238478762342", 1, 1, 1, 1);
        echo "ok";
    }

}
