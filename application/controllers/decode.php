<?php

class Decode extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $encode = $this->encrypt->encode("1151939211");
//        echo $encode . "<br>";
        $decode = $this->encrypt->decode("rh3OFwvRHcMgVJMtGGC/iunrSjOXf/apNHb4+VuNFC06HbJ9Z9yLaPZIA86F4tajPx39l0mMa+dl9/T0i61bbw==");
        echo $decode . "<br>";
    }
}
