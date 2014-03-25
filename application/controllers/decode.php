<?php

class Decode extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $encode = $this->encrypt->encode("1128474025");
//        echo $encode . "<br>";
        $decode = $this->encrypt->decode("a1D3lDPby42Ms9gesY5CEXzWpHvoW38Yw8zQSjDTw25ifhtViibENnyxyH/9lpJwhCeJ4wHLQSwWcgQgxv+wsQ==");
        echo $decode . "<br>";
    }
}
