<?php

class Decode extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $encode = $this->encrypt->encode("1151939211");
//        echo $encode . "<br>";
        $decode = $this->encrypt->decode("i0jnhPWtiXUIaROUs5ilfUwjBNe/PJaGw5orvxvZ2J4a844v+NLtL9t1yylwO/WIZeVOqh+5DPZCLMDnOGdRNA==");
        echo $decode . "<br>";
    }
}
