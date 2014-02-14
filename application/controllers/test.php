<?php

class Test extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
    }

    function index() {
//        $this->sendEmail("silisascolombia@gmail.com", "prayedomar@hotmail.com", "Prueba del sistema", "tal cosa <b>tal otra en negrita</b>");
//        $this->sendEmail("silisascolombia@gmail.com", "silisascolombia@gmail.com", "Prueba del sistema", "tal cosa <b>tal otra en negrita</b>");
        $id = 42412423;
        $dni = $this->select_model->t_dni_id(1)->tipo;
        $email = "prayedomar@hotmail.com";
        $asunto = "Bienvenido a la familia SILI S.A.S";
        $mensaje = '<p>A partir de este momento, ústed hace parte de la familia SILI S.A.S como: Empleado Activo.<br/>'
                . '<br/>Para ingresar a nuestro sistema y disfrutar de todas las herramientas que hemos diseñado para facilitar sus labores cotidianas al interior de la compañía, ingrese a traves de nuestra pagina web: <a href="http://www.sili.com.co" target="_blank">www.sili.com.co</a> y seleccione la opción "Acceder".</p>'
                . '<ul type="disc">'
                . '<li><p>Sus datos para ingresar al sistema son:</p>'
                . '<center>'
                . '<table>'
                    . '<tr>'
                        . '<td style="width:230px;"><b>Tipo de usuario: </b></td>'
                        . '<td>Empleado</td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td><b>Tipo de identificación: </b></td>'
                        . '<td>' . $dni . '</td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td><b>Identificación de usuario: </b></td>'
                        . '<td>' . $id . '</td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td><b>Contraseña: </b></td>'
                        . '<td>' . $id . '</td>'
                    . '</tr>'
                . '</table>'
                . '</center>'
                . '<br/><p>Para garantizar la seguridad de tu cuenta, una vez que ingrese por primera vez, modifique su contraseña a través de la opción: Opciones de usuario>Cambiar contraseña.</p>'
                . '</li>'
                . '<li><p>La información de su contrato laboral, es la siguiente:</p>'
                . '<center>'
                . '<table>'
                    . '<tr>'
                        . '<td style="width:230px;"><b>Tipo de usuario: </b></td>'
                        . '<td>Empleado</td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td><b>Tipo de identificación: </b></td>'
                        . '<td>' . $dni . '</td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td><b>Identificación de usuario: </b></td>'
                        . '<td>' . $id . '</td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td><b>Contraseña: </b></td>'
                        . '<td>' . $id . '</td>'
                    . '</tr>'
                . '</table>'
                . '</li>'
                . '<center><br/>¡Gracias por darnos la oportunidad de contar con su gran talento!</center>';
        $this->sendEmail("silisascolombia@gmail.com", $email, $asunto, $mensaje);
        $this->sendEmail("silisascolombia@gmail.com", "silisascolombia@gmail.com", $asunto, $mensaje);
    }

}
