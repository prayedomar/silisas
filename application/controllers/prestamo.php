<?php

class Prestamo extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
    }

//Crear: Prestamo a Clientes o Empleados
    function crear() {
        $data["tab"] = "crear_prestamo";
        $this->isLogin($data["tab"]);        
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
        $data['empleado'] = $this->select_model->empleado_sedes_responsable($id_responsable, $dni_responsable);
        $data['t_beneficiario'] = $this->select_model->t_usuario_prestamo();
        $data['fecha_actual'] = date('Y-m-d');

        $data['action_validar'] = base_url() . "prestamo/validar";
        $data['action_crear'] = base_url() . "prestamo/insertar";

        $data['action_llena_cuenta_responsable'] = base_url() . "prestamo/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "prestamo/llena_caja_responsable";
        $data['action_llena_clientes'] = base_url() . "prestamo/llena_clientes";

        $this->parser->parse('prestamo/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
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

    function insertar() {
        if ($this->input->post('submit')) {
            $this->escapar($_POST);
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
            
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_prestamo = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_prestamo = ($this->select_model->nextId_prestamo($prefijo_prestamo)->id) + 1;
            $t_trans = 2; //Prestamo
            $credito_debito = 0; //Debito

            $data["tab"] = "crear_prestamo";
            $this->isLogin($data["tab"]);            
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "prestamo/crear";
            $data['msn_recrear'] = "Crear otro Prestamo";

            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_prestamo, $id_prestamo, $credito_debito, $total, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $cuenta_origen, $valor_retirado, 1, $sede, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->prestamo($prefijo_prestamo, $id_prestamo, $t_beneficiario, $id_beneficiario, $dni_beneficiario, $total, $tasa_interes, $cant_cuotas, $cuota_fija, $fecha_desembolso, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, 1, $observacion, $id_responsable, $dni_responsable);
                if (isset($error1)) {
                    $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    $this->parser->parse('trans_success', $data);
                }
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_cuenta_responsable() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
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
            $this->escapar($_POST);
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

}
