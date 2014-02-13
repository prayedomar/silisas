<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//Para atrapar los errores critical y notice de php, por ejemplo en validar_fecha cuando el list no es completo
set_error_handler('exceptions_error_handler');

function exceptions_error_handler($severity, $message, $filename, $lineno) {
    if (error_reporting() == 0) {
        return;
    }
    if (error_reporting() & $severity) {
        throw new ErrorException($message, 0, $severity, $filename, $lineno);
    }
}

class CI_Controller {

    private static $instance;

    /**
     * Constructor
     */
    public function __construct() {
        self::$instance = & $this;

// Assign all the class objects that were instantiated by the
// bootstrap file (CodeIgniter.php) to local class variables
// so that CI can run as one big super object.
        foreach (is_loaded() as $var => $class) {
            $this->$var = & load_class($class);
        }

        $this->load = & load_class('Loader', 'core');

        $this->load->initialize();

        log_message('debug', "Controller Class Initialized");
    }

    public static function &get_instance() {
        return self::$instance;
    }

    static function escapar(&$data) {
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $data[$key] = trim(mysql_real_escape_string($value));
            }
        }
    }
    
    //Para enviar email
    function sendEmail($contenido, $from, $to, $asunto) {
        ob_start();
        include('application/views/testV.php');
        $message = ob_get_clean();
        echo $message;
        $asunto = utf8_decode($asunto);
        $headers = "From: $from \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        mail($to, $asunto, utf8_decode($message), $headers);
    }    

//callback de form_validation
    function select_default($campo) {
        if ($campo == "default") {
            $this->form_validation->set_message('select_default', 'El Campo %s, es obligatorio.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

//callback de form_validation, hay que tener en cuenta que si es vacio no se debe mostrar error.
    function miles_numeric($campo) {
        if ($campo) {
            if (is_numeric(str_replace(",", "", $campo))) {
                return TRUE;
            } else {
                $this->form_validation->set_message('miles_numeric', 'El Campo %s, debe ser númerico.');
                return FALSE;
            }
        }
    }

//callback de form_validation
    function valor_positivo($campo) {
        if ($campo) {
            if (is_numeric(str_replace(",", "", $campo))) {
                $valor = (str_replace(",", "", $campo));
                if ($valor >= 0) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('valor_positivo', 'El Campo %s, debe ser mayor o igual cero.');
                    return FALSE;
                }
            } else {
                $this->form_validation->set_message('valor_positivo', 'El Campo %s, debe ser númerico.');
                return FALSE;
            }
        }
    }

//callback de form_validation
    function mayor_cero($campo) {
        if ($campo) {
            if (is_numeric(str_replace(",", "", $campo))) {
                $valor = (str_replace(",", "", $campo));
                if ($valor > 0) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('mayor_cero', 'El Campo %s, debe ser mayor a cero.');
                    return FALSE;
                }
            } else {
                $this->form_validation->set_message('mayor_cero', 'El Campo %s, debe ser númerico.');
                return FALSE;
            }
        }
    }

//callback de form_validation
    function fecha_valida($campo) {
        if ($campo) {
            try {
//HAcemos try catch porq el explode puede explotar si no le llega 5745-5454-5454
                list($anyo, $mes, $dia) = explode("-", $campo);
                if (!checkdate($mes, $dia, $anyo)) {
                    $this->form_validation->set_message('fecha_valida', 'El campo %s, debe ser una fecha válida: yyyy-mm-dd.');
                    return FALSE;
                } else {
                    return TRUE;
                }
            } catch (Exception $e) {
                $this->form_validation->set_message('fecha_valida', 'El campo %s, debe ser una fecha válida: yyyy-mm-dd.');
                return FALSE;
            }
        }
    }

//callback de form_validation
    function porcentaje($campo) {
        if ($campo) {
            if (is_numeric(str_replace(",", "", $campo))) {
                $valor = (str_replace(",", "", $campo));
                if (($valor >= 0) && ($valor <= 100)) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('porcentaje', 'El Campo %s, debe estar entre 0 y 100.');
                    return FALSE;
                }
            } else {
                $this->form_validation->set_message('porcentaje', 'El Campo %s, debe ser númerico.');
                return FALSE;
            }
        }
    }

//Cantidad de dias entre 2 fechas
    function dias_entre_fechas($fechaStart, $fechaEnd) {
        list($anyoStart, $mesStart, $diaStart) = explode("-", $fechaStart);
        list($anyoEnd, $mesEnd, $diaEnd) = explode("-", $fechaEnd);
        $diasStartJuliano = gregoriantojd($mesStart, $diaStart, $anyoStart);
        $diasEndJuliano = gregoriantojd($mesEnd, $diaEnd, $anyoEnd);

        return $diasEndJuliano - $diasStartJuliano;
    }

}

// END Controller class

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */