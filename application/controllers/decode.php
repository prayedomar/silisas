<?php

class Decode extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->model('select_model');
        $usuario = $this->select_model->usuario_id_dni_t_usuario('43602288', '1', '1');
        $encode = $this->encrypt->encode("1151939211");
//        echo $encode . "<br>";
        $decode = $this->encrypt->decode($usuario->password);
        echo $decode . "<br>";
    }
}
