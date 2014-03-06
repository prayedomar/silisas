<?php

class Decode extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $encode = $this->encrypt->encode("1128474025");
//        echo $encode . "<br>";
        $decode = $this->encrypt->decode("H66ptP06zihmQr6XVxjZX9ss5oF7Sv+ES32Oe8WskAKvvkf4eA/joBJI+kP+Gt5DrlXcLviWPjIg7PQUhssdFw==");
        echo $decode . "<br>";
    }
}
