<?php

if (!defined('BASEPATH'))
    exit('No esta permitido el acceso directo a este controlador. Es necesario pasar antes por el menu principal');

class Index extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    public function index() {
        $data["tab"] = "crear_titular";
        $this->load->view("header", $data);
        $this->load->view('welcome');
        $this->load->view('footer');
    }

    //Crear: Sede Secundaria
    function crear_asignar_cuenta_sede() {
        $data = $this->navbar();
        $data['cuenta'] = $this->select_model->cuenta_banco();
        $data['action_anular_sede_cuenta'] = base_url() . "index_admon_sistema/anular_cuenta_sede";
        $data['action_agregar_sede_cuenta'] = base_url() . "index_admon_sistema/new_cuenta_sede";

        $data['action_llena_cuenta_bancaria'] = base_url() . "index_admon_sistema/llena_cuenta_bancaria";
        $data['action_llena_sedes_cuenta'] = base_url() . "index_admon_sistema/llena_sedes_cuenta_banco";

        $data['action_llena_checkbox_sedes_cuenta'] = base_url() . "index_admon_sistema/llena_checkbox_sedes_cuenta";

        $this->parser->parse('crear_asignar_cuenta_sede', $data);
        $this->load->view('footer');
    }

    public function new_cuenta_sede() {
        if ($this->input->is_ajax_request()) {
            //Validamos que haya seleccionado al menos una sede
            $checkbox = $this->input->post('sede_checkbox');
            if ($checkbox != TRUE) {
                $errors = array(
                    'mensaje' => '<p>Seleccione al menos una sede.</p>',
                    'respuesta' => 'error'
                );
                echo json_encode($errors);
                return FALSE;
            } else {
                $cuenta = $this->input->post('cuenta');
                $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
                $id_responsable = $this->input->post('id_responsable');
                $dni_responsable = $this->input->post('dni_responsable');

                foreach ($checkbox as $fila) {
                    $error = $this->insert_model->cuenta_x_sede($cuenta, $fila, 1);
                    $this->insert_model->asignar_cuenta_x_sede($cuenta, $fila, $fecha_trans, $id_responsable, $dni_responsable);
                    if (isset($error)) {
                        $response = array(
                            'respuesta' => 'error',
                            'mensaje' => '<p>' . $error . '</p>'
                        );
                        echo json_encode($response);
                        return FALSE;
                    }
                }
                $response = array(
                    'respuesta' => 'OK'
                );
                echo json_encode($response);
                return FALSE;
            }
        } else {
            redirect(base_url());
        }
    }

    public function anular_cuenta_sede() {
        if ($this->input->is_ajax_request()) {
            list($sede, $cuenta) = explode("-", $this->input->post('sede_cuenta'));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $error = $this->update_model->cuenta_x_sede($cuenta, $sede, 0);
            $error = $this->update_model->cuenta_x_sede_x_empleado_todos($cuenta, $sede, 0);

            if (isset($error)) {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p>' . $error . '</p>'
                );
            } else {
                //Para la historica no atrapo el error, si hubo error no me importa, con tal que se haya hecho la transaccion verdadera
                $this->insert_model->anular_cuenta_x_sede($cuenta, $sede, $fecha_trans, $id_responsable, $dni_responsable);
                $response = array(
                    'respuesta' => 'OK'
                );
            }
            echo json_encode($response);
            return FALSE;
        } else {
            redirect(base_url());
        }
    }

//Crear: Sede Secundaria
    function crear_asignar_cuenta_empleado() {
        $data = $this->navbar();
        $data['cuenta'] = $this->select_model->cuenta_banco();
        $data['action_anular_empleado_cuenta'] = base_url() . "index_admon_sistema/anular_cuenta_empleado";
        $data['action_agregar_empleado_cuenta'] = base_url() . "index_admon_sistema/new_cuenta_empleado";

        $data['action_llena_cuenta_bancaria'] = base_url() . "index_admon_sistema/llena_cuenta_bancaria";
        $data['action_llena_checkbox_empleados_cuenta'] = base_url() . "index_admon_sistema/llena_checkbox_empleados_cuenta";

        $data['action_llena_empleados_cuenta'] = base_url() . "index_admon_sistema/llena_empleados_cuenta_banco";

        $this->parser->parse('crear_asignar_cuenta_empleado', $data);
        $this->load->view('footer');
    }

    public function new_cuenta_empleado() {
        if ($this->input->is_ajax_request()) {
            //Validamos que haya seleccionado al menos una sede
            $checkbox = $this->input->post('empleados_checkbox');
            if ($checkbox != TRUE) {
                $errors = array(
                    'mensaje' => '<p>Seleccione al menos un empleado.</p>',
                    'respuesta' => 'error'
                );
                echo json_encode($errors);
                return FALSE;
            } else {
                $cuenta = $this->input->post('cuenta');
                $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
                $id_responsable = $this->input->post('id_responsable');
                $dni_responsable = $this->input->post('dni_responsable');

                foreach ($checkbox as $fila) {
                    list($id_encargado, $dni_encargado) = explode("-", $fila);
                    $sede = $this->select_model->empleado($id_encargado, $dni_encargado)->sede_ppal;
                    $error = $this->insert_model->cuenta_x_sede_x_empleado($cuenta, $sede, $id_encargado, $dni_encargado, 1);
                    $this->insert_model->asignar_cuenta_x_sede_x_empleado($cuenta, $sede, $id_encargado, $dni_encargado, $fecha_trans, $id_responsable, $dni_responsable);
                    if (isset($error)) {
                        $response = array(
                            'respuesta' => 'error',
                            'mensaje' => '<p>' . $error . '</p>'
                        );
                        echo json_encode($response);
                        return FALSE;
                    }
                }
                $response = array(
                    'respuesta' => 'OK'
                );
                echo json_encode($response);
                return FALSE;
            }
        } else {
            redirect(base_url());
        }
    }

    public function anular_cuenta_empleado() {
        if ($this->input->is_ajax_request()) {
            list($id_encargado, $dni_encargado, $cuenta) = explode("-", $this->input->post('empleado_cuenta'));
            $sede = $this->select_model->empleado($id_encargado, $dni_encargado)->sede_ppal;
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $error = $this->update_model->cuenta_x_sede_x_empleado($cuenta, $sede, $id_encargado, $dni_encargado, 0);

            if (isset($error)) {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p>' . $error . '</p>'
                );
            } else {
                //Para la historica no atrapo el error, si hubo error no me importa, con tal que se haya hecho la transaccion verdadera
                $this->insert_model->anular_cuenta_x_sede_x_empleado($cuenta, $sede, $id_encargado, $dni_encargado, $fecha_trans, $id_responsable, $dni_responsable);
                $response = array(
                    'respuesta' => 'OK'
                );
            }

            $response = array(
                'respuesta' => 'OK'
            );

            echo json_encode($response);
            return FALSE;
        } else {
            redirect(base_url());
        }
    }

    //Crear: Sede Secundaria
    function crear_sede_secundaria() {
        $data = $this->navbar();
        $data['empleado'] = $this->select_model->empleado_activo();
        $data['action_llena_empleado_sede_secundaria'] = base_url() . "index_admon_sistema/llena_empleado_sede_secundaria";
        $data['action_anular_secundaria'] = base_url() . "index_admon_sistema/anular_sede_secundaria";
        $data['action_agregar_secundaria'] = base_url() . "index_admon_sistema/new_sede_secundaria";
        $data['action_llena_checkbox_secundarias'] = base_url() . "index_admon_sistema/llena_checkbox_secundarias";
        $this->parser->parse('crear_sede_secundaria', $data);
        $this->load->view('footer');
    }

    public function new_sede_secundaria() {
        if ($this->input->is_ajax_request()) {
            //Validamos que haya seleccionado al menos una sede
            $checkbox = $this->input->post('sede_checkbox');
            if ($checkbox != TRUE) {
                $errors = array(
                    'mensaje' => '<p>Seleccione al menos una sede secundaria.</p>',
                    'respuesta' => 'error'
                );
                echo json_encode($errors);
                return FALSE;
            } else {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
                $id_responsable = $this->input->post('id_responsable');
                $dni_responsable = $this->input->post('dni_responsable');

                foreach ($checkbox as $fila) {
                    $error = $this->insert_model->empleado_x_sede($id_empleado, $dni_empleado, $fila);
                    $this->insert_model->asignar_empleado_x_sede($id_empleado, $dni_empleado, $fila, $fecha_trans, $id_responsable, $dni_responsable);
                    if (isset($error)) {
                        $response = array(
                            'respuesta' => 'error',
                            'mensaje' => '<p>' . $error . '</p>'
                        );
                        echo json_encode($response);
                        return FALSE;
                    }
                }
                $response = array(
                    'respuesta' => 'OK'
                );
                echo json_encode($response);
                return FALSE;
            }
        } else {
            redirect(base_url());
        }
    }

