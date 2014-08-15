<?php

class Decode extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $encode = $this->encrypt->encode("1151939211");
//        echo $encode . "<br>";
        $decode = $this->encrypt->decode("FEnFKBwJhFVDnUTKSkcF0jAy9vAc+tAbsDIiHMtROYQ+PXTE/Y5yL/11kTzh3I5yNtmM5LkgiC2ts5uSQbM4Ig==");
        echo $decode . "<br>";
    }
}
