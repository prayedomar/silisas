<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
        for ($i = 0; $i <= 625; $i++) {
            echo 'With Formas' . $i . '<br>
            .Fill.ForeColor.RGB = RGB(220, 230, 242)<br>
        End With<br>';
        }
    }

}
