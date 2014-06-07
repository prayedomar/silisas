<?php

class Decode extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $encode = $this->encrypt->encode("1151939211");
//        echo $encode . "<br>";
        $decode = $this->encrypt->decode("K7WGyFFTYiFIRKYsQD9nnCxqG2rFhTOf8SfU+A9dW/pNv5Y71qtREfaL+AQPBGIvpR07CmEjwqj8L88MQ6L4fg==");
        echo $decode . "<br>";
    }
}
