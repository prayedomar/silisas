<?php

class Decode extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $encode = $this->encrypt->encode("1128474025");
//        echo $encode . "<br>";
        $decode = $this->encrypt->decode("RQYyKes/pkQzgkmv9ZFtQkJc4+i6UGqUuoYyybdazLKFKorj5canu7QbYqUCm4RrKdHPf8503yhWEKJM24qpnQ==");
        echo $decode . "<br>";
    }
}
