<?php

class Test extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    function index() {
        $this->sendEmail("asdas", "noreplay@sili.com", "prayedomar@hotmail.com", "Hola");
        $this->sendEmail("asdas", "noreplay@sili.com", "luismec90@gmail.com", "Hola");
        $this->sendEmail("asdas", "noreplay@sili.com", "silisascolombia@gmail.com", "Hola");
    }

    function sendEmail($contenido, $from, $to, $asunto) {
        ob_start();
        include('application/views/testV.php');
        $message = ob_get_clean();
        echo $message;
        $asunto = utf8_decode($asunto);
        $headers = "From: SILI S.A.S \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        mail($to, $asunto, utf8_decode($message), $headers);
    }

}
