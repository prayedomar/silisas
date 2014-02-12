<?php

class Factura extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

//Crear: Nomina
    function crear() {
        $data["tab"] = "crear_factura";
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['empleado'] = $this->select_model->empleado_sede_ppal_responsable($id_responsable, $dni_responsable);
        $data['action_validar'] = base_url() . "factura/validar";
        $data['action_crear'] = base_url() . "factura/insertar";
        $data['action_recargar'] = base_url() . "factura/crear";
        $data['action_llena_info_contrato_laboral'] = base_url() . "factura/llena_info_contrato_laboral";
        $data['action_llena_info_ultimas_facturas'] = base_url() . "factura/llena_info_ultimas_facturas";
        $data['action_llena_info_adelantos'] = base_url() . "factura/llena_info_adelantos";
        $data['action_llena_info_prestamos'] = base_url() . "factura/llena_info_prestamos";
        $data['action_llena_info_ausencias'] = base_url() . "factura/llena_info_ausencias";
        $data['action_llena_info_seguridad_social'] = base_url() . "factura/llena_info_seguridad_social";
        $data['action_llena_concepto_pdtes_rrpp'] = base_url() . "factura/llena_concepto_pdtes_rrpp";
        $data['action_llena_concepto_cotidiano'] = base_url() . "factura/llena_concepto_cotidiano";
        $data['action_llena_agregar_concepto'] = base_url() . "factura/llena_agregar_concepto";
        $data['action_llena_info_t_concepto'] = base_url() . "factura/llena_info_t_concepto";
        $data['action_llena_periodicidad_factura'] = base_url() . "factura/llena_periodicidad_factura";
        $data['action_validar_fechas_periodicidad'] = base_url() . "factura/validar_fechas_periodicidad";

        $data['action_llena_cuenta_responsable'] = base_url() . "factura/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "factura/llena_caja_responsable";
        $this->parser->parse('factura/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('empleado', 'Empleado', 'required|callback_select_default');
            $this->form_validation->set_rules('periodicidad', 'Periodicidad', 'required|callback_select_default');
            $this->form_validation->set_rules('fecha_inicio', 'Fecha Inicio', 'required|xss_clean|callback_fecha_valida');
            $this->form_validation->set_rules('fecha_fin', 'Fecha Fin', 'required|xss_clean|callback_fecha_valida');
            $this->form_validation->set_rules('total', 'Total Nómina', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
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
                    $error_valores = "<p>La suma del valor retirado de una cuenta y el efectivo retirado de una caja, deben sumar exactamente: $" . $total . ", en vez de: $" . number_format(($valor_retirado + $efectivo_retirado), 2, '.', ',') . ".</p>";
                }
            }
            //Validamos los conceptos de nomina
            $error_conceptos = "";
            if (($this->input->post('t_concepto_nomina')) && ($this->input->post('detalle')) && ($this->input->post('cantidad')) && ($this->input->post('valor_unitario'))) {
                $t_concepto_nomina = $this->input->post('t_concepto_nomina');
                $detalle = $this->input->post('detalle');
                $cantidad = $this->input->post('cantidad');
                $valor_unitario = $this->input->post('valor_unitario');
                $i = 0;
                foreach ($t_concepto_nomina as $fila) {
                    if ($fila == "default") {
                        $error_conceptos .= "<p>El campo Tipo Concepto, es obligatorio.</p>";
                    }
                    if (($cantidad[$i] <= '0') || ($cantidad[$i] == '')) {
                        $error_conceptos .= "<p>El campo Cantidad, debe ser mayor a cero.</p>";
                    }
                    if (($valor_unitario[$i] <= '0') || ($valor_unitario[$i] == '')) {
                        $error_conceptos .= "<p>El campo Valor Unitario, debe ser mayor a cero.</p>";
                    }
                    $i++;
                }
            }

            if (($this->form_validation->run() == FALSE) || ($error_valores != "") || ($error_conceptos != "")) {
                echo form_error('empleado') . form_error('periodicidad') . form_error('fecha_inicio') . form_error('fecha_fin') . $error_conceptos . form_error('total') . form_error('valor_retirado') . form_error('efectivo_retirado') . $error_valores . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function insertar() {
        if ($this->input->post('submit')) {
            list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
            $t_periodicidad = $this->input->post('periodicidad');
            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin = $this->input->post('fecha_fin');
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
            $vigente = 1;
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_nomina = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_nomina = ($this->select_model->nextId_nomina($prefijo_nomina)->id) + 1;

            $data["tab"] = "crear_nomina";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "nomina/crear";
            $data['msn_recrear'] = "Crear otra Nómina";

            $error = $this->insert_model->nomina($prefijo_nomina, $id_nomina, $id_empleado, $dni_empleado, $t_periodicidad, $fecha_inicio, $fecha_fin, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $vigente, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                //traemos los campos de los conceptos
                $rrpp_nuevo = $this->input->post('rrpp_nuevo');
                $id_concepto = $this->input->post('id_concepto');
                $t_concepto_nomina = $this->input->post('t_concepto_nomina');
                $detalle = $this->input->post('detalle');
                $cantidad = $this->input->post('cantidad');
                $valor_unitario = $this->input->post('valor_unitario');
                if (($rrpp_nuevo == TRUE) && ($id_concepto == TRUE) && ($t_concepto_nomina == TRUE) && ($detalle == TRUE) && ($cantidad == TRUE) && ($valor_unitario == TRUE)) {
                    $i = 0;
                    foreach ($rrpp_nuevo as $fila) {
                        //En el caso que sean conceptos pendiente de nomina por ser actualizados a OK
                        if ($fila == '1') {
                            //ACtualizamos los conceptos de rrpp a 1: OK
                            $error1 = $this->update_model->concepto_nomina_estado($id_concepto[$i], 1);
                            if (isset($error1)) {
                                $data['trans_error'] = $error1;
                                $this->parser->parse('trans_error', $data);
                                return;
                            }
                            //en el caso en que hayan que crear los conceptos
                        } else {
                            $t_concepto_temp = $t_concepto_nomina[$i];
                            $detalle_temp = strtolower($detalle[$i]);
                            $cantidad_temp = $cantidad[$i];
                            $valor_unitario_temp = round(str_replace(",", "", $valor_unitario[$i]), 2);
                            $error2 = $this->insert_model->concepto_nomina($id_empleado, $dni_empleado, $prefijo_nomina, $id_nomina, $t_concepto_temp, $cantidad_temp, NULL, NULL, NULL, NULL, $cantidad_temp, $valor_unitario_temp, 1, $sede, $fecha_trans, $id_responsable, $dni_responsable);
                            if (isset($error2)) {
                                $data['trans_error'] = $error2;
                                $this->parser->parse('trans_error', $data);
                                return;
                            }
                        }
                        $i++;
                    }
                    $this->parser->parse('trans_success', $data);
                } else {
                    $data['trans_error'] = "<p>No llegaron correctamente los conceptos al servidor. Comuníquele este error a soporte de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                    return;
                }
            }
        } else {
            redirect(base_url());
        }
    }

}
