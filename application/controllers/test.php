<?php

class Test extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    function index() {
        $this->sendEmail("tal cosa <b>tal otra en negrita</b>", "silisascolombia@gmail.com", "prayedomar@hotmail.com", "Prueba del sistema");
        $this->sendEmail("tal cosa <b>tal otra en negrita</b>", "silisascolombia@gmail.com", "silisascolombia@gmail.com", "Prueba del sistema");
    }

}
