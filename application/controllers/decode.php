<?php

class Decode extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $encode = $this->encrypt->encode("1151939211");
//        echo $encode . "<br>";
        $decode = $this->encrypt->decode("F45eWcO8s2fetneVYIuoJ8HIKAutE8dXFNBdMf7tZFUke54YFNNh205IGtLJOVsqenaHFCjBAleDbw48e10dgg==");
        echo $decode . "<br>";
    }
}
