<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
        $error2 = $this->update_model->t_curso_alumno("1007111078", "4", "5");
        //No se pudo crear el empleado
        if (isset($error2)) {
            echo "error";
        } else {
            echo "ok";
        }
    }

}
