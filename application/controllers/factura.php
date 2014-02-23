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
