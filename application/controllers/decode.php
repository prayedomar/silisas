<?php

class Decode extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $encode = $this->encrypt->encode("1151939211");
//        echo $encode . "<br>";
        $decode = $this->encrypt->decode("PH0o/BKoy9+VEiSqM+VFYDxT+SDf6pW3g/Fmk/MHB9bOJiCY93di/lrjJKj4T1Hcj+kAetWTPUKLdz3Fkqs5yg==");
        echo $decode . "<br>";
    }
}