//Crear: Adelanto de nomina
    function crear_adelanto() {
        $data = $this->navbar();
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['empleado'] = $this->select_model->empleado_sedes_responsable($id_responsable, $dni_responsable);
        $data['t_ausencia'] = $this->select_model->t_ausencia();


        $data['action_validar'] = base_url() . "index_admon_sistema/validar_adelanto";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_adelanto";

        $data['action_llena_cuenta_responsable'] = base_url() . "index_admon_sistema/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "index_admon_sistema/llena_caja_responsable";

        $this->parser->parse('crear_adelanto', $data);
        $this->load->view('footer');
    }

    function validar_adelanto() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('empleado', 'Empleado', 'required|callback_select_default');
            //coloca maxlength en 15, para incluir los puntos de miles 888.777.666.273
            $this->form_validation->set_rules('total', 'Valor del Adelanto', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('valor_retirado', 'Valor Retirado de la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_retirado', 'Valor Retirado de la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'required|trim|xss_clean|max_length[255]');
            $error_valores = "";
            if ($this->input->post('total')) {
                $total = round(str_replace(",", "", $this->input->post('total')), 2);
                if (!$this->input->post('valor_retirado')) {
                    $valor_retirado = 0;
                } else {
                    $valor_retirado = round(str_replace(",", "", $this->input->post('valor_retirado')), 2);
                }
                if (!$this->input->post('efectivo_retirado')) {
                    $efectivo_retirado = 0;
                } else {
                    $efectivo_retirado = round(str_replace(",", "", $this->input->post('efectivo_retirado')), 2);
                }
                if (round(($valor_retirado + $efectivo_retirado), 2) != $total) {
                    $error_valores = "<p>La suma del valor retirado de una cuenta y el efectivo retirado de una caja, deben sumar exactamente: $" . $this->input->post('total') . ", en vez de: $" . number_format(($valor_retirado + $efectivo_retirado), 2, '.', ',') . ".</p>";
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_valores != "")) {
                echo form_error('empleado') . form_error('total') . form_error('valor_retirado') . form_error('efectivo_retirado') . $error_valores . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function new_adelanto() {
        if ($this->input->post('submit')) {
            list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
            $total = round(str_replace(",", "", $this->input->post('total')), 2);
            if (($this->input->post('cuenta')) && ($this->input->post('valor_retirado')) && ($this->input->post('valor_retirado') != 0)) {
                $cuenta_origen = $this->input->post('cuenta');
                $valor_retirado = round(str_replace(",", "", $this->input->post('valor_retirado')), 2);
            } else {
                $cuenta_origen = NULL;
                $valor_retirado = NULL;
            }
            if (($this->input->post('caja')) && ($this->input->post('efectivo_retirado')) && ($this->input->post('efectivo_retirado') != 0)) {
                list($sede_caja_origen, $t_caja_origen) = explode("-", $this->input->post('caja'));
                $efectivo_retirado = round(str_replace(",", "", $this->input->post('efectivo_retirado')), 2);
            } else {
                $sede_caja_origen = NULL;
                $t_caja_origen = NULL;
                $efectivo_retirado = NULL;
            }
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_adelanto = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_adelanto = ($this->select_model->nextId_adelanto($prefijo_adelanto)->id) + 1;

            $error = $this->insert_model->adelanto($prefijo_adelanto, $id_adelanto, $id_empleado, $dni_empleado, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, 1, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "index_admon_sistema/crear_adelanto";
            $data['msn_recrear'] = "Crear otro Adelanto";
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                $this->parser->parse('trans_success', $data);
            }
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url());
        }
    }

//Crear: Abono a Adelanto de nomina
    function crear_abono_adelanto() {
        $data = $this->navbar();
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['empleado'] = $this->select_model->empleado_sedes_responsable($id_responsable, $dni_responsable);

        $data['action_validar'] = base_url() . "index_admon_sistema/validar_abono_adelanto";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_abono_adelanto";

        $data['action_llena_empleado_adelanto'] = base_url() . "index_admon_sistema/llena_empleado_adelanto";
        $data['action_llena_adelanto_empleado'] = base_url() . "index_admon_sistema/llena_adelanto_empleado";
        $data['action_llena_cuenta_responsable'] = base_url() . "index_admon_sistema/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "index_admon_sistema/llena_caja_responsable";

        $this->parser->parse('crear_abono_adelanto', $data);
        $this->load->view('footer');
    }

    function validar_abono_adelanto() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('empleado', 'Empleado', 'required|callback_select_default');
            $this->form_validation->set_rules('adelanto', 'Adelanto a Abonar', 'required');
            $this->form_validation->set_rules('total', 'Valor del Abono', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('valor_consignado', 'Valor Consignado a la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_ingresado', 'Efectivo Ingresado a la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');

            $error_valores = "";
            if ($this->input->post('total')) {
                $total = round(str_replace(",", "", $this->input->post('total')), 2);
                list($prefijo_adelanto, $id_adelanto, $saldo) = explode("-", $this->input->post('adelanto'));
                if ($total > $saldo) {
                    $error_valores = "<p>El valor del abono no puede ser mayor que el saldo del adelanto: $" . number_format($saldo, 2, '.', ',') . ".</p>";
                } else {
                    if (!$this->input->post('valor_consignado')) {
                        $valor_consignado = 0;
                    } else {
                        $valor_consignado = round(str_replace(",", "", $this->input->post('valor_consignado')), 2);
                    }
                    if (!$this->input->post('efectivo_ingresado')) {
                        $efectivo_ingresado = 0;
                    } else {
                        $efectivo_ingresado = round(str_replace(",", "", $this->input->post('efectivo_ingresado')), 2);
                    }
                    if (round(($valor_consignado + $efectivo_ingresado), 2) != $total) {
                        $error_valores = "<p>La suma del valor consignado a la cuenta y el efectivo ingresado a la caja, deben sumar exactamente: $" . $this->input->post('total') . ", en vez de: $" . number_format(($valor_consignado + $efectivo_ingresado), 2, '.', ',') . ".</p>";
                    }
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_valores != "")) {
                echo form_error('empleado') . form_error('adelanto') . form_error('total') . form_error('valor_consignado') . form_error('efectivo_ingresado') . $error_valores . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function new_abono_adelanto() {
        if ($this->input->post('submit')) {
            list($prefijo_adelanto, $id_adelanto, $saldo) = explode("-", $this->input->post('adelanto'));
            list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
            $total = round(str_replace(",", "", $this->input->post('total')), 2);
            if (($this->input->post('cuenta')) && ($this->input->post('valor_consignado')) && ($this->input->post('valor_consignado') != 0)) {
                $cuenta_destino = $this->input->post('cuenta');
                $valor_consignado = round(str_replace(",", "", $this->input->post('valor_consignado')), 2);
            } else {
                $cuenta_destino = NULL;
                $valor_consignado = NULL;
            }
            if (($this->input->post('caja')) && ($this->input->post('efectivo_ingresado')) && ($this->input->post('efectivo_ingresado') != 0)) {
                list($sede_caja_destino, $t_caja_destino) = explode("-", $this->input->post('caja'));
                $efectivo_ingresado = round(str_replace(",", "", $this->input->post('efectivo_ingresado')), 2);
            } else {
                $sede_caja_destino = NULL;
                $t_caja_destino = NULL;
                $efectivo_ingresado = NULL;
            }
            $vigente = 1;
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;

            $error = $this->insert_model->abono_adelanto($prefijo_adelanto, $id_adelanto, $total, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $sede, $vigente, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "index_admon_sistema/crear_abono_adelanto";
            $data['msn_recrear'] = "Crear otro Abono a Adelanto";
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                //Si no hubo error entonces si el saldo es igual al total abonado, entonces lo colocamos paz y salvo
                if ($total == $saldo) {
                    $new_estado = 3; //Paz y Salvo Voluntario   
                    $this->update_model->adelanto_estado($prefijo_adelanto, $id_adelanto, $new_estado);
                }
                $this->parser->parse('trans_success', $data);
            }
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url());
        }
    }

//Crear: Prestamo a Clientes o Empleados
    function crear_prestamo() {
        $data = $this->navbar();
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['empleado'] = $this->select_model->empleado_sedes_responsable($id_responsable, $dni_responsable);
        $data['t_beneficiario'] = $this->select_model->t_usuario_prestamo();
        $data['fecha_actual'] = date('Y-m-d');

        $data['action_validar'] = base_url() . "index_admon_sistema/validar_prestamo";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_prestamo";

        $data['action_llena_cuenta_responsable'] = base_url() . "index_admon_sistema/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "index_admon_sistema/llena_caja_responsable";
        $data['action_llena_clientes'] = base_url() . "index_admon_sistema/llena_clientes";

        $this->parser->parse('crear_prestamo', $data);
        $this->load->view('footer');
    }

    function validar_prestamo() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('t_beneficiario', 'Tipo de Usuario Beneficiario', 'required|callback_select_default');
            //coloca maxlength en 18, para incluir los puntos de miles 888,777,666,273,23
            $this->form_validation->set_rules('total', 'Valor del Prestamo', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('tasa_interes', 'Tasa de Interés', 'required|trim|xss_clean|max_length[6]|callback_miles_numeric|callback_porcentaje');
            $this->form_validation->set_rules('cant_cuotas', 'Cantidad de Cuotas', 'required|trim|max_length[3]|integer|callback_mayor_cero');
            $this->form_validation->set_rules('fecha_desembolso', 'Fecha Desembolso Dinero', 'required|xss_clean|callback_fecha_valida');
            $this->form_validation->set_rules('valor_retirado', 'Valor Retirado de la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_retirado', 'Valor Retirado de la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');

            //Si escogió empleado o cliente, valido los campos
            if ($this->input->post('t_beneficiario') == '1') {
                $this->form_validation->set_rules('empleado', 'Empleado Beneficiario', 'required|callback_select_default');
            } else {
                if ($this->input->post('t_beneficiario') == '4') {
                    $this->form_validation->set_rules('cliente', 'Cliente Beneficiario', 'required|callback_select_default');
                }
            }

            $error_valores = "";
            if ($this->input->post('total')) {
                $total = round(str_replace(",", "", $this->input->post('total')), 2);
                if (!$this->input->post('valor_retirado')) {
                    $valor_retirado = 0;
                } else {
                    $valor_retirado = round(str_replace(",", "", $this->input->post('valor_retirado')), 2);
                }
                if (!$this->input->post('efectivo_retirado')) {
                    $efectivo_retirado = 0;
                } else {
                    $efectivo_retirado = round(str_replace(",", "", $this->input->post('efectivo_retirado')), 2);
                }
                if (round(($valor_retirado + $efectivo_retirado), 2) != $total) {
                    $error_valores = "<p>La suma del valor retirado de una cuenta y el efectivo retirado de una caja, deben sumar exactamente: $" . $this->input->post('total') . ", en vez de: $" . number_format(($valor_retirado + $efectivo_retirado), 2, '.', ',') . ".</p>";
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_valores != "")) {
                echo form_error('t_beneficiario') . form_error('empleado') . form_error('cliente') . form_error('total') . form_error('tasa_interes') . form_error('cant_cuotas') . form_error('fecha_desembolso') . form_error('valor_retirado') . form_error('efectivo_retirado') . $error_valores . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function new_prestamo() {
        if ($this->input->post('submit')) {
            $t_beneficiario = $this->input->post('t_beneficiario');
            if ($t_beneficiario == 1) {
                //En el caso en que el beneficiario sea un empleado
                list($id_beneficiario, $dni_beneficiario) = explode("-", $this->input->post('empleado'));
            } else {
                //En el caso en que sea un cliente
                list($id_beneficiario, $dni_beneficiario) = explode("-", $this->input->post('cliente'));
            }
            $total = round(str_replace(",", "", $this->input->post('total')), 2);
            $tasa_interes = round(str_replace(",", "", $this->input->post('tasa_interes')), 2);
            $cant_cuotas = $this->input->post('cant_cuotas');
            if ($tasa_interes == 0) {
                $cuota_fija = round(($total / $cant_cuotas), 2);
            } else {
                //Segun la formula de couta fija para el sistema de credito frances de amortizacion y redondeamos a 2 cifras
                $tasa = $tasa_interes / 100;
                $cuota_fija = round($total / ((1 - (pow((1 + $tasa), -$cant_cuotas))) / $tasa), 2);
            }
            $fecha_desembolso = $this->input->post('fecha_desembolso');
            if (($this->input->post('cuenta')) && ($this->input->post('valor_retirado')) && ($this->input->post('valor_retirado') != 0)) {
                $cuenta_origen = $this->input->post('cuenta');
                $valor_retirado = round(str_replace(",", "", $this->input->post('valor_retirado')), 2);
            } else {
                $cuenta_origen = NULL;
                $valor_retirado = NULL;
            }
            if (($this->input->post('caja')) && ($this->input->post('efectivo_retirado')) && ($this->input->post('efectivo_retirado') != 0)) {
                list($sede_caja_origen, $t_caja_origen) = explode("-", $this->input->post('caja'));
                $efectivo_retirado = round(str_replace(",", "", $this->input->post('efectivo_retirado')), 2);
            } else {
                $sede_caja_origen = NULL;
                $t_caja_origen = NULL;
                $efectivo_retirado = NULL;
            }
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_prestamo = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_prestamo = ($this->select_model->nextId_prestamo($prefijo_prestamo)->id) + 1;

            $error = $this->insert_model->prestamo($prefijo_prestamo, $id_prestamo, $t_beneficiario, $id_beneficiario, $dni_beneficiario, $total, $tasa_interes, $cant_cuotas, $cuota_fija, $fecha_desembolso, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, 1, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "index_admon_sistema/crear_prestamo";
            $data['msn_recrear'] = "Crear otro Prestamo";
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                //Si creo el prestamo entonces creamos el plan pagos de prestamo
                for ($i = 1; $i <= $cant_cuotas; $i++) {
                    $fecha_pago = date("Y-m-d", strtotime("$fecha_desembolso +$i month"));
                    $error1 = $this->insert_model->plan_pago_prestamo($prefijo_prestamo, $id_prestamo, $i, $fecha_pago, 0);
                    if (isset($error1)) {
                        $data['trans_error'] = $error2;
                        $this->parser->parse('trans_error', $data);
                        $this->parser->parse('welcome', $data);
                        $this->load->view('footer');
                        return;
                    }
                }
                $this->parser->parse('trans_success', $data);
            }
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url());
        }
    }

//Crear: Abono a Prestamo
    function crear_abono_prestamo() {
        $data = $this->navbar();
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];

        $data['t_beneficiario'] = $this->select_model->t_usuario_prestamo();
        $data['empleado'] = $this->select_model->empleado_sedes_responsable($id_responsable, $dni_responsable);
        $data['dni'] = $this->select_model->t_dni_cliente();

        $data['action_validar'] = base_url() . "index_admon_sistema/validar_abono_prestamo";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_abono_prestamo";


        $data['action_llena_empleado_prestamo'] = base_url() . "index_admon_sistema/llena_empleado_prestamo";
        $data['action_llena_cliente_prestamo'] = base_url() . "index_admon_sistema/llena_cliente_prestamo";
        $data['action_llena_prestamo_beneficiario'] = base_url() . "index_admon_sistema/llena_prestamo_beneficiario";
        $data['action_llena_cuotas_prestamo_pdtes'] = base_url() . "index_admon_sistema/llena_cuotas_prestamo_pdtes";
        $data['action_llena_cuenta_responsable'] = base_url() . "index_admon_sistema/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "index_admon_sistema/llena_caja_responsable";

        $this->parser->parse('crear_abono_prestamo', $data);
        $this->load->view('footer');
    }

    function validar_abono_prestamo() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('t_beneficiario', 'Tipo de Usuario Beneficiario', 'required|callback_select_default');
            //Si escogió empleado o cliente, valido los campos
            if ($this->input->post('t_beneficiario') == '1') {
                $this->form_validation->set_rules('empleado', 'Empleado Beneficiario', 'required|callback_select_default');
            } else {
                if ($this->input->post('t_beneficiario') == '4') {
                    $this->form_validation->set_rules('cliente', 'Cliente Beneficiario', 'required|callback_select_default');
                }
            }
            $this->form_validation->set_rules('prestamo', 'Prestamo a Abonar', 'required');
            $this->form_validation->set_rules('cuota', 'Cuota a Cancelar', 'required');
            $this->form_validation->set_rules('valor_consignado', 'Valor Consignado a la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_ingresado', 'Efectivo Ingresado a la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');

            $error_valores = "";
            if (($this->input->post('subtotal')) && ($this->input->post('cuota'))) {
                $this->form_validation->set_rules('subtotal', 'Valor del Abono', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
                $subtotal = round(str_replace(",", "", $this->input->post('subtotal')), 2);
                $total = round(str_replace(",", "", $this->input->post('total')), 2);
                $abono_minimo = $this->input->post('abono_minimo');
                $abono_maximo = $this->input->post('abono_maximo');
                if ($subtotal > $abono_maximo) {
                    $error_valores = "<p>El valor del Abono no puede ser mayor que el Abono Máximo: $" . number_format($abono_maximo, 2, '.', ',') . ".</p>";
                } else {
                    if ($subtotal < $abono_minimo) {
                        $error_valores = "<p>El valor del Abono no puede ser menor que el Abono Mínimo: $" . number_format($abono_minimo, 2, '.', ',') . ".</p>";
                    } else {
                        if (!$this->input->post('valor_consignado')) {
                            $valor_consignado = 0;
                        } else {
                            $valor_consignado = round(str_replace(",", "", $this->input->post('valor_consignado')), 2);
                        }
                        if (!$this->input->post('efectivo_ingresado')) {
                            $efectivo_ingresado = 0;
                        } else {
                            $efectivo_ingresado = round(str_replace(",", "", $this->input->post('efectivo_ingresado')), 2);
                        }
                        if (round(($valor_consignado + $efectivo_ingresado), 2) != $total) {
                            $error_valores = "<p>La suma del valor consignado a la cuenta y el efectivo ingresado a la caja, deben sumar exactamente: $" . $this->input->post('total') . ", en vez de: $" . number_format(($valor_consignado + $efectivo_ingresado), 2, '.', ',') . ".</p>";
                        }
                    }
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_valores != "")) {
                echo form_error('t_beneficiario') . form_error('cliente') . form_error('empleado') . form_error('prestamo') . form_error('cuota') . form_error('subtotal') . form_error('valor_consignado') . form_error('efectivo_ingresado') . $error_valores . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function new_abono_prestamo() {
        if ($this->input->post('submit')) {
            list($prefijo_prestamo, $id_prestamo) = explode("-", $this->input->post('prestamo'));
            $subtotal = round(str_replace(",", "", $this->input->post('subtotal')), 2);
            $cant_dias_mora = $this->input->post('cant_dias_mora');
            $int_mora = round(str_replace(",", "", $this->input->post('int_mora')), 2);

            if (($this->input->post('cuenta')) && ($this->input->post('valor_consignado')) && ($this->input->post('valor_consignado') != 0)) {
                $cuenta_destino = $this->input->post('cuenta');
                $valor_consignado = round(str_replace(",", "", $this->input->post('valor_consignado')), 2);
            } else {
                $cuenta_destino = NULL;
                $valor_consignado = NULL;
            }
            if (($this->input->post('caja')) && ($this->input->post('efectivo_ingresado')) && ($this->input->post('efectivo_ingresado') != 0)) {
                list($sede_caja_destino, $t_caja_destino) = explode("-", $this->input->post('caja'));
                $efectivo_ingresado = round(str_replace(",", "", $this->input->post('efectivo_ingresado')), 2);
            } else {
                $sede_caja_destino = NULL;
                $t_caja_destino = NULL;
                $efectivo_ingresado = NULL;
            }
            $vigente = 1;
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;


            $error = $this->insert_model->abono_prestamo($prefijo_prestamo, $id_prestamo, $subtotal, $cant_dias_mora, $int_mora, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $sede, $vigente, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "index_admon_sistema/crear_abono_prestamo";
            $data['msn_recrear'] = "Crear otro Abono a Prestamo";
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                //Si no hubo error entonces si el saldo es igual al total abonado, entonces lo colocamos paz y salvo
                $abono_maximo = $this->input->post('abono_maximo');
                if ($subtotal == $abono_maximo) {
                    $new_estado = 3; //Paz y Salvo Voluntario   
                    $this->update_model->prestamo_estado($prefijo_prestamo, $id_prestamo, $new_estado);
                }
                $this->parser->parse('trans_success', $data);
            }
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url());
        }
    }

//Crear: Nomina
    function crear_nomina() {
        $data = $this->navbar();
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['empleado'] = $this->select_model->empleado_sede_ppal_responsable($id_responsable, $dni_responsable);
//        $data['t_concepto_nomina'] = $this->select_model->t_concepto_nomina_depto_empleado();

        $data['action_validar'] = base_url() . "index_admon_sistema/validar_nomina";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_nomina";
        $data['action_recargar'] = base_url() . "index_admon_sistema/crear_nomina";
        $data['action_llena_info_contrato_laboral'] = base_url() . "index_admon_sistema/llena_info_contrato_laboral";
        $data['action_llena_info_ultimas_nominas'] = base_url() . "index_admon_sistema/llena_info_ultimas_nominas";
        $data['action_llena_info_adelantos'] = base_url() . "index_admon_sistema/llena_info_adelantos";
        $data['action_llena_info_prestamos'] = base_url() . "index_admon_sistema/llena_info_prestamos";
        $data['action_llena_info_ausencias'] = base_url() . "index_admon_sistema/llena_info_ausencias";
        $data['action_llena_info_seguridad_social'] = base_url() . "index_admon_sistema/llena_info_seguridad_social";
        $data['action_llena_concepto_pdtes_rrpp'] = base_url() . "index_admon_sistema/llena_concepto_pdtes_rrpp";
        $data['action_llena_agregar_concepto'] = base_url() . "index_admon_sistema/llena_agregar_concepto";
        $data['action_llena_info_t_concepto'] = base_url() . "index_admon_sistema/llena_info_t_concepto";

        $data['action_llena_cuenta_responsable'] = base_url() . "index_admon_sistema/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "index_admon_sistema/llena_caja_responsable";
        $this->parser->parse('crear_nomina', $data);
        $this->load->view('footer');
    }

    function validar_nomina() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('empleado', 'Empleado', 'required|callback_select_default');
            //coloca maxlength en 15, para incluir los puntos de miles 888.777.666.273
            $this->form_validation->set_rules('total', 'Valor del Adelanto', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('valor_retirado', 'Valor Retirado de la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_retirado', 'Valor Retirado de la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
            $error_valores = "";
            if ($this->input->post('total')) {
                $total = round(str_replace(",", "", $this->input->post('total')), 2);
                if (!$this->input->post('valor_retirado')) {
                    $valor_retirado = 0;
                } else {
                    $valor_retirado = round(str_replace(",", "", $this->input->post('valor_retirado')), 2);
                }
                if (!$this->input->post('efectivo_retirado')) {
                    $efectivo_retirado = 0;
                } else {
                    $efectivo_retirado = round(str_replace(",", "", $this->input->post('efectivo_retirado')), 2);
                }
                if (round(($valor_retirado + $efectivo_retirado), 2) != $total) {
                    $error_valores = "<p>La suma del valor retirado de una cuenta y el efectivo retirado de una caja, deben sumar exactamente: $" . $this->input->post('total') . ", en vez de: $" . number_format(($valor_retirado + $efectivo_retirado), 2, '.', ',') . ".</p>";
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_valores != "")) {
                echo form_error('empleado') . form_error('total') . form_error('valor_retirado') . form_error('efectivo_retirado') . $error_valores . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function new_nomina() {
        if ($this->input->post('submit')) {
            list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
            $total = round(str_replace(",", "", $this->input->post('total')), 2);
            if (($this->input->post('cuenta')) && ($this->input->post('valor_retirado')) && ($this->input->post('valor_retirado') != 0)) {
                $cuenta_origen = $this->input->post('cuenta');
                $valor_retirado = round(str_replace(",", "", $this->input->post('valor_retirado')), 2);
            } else {
                $cuenta_origen = NULL;
                $valor_retirado = NULL;
            }
            if (($this->input->post('caja')) && ($this->input->post('efectivo_retirado')) && ($this->input->post('efectivo_retirado') != 0)) {
                list($sede_caja_origen, $t_caja_origen) = explode("-", $this->input->post('caja'));
                $efectivo_retirado = round(str_replace(",", "", $this->input->post('efectivo_retirado')), 2);
            } else {
                $sede_caja_origen = NULL;
                $t_caja_origen = NULL;
                $efectivo_retirado = NULL;
            }
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;


            $error = $this->insert_model->adelanto($id_empleado, $dni_empleado, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, 1, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "index_admon_sistema/crear_nomina";
            $data['msn_recrear'] = "Crear otra Nómina";
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                $this->parser->parse('trans_success', $data);
            }
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url());
        }
    }

//Crear: Egreso
    function crear_egreso() {
        $data = $this->navbar();
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];

        $data['t_egreso'] = $this->select_model->t_egreso();
        $data['t_beneficiario'] = $this->select_model->t_usuario_ingreso_egreso();
        $data['dni'] = $this->select_model->t_dni_todos();
        $data['action_validar'] = base_url() . "index_admon_sistema/validar_egreso";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_egreso";

        $data['action_llena_cuenta_responsable'] = base_url() . "index_admon_sistema/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "index_admon_sistema/llena_caja_responsable";

        $this->parser->parse('crear_egreso', $data);
        $this->load->view('footer');
    }

    function validar_egreso() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('t_egreso', 'Tipo de Egreso', 'required|callback_select_default');
            $this->form_validation->set_rules('t_beneficiario', 'Tipo de Usuario Beneficiario', 'required|callback_select_default');
            $this->form_validation->set_rules('dni_beneficiario', 'Tipo Id. Beneficiario', 'required|callback_select_default');
            $this->form_validation->set_rules('id_beneficiario', 'Número Id. Beneficiario', 'required|trim|min_length[5]|max_length[13]|integer|callback_valor_positivo');
            if ($this->input->post('t_beneficiario') == '6') {
                $this->form_validation->set_rules('nombre_beneficiario', 'Nombre Beneficiario', 'required|trim|xss_clean|max_length[100]');
            }
            $this->form_validation->set_rules('total', 'Valor del Egreso', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('valor_retirado', 'Valor Retirado de la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_retirado', 'Valor Retirado de la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');

            //Validamos que los usuarios si existan
            $error_key_exists = "";
            if (($this->input->post('t_beneficiario') != "default") && ($this->input->post('dni_beneficiario') != "default") && $this->input->post('id_beneficiario')) {
                if ($this->input->post('t_beneficiario') == '1') {
                    $t_usuario = 1; //Empleado
                    $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_beneficiario'), $this->input->post('dni_beneficiario'), $t_usuario);
                    if ($check_usuario != TRUE) {
                        $error_key_exists = "<p>El Empleado ingresado, no existe en la Base de Datos.</p>";
                    }
                } else {
                    if ($this->input->post('t_beneficiario') == '2') {
                        $t_usuario = 2; //Titular
                        $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_beneficiario'), $this->input->post('dni_beneficiario'), $t_usuario);
                        if ($check_usuario != TRUE) {
                            $error_key_exists = "<p>El Titular ingresado, no existe en la Base de Datos.</p>";
                        }
                    } else {
                        if ($this->input->post('t_beneficiario') == '3') {
                            $t_usuario = 3; //Alumno
                            $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_beneficiario'), $this->input->post('dni_beneficiario'), $t_usuario);
                            if ($check_usuario != TRUE) {
                                $error_key_exists = "<p>El Alumno ingresado, no existe en la Base de Datos.</p>";
                            }
                        } else {
                            if ($this->input->post('t_beneficiario') == '4') {
                                $t_usuario = 4; //Cliente Prestatario
                                $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_beneficiario'), $this->input->post('dni_beneficiario'), $t_usuario);
                                if ($check_usuario != TRUE) {
                                    $error_key_exists = "<p>El Cliente Prestatario ingresado, no existe en la Base de Datos.</p>";
                                }
                            } else {
                                if ($this->input->post('t_beneficiario') == '5') {
                                    if ($this->input->post('dni_beneficiario') != "6") {
                                        $d_v = NULL;
                                    } else {
                                        $d_v = $this->input->post('d_v');
                                    }
                                    $check_usuario = $this->select_model->proveedor_id_dni($this->input->post('id_beneficiario'), $this->input->post('dni_beneficiario'), $d_v);
                                    if ($check_usuario != TRUE) {
                                        $error_key_exists = "<p>El Proveedor ingresado, no existe en la Base de Datos.</p>";
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $error_valores = "";
            if ($this->input->post('total')) {
                $total = round(str_replace(",", "", $this->input->post('total')), 2);
                if (!$this->input->post('valor_retirado')) {
                    $valor_retirado = 0;
                } else {
                    $valor_retirado = round(str_replace(",", "", $this->input->post('valor_retirado')), 2);
                }
                if (!$this->input->post('efectivo_retirado')) {
                    $efectivo_retirado = 0;
                } else {
                    $efectivo_retirado = round(str_replace(",", "", $this->input->post('efectivo_retirado')), 2);
                }
                if (round(($valor_retirado + $efectivo_retirado), 2) != $total) {
                    $error_valores = "<p>La suma del valor retirado de una cuenta y el efectivo retirado de una caja, deben sumar exactamente: $" . $this->input->post('total') . ", en vez de: $" . number_format(($valor_retirado + $efectivo_retirado), 2, '.', ',') . ".</p>";
                }
            }

            if ((($this->input->post('t_egreso')) == "8") || (($this->input->post('t_egreso')) == "9")) { //t_egreso = 8: Otros
                $this->form_validation->set_rules('descripcion', 'Descripcion', 'required|trim|xss_clean|max_length[255]');
            } else {
                $this->form_validation->set_rules('descripcion', 'Descripcion', 'trim|xss_clean|max_length[255]');
            }

            if (($this->form_validation->run() == FALSE) || ($error_valores != "") || ($error_key_exists != "")) {
                echo form_error('t_egreso') . form_error('t_beneficiario') . form_error('dni_beneficiario') . form_error('id_beneficiario') . form_error('nombre_beneficiario') . $error_key_exists . form_error('total') . form_error('valor_retirado') . form_error('efectivo_retirado') . $error_valores . form_error('descripcion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function new_egreso() {
        if ($this->input->post('submit')) {
            $t_egreso = $this->input->post('t_egreso');
            $t_beneficiario = $this->input->post('t_beneficiario');
            $dni_beneficiario = $this->input->post('dni_beneficiario');
            $id_beneficiario = $this->input->post('id_beneficiario');
            if ($dni_beneficiario != "6") {
                $d_v = NULL;
            } else {
                $d_v = $this->input->post('d_v');
            }
            if (($t_beneficiario == 1) || ($t_beneficiario == 2) || ($t_beneficiario == 3) || ($t_beneficiario == 4) || ($t_beneficiario == 5)) {
                $nombre_beneficiario = NULL;
            } else {
                $nombre_beneficiario = ucwords(strtolower($this->input->post('nombre_beneficiario')));
            }
            $total = round(str_replace(",", "", $this->input->post('total')), 2);
            if (($this->input->post('cuenta')) && ($this->input->post('valor_retirado')) && ($this->input->post('valor_retirado') != 0)) {
                $cuenta_origen = $this->input->post('cuenta');
                $valor_retirado = round(str_replace(",", "", $this->input->post('valor_retirado')), 2);
            } else {
                $cuenta_origen = NULL;
                $valor_retirado = NULL;
            }
            if (($this->input->post('caja')) && ($this->input->post('efectivo_retirado')) && ($this->input->post('efectivo_retirado') != 0)) {
                list($sede_caja_origen, $t_caja_origen) = explode("-", $this->input->post('caja'));
                $efectivo_retirado = round(str_replace(",", "", $this->input->post('efectivo_retirado')), 2);
            } else {
                $sede_caja_origen = NULL;
                $t_caja_origen = NULL;
                $efectivo_retirado = NULL;
            }
            $descripcion = ucfirst(strtolower($this->input->post('descripcion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_egreso = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_egreso = ($this->select_model->nextId_egreso($prefijo_egreso)->id) + 1;

            $error = $this->insert_model->egreso($prefijo_egreso, $id_egreso, $t_egreso, $t_beneficiario, $id_beneficiario, $dni_beneficiario, $d_v, $nombre_beneficiario, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, 1, $descripcion, $fecha_trans, $id_responsable, $dni_responsable);

            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "index_admon_sistema/crear_egreso";
            $data['msn_recrear'] = "Crear otro Egreso";
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                $this->parser->parse('trans_success', $data);
            }
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url());
        }
    }

    //Crear: Contrato Físico
    function crear_contrato_fisico() {
        $data = $this->navbar();
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['sede'] = $this->select_model->sede_activa_responsable($id_responsable, $dni_responsable);
        $data['action_validar'] = base_url() . "index_admon_sistema/validar_contrato_fisico";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_contrato_fisico";
        $this->parser->parse('crear_contrato_fisico', $data);
        $this->load->view('footer');
    }

    function validar_contrato_fisico() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('contrato_inicial', 'Número de Contrato Inicial', 'required|trim|min_length[3]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('contrato_final', 'Número de Contrato Final', 'required|trim|min_length[3]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('sede_actual', 'Sede Actual', 'required|callback_select_default');

            //Cuando ya se hayan validado que los campos tengan el tamaño ideal y el required entonces validamos lo otro
            $error_valores = "";
            if ($this->form_validation->run() != FALSE) {
                $contrato_inicial = $this->input->post('contrato_inicial');
                $contrato_final = $this->input->post('contrato_final');
                if ($contrato_inicial > $contrato_final) {
                    $error_valores = "<p>El campo Contrato Inicial, debe ser menor o igual al campo Contrato Final</p>";
                } else {
                    //Maxímo una insercion de 10.000 contratos.
                    if (($contrato_final - $contrato_inicial) >= 10000) {
                        $error_valores = "<p>La inserción masiva, es de máximo 10.000 Contratos (Ejemplo: 20000->29999).</p>";
                    } else {
                        $bandera_contrato = 0;
                        $error_valores = "<p>Los siguientes contratos, ya existen en la base de datos: ";
                        for ($i = $contrato_inicial; $i <= $contrato_final; $i++) {
                            $check_contrato_fisico = $this->select_model->contrato_matricula_id($i);
                            if ($check_contrato_fisico == TRUE) {
                                $error_valores .= $i . " ";
                                $bandera_contrato = 1;
                            }
                        }
                        $error_valores .= "</p>";
                        if ($bandera_contrato == 0) {
                            $error_valores = "";
                        }
                    }
                }
            }

            if (($this->form_validation->run() == FALSE) || ($error_valores != "")) {
                echo form_error('contrato_inicial') . form_error('contrato_final') . $error_valores . form_error('sede_actual');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function new_contrato_fisico() {
        //si se ha pulsado el botón submit validamos el formulario con codeIgniter
        //Esto es muy importante, porq de lo contrario, podrian haber accedido aqui por la url directamente y daria error porq no vienen datos.
        if ($this->input->post('submit')) {
            $contrato_inicial = ucwords(strtolower($this->input->post('contrato_inicial')));
            $contrato_final = ucwords(strtolower($this->input->post('contrato_final')));
            $sede_actual = ucwords(strtolower($this->input->post('sede_actual')));
            $estado = 1; //1:Vacio
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "index_admon_sistema/crear_contrato_fisico";
            $data['msn_recrear'] = "Crear otros Contratos Físicos";

            for ($i = $contrato_inicial; $i <= $contrato_final; $i++) {
                $error = $this->insert_model->contrato_matricula($i, $sede_actual, $estado, $fecha_trans, $id_responsable, $dni_responsable);
                if (isset($error)) {
                    $data['trans_error'] = $error;
                    $this->parser->parse('trans_error', $data);
                    $this->parser->parse('welcome', $data);
                    $this->load->view('footer');
                    return;
                }
            }
            $this->parser->parse('trans_success', $data);
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url());
        }
    }

    //Crear: Traslado Contrato Físico
    function crear_traslado_contrato() {
        $data = $this->navbar();
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['sede_actual'] = $this->select_model->sede_activa_responsable($id_responsable, $dni_responsable);
        $data['sede_destino'] = $this->select_model->sede_activa();
        $data['action_validar'] = base_url() . "index_admon_sistema/validar_traslado_contrato";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_traslado_contrato";
        $this->parser->parse('crear_traslado_contrato', $data);
        $this->load->view('footer');
    }

    function validar_traslado_contrato() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('contrato_inicial', 'Número de Contrato Inicial', 'required|trim|min_length[3]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('contrato_final', 'Número de Contrato Final', 'required|trim|min_length[3]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('sede_actual', 'Sede Actual', 'required|callback_select_default');
            $this->form_validation->set_rules('sede_destino', 'Sede Destino', 'required|callback_select_default');

            //Cuando ya se hayan validado que los campos tengan el tamaño ideal y el required entonces validamos lo otro
            $error_valores = "";
            if ($this->form_validation->run() != FALSE) {
                $contrato_inicial = $this->input->post('contrato_inicial');
                $contrato_final = $this->input->post('contrato_final');
                if ($contrato_inicial > $contrato_final) {
                    $error_valores = "<p>El campo Contrato Inicial, debe ser menor o igual al campo Contrato Final</p>";
                } else {
                    //Maxímo una insercion de 1.000 contratos.
                    if (($contrato_final - $contrato_inicial) >= 1000) {
                        $error_valores = "<p>La inserción masiva, es de máximo 1.000 Contratos (Ejemplo: 20000->20999).</p>";
                    } else {
                        $bandera_contrato = 0;
                        $error_valores = "<p>Los siguientes contratos, no existen en la base de datos: ";
                        for ($i = $contrato_inicial; $i <= $contrato_final; $i++) {
                            $check_contrato_fisico = $this->select_model->contrato_matricula_id($i);
                            if ($check_contrato_fisico != TRUE) {
                                $error_valores .= $i . " ";
                                $bandera_contrato = 1;
                            }
                        }
                        $error_valores .= "</p>";
                        if ($bandera_contrato == 0) { //Porque no hubo errror de existencia
                            $sede_actual = $this->input->post('sede_actual');
                            $error_valores = "<p>Los siguientes contratos, no se encuentran en la Sede Actual ingresada: ";
                            for ($i = $contrato_inicial; $i <= $contrato_final; $i++) {
                                $check_contrato_fisico = $this->select_model->contrato_matricula_id_sede($i, $sede_actual);
                                if ($check_contrato_fisico != TRUE) {
                                    $error_valores .= $i . " ";
                                    $bandera_contrato = 1;
                                }
                            }
                            $error_valores .= "</p>";
                            if ($bandera_contrato == 0) { //Porque no hubo errror de existencia
                                $error_valores = "";
                            }
                        }
                    }
                }
            }

            $error_sedes = "";
            if ((($this->input->post('sede_actual')) != "default") && (($this->input->post('sede_destino')) != "default")) {
                if ($this->input->post('sede_actual') == $this->input->post('sede_destino')) {
                    $error_sedes = "<p>La Sede Destino, debe ser distinta a la Sede Actual.</p>";
                }
            }

            if (($this->form_validation->run() == FALSE) || ($error_valores != "") || ($error_sedes != "")) {
                echo form_error('contrato_inicial') . form_error('contrato_final') . $error_valores . form_error('sede_actual') . form_error('sede_destino') . $error_sedes;
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function new_traslado_contrato() {
        //si se ha pulsado el botón submit validamos el formulario con codeIgniter
        //Esto es muy importante, porq de lo contrario, podrian haber accedido aqui por la url directamente y daria error porq no vienen datos.
        if ($this->input->post('submit')) {
            $contrato_inicial = ucwords(strtolower($this->input->post('contrato_inicial')));
            $contrato_final = ucwords(strtolower($this->input->post('contrato_final')));
            $sede_actual = ucwords(strtolower($this->input->post('sede_actual')));
            $sede_destino = ucwords(strtolower($this->input->post('sede_destino')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "index_admon_sistema/crear_traslado_contrato";
            $data['msn_recrear'] = "Crear otro Traslado de Contratos";

            for ($i = $contrato_inicial; $i <= $contrato_final; $i++) {
                $error = $this->update_model->contrato_matricula_sede_actual($i, $sede_destino);
                if (isset($error)) {
                    $data['trans_error'] = $error;
                    $this->parser->parse('trans_error', $data);
                    $this->parser->parse('welcome', $data);
                    $this->load->view('footer');
                    return;
                } else {
                    $error1 = $this->insert_model->traslado_contrato($i, $sede_actual, $sede_destino, 2, $fecha_trans, $id_responsable, $dni_responsable);
                    if (isset($error1)) {
                        $data['trans_error'] = $error1;
                        $this->parser->parse('trans_error', $data);
                        $this->parser->parse('welcome', $data);
                        $this->load->view('footer');
                        return;
                    }
                }
            }
            $this->parser->parse('trans_success', $data);
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url());
        }
    }

    //Crear: Contrato Físico
    function crear_liquidar_matricula($contrato) {
        $data = $this->navbar();

        $data['id_matricula'] = "$contrato";

        $data['action_validar'] = base_url() . "index_admon_sistema/validar_liquidar_matricula";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_liquidar_matricula";

        $data['action_llena_matricula_iliquidada'] = base_url() . "index_admon_sistema/llena_matricula_iliquidada";
        $data['action_llena_detalle_matricula'] = base_url() . "index_admon_sistema/llena_detalle_matricula_liquidar";
        $data['action_llena_ejecutivo'] = base_url() . "index_admon_sistema/llena_empleado_rrpp_sedePpal";
        $data['action_llena_cargo_comision_faltante'] = base_url() . "index_admon_sistema/llena_cargo_comision_faltante";
        $data['action_llena_cargo_ejecutivo_directo'] = base_url() . "index_admon_sistema/llena_cargo_ejecutivo_directo";

        $this->parser->parse('crear_liquidar_matricula', $data);
        $this->load->view('footer');
    }

    function validar_liquidar_matricula() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('matricula', 'Número de Matrícula', 'required|callback_select_default');
            $this->form_validation->set_rules('ejecutivo_directo', 'Comisión Directa', 'required|callback_select_default');

            $error_escalas = "";
            if (($this->input->post('escalas')) && ($this->input->post('cargos_escalas'))) {
                $cargos_escalas = $this->input->post('cargos_escalas');
                $escalas = $this->input->post('escalas');
                $i = 0;
                foreach ($escalas as $fila) {
                    if ($fila == "default") {
                        list($id_cargo, $nombre_cargo) = explode("-", $cargos_escalas[$i]);
                        $error_escalas .= "<p>La Escala: " . $nombre_cargo . ", es obligatoria.</p>";
                    }
                    $i++;
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_escalas != "")) {
                echo form_error('matricula') . form_error('ejecutivo_directo') . $error_escalas;
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function new_liquidar_matricula() {
        if ($this->input->post('submit')) {
            $id_matricula = $this->input->post('matricula');
            list($id_ejecutivo_original, $dni_ejecutivo_original, $cargo_ejecutivo_original) = explode("-", $this->input->post('ejecutivo_original'));
            list($id_ejecutivo_directo, $dni_ejecutivo_directo, $cargo_ejecutivo_directo) = explode("-", $this->input->post('ejecutivo_directo'));

            $est_concepto_nomina = 2; //2: Pendiente
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;

            $matricula = $this->select_model->matricula_id($id_matricula);
            $t_concepto_nomina = 29; //29, 'Comisión Directa Matricula
            $plan = $matricula->plan;
            $valor_unitario = $this->select_model->comision_matricula($plan, $cargo_ejecutivo_directo)->comision;
            //Si no encuentra en la base de datos la comision, entonces la comision es cero.
            if ($valor_unitario != TRUE) {
                $valor_unitario = 0.00;
            }
            $detalle = "Matrícula: " . $id_matricula;

            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "index_admon_sistema/crear_liquidar_matricula/new";
            $data['msn_recrear'] = "Crear otra Liquidación de Matrícula.";

            //si cambiaron el ejectuivo principal lo cambiamos en la matricula
            if ($id_ejecutivo_original != $id_ejecutivo_directo) {
                $error = $this->update_model->ejecutivo_matricula($id_matricula, $id_ejecutivo_directo, $dni_ejecutivo_directo);
                if (isset($error)) {
                    $data['trans_error'] = $error;
                    $this->parser->parse('trans_error', $data);
                    $this->parser->parse('welcome', $data);
                    $this->load->view('footer');
                    return;
                } else {
                    $this->insert_model->cambio_ejecutivo_matricula($id_matricula, $id_ejecutivo_original, $dni_ejecutivo_original, $id_ejecutivo_directo, $dni_ejecutivo_directo, $fecha_trans, $id_responsable, $dni_responsable);
                }
            }

            $error1 = $this->insert_model->concepto_nomina($id_ejecutivo_directo, $dni_ejecutivo_directo, NULL, NULL, $t_concepto_nomina, $detalle, $id_matricula, $plan, $cargo_ejecutivo_directo, $cargo_ejecutivo_directo, 1, $valor_unitario, $est_concepto_nomina, $sede, $fecha_trans, $id_responsable, $dni_responsable);
            if (isset($error1)) {
                $data['trans_error'] = $error1;
                $this->parser->parse('trans_error', $data);
            } else {
                $cargos_escalas = $this->input->post('cargos_escalas');
                $escalas = $this->input->post('escalas');
                //Si hay escalas las pagamos.
                if (($cargos_escalas == TRUE) && ($escalas == TRUE)) {
                    $t_concepto_nomina = 28; //28, 'Comisión Escala Matricula
                    $i = 0;
                    foreach ($escalas as $fila) {
                        if ($fila != "nula") {
                            list($id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo) = explode("-", $fila);
                            list($cargo_escala, $nombre_cargo) = explode("-", $cargos_escalas[$i]);
                            $valor_unitario = $this->select_model->comision_escala($plan, $cargo_escala)->comision;
                            if ($valor_unitario != TRUE) {
                                $valor_unitario = 0.00;
                            }
                            $error2 = $this->insert_model->concepto_nomina($id_ejecutivo, $dni_ejecutivo, NULL, NULL, $t_concepto_nomina, $detalle, $id_matricula, $plan, $cargo_escala, $cargo_ejecutivo, 1, $valor_unitario, $est_concepto_nomina, $sede, $fecha_trans, $id_responsable, $dni_responsable);
                            if (isset($error2)) {
                                $data['trans_error'] = $error2;
                                $this->parser->parse('trans_error', $data);
                                $this->parser->parse('welcome', $data);
                                $this->load->view('footer');
                                return;
                            }
                        }
                        $i++;
                    }
                }
                //ACtualizamos el estado de la matricula a liquidada.
                $error2 = $this->update_model->matricula_liquidacion_escalas($id_matricula, 1);
                if (isset($error2)) {
                    $data['trans_error'] = $error2;
                    $this->parser->parse('trans_error', $data);
                } else {
                    $this->parser->parse('trans_success', $data);
                }
            }
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url());
        }
    }

//Crear: Ingreso
    function crear_ingreso() {
        $data = $this->navbar();
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];

        $data['t_ingreso'] = $this->select_model->t_ingreso();
        $data['t_depositante'] = $this->select_model->t_usuario_ingreso_egreso();
        $data['dni'] = $this->select_model->t_dni_todos();
        $data['action_validar'] = base_url() . "index_admon_sistema/validar_ingreso";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_ingreso";

        $data['action_llena_cuenta_responsable'] = base_url() . "index_admon_sistema/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "index_admon_sistema/llena_caja_responsable";

        $this->parser->parse('crear_ingreso', $data);
        $this->load->view('footer');
    }

    function validar_ingreso() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('t_ingreso', 'Tipo de Ingreso', 'required|callback_select_default');
            $this->form_validation->set_rules('t_depositante', 'Tipo de Usuario Depositante', 'required|callback_select_default');
            $this->form_validation->set_rules('dni_depositante', 'Tipo Id. Depositante', 'required|callback_select_default');
            $this->form_validation->set_rules('id_depositante', 'Número Id. Depositante', 'required|trim|min_length[5]|max_length[13]|integer|callback_valor_positivo');
            if ($this->input->post('t_depositante') == '6') {
                $this->form_validation->set_rules('nombre_depositante', 'Nombre Depositante', 'required|trim|xss_clean|max_length[100]');
            }
            $this->form_validation->set_rules('total', 'Valor del Ingreso', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('valor_consignado', 'Valor Consignado a la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_ingresado', 'Efectivo Ingresado a la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');

            //Validamos que los usuarios si existan
            $error_key_exists = "";
            if (($this->input->post('t_depositante') != "default") && ($this->input->post('dni_depositante') != "default") && $this->input->post('id_depositante')) {
                if ($this->input->post('t_depositante') == '1') {
                    $t_usuario = 1; //Empleado
                    $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_depositante'), $this->input->post('dni_depositante'), $t_usuario);
                    if ($check_usuario != TRUE) {
                        $error_key_exists = "<p>El Empleado ingresado, no existe en la Base de Datos.</p>";
                    }
                } else {
                    if ($this->input->post('t_depositante') == '2') {
                        $t_usuario = 2; //Titular
                        $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_depositante'), $this->input->post('dni_depositante'), $t_usuario);
                        if ($check_usuario != TRUE) {
                            $error_key_exists = "<p>El Titular ingresado, no existe en la Base de Datos.</p>";
                        }
                    } else {
                        if ($this->input->post('t_depositante') == '3') {
                            $t_usuario = 3; //Alumno
                            $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_depositante'), $this->input->post('dni_depositante'), $t_usuario);
                            if ($check_usuario != TRUE) {
                                $error_key_exists = "<p>El Alumno ingresado, no existe en la Base de Datos.</p>";
                            }
                        } else {
                            if ($this->input->post('t_depositante') == '4') {
                                $t_usuario = 4; //Cliente Prestatario
                                $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_depositante'), $this->input->post('dni_depositante'), $t_usuario);
                                if ($check_usuario != TRUE) {
                                    $error_key_exists = "<p>El Cliente Prestatario ingresado, no existe en la Base de Datos.</p>";
                                }
                            } else {
                                if ($this->input->post('t_depositante') == '5') {
                                    if ($this->input->post('dni_depositante') != "6") {
                                        $d_v = NULL;
                                    } else {
                                        $d_v = $this->input->post('d_v');
                                    }
                                    $check_usuario = $this->select_model->proveedor_id_dni($this->input->post('id_depositante'), $this->input->post('dni_depositante'), $d_v);
                                    if ($check_usuario != TRUE) {
                                        $error_key_exists = "<p>El Proveedor ingresado, no existe en la Base de Datos.</p>";
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $error_valores = "";
            if ($this->input->post('total')) {
                $total = round(str_replace(",", "", $this->input->post('total')), 2);
                if (!$this->input->post('valor_consignado')) {
                    $valor_consignado = 0;
                } else {
                    $valor_consignado = round(str_replace(",", "", $this->input->post('valor_consignado')), 2);
                }
                if (!$this->input->post('efectivo_ingresado')) {
                    $efectivo_ingresado = 0;
                } else {
                    $efectivo_ingresado = round(str_replace(",", "", $this->input->post('efectivo_ingresado')), 2);
                }
                if (round(($valor_consignado + $efectivo_ingresado), 2) != $total) {
                    $error_valores = "<p>La suma del valor consignado a la cuenta y el efectivo ingresado a la caja, deben sumar exactamente: $" . $this->input->post('total') . ", en vez de: $" . number_format(($valor_consignado + $efectivo_ingresado), 2, '.', ',') . ".</p>";
                }
            }

            if ((($this->input->post('t_ingreso')) == "4") || (($this->input->post('t_ingreso')) == "5")) { //t_ingreso = 4: Otros
                $this->form_validation->set_rules('descripcion', 'Descripcion', 'required|trim|xss_clean|max_length[255]');
            } else {
                $this->form_validation->set_rules('descripcion', 'Descripcion', 'trim|xss_clean|max_length[255]');
            }

            if (($this->form_validation->run() == FALSE) || ($error_valores != "") || ($error_key_exists != "")) {
                echo form_error('t_ingreso') . form_error('t_depositante') . form_error('dni_depositante') . form_error('id_depositante') . form_error('nombre_depositante') . $error_key_exists . form_error('total') . form_error('valor_consignado') . form_error('efectivo_ingresado') . $error_valores . form_error('descripcion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function new_ingreso() {
        if ($this->input->post('submit')) {
            $t_ingreso = $this->input->post('t_ingreso');
            $t_depositante = $this->input->post('t_depositante');
            $dni_depositante = $this->input->post('dni_depositante');
            $id_depositante = $this->input->post('id_depositante');
            if ($dni_depositante != "6") {
                $d_v = NULL;
            } else {
                $d_v = $this->input->post('d_v');
            }
            if (($t_depositante == 1) || ($t_depositante == 2) || ($t_depositante == 3) || ($t_depositante == 4) || ($t_depositante == 5)) {
                $nombre_depositante = NULL;
            } else {
                $nombre_depositante = ucwords(strtolower($this->input->post('nombre_depositante')));
            }
            $total = round(str_replace(",", "", $this->input->post('total')), 2);
            if (($this->input->post('cuenta')) && ($this->input->post('valor_consignado')) && ($this->input->post('valor_consignado') != 0)) {
                $cuenta_destino = $this->input->post('cuenta');
                $valor_consignado = round(str_replace(",", "", $this->input->post('valor_consignado')), 2);
            } else {
                $cuenta_destino = NULL;
                $valor_consignado = NULL;
            }
            if (($this->input->post('caja')) && ($this->input->post('efectivo_ingresado')) && ($this->input->post('efectivo_ingresado') != 0)) {
                list($sede_caja_destino, $t_caja_destino) = explode("-", $this->input->post('caja'));
                $efectivo_ingresado = round(str_replace(",", "", $this->input->post('efectivo_ingresado')), 2);
            } else {
                $sede_caja_destino = NULL;
                $t_caja_destino = NULL;
                $efectivo_ingresado = NULL;
            }
            $vigente = 1;
            $descripcion = ucfirst(strtolower($this->input->post('descripcion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_ingreso = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_ingreso = ($this->select_model->nextId_ingreso($prefijo_ingreso)->id) + 1;

            $error = $this->insert_model->ingreso($prefijo_ingreso, $id_ingreso, $t_ingreso, $t_depositante, $id_depositante, $dni_depositante, $d_v, $nombre_depositante, $total, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $sede, $vigente, $descripcion, $fecha_trans, $id_responsable, $dni_responsable);

            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "index_admon_sistema/crear_ingreso";
            $data['msn_recrear'] = "Crear otro Ingreso";
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                $this->parser->parse('trans_success', $data);
            }
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url());
        }
    }

    //Crear: Matrícula
    function crear_matricula() {
        $data = $this->navbar();

        $data['dni_titular'] = $this->select_model->t_dni_titular();

        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['empleado'] = $this->select_model->empleado_RRPP_sede_ppal($id_responsable, $dni_responsable);

        $data['action_llena_plan_comercial'] = base_url() . "index_admon_sistema/llena_plan_comercial";
        $data['action_llena_ejecutivo'] = base_url() . "index_admon_sistema/llena_empleado_rrpp_sedePpal";

        $data['action_validar'] = base_url() . "index_admon_sistema/validar_matricula";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_matricula";

        $this->parser->parse('crear_matricula', $data);
        $this->load->view('footer');
    }

    function validar_matricula() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('contrato', 'Número de Contrato Físico', 'required|trim|min_length[3]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('dni_titular', 'Tipo de Id. del Titular', 'required|callback_select_default');
            $this->form_validation->set_rules('id_titular', 'Número de Id. del Titular', 'required|trim|min_length[5]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('fecha_matricula', 'Fecha de Inicio', 'required|xss_clean|callback_fecha_valida');
            $this->form_validation->set_rules('ejecutivo', 'Ejecutivo', 'required|callback_select_default');
            $this->form_validation->set_rules('plan', 'Plan Comercial', 'required');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');

            //Validamos que el número de contrato físico exista en dicha sede
            $error_contrato = "";
            if ($this->input->post('contrato')) {
                $contrato = $this->input->post('contrato');
                $id_responsable = $this->input->post('id_responsable');
                $dni_responsable = $this->input->post('dni_responsable');
                $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;

                $check_contrato = $this->select_model->contrato_matricula_id($contrato);
                if ($check_contrato != TRUE) {
                    $error_contrato = "<p>El contrato físico ingresado, no existe en la base de datos.</p>";
                } else {
                    $check_contrato = $this->select_model->contrato_matricula_id_sede($contrato, $sede);
                    if ($check_contrato != TRUE) {
                        $error_contrato = "<p>El contrato físico ingresado, no se encuentra en su sede principal.</p>";
                    } else {
                        $check_contrato = $this->select_model->contrato_matricula_vacio_id($contrato);
                        if ($check_contrato != TRUE) {
                            $error_contrato = "<p>El contrato físico ingresado, no se encuentra vacío.</p>";
                        }
                    }
                }
            }
            $error_titular = "";
            if (($this->input->post('id_titular')) && ($this->input->post('dni_titular'))) {
                $t_usuario = 2; //Titular
                $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_titular'), $this->input->post('dni_titular'), $t_usuario);
                if ($check_usuario != TRUE) {
                    $error_titular = "<p>El Titular ingresado, no existe en la Base de Datos.</p>";
                }
            }

            if (($this->form_validation->run() == FALSE) || ($error_contrato != "") || ($error_titular != "")) {
                echo form_error('contrato') . $error_contrato . form_error('fecha_matricula') . form_error('dni_titular') . form_error('id_titular') . $error_titular . form_error('ejecutivo') . form_error('plan') . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function new_matricula() {
        if ($this->input->post('submit')) {
            $contrato = $this->input->post('contrato');
            $fecha_matricula = $this->input->post('fecha_matricula');
            $id_titular = $this->input->post('id_titular');
            $dni_titular = $this->input->post('dni_titular');
            list($id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo) = explode("-", $this->input->post('ejecutivo'));
            $plan = $this->input->post('plan');
            //La cantidad de alumnos y materiales es la misma que la que se describe en el plan comercial seleccionado.
            $cant_alumnos_disponibles = $this->select_model->t_plan_id($plan)->cant_alumnos;
            $cant_materiales_disponibles = $cant_alumnos_disponibles;
            $datacredito = 1;
            $juridico = 0;
            $liquidacion_escalas = 0;  //Hasta el moemento no se han creados las comisiones de las escalas
            $estado = 2; //2: Activo            
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;

            $error = $this->insert_model->matricula($contrato, $fecha_matricula, $id_titular, $dni_titular, $id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo, $plan, $cant_alumnos_disponibles, $cant_materiales_disponibles, $datacredito, $juridico, $liquidacion_escalas, $sede, $estado, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            if (isset($error)) {
                $data = $this->navbar();
                $data['trans_error'] = $error;
                $data['url_recrear'] = base_url() . "index_admon_sistema/crear_matricula";
                $data['msn_recrear'] = "Crear otra Matrícula";
                $this->parser->parse('trans_error', $data);
                $this->parser->parse('welcome', $data);
                $this->load->view('footer');
            } else {
                //Si todo salió bien, entonces cambiamos el estado del contrato fisico, de 1:vacío a 2:Activo
                $new_estado = 2;
                $error1 = $this->update_model->contrato_matricula_estado($contrato, $new_estado);
                if (isset($error1)) {
                    $data = $this->navbar();
                    $data['trans_error'] = $error1;
                    $data['url_recrear'] = base_url() . "index_admon_sistema/crear_matricula";
                    $data['msn_recrear'] = "Crear otra Matrícula";
                    $this->parser->parse('trans_error', $data);
                    $this->parser->parse('welcome', $data);
                    $this->load->view('footer');
                    return;
                }

                //Sí todo salió bien, Enviamos al formulario de liquidar_matricula
                redirect(base_url() . 'index_admon_sistema/crear_liquidar_matricula/' . $contrato);
            }
        } else {
            redirect(base_url());
        }
    }

//Crear: Cuenta Bancaria
    function crear_cuenta() {
        $data = $this->navbar();
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['empleado'] = $this->select_model->empleado_sedes_responsable($id_responsable, $dni_responsable);
        $data['sede'] = $this->select_model->sede_activa_responsable($id_responsable, $dni_responsable);
        $data['t_cuenta'] = $this->select_model->t_cuenta();
        $data['pais'] = $this->select_model->pais();

        $data['action_validar'] = base_url() . "index_admon_sistema/validar_cuenta";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_cuenta";
        $data['action_llena_banco_pais'] = base_url() . "index_admon_sistema/llena_banco_pais";
        $this->parser->parse('crear_cuenta', $data);
        $this->load->view('footer');
    }

    function validar_cuenta() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('cuenta', 'Cuenta Bancaria', 'required|trim|min_length[12]|max_length[12]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('t_cuenta', 'Tipo de Cuenta', 'required|callback_select_default');
            $this->form_validation->set_rules('pais', 'País del Banco', 'required|callback_select_default');
            $this->form_validation->set_rules('banco', 'Banco', 'required|callback_select_default');
            $this->form_validation->set_rules('nombre_cuenta', 'Nombre de la Cuenta', 'required|trim|xss_clean|max_length[60]');

            //Validamos que la clave primaria no este repetida
            $duplicate_key = "";
            if ($this->input->post('cuenta')) {
                $check_usuario = $this->select_model->cuenta_banco_id($this->input->post('cuenta'));
                if ($check_usuario == TRUE) {
                    $duplicate_key = "<p>La Cuenta ingresada ya existe en la Base de Datos.</p>";
                }
            }

            if (($this->form_validation->run() == FALSE) || ($duplicate_key != "")) {
                echo form_error('cuenta') . $duplicate_key . form_error('t_cuenta') . form_error('pais') . form_error('banco') . form_error('nombre_cuenta');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function new_cuenta() {
        if ($this->input->post('submit')) {
            $cuenta = $this->input->post('cuenta');
            $t_cuenta = $this->input->post('t_cuenta');
            $banco = $this->input->post('banco');
            $nombre_cuenta = ucwords(strtolower($this->input->post('nombre_cuenta')));
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $error = $this->insert_model->cuenta($cuenta, $t_cuenta, $banco, $nombre_cuenta, 1, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "index_admon_sistema/crear_cuenta";
            $data['msn_recrear'] = "Crear otra Cuenta";
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                //Cuando cree una cuenta, automaticamente se la debe asignar al sistema, para que tenga acceso a todo.
                //Colocamos vigente en cero porq al momento de crear una cuenta logicamente no esta asignada a ninguna sede.
                $this->insert_model->cuenta_x_sede($cuenta, 1, 0);
                $this->insert_model->cuenta_x_sede_x_empleado($cuenta, 1, 1, 1, 1);
                $this->parser->parse('trans_success', $data);
            }
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url());
        }
    }

    //Editar: Sede Empleado
    function editar_sedes_empleado() {
        $data = $this->navbar();
        $data['empleado'] = $this->select_model->empleado_activo();
        $data['action_editar_ppal'] = base_url() . "index_admon_sistema/editar_sede_ppal";
        $data['action_llena_empleado_sede_ppal'] = base_url() . "index_admon_sistema/llena_empleado_sede_ppal";
        $data['action_llena_empleado_sede_secundaria'] = base_url() . "index_admon_sistema/llena_empleado_sede_secundaria";
        $data['action_llena_checkbox_secundarias'] = base_url() . "index_admon_sistema/llena_checkbox_secundarias";
        $data['action_llena_sede_ppal_faltante'] = base_url() . "index_admon_sistema/llena_sede_ppal_faltante";
        $data['action_anular_secundaria'] = base_url() . "index_admon_sistema/anular_sede_secundaria";
        $data['action_agregar_secundaria'] = base_url() . "index_admon_sistema/new_sede_secundaria";
        $this->parser->parse('editar_sedes_empleado', $data);
        $this->load->view('footer');
    }

    public function editar_sede_ppal() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('sede_ppal', 'sede_ppal', 'required|callback_select_default');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
            if ($this->form_validation->run() == FALSE) {
                //de esta forma devolvemos los errores de formularios
                //con ajax desde codeigniter, aunque con php es lo mismo
                $errors = array(
                    'mensaje' => '<p>' . form_error('sede_ppal') . form_error('observacion') . '</p>',
                    'respuesta' => 'error'
                );
                //y lo devolvemos así para parsearlo con JSON.parse
                echo json_encode($errors);
                return FALSE;
            } else {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $sede_ppal = $this->input->post('sede_ppal');
                $observacion = ucfirst(strtolower($this->input->post('observacion')));
                $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
                $id_responsable = $this->input->post('id_responsable');
                $dni_responsable = $this->input->post('dni_responsable');

                $error = $this->update_model->empleado_sede_ppal($id_empleado, $dni_empleado, $sede_ppal);

                if (isset($error)) {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p>' . $error . '</p>'
                    );
                } else {
                    //Para la historica no atrapo el error, si hubo error no me importa, con tal que se haya hecho la transaccion verdadera
                    $this->insert_model->cambio_sede_empleado($id_empleado, $dni_empleado, $sede_ppal, $observacion, $fecha_trans, $id_responsable, $dni_responsable);
                    $response = array(
                        'respuesta' => 'OK'
                    );
                }
                echo json_encode($response);
                return FALSE;
            }
        } else {
            redirect(base_url());
        }
    }

    public function anular_sede_secundaria() {
        if ($this->input->is_ajax_request()) {
            list($sede_secundaria, $id_empleado, $dni_empleado) = explode("-", $this->input->post('id_empleado_sede'));
            $sede_ppal = $this->input->post('sede_ppal');
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $vigente = 0;

            $error = $this->update_model->empleado_x_sede($id_empleado, $dni_empleado, $sede_secundaria, $vigente);

            if (isset($error)) {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p>' . $error . '</p>'
                );
            } else {
                //Para la historica no atrapo el error, si hubo error no me importa, con tal que se haya hecho la transaccion verdadera
                $this->insert_model->anular_empleado_x_sede($id_empleado, $dni_empleado, $sede_secundaria, $fecha_trans, $id_responsable, $dni_responsable);
                $response = array(
                    'respuesta' => 'OK'
                );
            }
            echo json_encode($response);
            return FALSE;
        } else {
            redirect(base_url());
        }
    }

    //Editar: Cargo y Jefe
    function editar_cargo_jefe() {
        $data = $this->navbar();
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['empleado'] = $this->select_model->empleado_RRPP_sedes_responsable($id_responsable, $dni_responsable);
        $data['cargo'] = $this->select_model->t_cargo();

        $data['action_editar_cargo'] = base_url() . "index_admon_sistema/editar_cargo_empleado";
        $data['action_editar_jefe'] = base_url() . "index_admon_sistema/editar_jefe_empleado";

        $data['action_llena_empleado_rrpp_sedes_responsable'] = base_url() . "index_admon_sistema/llena_empleado_rrpp_sedes_responsable";
        $data['action_llena_cargo_empleado'] = base_url() . "index_admon_sistema/llena_cargo_empleado";
        $data['action_llena_jefe_empleado'] = base_url() . "index_admon_sistema/llena_jefe_empleado";
        $data['action_llena_jefe_faltante'] = base_url() . "index_admon_sistema/llena_jefe_faltante";
        $data['action_llena_cargo_genero_cargo_old'] = base_url() . "index_admon_sistema/llena_cargo_genero_cargo_old";

        $this->parser->parse('editar_cargo_jefe', $data);
        $this->load->view('footer');
    }

    public function editar_cargo_empleado() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('cargo', 'Nuevo Cargo', 'required|callback_select_default');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
            if ($this->form_validation->run() == FALSE) {
                //de esta forma devolvemos los errores de formularios
                //con ajax desde codeigniter, aunque con php es lo mismo
                $errors = array(
                    'mensaje' => '<p>' . form_error('cargo') . form_error('observacion') . '</p>',
                    'respuesta' => 'error'
                );
                //y lo devolvemos así para parsearlo con JSON.parse
                echo json_encode($errors);
                return FALSE;
            } else {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $cargo = $this->input->post('cargo');

                $error = $this->update_model->empleado_cargo($id_empleado, $dni_empleado, $cargo);

                if (isset($error)) {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p>' . $error . '</p>'
                    );
                } else {
                    list($genero, $cargo_old) = explode("-", $this->input->post('genero_cargo'));
                    $empleado = $this->select_model->empleado($id_empleado, $dni_empleado);
                    $sede = $empleado->sede_ppal;
                    $observacion = ucfirst(strtolower($this->input->post('observacion')));
                    $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
                    $id_responsable = $this->input->post('id_responsable');
                    $dni_responsable = $this->input->post('dni_responsable');
                    //comprobamos si seleccionó el cheked de la placa
                    $check_placa = $this->input->post('checkbox_placa');
                    if ($check_placa == TRUE) {
                        $solicitar_placa = 1;
                        $error1 = $this->insert_model->solicitar_placa($id_empleado, $dni_empleado, $cargo, $sede, $observacion, $fecha_trans, $id_responsable, $dni_responsable);
                        if (isset($error1)) {
                            $response = array(
                                'respuesta' => 'error',
                                'mensaje' => '<p>' . $error1 . '</p>'
                            );
                            echo json_encode($response);
                            return FALSE;
                        }
                    } else {
                        $solicitar_placa = 0;
                    }
                    $this->insert_model->cambio_cargo($id_empleado, $dni_empleado, $cargo_old, $cargo, $solicitar_placa, $sede, $observacion, $fecha_trans, $id_responsable, $dni_responsable);
                    $response = array(
                        'respuesta' => 'OK'
                    );
                }
                echo json_encode($response);
                return FALSE;
            }
        } else {
            redirect(base_url());
        }
    }

    public function editar_jefe_empleado() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('jefe', 'Nuevo Jefe', 'required|callback_select_default');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
            if ($this->form_validation->run() == FALSE) {
                //de esta forma devolvemos los errores de formularios
                //con ajax desde codeigniter, aunque con php es lo mismo
                $errors = array(
                    'mensaje' => '<p>' . form_error('jefe') . form_error('observacion') . '</p>',
                    'respuesta' => 'error'
                );
                //y lo devolvemos así para parsearlo con JSON.parse
                echo json_encode($errors);
                return FALSE;
            } else {
                list($id_empleado, $dni_empleado, $id_jefe_old, $dni_jefe_old) = explode("-", $this->input->post('empleado_jefe'));
                list($id_jefe_new, $dni_jefe_new) = explode("-", $this->input->post('jefe'));

                $error = $this->update_model->empleado_jefe($id_empleado, $dni_empleado, $id_jefe_new, $dni_jefe_new);

                if (isset($error)) {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p>' . $error . '</p>'
                    );
                } else {
                    $observacion = ucfirst(strtolower($this->input->post('observacion')));
                    $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
                    $id_responsable = $this->input->post('id_responsable');
                    $dni_responsable = $this->input->post('dni_responsable');

                    $this->insert_model->cambio_jefe($id_empleado, $dni_empleado, $id_jefe_old, $dni_jefe_old, $id_jefe_new, $dni_jefe_new, $observacion, $fecha_trans, $id_responsable, $dni_responsable);
                    $response = array(
                        'respuesta' => 'OK'
                    );
                }
                echo json_encode($response);
                return FALSE;
            }
        } else {
            redirect(base_url());
        }
    }

    //Llenar elementos html dinamicamente
    public function llena_banco_pais() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('pais')) && ($this->input->post('pais') != '{id}') && ($this->input->post('pais') != 'default')) {
                $pais = $this->input->post('pais');
                $bancos = $this->select_model->banco_pais($pais);
                if ($bancos == TRUE) {
                    foreach ($bancos as $fila) {
                        echo '<option value="' . $fila->id . '">' . $fila->nombre . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_t_caja_sede() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('sede')) && ($this->input->post('sede') != '{id}') && ($this->input->post('sede') != 'default')) {
                $sede = $this->input->post('sede');
                $t_cajas = $this->select_model->t_caja_faltante($sede);
                if ($t_cajas == TRUE) {
                    foreach ($t_cajas as $fila) {
                        echo '<option value="' . $fila->id . '">' . $fila->tipo . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_encargado_sede() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('sede')) && ($this->input->post('sede') != '{id}') && ($this->input->post('sede') != 'default')) {
                $sede = $this->input->post('sede');
                $t_cajas = $this->select_model->empleado_sede_caja($sede);
                if ($t_cajas == TRUE) {
                    foreach ($t_cajas as $fila) {
                        echo '<option value="' . $fila->id . "-" . $fila->dni . '">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_t_concepto_salario() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('t_salario')) {
                $t_salario = $this->input->post('t_salario');
                $conceptos = $this->select_model->t_concepto_nomina_base($t_salario);
                if ($conceptos == TRUE) {
                    foreach ($conceptos as $fila) {
                        echo '<div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="margin_label">' . $fila->tipo . '</label>   
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input name="values_conceptos[]" type="hidden" value="' . $fila->id . '">
                                        <input type="text" name="conceptos[]" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12">
                                    </div>
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_cargo_comision_faltante() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable')) && ($this->input->post('ejecutivoDirecto')) && (($this->input->post('ejecutivoDirecto')) != "default")) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                list($id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo) = explode("-", $this->input->post('ejecutivoDirecto'));
                $t_cargos = $this->select_model->t_cargo_superior_rrpp($cargo_ejecutivo);
                if ($t_cargos == TRUE) {
                    foreach ($t_cargos as $fila) {
                        echo '<div class="form-group">
                            <label>Escala: ' . $fila->cargo_masculino . '<em class="required_asterisco">*</em></label>
                            <input name="cargos_escalas[]" type="hidden" value="' . $fila->id . "-" . $fila->cargo_masculino . '">
                            <select name="escalas[]" class="form-control exit_caution">
                            <option value="default">Seleccione Ejecutivo para la escala</option>';
                        $ejecutivos = $this->select_model->empleado_rrpp_cargo_superior($fila->id, $id_responsable, $dni_responsable);
                        if ($ejecutivos == TRUE) {
                            foreach ($ejecutivos as $registro) {
                                echo '<option value="' . $registro->id . "-" . $registro->dni . "-" . $registro->cargo . '">' . $registro->nombre1 . " " . $registro->nombre2 . " " . $registro->apellido1 . " " . $registro->apellido2 . '</option>';
                            }
                        }
                        echo '<option value="nula">ÉSTA ESCALA NO SE PAGARÁ A NADIE</option>
                        </select>
                        </div>  ';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_adelanto_empleado() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('empleado')) && ($this->input->post('empleado') != "default")) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $adelantos = $this->select_model->adelanto_vigente_empleado($id_empleado, $dni_empleado);
                if ($adelantos == TRUE) {
                    foreach ($adelantos as $fila) {
                        echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="adelanto" id="adelanto" value="' . $fila->prefijo_adelanto . "-" . $fila->id_adelanto . "-" . $fila->saldo . '"/></td>
                            <td class="text-center">$' . number_format($fila->total, 2, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($fila->saldo, 2, '.', ',') . '</td>
                            <td class="text-center">' . $fila->sede . '</td>
                            <td>' . $fila->observacion . '</td>                                
                            <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>  
                        </tr>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_detalle_matricula_liquidar() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('matricula')) && ($this->input->post('matricula') != "default")) {
                $contrato = $this->input->post('matricula');
                $detalle = $this->select_model->detalle_matricula_liquidar($contrato);
                if ($detalle == TRUE) {
                    $response = array(
                        'respuesta' => 'OK',
                        'detalleMatricula' => '',
                        'IdDniEjecutivo' => $detalle->id . "-" . $detalle->dni . "-" . $detalle->cargo,
                        'CargoEjecutivo' => $detalle->cargo
                    );
                    $response['detalleMatricula'] = '<tr>
                            <td class="text-center">' . $detalle->titular . '</td>
                            <td class="text-center">' . $detalle->plan . '</td>
                            <td class="text-center">' . $detalle->observacion . '</td>
                            <td class="text-center">' . $detalle->ejecutivo . '</td>                                
                            <td class="text-center">' . $detalle->name_cargo . '</td>
                            <td class="text-center">' . $detalle->fecha_matricula . '</td>
                        </tr>';
                    echo json_encode($response);
                    return false;
                } else {
                    $response = array(
                        'respuesta' => 'error'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_empleado_rrpp_sedePpal() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $empleados = $this->select_model->empleado_RRPP_sede_ppal($id_responsable, $dni_responsable);
                //Validamos que la consulta devuelva algo
                if ($empleados == TRUE) {
                    foreach ($empleados as $fila) {
                        echo '<option value="' . $fila->id . "-" . $fila->dni . "-" . $fila->cargo . '">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_empleado_rrpp_sedes_responsable() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $empleados = $this->select_model->empleado_RRPP_sede_ppal($id_responsable, $dni_responsable);
                //Validamos que la consulta devuelva algo
                if ($empleados == TRUE) {
                    foreach ($empleados as $fila) {
                        echo '<option value="' . $fila->id . "-" . $fila->dni . "-" . $fila->cargo . '">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_cargo_ejecutivo_directo() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('ejecutivoDirecto')) && ($this->input->post('ejecutivoDirecto') != "default")) {
                list($id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo) = explode("-", $this->input->post('ejecutivoDirecto'));
                $t_cargo = $this->select_model->t_cargo_id($cargo_ejecutivo);
                //Validamos que la consulta devuelva algo
                if ($t_cargo == TRUE) {
                    echo '<label>Cargo: ' . $t_cargo->cargo_masculino . '</label><em class="required_asterisco">*</em>';
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_plan_comercial() {
        if ($this->input->is_ajax_request()) {
            $planes = $this->select_model->t_plan_activo();
            if ($planes == TRUE) {
                foreach ($planes as $fila) {
                    echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="plan" id="plan" value="' . $fila->id . '"/></td>
                            <td class="text-center">' . $fila->nombre . '</td>
                            <td class="text-center">' . $fila->anio . '</td>                                
                            <td class="text-center">' . $fila->cant_alumnos . '</td>
                            <td class="text-center">$' . number_format($fila->valor_total, 0, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($fila->valor_inicial, 0, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($fila->valor_cuota, 0, '.', ',') . '</td>                                
                            <td class="text-center">' . $fila->cant_cuotas . '</td>
                        </tr>';
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_prestamo_beneficiario() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('beneficiario')) && ($this->input->post('beneficiario') != '{id}-{dni}') && ($this->input->post('beneficiario') != 'default')) {
                list($id_beneficiario, $dni_beneficiario) = explode("-", $this->input->post('beneficiario'));
                $prestamos = $this->select_model->prestamo_vigente_beneficiario($id_beneficiario, $dni_beneficiario);
                if ($prestamos == TRUE) {
                    foreach ($prestamos as $fila) {
                        echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="prestamo" id="prestamo" value="' . $fila->prefijo_prestamo . "-" . $fila->id_prestamo . '"/></td>
                            <td class="text-center">$' . number_format($fila->total, 2, '.', ',') . '</td>
                            <td class="text-center">' . $fila->cant_cuotas . '</td>
                            <td class="text-center">$' . $fila->tasa_interes . '%</td>                                
                            <td class="text-center">$' . number_format($fila->cuota_fija, 2, '.', ',') . '</td>                                
                            <td class="text-center">' . $fila->sede . '</td>                         
                            <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>  
                        </tr>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_cuotas_prestamo_pdtes() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('prestamo')) {
                list($prefijo_prestamo, $id_prestamo) = explode("-", $this->input->post('prestamo'));
                $matriz_prestamo = $this->matriz_prestamo($prefijo_prestamo, $id_prestamo);
                $prestamo = $this->select_model->prestamo_prefijo_id($prefijo_prestamo, $id_prestamo);
                if ($matriz_prestamo) {
                    $cant_cuotas = $prestamo->cant_cuotas;

                    $response = array(
                        'respuesta' => 'OK',
                        'abonoMinimo' => '0.00',
                        'abonoMaximo' => '0.00',
                        'cantMora' => '0',
                        'intMora' => '0.00',
                        'filasTabla' => ''
                    );

                    //Solo abrá una cuota que tendrá radio y será la primera no cancelada con saldo = 0.
                    $bandera_radio = 0;
                    for ($i = 1; $i <= $cant_cuotas; $i++) {
                        //Solo se mostraran las cuotas cuyo valor minimo sea > a cero.
                        if ($matriz_prestamo[$i][2] > 0) {
                            $num_cuota = $matriz_prestamo[$i][1];
                            $abono_minimo = $matriz_prestamo[$i][2];
                            $abono_maximo = $matriz_prestamo[$i][3];
                            $cant_dias_mora = $matriz_prestamo[$i][5];
                            $int_mora = $matriz_prestamo[$i][6];

                            if (($matriz_prestamo[$i][12] == 0) && ($bandera_radio == 0)) {
                                //Enviamos datos por ajax
                                $response['abonoMinimo'] = $abono_minimo;
                                $response['abonoMaximo'] = $abono_maximo;
                                $response['cantMora'] = $cant_dias_mora;
                                $response['intMora'] = $int_mora;
                                $escojer = '<input type="radio" class="exit_caution" name="cuota" id="cuota" checked/>';
                                $cuota_pagada = "";
                                $saldo_deuda = "";
                                $bandera_radio = 1;
                            } else {
                                $escojer = '';
                                $cuota_pagada = "$" . number_format($matriz_prestamo[$i][4], 2, '.', ',');
                                $saldo_deuda = "$" . number_format($matriz_prestamo[$i][9], 2, '.', ',');
                            }
                            $response['filasTabla'] .= '<tr>
                            <td class="text-center">' . $escojer . '</td>
                            <td class="text-center">' . $num_cuota . '</td>                                
                            <td class="text-center">$' . number_format($abono_minimo, 2, '.', ',') . '</td>                        
                            <td class="text-center">$' . number_format($abono_maximo, 2, '.', ',') . '</td>                                
                            <td class="text-center">' . $cuota_pagada . '</td> 
                            <td class="text-center">' . $cant_dias_mora . '</td>                                
                            <td class="text-center">$' . number_format($int_mora, 2, '.', ',') . '</td>                                
                            <td class="text-center">' . $saldo_deuda . '</td>
                            <td class="text-center">' . $matriz_prestamo[$i][10] . '</td>                               
                            <td class="text-center">' . $matriz_prestamo[$i][11] . '</td>
                        </tr>';
                        }
                        //Para que no muestre mas cuotas despues de la cuota proxima a cancelar.
//                    if ($bandera_radio == 1) {
//                        break;
//                    }
                    }
                    echo json_encode($response);
                    return false;
                } else {
                    $response = array(
                        'respuesta' => 'error'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_empleado_sede_ppal() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $sede_ppal = $this->select_model->empleado_sede_ppal($id_empleado, $dni_empleado);
                if ($sede_ppal == TRUE) {
                    echo '<tr>
                        <td>' . $sede_ppal->nombre . '</td>
                        <td class="text-center">
                        <button type="button" class="btn btn-primary btn-xs editar_sede" id="' . $sede_ppal->id . '"><span class="glyphicon glyphicon-edit"></span> Editar </button>
                        </td>
                     </tr>';
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_sedes_cuenta_banco() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('cuenta')) {
                $cuenta = $this->input->post('cuenta');
                $sedes = $this->select_model->sedes_cuenta_bancaria($cuenta);
                if ($sedes == TRUE) {
                    foreach ($sedes as $fila) {
                        echo '<tr>
                            <td>' . $fila->nombre . '</td>
                            <td class="text-center">
                            <button class="btn btn-danger btn-xs anular_sede_cuenta" id="' . $fila->id . "-" . $cuenta . '"><span class="glyphicon glyphicon-remove"></span> Desautorizar </button>
                            </td>
                         </tr>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_empleados_cuenta_banco() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('cuenta')) {
                $cuenta = $this->input->post('cuenta');
                $empleados = $this->select_model->empleados_cuenta_bancaria($cuenta);
                if ($empleados == TRUE) {
                    foreach ($empleados as $fila) {
                        echo '<tr>
                            <td>' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</td>
                            <td class="text-center">
                            <button class="btn btn-danger btn-xs anular_empleado_cuenta" id="' . $fila->id . "-" . $fila->dni . "-" . $cuenta . '"><span class="glyphicon glyphicon-remove"></span> Desautorizar </button>
                            </td>
                         </tr>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_empleado_sede_secundaria() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $sedes_secundarias = $this->select_model->empleado_sede_secundaria($id_empleado, $dni_empleado);
                if ($sedes_secundarias == TRUE) {
                    foreach ($sedes_secundarias as $fila) {
                        echo '<tr>
                            <td>' . $fila->nombre . '</td>
                            <td class="text-center">
                            <button class="btn btn-danger btn-xs anular_sede" id="' . $fila->sede_secundaria . "-" . $id_empleado . "-" . $dni_empleado . '"><span class="glyphicon glyphicon-remove"></span> Eliminar </button>
                            </td>
                         </tr>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_sede_ppal_faltante() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('sede_ppal')) {
                $sede_ppal = $this->input->post('sede_ppal');
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $sedes = $this->select_model->sede_activa_faltante_responsable($sede_ppal, $id_responsable, $dni_responsable);
                //Validamos que las dos consultas devuelvan algo
                if ($sedes == TRUE) {
                    foreach ($sedes as $fila) {
                        echo '<option value="' . $fila->id . '">' . $fila->nombre . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_checkbox_secundarias() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $sedes_secundarias = $this->select_model->sede_secundaria_faltante_empleado_responsable($id_empleado, $dni_empleado, $id_responsable, $dni_responsable);
                if ($sedes_secundarias == TRUE) {
                    foreach ($sedes_secundarias as $fila) {
                        echo '<div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" name="sede_checkbox[]" class="input_modal_3" value="' . $fila->id . '"/><h4 class="h_negrita">' . $fila->nombre . '</h4></label>
                            </div>
                        </div>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_checkbox_sedes_cuenta() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('cuenta')) && ($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $cuenta = $this->input->post('cuenta');
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $sedes = $this->select_model->sede_faltante_cuenta_bancaria_responsable($cuenta, $id_responsable, $dni_responsable);
                if ($sedes == TRUE) {
                    foreach ($sedes as $fila) {
                        echo '<div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" name="sede_checkbox[]" class="input_modal_3" value="' . $fila->id . '"/><h4 class="h_negrita">' . $fila->nombre . '</h4></label>
                            </div>
                        </div>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_checkbox_empleados_cuenta() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('cuenta')) && ($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $cuenta = $this->input->post('cuenta');
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $empleados = $this->select_model->empleado_faltante_cuenta_bancaria_responsable($cuenta, $id_responsable, $dni_responsable);
                if ($empleados == TRUE) {
                    foreach ($empleados as $fila) {
                        echo '<div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" name="empleados_checkbox[]" class="input_modal_3" value="' . $fila->id . "-" . $fila->dni . '"/><h4 class="h_negrita">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</h4></label>
                            </div>
                        </div>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_cargo_departamento() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('depto')) {
                $depto = $this->input->post('depto');
                $t_cargo = $this->select_model->cargo_depto($depto);
                //Validamos que las dos consultas devuelvan algo
                if ($t_cargo == TRUE) {
                    foreach ($t_cargo as $fila) {
                        echo '<option value="' . $fila->id . '-' . $fila->perfil . '">' . $fila->cargo_masculino . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_cargo_genero_cargo_old() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('genero_cargo')) {
                list($genero, $cargo_old) = explode("-", $this->input->post('genero_cargo'));
                //Sacamos el cargo que ya tiene para no mostrarlo
                $t_cargo = $this->select_model->t_cargo_faltante_rrpp($cargo_old);

                //Validamos que las dos consultas devuelvan algo
                if (($genero == TRUE) && ($t_cargo == TRUE)) {
                    if ($genero == 'M') {
                        foreach ($t_cargo as $fila) {
                            echo '<option value="' . $fila->id . '-' . $fila->perfil . '">' . $fila->cargo_masculino . '</option>';
                        }
                    } else {
                        if ($genero == 'F') {
                            foreach ($t_cargo as $fila) {
                                echo '<option value="' . $fila->id . '-' . $fila->perfil . '">' . $fila->cargo_femenino . '</option>';
                            }
                        } else {
                            echo "";
                        }
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_salario_departamento() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('depto')) {
                $depto = $this->input->post('depto');
                $salarios = $this->select_model->salario_t_salario_x_t_depto($depto);
                if ($salarios == TRUE) {
                    foreach ($salarios as $fila) {
                        echo '<option value="' . $fila->id . '">' . $fila->nombre . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_cargo_empleado() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $empleado = $this->select_model->empleado($id_empleado, $dni_empleado);
                $cargo = $this->select_model->empleado_cargo($id_empleado, $dni_empleado);
                if (($cargo == TRUE) AND ($empleado == TRUE)) {
                    if ($empleado->genero == 'M') {
                        echo '<tr>
                            <td>' . $cargo->cargo_masculino . '</td>
                            <td class="text-center">
                            <button type="button" class="btn btn-primary btn-xs editar_cargo" id="' . $empleado->genero . '-' . $cargo->id . '"><span class="glyphicon glyphicon-edit"></span> Editar </button>
                            </td>
                     </tr>';
                    } else {
                        echo '<tr>
                            <td>' . $cargo->cargo_femenino . '</td>
                            <td class="text-center">
                            <button type="button" class="btn btn-primary btn-xs editar_cargo" id="' . $empleado->genero . '-' . $cargo->id . '"><span class="glyphicon glyphicon-edit"></span> Editar </button>
                            </td>
                         </tr>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_jefe_empleado() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $jefe = $this->select_model->empleado_jefe($id_empleado, $dni_empleado);
                if (($jefe == TRUE)) {
                    echo '<tr>
                        <td>' . $jefe->nombre1 . " " . $jefe->nombre2 . " " . $jefe->apellido1 . " " . $jefe->apellido2 . '</td>
                        <td class="text-center">
                        <button type="button" class="btn btn-primary btn-xs editar_jefe" id="' . $id_empleado . "-" . $dni_empleado . "-" . $jefe->id . "-" . $jefe->dni . '"><span class="glyphicon glyphicon-edit"></span> Editar </button>
                        </td>
                     </tr>';
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_jefe_faltante() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado_jefe')) {
                list($id_empleado, $dni_empleado, $id_jefe, $dni_jefe) = explode("-", $this->input->post('empleado_jefe'));
                //Sacamos el empleado de las lista de jefes
                $jefes = $this->select_model->empleado_jefe_faltante_rrpp($id_empleado, $dni_empleado);

                //Validamos que la consulta devuelva algo
                if ($jefes == TRUE) {
                    foreach ($jefes as $fila) {
                        echo '<option value="' . $fila->id . "-" . $fila->dni . '">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_jefe_new_empleado() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('cargo')) && ($this->input->post('sedePpal')) && ($this->input->post('depto'))) {
                list($cargo, $perfil) = explode("-", $this->input->post('cargo'));
                $sede_ppal = $this->input->post('sedePpal');
                $depto = $this->input->post('depto');
                $jefes = $this->select_model->empleado_jefe_faltante_sede_depto_cargo($sede_ppal, $depto, $cargo);
                //Validamos que la consulta devuelva algo
                if ($jefes == TRUE) {
                    foreach ($jefes as $fila) {
                        echo '<option value="' . $fila->id . "-" . $fila->dni . '">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_matricula_iliquidada() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $matriculas = $this->select_model->matricula_iliquida_responsable($id_responsable, $dni_responsable);
                //Validamos que la consulta devuelva algo
                if ($matriculas == TRUE) {
                    foreach ($matriculas as $fila) {
                        echo '<option value="' . $fila->contrato . '">' . $fila->contrato . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_solicitud_placa() {
        if ($this->input->is_ajax_request()) {
            $solicitudes = $this->select_model->solicitud_placa();
            if ($solicitudes == TRUE) {
                foreach ($solicitudes as $fila) {
                    echo '<tr>
                            <td class="text-center"><input type="checkbox" class="exit_caution" name="placas_checkbox[]"  value="' . $fila->id_solicitud . '"/></td>
                            <td>' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</td>
                            <td>' . $fila->cargo . '</td>
                            <td class="text-center">' . $fila->sede . '</td>
                            <td>' . $fila->observacion . '</td>
                            <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>
                        </tr>';
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_empleado_adelanto() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $empleados = $this->select_model->empleado_sedes_responsable_adelantos($id_responsable, $dni_responsable);
                if ($empleados == TRUE) {
                    foreach ($empleados as $fila) {
                        echo '<option value="' . $fila->id . "-" . $fila->dni . '">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_empleado_prestamo() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $empleados = $this->select_model->empleado_sedes_responsable_prestamos($id_responsable, $dni_responsable);
                if ($empleados == TRUE) {
                    foreach ($empleados as $fila) {
                        echo '<option value="' . $fila->id . "-" . $fila->dni . '">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_clientes() {
        if ($this->input->is_ajax_request()) {
            $clientes = $this->select_model->cliente();
            if ($clientes == TRUE) {
                foreach ($clientes as $fila) {
                    echo '<option value="' . $fila->id . "-" . $fila->dni . '">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</option>';
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_cliente_prestamo() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $clientes = $this->select_model->cliente_prestamo($id_responsable, $dni_responsable);
                if ($clientes == TRUE) {
                    foreach ($clientes as $fila) {
                        echo '<option value="' . $fila->id . "-" . $fila->dni . '">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_despacho_placa() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $empleado = $this->select_model->empleado($id_responsable, $dni_responsable);
                $sede_responsable = $empleado->sede_ppal;
                $despachos = $this->select_model->despacho_placa($sede_responsable);
                if (($despachos == TRUE)) {
                    foreach ($despachos as $fila) {
                        echo '<tr>
                            <td class="text-center"><input type="checkbox" class="exit_caution" name="placas_checkbox[]"  value="' . $fila->id_despacho . '"/></td>
                            <td>' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</td>
                            <td>' . $fila->cargo . '</td>
                            <td class="text-center">' . $fila->sede . '</td>
                            <td>' . $fila->observacion . '</td>
                            <td class="text-center">' . $fila->fecha_despacho . '</td>
                        </tr>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_cuenta_bancaria() {
        if ($this->input->is_ajax_request()) {
            $cuentas = $this->select_model->cuenta_banco();
            if (($cuentas == TRUE)) {
                foreach ($cuentas as $fila) {
                    echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="cuenta" id="cuenta" value="' . $fila->id . '"/></td>
                            <td>' . $fila->id . '</td>
                            <td class="text-center">' . $fila->t_cuenta . '</td>
                            <td>' . $fila->banco . '</td>
                            <td>' . $fila->nombre_cuenta . '</td>    
                            <td>' . $fila->observacion . '</td>   
                            <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>    
                        </tr>';
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_cuenta_responsable() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $cuentas = $this->select_model->cuenta_banco_responsable($id_responsable, $dni_responsable);
                if (($cuentas == TRUE)) {
                    foreach ($cuentas as $fila) {
                        echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="cuenta" id="cuenta" value="' . $fila->id . '"/></td>
                            <td>' . $fila->id . '</td>
                            <td class="text-center">' . $fila->t_cuenta . '</td>
                            <td>' . $fila->banco . '</td>
                            <td>' . $fila->nombre_cuenta . '</td>    
                            <td>' . $fila->observacion . '</td>   
                            <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>    
                        </tr>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_caja_responsable() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $cuentas = $this->select_model->caja_responsable($id_responsable, $dni_responsable);
                if (($cuentas == TRUE)) {
                    foreach ($cuentas as $fila) {
                        echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="caja" id="caja" value="' . $fila->sede . "-" . $fila->t_caja . '"/></td>
                            <td class="text-center">' . $fila->name_sede . '</td>
                            <td>' . $fila->name_t_caja . '</td>  
                            <td>' . $fila->observacion . '</td>   
                            <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>    
                        </tr>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_info_contrato_laboral() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $contrato = $this->select_model->contrato_laboral_empleado($id_empleado, $dni_empleado);
                if (($contrato == TRUE)) {
                    if ($contrato->cant_meses == NULL) {
                        $duracion = "Indefinido";
                    } else {
                        if ($contrato->cant_meses == 1) {
                            $duracion = $contrato->cant_meses . " mes";
                        } else {
                            $duracion = $contrato->cant_meses . " meses";
                        }
                    }
                    echo '<div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Fecha Inicio</th>
                                                <th class="text-center">Tipo Contrato</th>
                                                <th class="text-center">Duración</th>
                                                <th class="text-center">Cargo</th>                                                
                                                <th class="text-center">Salario</th>
                                                <th class="text-center">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>                         
                                            <td class="text-center">' . $contrato->fecha_inicio . '</td>
                                            <td class="text-center">' . $contrato->tipo_contrato . '</td>
                                            <td class="text-center">' . $duracion . '</td> 
                                            <td class="text-center">' . $contrato->cargo . '</td>                                                
                                            <td class="text-center">' . $contrato->nombre_salario . '</td>                                  
                                            <td>' . $contrato->observacion . '</td>  
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>';
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_info_ultimas_nominas() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $nominas = $this->select_model->ultimas_nominas_empleado($id_empleado, $dni_empleado);
                if (($nominas == TRUE)) {
                    echo '<div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Id. Nómina</th>
                                                <th class="text-center">Fecha Inicial</th>
                                                <th class="text-center">Fecha Final</th>
                                                <th class="text-center">Sede</th>                                                
                                                <th class="text-center">Total Nómina</th>
                                                <th class="text-center">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($nominas as $fila) {
                        echo '<tr>
                                <td class="text-center">' . $fila->prefijo . "-" . $fila->id . '</td>                            
                                <td class="text-center">' . $fila->fecha_inicio . '</td>
                                <td class="text-center">' . $fila->fecha_fin . '</td>  
                                <td class="text-center">' . $fila->nombre_sede . '</td>                                
                                <td class="text-center">$' . number_format($fila->total, 2, '.', ',') . '</td>                                   
                                <td>' . $fila->observacion . '</td>  
                            </tr>';
                    }
                    echo '</tbody>
                        </table>
                    </div>';
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_info_adelantos() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $adelantos = $this->select_model->adelanto_vigente_empleado($id_empleado, $dni_empleado);
                if (($adelantos == TRUE)) {
                    echo '<div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Id. Adelanto</th>
                                                <th class="text-center">Fecha</th>
                                                <th class="text-center">Sede</th>
                                                <th class="text-center">Valor Inicial</th>                                            
                                                <th class="text-center">Saldo Pdte.</th>
                                                <th class="text-center">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($adelantos as $fila) {
                        echo '<tr>
                                <td class="text-center">' . $fila->prefijo_adelanto . "-" . $fila->id_adelanto . '</td>       
                                <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>                                
                                <td class="text-center">' . $fila->sede . '</td>
                                <td class="text-center">$' . number_format($fila->total, 2, '.', ',') . '</td>  
                                <td class="text-center">$' . number_format($fila->saldo, 2, '.', ',') . '</td>                                
                                <td>' . $fila->observacion . '</td>  
                            </tr>';
                    }
                    echo '</tbody>
                        </table>
                    </div>';
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_info_prestamos() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $prestamos = $this->select_model->prestamo_vigente_beneficiario($id_empleado, $dni_empleado);
                if (($prestamos == TRUE)) {
                    echo '<p class="help-block"><B>> </B>Los abonos a préstamos no se realizan por la nomina, sino por la opción: Crear->Abono a Préstamo.</p>
                        <div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Id. Préstamo</th>
                                                <th class="text-center">Fecha</th>
                                                <th class="text-center">Sede</th>
                                                <th class="text-center">Valor Inicial</th>                                        
                                                <th class="text-center">Cant Cuotas</th>
                                                <th class="text-center">Cuota Fija</th>
                                                <th class="text-center">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($prestamos as $fila) {
                        echo '<tr>
                                <td class="text-center">' . $fila->prefijo_prestamo . "-" . $fila->id_prestamo . '</td>   
                                <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>                                
                                <td class="text-center">' . $fila->sede . '</td>
                                <td class="text-center">$' . number_format($fila->total, 2, '.', ',') . '</td>
                                <td class="text-center">' . $fila->cant_cuotas . '</td>                                
                                <td class="text-center">$' . number_format($fila->cuota_fija, 2, '.', ',') . '</td>                               
                                <td>' . $fila->observacion . '</td>
                            </tr>';
                    }
                    echo '</tbody>
                        </table>
                    </div>';
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_info_ausencias() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('empleado')) && ($this->input->post('fechaInicio')) && ($this->input->post('fechaFin'))) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $fecha_inicio_nomina = $this->input->post('fechaInicio');
                $fecha_fin_nomina = $this->input->post('fechaFin');
                $ausencias = $this->select_model->ausencia_entre_fechas($id_empleado, $dni_empleado, $fecha_inicio_nomina, $fecha_fin_nomina);
                $response = array(
                    'respuesta' => 'OK',
                    'html_ausencias' => '',
                    'cant_nomina' => $this->dias_entre_fechas($fecha_inicio_nomina, $fecha_fin_nomina) + 1,
                    'cant_ausencias' => 0,
                    'cant_incapacidad' => 0
                );
                if (($ausencias == TRUE)) {
                    $response['html_ausencias'] = '<p class="help-block"><B>> </B>Sólo aparecerán las ausencias ocurridas entre el rango de fechas de la Nómina.</p>
                        <div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Fecha Inicial</th>
                                                <th class="text-center">Fecha Final</th>                                        
                                                <th class="text-center">Días de Ausencia en Nómina</th>                                                 
                                                <th class="text-center">Tipo de Ausencia</th>
                                                <th class="text-center">Remuneración</th>
                                                <th class="text-center">Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($ausencias as $fila) {
                        //Calculamos la cantidad de dias de ausencia dentro de la nomina
                        if ((($fila->fecha_inicio >= $fecha_inicio_nomina) and ($fila->fecha_inicio <= $fecha_fin_nomina)) && (($fila->fecha_fin >= $fecha_inicio_nomina) and ($fila->fecha_fin <= $fecha_fin_nomina))) {
                            $cant_ausencia = $this->dias_entre_fechas($fila->fecha_inicio, $fila->fecha_fin) + 1;
                        } else {
                            if ((($fila->fecha_inicio >= $fecha_inicio_nomina) and ($fila->fecha_inicio <= $fecha_fin_nomina))) {
                                $cant_ausencia = $this->dias_entre_fechas($fila->fecha_inicio, $fecha_fin_nomina) + 1;
                            } else {
                                $cant_ausencia = $this->dias_entre_fechas($fecha_inicio_nomina, $fila->fecha_fin) + 1;
                            }
                        }
                        if ($fila->t_ausencia == 2) {
                            $response['cant_incapacidad'] += $cant_ausencia;
                        }
                        $response['cant_ausencias'] += $cant_ausencia;
                        $response['html_ausencias'] .= '<tr>
                                <td class="text-center">' . $fila->fecha_inicio . '</td>   
                                <td class="text-center">' . $fila->fecha_fin . '</td>                                 
                                <td class="text-center">' . $cant_ausencia . '</td>                                
                                <td class="text-center">' . $fila->tipo . '</td>
                                <td class="text-center">' . $fila->salarial . '</td>                                                              
                                <td>' . $fila->descripcion . '</td>
                            </tr>';
                    }
                    $response['html_ausencias'] .= '</tbody>
                        </table>
                    </div>';
                }
                echo json_encode($response);
                return false;
            } else {
                $response = array(
                    'respuesta' => 'error'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_info_seguridad_social() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $seguridades = $this->select_model->concepto_nomina_seguridad_social($id_empleado, $dni_empleado);
                if (($seguridades == TRUE)) {
                    echo '<div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Id. Nomina</th>
                                                <th class="text-center">Sede</th>                                             
                                                <th class="text-center">Fecha del Concepto</th>
                                                <th class="text-center">Valor del Pago SS.SS</th>
                                                <th class="text-center">Detalle</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($seguridades as $fila) {
                        echo '<tr>
                                <td class="text-center">' . $fila->prefijo_nomina . "-" . $fila->id_nomina . '</td>
                                <td class="text-center">' . $fila->nombre_sede . '</td>                                
                                <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>
                                <td class="text-center">$' . number_format(($fila->cantidad * $fila->valor_unitario), 2, '.', ',') . '</td>                                    
                                <td class="text-center">' . $fila->detalle . '</td>
                            </tr>';
                    }
                    echo '</tbody>
                        </table>
                    </div>';
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_concepto_pdtes_rrpp() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $conceptos = $this->select_model->concepto_nomina_pdte_rrpp($id_empleado, $dni_empleado);
                if ($conceptos == TRUE) {
                    echo '<label>Conceptos Pendientes de RRPP</label>';
                    $i = 1;
                    foreach ($conceptos as $fila) {
                        echo '<div class="div_input_group renglon_concepto_pdte" id="div_concepto_pdte_' . $i . '">
                                <div class="row">
                                    <input type="hidden" name="t_concepto_nomina[]" id="t_concepto_nomina" value="' . $fila->t_concepto_nomina . '">
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Tipo de Concepto</label>
                                            <input name="nombre_concepto[]" id="nombre_concepto" type="text" class="form-control text-center" readonly value="' . $fila->tipo_concepto . '">
                                        </div>                            
                                    </div>     
                                    <input type="hidden" name="debito_credito[]" id="debito_credito" value="1">
                                    <div class="col-xs-3 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label class="required">Nombre Escala</label>
                                            <input name="escala[]" id="escala" type="text" class="form-control text-center" readonly value="' . $fila->escala . '">
                                        </div>                            
                                    </div>                                    
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label class="required">Detalle</label>
                                            <input name="detalle[]" id="detalle" type="text" class="form-control text-center" readonly value="' . $fila->detalle . '">
                                        </div>                            
                                    </div>
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label class="required">Fecha</label>
                                            <input name="fecha_concepto[]" id="fecha_concepto" type="text" class="form-control text-center" readonly value="' . date("Y-m-d", strtotime($fila->fecha_trans)) . '">
                                        </div>                            
                                    </div>                                    
                                    <input type="hidden" name="cantidad[]" id="cantidad" value="' . number_format($fila->cantidad, 2, '.', ',') . '">
                                    <input type="hidden" name="valor_unitario[]" id="valor_unitario" value="' . number_format($fila->valor_unitario, 2, '.', ',') . '">                                    
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Devengado</label>                            
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" name="total_concepto[]" id="total_concepto" class="form-control decimal2 miles text-center" readonly>
                                            </div>
                                        </div>                          
                                    </div>
                                    <div class="col-xs-1 padding_remove">
                                        <label class="label_btn_remove">. </label>                                
                                        <div class="form-group sin_margin_bottom text-center">
                                            <button class="btn btn-default drop_concepto_pdte" id="' . $i . '" type="button"><span class="glyphicon glyphicon-remove"></span></button>  
                                        </div>
                                    </div>
                                </div>      
                            </div>';
                        $i++;
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_agregar_concepto() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idUltimoConcepto')) && ($this->input->post('empleado'))) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $i = $this->input->post('idUltimoConcepto') + 1;
                $t_concepto = $this->select_model->t_concepto_nomina_depto_empleado($id_empleado, $dni_empleado);
                echo '<div class="div_input_group renglon_concepto_pdte" id="div_concepto_new_' . $i . '">
                                <div class="row">
                                    <div class="col-xs-3 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Tipo de Concepto<em class="required_asterisco">*</em></label>
                                            <select name="t_concepto_nomina[]" id="t_concepto_nomina" class="form-control exit_caution">
                                                <option value="default">T. de Concepto Nómina</option>';
                if (($t_concepto == TRUE)) {
                    foreach ($t_concepto as $fila) {
                        echo '                  <option value="' . $fila->id . '">' . $fila->tipo . '</option>';
                    }
                }
                echo '                       </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="debito_credito[]" id="debito_credito">                                    
                                    <div class="col-xs-3 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label class="required">Detalle Adicional</label>
                                            <input name="detalle[]" id="detalle" type="text" class="form-control exit_caution letras_numeros" placeholder="Detalle Adicional" maxlength="50" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xs-1 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Cantidad<em class="required_asterisco">*</em></label>
                                            <input name="cantidad[]" id="cantidad" type="text" class="form-control exit_caution numerico input_center" placeholder="Cantidad" maxlength="3" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Valor Unitario<em class="required_asterisco">*</em></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" name="valor_unitario[]" id="valor_unitario" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12" disabled>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <div id="label_total_concepto"><label>Total Concepto</label></div>
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" name="total_concepto[]" id="total_concepto" class="form-control decimal decimal2 miles text-center" placeholder="0.00" maxlength="12" readonly>
                                            </div>
                                        </div>  
                                    </div>                                    
                                    <div class="col-xs-1  padding_remove">
                                        <label class="label_btn_remove">. </label>                                
                                        <div class="form-group sin_margin_bottom text-center">
                                            <button class="btn btn-default drop_concepto_new" id="' . $i . '" type="button"><span class="glyphicon glyphicon-remove"></span></button>  
                                        </div>
                                    </div>
                                </div>
                            </div>';
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_info_t_concepto() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('tConceptoNomina')) && ($this->input->post('empleado'))) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $id_t_concepto = $this->input->post('tConceptoNomina');
                $concepto_base = $this->select_model->concepto_base_nomina_empleado($id_empleado, $dni_empleado, $id_t_concepto);
                $t_concepto = $this->select_model->t_concepto_nomina_id($id_t_concepto);
                if ($t_concepto == TRUE) {
                    //Si existe un concpeto base para el t_conepto y el salario del empleado, pasamos su valor unitario.
                    if ($concepto_base == TRUE) {
                        $concepto_base = number_format($concepto_base->valor_unitario, 2, '.', ',');
                    } else {
                        $concepto_base = 0.00;
                    }
                    $response = array(
                        'respuesta' => 'OK',
                        'valor_unitario' => $concepto_base,
                        'debito_credito' => $t_concepto->debito_credito,
                        't_cantidad_dias' => $t_concepto->t_cantidad_dias
                    );
                    echo json_encode($response);
                    return FALSE;
                } else {
                    $response = array(
                        'respuesta' => 'error'
                    );
                    echo json_encode($response);
                    return FALSE;
                }
            } else {
                $response = array(
                    'respuesta' => 'error'
                );
                echo json_encode($response);
                return FALSE;
            }
        } else {
            redirect(base_url());
        }
    }

    function matriz_prestamo($prefijo_prestamo, $id_prestamo) {
        $prestamo = $this->select_model->prestamo_prefijo_id($prefijo_prestamo, $id_prestamo);
        if ($prestamo == TRUE) {
            $fecha_desembolso = $prestamo->fecha_desembolso;
            $total_prestamo = $prestamo->total;
            $tasa_interes = $prestamo->tasa_interes;
            if ($prestamo->tasa_interes != 0) {
                $tasa_interes = $prestamo->tasa_interes / 100;
            }
            $cant_cuotas = $prestamo->cant_cuotas;
            $cuota_fija = $prestamo->cuota_fija;

            //La primera fila la hacemos manual para que la formula funciones.
            $matriz_prestamo = array();
            $matriz_prestamo[0][1] = 0;
            $matriz_prestamo[0][2] = 0;
            $matriz_prestamo[0][3] = 0;
            $matriz_prestamo[0][4] = 0;
            $matriz_prestamo[0][5] = 0;
            $matriz_prestamo[0][6] = 0;
            $matriz_prestamo[0][7] = 0;
            $matriz_prestamo[0][8] = 0;
            $matriz_prestamo[0][9] = $total_prestamo;
            $matriz_prestamo[0][10] = $fecha_desembolso;
            $matriz_prestamo[0][11] = "";
            $matriz_prestamo[0][12] = 0;

            //Llenamos de ceros todas las columnas de ceros que seran llenadas con pagos
            for ($i = 1; $i <= $cant_cuotas; $i++) {
                $matriz_prestamo[$i][4] = 0;
                $matriz_prestamo[$i][5] = 0;
                $matriz_prestamo[$i][6] = 0;
                $matriz_prestamo[$i][11] = "";
                $matriz_prestamo[$i][12] = 0;
            }

            //Llenamos los pagos realizados al prestamo
            $abonos = $this->select_model->abono_prestamo_prestamo($prefijo_prestamo, $id_prestamo);
            if ($abonos == TRUE) {
                $i = 1;
                foreach ($abonos as $fila) {
                    $matriz_prestamo[$i][4] = $fila->subtotal;
                    $matriz_prestamo[$i][5] = $fila->cant_dias_mora;
                    $matriz_prestamo[$i][6] = $fila->int_mora;
                    $matriz_prestamo[$i][11] = date("Y-m-d", strtotime($fila->fecha_trans));
                    $matriz_prestamo[$i][12] = 1;
                    $i++;
                }
            }

            //Llenamos las columnas que se calculan a partir de los pagos realizados
            for ($i = 1; $i <= $cant_cuotas; $i++) {
                $saldo_anterior = $matriz_prestamo[$i - 1][9];
                $intereses = round($saldo_anterior * $tasa_interes, 2);
                if (($saldo_anterior + $intereses) >= $cuota_fija) {
                    $cuota_minima = $cuota_fija;
                } else {
                    $cuota_minima = round($saldo_anterior + $intereses, 2);
                }
                $cuota_maxima = round($saldo_anterior + $intereses, 2);
                $cuota_pagada = $matriz_prestamo[$i][4];
                if ($cuota_pagada != 0) {
                    $abono_capital = round($cuota_pagada - $intereses, 2);
                } else {
                    $abono_capital = round($cuota_minima - $intereses, 2);
                }
                $saldo_prestamo = round($saldo_anterior - $abono_capital, 2);
                //Si el saldo es mejor a 1 pesos se perdona. Por errores de aproximacion pueden quedar saldos
                if ($saldo_prestamo < 1) {
                    $saldo_prestamo = 0.00;
                }
                $fecha_pago = date("Y-m-d", strtotime("$fecha_desembolso +$i month"));

                $matriz_prestamo[$i][1] = $i;
                $matriz_prestamo[$i][2] = $cuota_minima;
                $matriz_prestamo[$i][3] = $cuota_maxima;
                $matriz_prestamo[$i][7] = $abono_capital;
                $matriz_prestamo[$i][8] = $intereses;
                $matriz_prestamo[$i][9] = $saldo_prestamo;
                $matriz_prestamo[$i][10] = $fecha_pago;

                $cuota_cancelada = $matriz_prestamo[$i][12];
                $fecha_hoy = date('Y-m-d');
                if (($cuota_cancelada == 0) && ($fecha_pago < $fecha_hoy)) {
                    $dias_mora = $this->dias_entre_fechas($fecha_pago, $fecha_hoy);
                    //Descartamos una mora inferior a 4 dias de gracia.   
                    //Pero si es mayor a 4 la contamos completa sin descartar los 4 dias.
                    if ($dias_mora > 4) {
                        $matriz_prestamo[$i][5] = $dias_mora;
                        $tasa_mora_anual = $this->select_model->interes_mora()->tasa_mora_anual;
                        if ($tasa_mora_anual) {
                            $Int_mora = round(((((pow((1 + ($tasa_mora_anual / 100)), (1 / 360))) - 1) * $dias_mora) * $cuota_minima), 2);
                            $matriz_prestamo[$i][6] = $Int_mora;
                        }
                    }
                }
            }
            return $matriz_prestamo;
        } else {
            return false;
        }
    }

}
