<?php

if (!defined('BASEPATH'))
    exit('No esta permitido el acceso directo a este controlador. Es necesario pasar antes por el menu principal');

class Index extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    public function index() {
        //Esta funcion es para cuando se deslogueen y den atras no se devuelva a un controlador
        if ($this->session->userdata('perfil') == FALSE) {
            redirect(base_url() . 'login');
        }
        $data["tab"] = "index";
        $this->load->view("header", $data);
        $this->load->view('welcome');
        $this->load->view('footer');
    }

}
