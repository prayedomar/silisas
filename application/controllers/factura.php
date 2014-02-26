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
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['dni'] = $this->select_model->t_dni_titular();

        $data['empleado'] = $this->select_model->empleado_sede_ppal_responsable($id_responsable, $dni_responsable);
        $data['action_validar'] = base_url() . "factura/validar";
        $data['action_crear'] = base_url() . "factura/insertar";
        $data['action_recargar'] = base_url() . "factura/crear";

        $data['action_validar_titular_llena_matriculas'] = base_url() . "factura/validar_titular_llena_matriculas";
        $data['action_llena_cuotas_matricula'] = base_url() . "factura/llena_cuotas_matricula";


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
            $this->escapar($_POST);
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
            $this->escapar($_POST);
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
            
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_nomina = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_nomina = ($this->select_model->nextId_nomina($prefijo_nomina)->id) + 1;

            $data["tab"] = "crear_nomina";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "nomina/crear";
            $data['msn_recrear'] = "Crear otra Nómina";

            $error = $this->insert_model->nomina($prefijo_nomina, $id_nomina, $id_empleado, $dni_empleado, $t_periodicidad, $fecha_inicio, $fecha_fin, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $vigente, $observacion, $id_responsable, $dni_responsable);

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
                            $error2 = $this->insert_model->concepto_nomina($id_empleado, $dni_empleado, $prefijo_nomina, $id_nomina, $t_concepto_temp, $cantidad_temp, NULL, NULL, NULL, NULL, $cantidad_temp, $valor_unitario_temp, 1, $sede, $id_responsable, $dni_responsable);
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

    public function validar_titular_llena_matriculas() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $dni_titular = $this->input->post('dni');
            $id_titular = $this->input->post('id');
            $titular = $this->select_model->titular($id_titular, $dni_titular);
            if ($titular == TRUE) {
                $matriculas = $this->select_model->matricula_vigente_titular($id_titular, $dni_titular);
                if ($matriculas == TRUE) {
                    $response = array(
                        'respuesta' => 'OK',
                        'nombreTitular' => $titular->nombre1 . " " . $titular->nombre2 . " " . $titular->apellido1 . " " . $titular->apellido2,
                        'filasTabla' => ''
                    );
                    foreach ($matriculas as $fila) {
                        $response['filasTabla'] .= '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="matricula" id="matricula" value="' . $fila->contrato . '"/></td>
                            <td class="text-center">' . $fila->contrato . '</td>
                            <td>' . $fila->nombre_plan . '</td>
                            <td class="text-center">$' . number_format($fila->valor_total, 2, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($fila->saldo, 2, '.', ',') . '</td>                             
                            <td class="text-center">' . $fila->sede . '</td>                         
                            <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>  
                        </tr>';
                    }
                    echo json_encode($response);
                    return false;
                } else {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p>El titular no tiene matrículas vigentes.</p>'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p>El titular no existe en la base de datos.</p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_cuotas_matricula() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if ($this->input->post('matricula')) {
                $matricula = $this->input->post('matricula');
                $matriz_matricula = $this->matriz_matricula($matricula);
                if ($matriz_matricula) {
                    $matricula = $this->select_model->matricula_id($id_matricula);
                    $plan = $this->select_model->t_plan_id($matricula->plan);
                    $cant_cuotas = $plan->cant_cuotas;
                    $response = array(
                        'respuesta' => 'OK',
                        'abonoMinimo' => '0.00',
                        'abonoMaximo' => '0.00',
                        'cantMora' => '0',
                        'intMora' => '0.00',
                        'filasTabla' => ''
                    );

                    //Solo mostrará las cuotas pendientes de pago
                    $bandera_pendiente = 0;
                    for ($i = 0; $i <= $cant_cuotas; $i++) {
                        //Solo se mostraran las cuotas que no se han cancelado
                        if ($matriz_matricula[$i][6] == 0) {
                            $num_cuota = $matriz_matricula[$i][1];
                            $t_detalle = $matriz_matricula[$i][2];
                            $pendiente = $matriz_matricula[$i][5];
                            $cant_dias_mora = $matriz_matricula[$i][8];
                            $int_mora = $matriz_matricula[$i][9];

                            if (($matriz_matricula[$i][12] == 0) && ($bandera_pendiente == 0)) {
                                //Enviamos datos por ajax
                                $response['abonoMinimo'] = $abono_minimo;
                                $response['abonoMaximo'] = $abono_maximo;
                                $response['cantMora'] = $cant_dias_mora;
                                $response['intMora'] = $int_mora;
                                $escojer = '<input type="radio" class="exit_caution" name="cuota" id="cuota" checked/>';
                                $cuota_pagada = "";
                                $saldo_deuda = "";
                                $bandera_pendiente = 1;
                            } else {
                                $escojer = '';
                                $cuota_pagada = "$" . number_format($matriz_matricula[$i][4], 2, '.', ',');
                                $saldo_deuda = "$" . number_format($matriz_matricula[$i][9], 2, '.', ',');
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
                            <td class="text-center">' . $matriz_matricula[$i][10] . '</td>                               
                            <td class="text-center">' . $matriz_matricula[$i][11] . '</td>
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

    function matriz_matricula($matricula) {
        $id_matricula = $this->select_model->prestamo_prefijo_id($matricula);
        if ($id_matricula == TRUE) {
            $matricula = $this->select_model->matricula_id($id_matricula);
            $plan = $this->select_model->t_plan_id($matricula->plan);
            var_dump($matricula);
            var_dump($plan);
            $fecha_inicio = date("Y-m-d", strtotime($matricula->fecha_matricula));
            $fecha_hoy = date('Y-m-d');
            $valor_total = $plan->valor_total;
            $cant_cuotas = $plan->cant_cuotas;
            $valor_inicial = $plan->valor_inicial;
            $valor_cuota = $plan->valor_cuota;
            $total_abonos = $this->select_model->total_abonos_matricula($id_matricula)->total;
            $tasa_mora_anual = $this->select_model->interes_mora()->tasa_mora_anual;

            echo "plan" . $plan->nombre . "<br>";

            //Llenamos la primer fila
            $matriz_matricula = array();
            $matriz_matricula[0][1] = 0; //Numero de cuota
            if ($cant_cuotas == 0) { //Plan contado
                $matriz_matricula[0][2] = 3; //T_detalle: pago total 
            } else {
                $matriz_matricula[0][2] = 1; //T_detalle: pago inicial
            }
            $matriz_matricula[0][3] = $valor_inicial; //Valor esperado
            //Miramos si se canceló esta cuota con el total de abonos
            if ($total_abonos >= $valor_inicial) {
                $total_abonos = $total_abonos - $valor_inicial; //Restamos del total de abonos esta cuota
                $matriz_matricula[0][4] = $valor_inicial; //Abonado
            } else {
                $matriz_matricula[0][4] = $total_abonos; //Abonado
                $total_abonos = 0; //Gastamos todo el total de abonados en este pago
            }
            $matriz_matricula[0][5] = $matriz_matricula[0][3] - $matriz_matricula[0][4]; //Valor pendiente de pago
            if ($matriz_matricula[0][5] <= 0) { //Puede que sea negativo en el caso en que halla abonado mas de lo esperado
                $matriz_matricula[0][6] = 1; //1: Cuota cancelada
            } else {
                $matriz_matricula[0][6] = 0; //0: Cuota no cancelada
            }
            $matriz_matricula[0][7] = $fecha_inicio; //Fecha esperada
            $valor_esperado = $matriz_matricula[0][3];
            $cuota_cancelada = $matriz_matricula[0][6];
            $fecha_esperada = $matriz_matricula[0][7];
            $matriz_matricula[0][8] = 0; //dias mora
            $matriz_matricula[0][9] = 0; //int mora            
            if (($cuota_cancelada == 0) && ($fecha_esperada < $fecha_hoy)) {
                $dias_mora = $this->dias_entre_fechas($fecha_esperada, $fecha_hoy);
                //Descartamos una mora inferior a 4 dias de gracia.   
                //Pero si es mayor a 4 la contamos completa sin descartar los 4 dias.
                if ($dias_mora > 4) {
                    $matriz_matricula[0][8] = $dias_mora;
                    if ($tasa_mora_anual) {
                        $Int_mora = round(((((pow((1 + ($tasa_mora_anual / 100)), (1 / 360))) - 1) * $dias_mora) * $valor_esperado), 2);
                        $matriz_matricula[0][9] = $Int_mora;
                    }
                }
            }

            //Calculamos el resto de cuotas
            for ($i = 1; $i <= $cant_cuotas; $i++) {
                $matriz_matricula[$i][1] = $i; //Numero de cuota
                $matriz_matricula[$i][2] = 2; //T_detalle: Abono total 
                $matriz_matricula[$i][3] = $valor_cuota; //valor esperado
                $valor_esperado = $matriz_matricula[$i][3];
                if ($total_abonos >= $valor_esperado) {
                    $total_abonos = $total_abonos - $valor_esperado; //Restamos del total de abonos esta cuota
                    $matriz_matricula[$i][4] = $valor_esperado; //Abonado
                } else {
                    $matriz_matricula[$i][4] = $total_abonos; //Abonado
                    $total_abonos = 0; //Gastamos todo el total de abonados en este pago
                }
                $valor_abonado = $matriz_matricula[$i][4];
                $matriz_matricula[$i][5] = $valor_esperado - $valor_abonado; //valor pendiente
                $valor_pendiente = $matriz_matricula[$i][5];
                if ($valor_pendiente <= 0) {
                    $matriz_matricula[$i][6] = 1; //0: Cuota cancelada
                } else {
                    $matriz_matricula[$i][6] = 0;  //0: Cuota no cancelada
                }
                $matriz_matricula[$i][7] = date("Y-m-d", strtotime("$fecha_inicio +$i month")); //Fecha esperada
                $cuota_cancelada = $matriz_matricula[$i][6];
                $fecha_esperada = $matriz_matricula[$i][7];
                $matriz_matricula[$i][8] = 0; //dias mora
                $matriz_matricula[$i][9] = 0; //int mora         
                if (($cuota_cancelada == 0) && ($fecha_esperada < $fecha_hoy)) {
                    $dias_mora = $this->dias_entre_fechas($fecha_esperada, $fecha_hoy);
                    //Descartamos una mora inferior a 4 dias de gracia.   
                    //Pero si es mayor a 4 la contamos completa sin descartar los 4 dias.
                    if ($dias_mora > 4) {
                        $matriz_matricula[$i][8] = $dias_mora;
                        if ($tasa_mora_anual) {
                            $Int_mora = round(((((pow((1 + ($tasa_mora_anual / 100)), (1 / 360))) - 1) * $dias_mora) * $valor_esperado), 2);
                            $matriz_matricula[$i][9] = $Int_mora;
                        }
                    }
                }
            }
            return $matriz_matricula;
        } else {
            return false;
        }
    }

}
