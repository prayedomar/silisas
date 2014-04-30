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

    public function __construct() {
        self::$instance = & $this;

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

    function isLogin($tab) {
        if (empty($_SESSION["perfil"])) {
            redirect(base_url() . 'login');
            exit();
        }
        $perfil = $_SESSION["perfil"];
        switch ($perfil) {
            case "admon_sistema":
                $privilegios = array("cambiar_password", "crear_ingreso", "crear_sede", "crear_salon", "crear_salario", "crear_empleado", "crear_sede_secundaria", "crear_despachar_placa", "crear_recibir_placa", "crear_ausencia_laboral", "crear_llamado_atencion", "crear_titular", "crear_alumno", "crear_cliente", "crear_proveedor", "crear_caja", "crear_cuenta", "crear_asignar_cuenta_sede", "crear_asignar_cuenta_empleado", "crear_adelanto", "crear_prestamo", "crear_abono_adelanto", "crear_abono_prestamo", "crear_ingreso", "crear_egreso", "crear_abono_matricula", "crear_factura", "crear_recibo_caja", "crear_nomina", "crear_contrato_matricula", "crear_matricula", "crear_liquidar_comisiones", "crear_traslado_contrato_matricula", "editar_sedes_empleado", "editar_cargo_jefe_rrpp", "editar_alumno", "editar_plan_matricula", "consultar_sede", "consultar_salon", "consultar_salario", "consultar_empleado", "consultar_ausencia_laboral", "consultar_llamado_atencion", "consultar_titular", "consultar_alumno", "consultar_cliente", "consultar_proveedor", "consultar_caja", "consultar_cuenta", "consultar_transacciones", "consultar_adelanto", "consultar_abono_adelanto", "consultar_factura", "consultar_recibo_caja", "consultar_matricula", "consultar_liquidar_comisiones");
                if (!in_array($tab, $privilegios))
                    redirect(base_url() . 'login');
                break;            
            case "directivo":
                $privilegios = array("cambiar_password", "crear_ingreso", "crear_sede", "crear_salon", "crear_salario", "crear_empleado", "crear_sede_secundaria", "crear_despachar_placa", "crear_recibir_placa", "crear_ausencia_laboral", "crear_llamado_atencion", "crear_titular", "crear_alumno", "crear_cliente", "crear_proveedor", "crear_caja", "crear_cuenta", "crear_asignar_cuenta_sede", "crear_asignar_cuenta_empleado", "crear_adelanto", "crear_prestamo", "crear_abono_adelanto", "crear_abono_prestamo", "crear_ingreso", "crear_egreso", "crear_abono_matricula", "crear_factura", "crear_recibo_caja", "crear_nomina", "crear_contrato_matricula", "crear_matricula", "crear_liquidar_comisiones", "crear_traslado_contrato_matricula", "editar_sedes_empleado", "editar_cargo_jefe_rrpp", "editar_alumno", "editar_plan_matricula", "consultar_sede", "consultar_salon", "consultar_salario", "consultar_empleado", "consultar_ausencia_laboral", "consultar_llamado_atencion", "consultar_titular", "consultar_alumno", "consultar_cliente", "consultar_proveedor", "consultar_caja", "consultar_cuenta", "consultar_transacciones", "consultar_adelanto", "consultar_abono_adelanto", "consultar_factura", "consultar_recibo_caja", "consultar_matricula", "consultar_liquidar_comisiones");
                if (!in_array($tab, $privilegios))
                    redirect(base_url() . 'login');
                break;
            case "admon_sede":
                $privilegios = array("cambiar_password", "crear_empleado", "crear_despachar_placa", "crear_recibir_placa", "crear_ausencia_laboral", "crear_llamado_atencion", "crear_titular", "crear_alumno", "crear_proveedor", "crear_adelanto", "crear_abono_adelanto", "crear_ingreso", "crear_egreso", "crear_abono_matricula", "crear_factura", "crear_recibo_caja", "crear_nomina", "crear_matricula", "crear_liquidar_comisiones", "editar_cargo_jefe_rrpp", "editar_alumno", "editar_plan_matricula", "consultar_sede", "consultar_salon", "consultar_salario", "consultar_empleado", "consultar_ausencia_laboral", "consultar_llamado_atencion", "consultar_titular", "consultar_alumno", "consultar_proveedor", "consultar_transacciones", "consultar_adelanto", "consultar_abono_adelanto", "consultar_factura", "consultar_recibo_caja", "consultar_matricula", "consultar_liquidar_comisiones");
                if (!in_array($tab, $privilegios))
                    redirect(base_url() . 'login');
                break;   
            case "aux_admon":
                $privilegios = array("cambiar_password", "crear_empleado", "crear_despachar_placa", "crear_abono_matricula", "crear_factura", "crear_recibo_caja", "editar_alumno", "consultar_sede", "consultar_salon", "consultar_empleado",  "consultar_titular", "consultar_alumno", "consultar_proveedor", "consultar_matricula", "consultar_liquidar_comisiones", "consultar_factura", "consultar_recibo_caja");
                if (!in_array($tab, $privilegios))
                    redirect(base_url() . 'login');
                break;        
            case "cartera":
                $privilegios = array("cambiar_password", "crear_abono_matricula", "crear_factura", "crear_recibo_caja", "consultar_sede", "consultar_salon", "consultar_empleado", "consultar_titular", "consultar_alumno", "consultar_proveedor", "consultar_matricula", "consultar_liquidar_comisiones", "consultar_factura", "consultar_recibo_caja");
                if (!in_array($tab, $privilegios))
                    redirect(base_url() . 'login');
                break; 
            case "contador":
                $privilegios = array("cambiar_password", "consultar_sede", "consultar_empleado", "crear_ausencia_laboral", "consultar_proveedor", "consultar_caja", "consultar_cuenta", "consultar_transacciones", "consultar_factura", "consultar_recibo_caja");
                if (!in_array($tab, $privilegios))
                    redirect(base_url() . 'login');
                break; 
            case "calidad":
                $privilegios = array("cambiar_password", "consultar_sede", "consultar_salon", "consultar_titular", "consultar_alumno", "consultar_matricula");
                if (!in_array($tab, $privilegios))
                    redirect(base_url() . 'login');
                break; 
            case "docente":
                $privilegios = array("cambiar_password",  "editar_alumno", "consultar_sede", "consultar_salon", "consultar_titular", "consultar_alumno", "consultar_matricula");
                if (!in_array($tab, $privilegios))
                    redirect(base_url() . 'login');
                break; 
            case "empleado_admon":
                $privilegios = array("cambiar_password", "consultar_sede");
                if (!in_array($tab, $privilegios))
                    redirect(base_url() . 'login');
                break;   
            case "empleado_rrpp":
                $privilegios = array("cambiar_password", "consultar_sede", "consultar_salon");
                if (!in_array($tab, $privilegios))
                    redirect(base_url() . 'login');
                break;   
            case "secretaria":
                $privilegios = array("cambiar_password", "crear_abono_matricula", "crear_factura", "crear_recibo_caja", "editar_alumno", "consultar_sede", "consultar_salon", "consultar_titular", "consultar_alumno", "consultar_matricula", "consultar_factura", "consultar_recibo_caja");
                if (!in_array($tab, $privilegios))
                    redirect(base_url() . 'login');
                break;   
            case "titular":
                $privilegios = array("cambiar_password");
                if (!in_array($tab, $privilegios))
                    redirect(base_url() . 'login');
                break;  
            case "alumno":
                $privilegios = array("cambiar_password");
                if (!in_array($tab, $privilegios))
                    redirect(base_url() . 'login');
                break;                 
        }
    }

    static function escapar(&$data) {
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $data[$key] = trim(mysql_real_escape_string($value));
            }
        }
    }

    //Para enviar email
    function sendEmail($from, $to, $asunto, $contenido) {
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