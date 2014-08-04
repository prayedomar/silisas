<?php

class Recibo_caja extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function crear() {
        $data["tab"] = "crear_recibo_caja";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);

        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
        $data['dni'] = $this->select_model->t_dni_titular();
        $data['dni_a_nombre_de'] = $this->select_model->t_dni_todos();
        $data['empleado'] = $this->select_model->empleado_activo_sedes_responsable($id_responsable, $dni_responsable);
        $data['action_validar'] = base_url() . "recibo_caja/validar";
        $data['action_crear'] = base_url() . "recibo_caja/insertar";
        $data['action_recargar'] = base_url() . "recibo_caja/crear";

        $data['action_validar_titular_llena_matriculas'] = base_url() . "recibo_caja/validar_titular_llena_matriculas";
        $data['action_llena_cuotas_matricula'] = base_url() . "recibo_caja/llena_cuotas_matricula";

        $data['action_llena_cuenta_responsable'] = base_url() . "recibo_caja/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "recibo_caja/llena_caja_responsable";
        $this->parser->parse('recibo_caja/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('dni', 'Tipo de Identificación', 'required|callback_select_default');
            $this->form_validation->set_rules('id', 'Número de Identificación', 'required|trim|min_length[5]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('matricula', 'Matricula a cancelar', 'required');
            $this->form_validation->set_rules('dni_a_nombre_de', 'Tipo de Identificación', 'required|callback_select_default');
            $this->form_validation->set_rules('id_a_nombre_de', 'Número de Identificación', 'required|trim|min_length[5]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('a_nombre_de', 'Nombre completo / Razón Social', 'required|trim|xss_clean|max_length[100]');
            $this->form_validation->set_rules('direccion_a_nombre_de', 'Direccion', 'trim|xss_clean|max_length[80]');
            $this->form_validation->set_rules('valor_consignado', 'Valor Consignado a la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_ingresado', 'Efectivo Ingresado a la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');

            $error_descuento = "";
            $error_valores = "";
            if ($this->input->post('matricula')) {
                $saldo = $this->input->post('saldo');
                if ($saldo > '0') {
                    if ($this->input->post('tipo_pago')) {
                        if ($this->input->post('tipo_pago') == "default") {
                            $this->form_validation->set_rules('tipo_pago', 'Tipo de pago', 'required|callback_select_default');
                        } else {
                            if ($this->input->post('tipo_pago') == "1") {
                                $this->form_validation->set_rules('cuotas', 'Cuotas a cancelar', 'required');
                                $this->form_validation->set_rules('subtotal', 'Total abonos', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
                                $this->form_validation->set_rules('int_mora', 'Total intereses', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
                                $this->form_validation->set_rules('descuento', 'Descuento', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
                                $this->form_validation->set_rules('total', 'Pago total', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
                                if (($this->input->post('int_mora')) && ($this->input->post('descuento'))) {
                                    $int_mora = round(str_replace(",", "", $this->input->post('int_mora')), 2);
                                    $descuento = round(str_replace(",", "", $this->input->post('descuento')), 2);
                                    if ($descuento > $int_mora) {
                                        $error_descuento = "<p>El descuento ingresado, no puede ser mayor al total de los intereses.</p>";
                                    }
                                }
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
                            } else {
                                $this->form_validation->set_rules('total', 'Valor del abono', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
                                if ($this->input->post('total_abono')) {
                                    $total_abono = round(str_replace(",", "", $this->input->post('total')), 2);
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
                                    if (round(($valor_consignado + $efectivo_ingresado), 2) != $total_abono) {
                                        $error_valores = "<p>La suma del valor consignado a la cuenta y el efectivo ingresado a la caja, deben sumar exactamente: $" . $total_abono . ", en vez de: $" . number_format(($valor_consignado + $efectivo_ingresado), 2, '.', ',') . ".</p>";
                                    }
                                    $saldo = $this->input->post('saldo');
                                    if ($total_abono > $saldo) {
                                        $error_valores = "<p>El valor del abono: " . number_format($total_abono, 2, '.', ',') . ", no puede ser mayor al saldo de la matrícula: " . number_format($saldo, 2, '.', ',') . "</p>";
                                    } else {
                                        if (round(($valor_consignado + $efectivo_ingresado), 2) != $total_abono) {
                                            $error_valores = "<p>La suma del valor consignado a la cuenta y el efectivo ingresado a la caja, deben sumar exactamente: $" . $total_abono . ", en vez de: $" . number_format(($valor_consignado + $efectivo_ingresado), 2, '.', ',') . ".</p>";
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $error_valores = "<p>La matrícula seleccionada, se encuentra a paz y salvo.</p>";
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_valores != "") || ($error_descuento != "")) {
                echo form_error('dni') . form_error('id') . form_error('dni_a_nombre_de') . form_error('id_a_nombre_de') . form_error('direccion_a_nombre_de') . form_error('matricula') . form_error('tipo_pago') . form_error('cuotas') . form_error('subtotal') . form_error('int_mora') . $error_descuento . form_error('descuento') . form_error('total') . form_error('valor_consignado') . form_error('efectivo_ingresado') . $error_valores . form_error('observacion');
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
            $id_titular = $this->input->post('id');
            $titular = $this->input->post('titular_name');
            $matricula = $this->input->post('matricula');
            $id_a_nombre_de = $this->input->post('id_a_nombre_de');
            $dni_a_nombre_de = $this->input->post('dni_a_nombre_de');
            if ($dni_a_nombre_de != "6") {
                $d_v_a_nombre_de = NULL;
            } else {
                $d_v_a_nombre_de = $this->input->post('d_v_a_nombre_de');
            }
            $a_nombre_de = $this->input->post('a_nombre_de');
            $direccion_a_nombre_de = $this->input->post('direccion_a_nombre_de');
            $tipo_pago = $this->input->post('tipo_pago');
            if ($tipo_pago == '1') {
                $subtotal = round(str_replace(",", "", $this->input->post('subtotal')), 2);
                $int_mora = round(str_replace(",", "", $this->input->post('int_mora')), 2);
                $descuento = round(str_replace(",", "", $this->input->post('descuento')), 2);
            } else {
                $subtotal = round(str_replace(",", "", $this->input->post('total')), 2);
                $int_mora = '0';
                $descuento = '0';
            }
            if (($this->input->post('caja')) && ($this->input->post('efectivo_ingresado')) && ($this->input->post('efectivo_ingresado') != 0)) {
                list($sede_caja_destino, $t_caja_destino) = explode("-", $this->input->post('caja'));
                $efectivo_ingresado = round(str_replace(",", "", $this->input->post('efectivo_ingresado')), 2);
            } else {
                $sede_caja_destino = NULL;
                $t_caja_destino = NULL;
                $efectivo_ingresado = NULL;
            }
            if (($this->input->post('cuenta')) && ($this->input->post('valor_consignado')) && ($this->input->post('valor_consignado') != 0)) {
                $cuenta_destino = $this->input->post('cuenta');
                $valor_consignado = round(str_replace(",", "", $this->input->post('valor_consignado')), 2);
            } else {
                $cuenta_destino = NULL;
                $valor_consignado = NULL;
            }
            $vigente = 1;
            $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_recibo_caja = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_recibo_caja = ($this->select_model->nextId_recibo_caja($prefijo_recibo_caja)->id) + 1;
            $t_trans = 8; //Recibo de caja
            $credito_debito = 1; //Credito   

            $detalle_array = array(
                "Matrícula" => $matricula,
                "Titular" => $titular,
                "Identificación" => $id_titular,
                "Observación" => $observacion
            );
            $detalle_json = json_encode($detalle_array);

            $data["tab"] = "crear_recibo_caja";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "recibo_caja/crear";
            $data['msn_recrear'] = "Crear otro recibo de caja";
            $data['url_imprimir'] = base_url() . "recibo_caja/consultar_pdf/" . $prefijo_recibo_caja . "_" . $id_recibo_caja . "/I";

            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_recibo_caja, $id_recibo_caja, $credito_debito, (($subtotal + $int_mora) - $descuento), $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $cuenta_destino, $valor_consignado, 1, $detalle_json, $sede, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->recibo_caja($prefijo_recibo_caja, $id_recibo_caja, $tipo_pago, $matricula, $id_a_nombre_de, $dni_a_nombre_de, $d_v_a_nombre_de, $a_nombre_de, $direccion_a_nombre_de, $subtotal, $int_mora, $descuento, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $cuenta_destino, $valor_consignado, $sede, $vigente, $observacion, $id_responsable, $dni_responsable);
                if (isset($error1)) {
                    $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    //traemos los checxbox de las cuotas canceladas
                    if ($tipo_pago == '1') {
                        $checkbox_cuotas = $this->input->post('cuotas');
                        if ($checkbox_cuotas == TRUE) {
                            foreach ($checkbox_cuotas as $fila) {
                                list($num_cuota, $id_t_detalle, $t_detalle, $valor_pendiente, $fecha_esperada, $cant_dias_mora, $int_mora_cuota) = explode("_", $fila);
                                $error3 = $this->insert_model->detalle_recibo_caja($prefijo_recibo_caja, $id_recibo_caja, $matricula, $id_t_detalle, $num_cuota, $valor_pendiente, $fecha_esperada, $cant_dias_mora, $int_mora_cuota);
                                if (isset($error3)) {
                                    $data['trans_error'] = $error3 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                                    $this->parser->parse('trans_error', $data);
                                    return FALSE;
                                }
                            }
                            $this->parser->parse('trans_success_print', $data);
                        } else {
                            $data['trans_error'] = "<p>No llegaron correctamente las cuotas al servidor. Comuníque éste error al departamento de sistemas.</p>";
                            $this->parser->parse('trans_error', $data);
                            return;
                        }
                    } else {
                        //Insertamos un solo detalle de recibo_caja
                        $saldo = $this->input->post('saldo');
                        //Es porque pagó todo el saldo pendiente
                        if ($subtotal == $saldo) {
                            $id_t_detalle = '3';
                        } else {
                            $id_t_detalle = '2';
                        }
                        $num_cuota = NULL;
                        $fecha_esperada = NULL;
                        $cant_dias_mora = '0';
                        $int_mora_cuota = '0.00';
                        $error3 = $this->insert_model->detalle_recibo_caja($prefijo_recibo_caja, $id_recibo_caja, $matricula, $id_t_detalle, $num_cuota, $subtotal, $fecha_esperada, $cant_dias_mora, $int_mora_cuota);
                        if (isset($error3)) {
                            $data['trans_error'] = $error3 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                            $this->parser->parse('trans_error', $data);
                            return FALSE;
                        } else {
                            $this->parser->parse('trans_success_print', $data);
                        }
                    }
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
                        'direccion' => $titular->direccion,
                        'filasTabla' => ''
                    );
                    foreach ($matriculas as $fila) {
                        $response['filasTabla'] .= '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="matricula" id="matricula" value="' . $fila->contrato . '" data-saldo="' . $fila->saldo . '"/></td>
                            <td class="text-center">' . $fila->contrato . '</td>
                            <td>' . $fila->nombre_plan . '</td>
                            <td class="text-center">$' . number_format($fila->valor_total, 2, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($fila->saldo, 2, '.', ',') . '</td>                             
                            <td class="text-center">' . $fila->sede . '</td>                         
                            <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_matricula)) . '</td>  
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
                $id_matricula = $this->input->post('matricula');
                $matriz_matricula = $this->matriz_matricula($id_matricula);
                if ($matriz_matricula) {
                    $matricula = $this->select_model->matricula_id($id_matricula);
                    $plan = $this->select_model->t_plan_id($matricula->plan);
                    $cant_cuotas = $plan->cant_cuotas;
                    $response = array(
                        'respuesta' => 'OK',
                        'filasTabla' => ''
                    );
                    //Solo mostrará las cuotas pendientes de pago
                    for ($i = 0; $i <= $cant_cuotas; $i++) {
                        //Solo se mostraran las cuotas que no se han cancelado
                        if ($matriz_matricula[$i][6] == 0) {
                            $num_cuota = $matriz_matricula[$i][1];
                            $id_t_detalle = $matriz_matricula[$i][2];
                            $t_detalle = $this->select_model->t_detalle($id_t_detalle)->tipo;
                            $valor_pendiente = $matriz_matricula[$i][5];
                            $fecha_esperada = $matriz_matricula[$i][7];
                            $cant_dias_mora = $matriz_matricula[$i][8];
                            $int_mora = $matriz_matricula[$i][9];

                            $response['filasTabla'] .= '<tr>
                            <td class="text-center"><input type="checkbox" class="exit_caution" name="cuotas[]" id="cuotas"  value="' . $num_cuota . "_" . $id_t_detalle . "_" . $t_detalle . "_" . $valor_pendiente . "_" . $fecha_esperada . "_" . $cant_dias_mora . "_" . $int_mora . '" data-num_cuota="' . $num_cuota . '" data-t_detalle="' . $t_detalle . '" data-valor_pendiente="' . $valor_pendiente . '" data-fecha_esperada="' . $fecha_esperada . '" data-cant_dias_mora="' . $cant_dias_mora . '" data-int_mora="' . $int_mora . '" /></td>
                            <td class="text-center">' . $num_cuota . '</td>
                            <td class="text-center">' . $t_detalle . '</td>
                            <td class="text-center">$' . number_format($valor_pendiente, 2, '.', ',') . '</td>                            
                            <td class="text-center">' . $fecha_esperada . '</td> 
                            <td class="text-center">' . $cant_dias_mora . '</td>                                
                            <td class="text-center">$' . number_format($int_mora, 2, '.', ',') . '</td>
                        </tr>';
                        }
                    }
//                    echo $response['filasTabla'];
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

    function matriz_matricula($id_matricula) {
        $matricula = $this->select_model->matricula_id($id_matricula);
        $plan = $this->select_model->t_plan_id($matricula->plan);
        $fecha_inicio = date("Y-m-d", strtotime($matricula->fecha_matricula));
        $fecha_hoy = date('Y-m-d');
        $valor_total = $plan->valor_total;
        $cant_cuotas = $plan->cant_cuotas;
        $valor_inicial = $plan->valor_inicial;
        $valor_cuota = $plan->valor_cuota;
        $total_abonos = $this->select_model->total_abonos_matricula($id_matricula)->total;
        $tasa_mora_anual = $this->select_model->interes_mora()->tasa_mora_anual;

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
                    $int_mora = round(((((pow((1 + ($tasa_mora_anual / 100)), (1 / 360))) - 1) * $dias_mora) * $matriz_matricula[0][5]), 2);
                    $matriz_matricula[0][9] = $int_mora;
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
                        $Int_mora = round(((((pow((1 + ($tasa_mora_anual / 100)), (1 / 360))) - 1) * $dias_mora) * $matriz_matricula[$i][5]), 2);
                        $matriz_matricula[$i][9] = $Int_mora;
                    }
                }
            }
        }
        return $matriz_matricula;
    }

    public function llena_cuenta_responsable() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $cuentas = $this->select_model->cuenta_banco_responsable_ingresar($id_responsable, $dni_responsable);
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
                $cajas = $this->select_model->caja_responsable($id_responsable, $dni_responsable);
                if (($cajas == TRUE)) {
                    foreach ($cajas as $fila) {
                        $responsable = $this->select_model->empleado($fila->id_encargado, $fila->dni_encargado);
                        echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="caja" id="caja" value="' . $fila->sede . "-" . $fila->t_caja . '"/></td>
                            <td class="text-center">' . $fila->name_sede . '</td>
                            <td>' . $fila->name_t_caja . '</td>  
                            <td>' . $responsable->nombre1 . " " . $responsable->nombre2 . " " . $responsable->apellido1 . " " . '</td>  
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

    function consultar() {
        $data["tab"] = "consultar_recibo_caja";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['error_consulta'] = "";
        $data['action_crear'] = base_url() . "recibo_caja/consultar_validar";
        $data['action_recargar'] = base_url() . "recibo_caja/consultar";
        $this->parser->parse('recibo_caja/consultar', $data);
        $this->load->view('footer');
    }

    public function consultar_validar() {
        $this->escapar($_POST);
        $this->form_validation->set_rules('prefijo', 'Prefijo de sede', 'required|callback_select_default');
        $this->form_validation->set_rules('id', 'Número o consecutivo', 'required|trim|max_length[13]|integer|callback_valor_positivo');
        $prefijo = $this->input->post('prefijo');
        $id = $this->input->post('id');
        $error_transaccion = "";
        if (($this->input->post('prefijo') != "default") && ($this->input->post('id'))) {
            $recibo_caja = $this->select_model->recibo_caja_prefijo_id($prefijo, $id);
            if ($recibo_caja != TRUE) {
                $error_transaccion = "El recibo de caja, no existe en la base de datos.";
            }
        }
        if (($this->form_validation->run() == FALSE) || ($error_transaccion != "")) {
            $data["tab"] = "consultar_recibo_caja";
            $this->isLogin($data["tab"]);
            $data["error_consulta"] = form_error('prefijo') . form_error('id') . $error_transaccion;
            $data["prefijo"] = $prefijo;
            $data["id"] = $id;
            $this->load->view("header", $data);
            $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
            $data['action_crear'] = base_url() . "recibo_caja/consultar_validar";
            $this->parser->parse('recibo_caja/consultar', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url() . "recibo_caja/consultar_pdf/" . $prefijo . "_" . $id . "/I");
        }
    }

    function consultar_pdf($id_recibo_caja, $salida_pdf) {
        $recibo_caja_prefijo_id = $id_recibo_caja;
        $id_recibo_caja_limpio = str_replace("_", " ", $recibo_caja_prefijo_id);
        list($prefijo, $id) = explode("_", $recibo_caja_prefijo_id);
        $recibo_caja = $this->select_model->recibo_caja_prefijo_id($prefijo, $id);
        $detalles_recibo_caja = $this->select_model->detalle_recibo_caja_prefijo_id($prefijo, $id);
        if (($recibo_caja == TRUE) && ($detalles_recibo_caja == TRUE)) {
            $responsable = $this->select_model->empleado($recibo_caja->id_responsable, $recibo_caja->dni_responsable);
            $dni_abreviado = $this->select_model->t_dni_id($recibo_caja->dni_a_nombre_de)->abreviacion;
            if (($recibo_caja->d_v_a_nombre_de) == (NULL)) {
                $d_v = "";
            } else {
                $d_v = " - " . $recibo_caja->d_v_a_nombre_de;
            }


            $this->load->library('Pdf');
            $pdf = new Pdf('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Sili S.A.S');
            $pdf->SetTitle('Recibo de caja ' . $id_recibo_caja_limpio . ' Sili S.A.S');
            $pdf->SetSubject('Recibo de caja ' . $id_recibo_caja_limpio . ' Sili S.A.S');
            $pdf->SetKeywords('sili, sili sas');


//// se pueden modificar en el archivo tcpdf_config.php de libraries/config
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//relación utilizada para ajustar la conversión de los píxeles
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// ---------------------------------------------------------
// establecer el modo de fuente por defecto            
            $pdf->setFontSubsetting(true);
            $pdf->setPrintHeader(false); //no imprime la cabecera ni la linea
            $pdf->setPrintFooter(false); //no imprime el pie ni la linea        
// Añadir una página
// Este método tiene varias opciones, consulta la documentación para más información.
            if ($recibo_caja->vigente == "1") {
                $pdf->AddPage();

                //preparamos y maquetamos el contenido a crear
                $html = '';
                $html .= '<style type=text/css>';
                $html .= 'h2{font-family: "times new roman", times, serif;font-size:50px;font-weight: bold;font-style: italic;line-height:20px;}';
                $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:7px;}';
                $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:0px;text-align:center;}';
                $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
                $html .= 'td.c1{width:418px;}td.c1000{line-height:100px;}';
                $html .= 'td.c2{width:310px;}';
                $html .= 'td.c3{width:112px;}';
                $html .= 'td.c4{width:306px;}';
                $html .= 'td.c7{border-top-color:#FFFFFF;border-left-color:#000000;font-family: helvetica, sans-serif;font-size:12px;}';
                $html .= 'td.c8{border-top-color:#FFFFFF;border-left-color:#000000;border-right-color:#000000;font-family: helvetica, sans-serif;font-size:12px;}';
                $html .= 'td.c20{width:418px;height:20px;line-height:20px;line-height:19px;}';
                $html .= 'td.c21{width:190px;height:20px;line-height:20px;font-weight: bold;}';
                $html .= 'td.c22{width:120px;height:20px;line-height:20px;font-weight: bold;}';
                $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;}';
                $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;line-height:15px;height:30px;line-height:25px;border-top-color:#FFFFFF;border-left-color:#FFFFFF;border-right-color:#FFFFFF;}';
                $html .= 'td.c25{border-top-color:#000000;border-bottom-color:#000000;border-left-color:#000000;border-right-color:#000000;}';
                $html .= 'td.c26{background-color:#F5F5F5;}';
                $html .= 'td.a1{text-align:left;}';
                $html .= 'td.a2{text-align:center;}';
                $html .= 'th.c26{background-color:#F5F5F5;}';
                $html .= 'th.a1{text-align:left;}';
                $html .= 'th.a2{text-align:center;}';
                $html .= 'th.d1{width:263px;font-weight: bold;height:22px;line-height:20px;}';
                $html .= 'th.d2{width:105px;font-weight: bold;height:22px;line-height:20px;}';
                $html .= 'th.d3{width:85px;font-weight: bold;height:22px;line-height:20px;}';
                $html .= 'th.d4{width:105px;font-weight: bold;height:22px;line-height:20px;}';
                $html .= 'th.d5{width:120px;font-weight: bold;height:22px;line-height:20px;}';
                $html .= 'th.d6{width:50px;font-weight: bold;height:22px;line-height:20px;}';
                $html .= 'table{border-spacing: 0;}';
                $html .= 'table.t1{text-align:left;}';
                $html .= '</style>';
                $html .= '<table width="100%"><tr>'
                        . '<td class="c1 a2" rowspan="5" colspan="2"><h2></h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p><p class="b2">Resolución de facturación DIAN No. 110000581182 del 28/05/2014</p>'
                        . '<p class="b1">Medellín: Calle 47D # 77 AA - 67  (Floresta)  / Tels.: 4114107 – 4126800<br>'
                        . 'Medellín: Carrera 48B # 10 SUR - 118 (Poblado) / Tels.: 3128614 – 3126060<br>'
                        . 'Cali Sur: Carrera 44 # 5A – 26 (Tequendama) / Tels.: 3818008 – 3926723<br>'
                        . 'Cali Norte: Calle 25 # Norte 6A – 32 (Santa Mónica) / Tels.: 3816803 – 3816734<br>'
                        . 'Bucaramanga: Carrera 33 # 54 – 91 (Cabecera) / Tels.: 6832612 – 6174057<br>'
                        . 'Montería: Calle 58 # 6 – 39 (Castellana) / Tels.:7957110 – 7957110<br>'
                        . 'Montelíbano: Calle 17 # 13 2do piso / Tels.: 7625202 – 7625650<br>'
                        . 'Santa Marta: Carrera 13 B # 27 B – 84  (B. Bavaria) / Tels.: 4307566 – 4307570<br>'
                        . 'El Bagre: Calle 1 # 32 (Cornaliza) / Tels.: 8372645 – 8372653<br>'
                        . 'Caucasia: Carrera 8A # 22 – 48. 2do Piso (B. Kennedy) / Tels.: 8391693 - 8393582</p>'
                        . '</td>'
                        . '<td class="c2 a2 c1000"  colspan="2"></td>'
                        . '<br>'
                        . '</tr><tr>'
                        . '<td class="c24 a2" colspan="2">RECIBO DE CAJA</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td class="c23 c25"><b>Número:</b></td><td class="c23 c25">' . $id_recibo_caja_limpio . '</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td class="c23 c25"><b>Fecha de emisión:</b></td><td class="c23 c25">' . date("Y-m-d", strtotime($recibo_caja->fecha_trans)) . '</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td class="c23 c25"><b>Responsable empresa:</b></td><td class="c23 c25">' . $responsable->nombre1 . " " . $responsable->apellido1 . '</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td class="c3 c23 c25"><b>Cliente:</b></td><td class="c4 c23 c25">' . $recibo_caja->a_nombre_de . '</td>'
                        . '<td class="c23 c25"><b>Documento cliente:</b></td><td class="c23 c25">' . $dni_abreviado . ' ' . $recibo_caja->id_a_nombre_de . $d_v . '</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td class="c23 c25"><b>Dirección:</b></td><td class="c23 c25">' . $recibo_caja->direccion_a_nombre_de . '</td>'
                        . '<td class="c23 c25"><b>Número de matrícula:</b></td><td class="c23 c25">' . $recibo_caja->matricula . '</td>'
                        . '</tr>'
                        . '</table><br>'
                        . '<table border="1" class="t1">'
                        . '<tr>'
                        . '<th class="d1 c23 a2 c26">Detalle</th>'
                        . '<th class="d6 c23 a2 c26">Cuota</th>'
                        . '<th class="d2 c23 a2 c26">Abono</th>'
                        . '<th class="d3 c23 a2 c26">Dias Mora</th>'
                        . '<th class="d4 c23 a2 c26">Int. Mora</th>'
                        . '<th class="d5 c23 a2 c26">Subtotal</th>'
                        . '</tr>';
                $cont_filas = 0;
                foreach ($detalles_recibo_caja as $fila) {
                    $cont_filas ++;
                    $html .= '<tr>'
                            . '<td class="c7">' . $this->select_model->t_detalle($fila->t_detalle)->tipo . '</td>'
                            . '<td class="c8 a2">' . $fila->num_cuota . '</td>'
                            . '<td class="c8 a2">$' . number_format($fila->subtotal, 1, '.', ',') . '</td>'
                            . '<td class="c8 a2">' . $fila->cant_dias_mora . '</td>'
                            . '<td class="c8 a2">$' . number_format($fila->int_mora, 1, '.', ',') . '</td>'
                            . '<td class="c8 a2">$' . number_format((($fila->subtotal) + $fila->int_mora), 1, '.', ',') . '</td>'
                            . '</tr>';
                }
                for ($i = $cont_filas; $i < 10; $i++) {
                    $html .= '<tr><td class="c7"></td><td class="c8"></td><td class="c8"></td><td class="c8"></td><td class="c8"></td><td class="c8"></td></tr>';
                }
                $html .= '</table>'
                        . '<table border="1" class="t1">'
                        . '<tr>';
                if ($recibo_caja->vigente == "1") {
                    $html .= '<td class="c20" rowspan="4"><br><br><br><br>Firma y sello empresa: ______________________________</td>';
                } else {
                    $html .= '<td class="c20 a2" rowspan="4"><img src="' . base_url() . 'images/anulado.png" class="img-responsive" height="80" /></td>';
                }
                $html .= '<td class="c21 c23 c26">Total Abonos (+)</td>'
                        . '<td class="c22 c23 a2 c26">$' . number_format($recibo_caja->subtotal, 1, '.', ',') . '</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td class="c21 c23 c26">Total Int. Mora (+)</td>'
                        . '<td class="c22 c23 a2 c26">$' . number_format($recibo_caja->int_mora, 1, '.', ',') . '</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td class="c21 c23 c26">Total Descuento (-)</td>'
                        . '<td class="c22 c23 a2 c26">$' . number_format($recibo_caja->descuento, 1, '.', ',') . '</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td class="c21 c23 c26">Total a Pagar (=)</td>'
                        . '<td class="c22 c23 a2 c26">$' . number_format((($recibo_caja->subtotal + $recibo_caja->int_mora) - ($recibo_caja->descuento)), 1, '.', ',') . '</td>'
                        . '</tr>'
                        . '</table><p class="b3">- Copia para el cliente -</p>';

                // Imprimimos el texto con writeHTMLCell()
                $pdf->writeHTML($html, true, false, true, false, '');

                // reset pointer to the last page
                $pdf->lastPage();
            }
            $pdf->AddPage();
            $html = '';
            $html .= '<style type=text/css>';
            $html .= 'h2{font-family: "times new roman", times, serif;font-size:50px;font-weight: bold;font-style: italic;line-height:20px;}';
            $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:7px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:0px;text-align:center;}';
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $html .= 'td.c1{width:418px;}td.c1000{line-height:100px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:112px;}';
            $html .= 'td.c4{width:306px;}';
            $html .= 'td.c7{border-top-color:#FFFFFF;border-left-color:#000000;font-family: helvetica, sans-serif;font-size:12px;}';
            $html .= 'td.c8{border-top-color:#FFFFFF;border-left-color:#000000;border-right-color:#000000;font-family: helvetica, sans-serif;font-size:12px;}';
            $html .= 'td.c20{width:418px;height:20px;line-height:20px;line-height:19px;}';
            $html .= 'td.c21{width:190px;height:20px;line-height:20px;font-weight: bold;}';
            $html .= 'td.c22{width:120px;height:20px;line-height:20px;font-weight: bold;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;line-height:15px;height:30px;line-height:25px;border-top-color:#FFFFFF;border-left-color:#FFFFFF;border-right-color:#FFFFFF;}';
            $html .= 'td.c25{border-top-color:#000000;border-bottom-color:#000000;border-left-color:#000000;border-right-color:#000000;}';
            $html .= 'td.c26{background-color:#F5F5F5;}';
            $html .= 'td.a1{text-align:left;}';
            $html .= 'td.a2{text-align:center;}';
            $html .= 'th.c26{background-color:#F5F5F5;}';
            $html .= 'th.a1{text-align:left;}';
            $html .= 'th.a2{text-align:center;}';
            $html .= 'th.d1{width:263px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'th.d2{width:105px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'th.d3{width:85px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'th.d4{width:105px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'th.d5{width:120px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'th.d6{width:50px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'table{border-spacing: 0;}';
            $html .= 'table.t1{text-align:left;}';
            $html .= '</style>';
            $html .= '<table width="100%"><tr>'
                    . '<td class="c1 a2" rowspan="5" colspan="2"><h2></h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p><p class="b2">Resolución de facturación DIAN No. 110000581182 del 28/05/2014</p>'
                    . '<p class="b1">Medellín: Calle 47D # 77 AA - 67  (Floresta)  / Tels.: 4114107 – 4126800<br>'
                    . 'Medellín: Carrera 48B # 10 SUR - 118 (Poblado) / Tels.: 3128614 – 3126060<br>'
                    . 'Cali Sur: Carrera 44 # 5A – 26 (Tequendama) / Tels.: 3818008 – 3926723<br>'
                    . 'Cali Norte: Calle 25 # Norte 6A – 32 (Santa Mónica) / Tels.: 3816803 – 3816734<br>'
                    . 'Bucaramanga: Carrera 33 # 54 – 91 (Cabecera) / Tels.: 6832612 – 6174057<br>'
                    . 'Montería: Calle 58 # 6 – 39 (Castellana) / Tels.:7957110 – 7957110<br>'
                    . 'Montelíbano: Calle 17 # 13 2do piso / Tels.: 7625202 – 7625650<br>'
                    . 'Santa Marta: Carrera 13 B # 27 B – 84  (B. Bavaria) / Tels.: 4307566 – 4307570<br>'
                    . 'El Bagre: Calle 1 # 32 (Cornaliza) / Tels.: 8372645 – 8372653<br>'
                    . 'Caucasia: Carrera 8A # 22 – 48. 2do Piso (B. Kennedy) / Tels.: 8391693 - 8393582</p>'
                    . '</td>'
                    . '<td class="c2 a2 c1000"  colspan="2"></td>'
                    . '<br>'
                    . '</tr><tr>'
                    . '<td class="c24 a2" colspan="2">RECIBO DE CAJA</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25"><b>Número:</b></td><td class="c23 c25">' . $id_recibo_caja_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25"><b>Fecha de emisión:</b></td><td class="c23 c25">' . date("Y-m-d", strtotime($recibo_caja->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25"><b>Responsable empresa:</b></td><td class="c23 c25">' . $responsable->nombre1 . " " . $responsable->apellido1 . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c25"><b>Cliente:</b></td><td class="c4 c23 c25">' . $recibo_caja->a_nombre_de . '</td>'
                    . '<td class="c23 c25"><b>Documento cliente:</b></td><td class="c23 c25">' . $dni_abreviado . ' ' . $recibo_caja->id_a_nombre_de . $d_v . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25"><b>Dirección:</b></td><td class="c23 c25">' . $recibo_caja->direccion_a_nombre_de . '</td>'
                    . '<td class="c23 c25"><b>Número de matrícula:</b></td><td class="c23 c25">' . $recibo_caja->matricula . '</td>'
                    . '</tr>'
                    . '</table><br>'
                    . '<table border="1" class="t1">'
                    . '<tr>'
                    . '<th class="d1 c23 a2 c26">Detalle</th>'
                    . '<th class="d6 c23 a2 c26">Cuota</th>'
                    . '<th class="d2 c23 a2 c26">Abono</th>'
                    . '<th class="d3 c23 a2 c26">Dias Mora</th>'
                    . '<th class="d4 c23 a2 c26">Int. Mora</th>'
                    . '<th class="d5 c23 a2 c26">Subtotal</th>'
                    . '</tr>';
            $cont_filas = 0;
            foreach ($detalles_recibo_caja as $fila) {
                $cont_filas ++;
                $html .= '<tr>'
                        . '<td class="c7">' . $this->select_model->t_detalle($fila->t_detalle)->tipo . '</td>'
                        . '<td class="c8 a2">' . $fila->num_cuota . '</td>'
                        . '<td class="c8 a2">$' . number_format($fila->subtotal, 1, '.', ',') . '</td>'
                        . '<td class="c8 a2">' . $fila->cant_dias_mora . '</td>'
                        . '<td class="c8 a2">$' . number_format($fila->int_mora, 1, '.', ',') . '</td>'
                        . '<td class="c8 a2">$' . number_format((($fila->subtotal) + $fila->int_mora), 1, '.', ',') . '</td>'
                        . '</tr>';
            }
            for ($i = $cont_filas; $i < 10; $i++) {
                $html .= '<tr><td class="c7"></td><td class="c8"></td><td class="c8"></td><td class="c8"></td><td class="c8"></td><td class="c8"></td></tr>';
            }
            $html .= '</table>'
                    . '<table border="1" class="t1">'
                    . '<tr>';
            if ($recibo_caja->vigente == "1") {
                $html .= '<td class="c20" rowspan="4"><br><br><br><br>Firma y sello empresa: ______________________________</td>';
            } else {
                $html .= '<td class="c20 a2" rowspan="4"><img src="' . base_url() . 'images/anulado.png" class="img-responsive" height="80" /></td>';
            }
            $html .= '<td class="c21 c23 c26">Total Abonos (+)</td>'
                    . '<td class="c22 c23 a2 c26">$' . number_format($recibo_caja->subtotal, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c21 c23 c26">Total Int. Mora (+)</td>'
                    . '<td class="c22 c23 a2 c26">$' . number_format($recibo_caja->int_mora, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c21 c23 c26">Total Descuento (-)</td>'
                    . '<td class="c22 c23 a2 c26">$' . number_format($recibo_caja->descuento, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c21 c23 c26">Total a Pagar (=)</td>'
                    . '<td class="c22 c23 a2 c26">$' . number_format((($recibo_caja->subtotal + $recibo_caja->int_mora) - ($recibo_caja->descuento)), 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '</table><p class="b3">- Copia para la empresa -</p>';

// Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
            $nombre_archivo = utf8_decode('Recibo de caja ' . $id_recibo_caja_limpio . ' Sili S.A.S.pdf');
            $pdf->Output($nombre_archivo, $salida_pdf);
        } else {
            redirect(base_url() . 'recibo_caja/consultar/');
        }
    }

    function anular() {
        $data["tab"] = "anular_recibo_caja";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['action_validar'] = base_url() . "recibo_caja/validar_anular";
        $data['action_crear'] = base_url() . "recibo_caja/insertar_anular";
        $data['action_recargar'] = base_url() . "recibo_caja/anular";
        $data['action_validar_transaccion_anular'] = base_url() . "recibo_caja/validar_transaccion_anular";
        $this->parser->parse('recibo_caja/anular', $data);
        $this->load->view('footer');
    }

    function validar_anular() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('prefijo', 'Prefijo de sede', 'required|callback_select_default');
            $this->form_validation->set_rules('id', 'Consecutivo', 'required|trim|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'required|trim|xss_clean|max_length[255]');
            if ($this->form_validation->run() == FALSE) {
                echo form_error('prefijo') . form_error('id') . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function insertar_anular() {
        if ($this->input->post('submit')) {
            $this->escapar($_POST);
            $this->load->model('update_model');
            $prefijo = $this->input->post('prefijo');
            $id = $this->input->post('id');
            $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $t_trans = '8'; //Recibo de caja     
            $credito_debito = '1'; //credito
            $vigente = '0'; //Anulado

            $data["tab"] = "anular_recibo_caja";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "recibo_caja/anular";
            $data['msn_recrear'] = "Anular otro recibo de caja";
            $data['url_imprimir'] = base_url() . "recibo_caja/consultar_pdf/" . $prefijo . "_" . $id . "/I";            

            $this->load->model('transaccionesm');
            $movimiento_transaccion = $this->transaccionesm->movimiento_transaccion_id($t_trans, $prefijo, $id, $credito_debito);
            //Con el segundo argumento de jsondecode el true, convierto de objeto a array
            if (is_array(json_decode($movimiento_transaccion->detalle_json, true))) {
                $array_detalles = json_decode($movimiento_transaccion->detalle_json, true);
            }
            $responsable = $this->select_model->empleado($id_responsable, $dni_responsable);
            $array_detalles['Observación_Anulación'] = $observacion;
            $array_detalles['Responsable_Anulación'] = $responsable->nombre1 . " " . $responsable->nombre2 . " " . $responsable->apellido1;
            $array_detalles['Id_Responsable_Anulación'] = $id_responsable;
            $detalle_json = json_encode($array_detalles);

            $error = $this->update_model->movimiento_transaccion_vigente($t_trans, $prefijo, $id, $credito_debito, $vigente, $detalle_json);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->update_model->recibo_caja_vigente($prefijo, $id, $vigente);
                if (isset($error1)) {
                    $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    $error2 = $this->insert_model->anular_transaccion($t_trans, $prefijo, $id, $observacion, $id_responsable, $dni_responsable);
                    if (isset($error2)) {
                        $data['trans_error'] = $error2 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                        $this->parser->parse('trans_error', $data);
                    } else {
                        $this->parser->parse('trans_success_print', $data);
                    }
                }
            }
        } else {
            redirect(base_url());
        }
    }

    public function validar_transaccion_anular() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $prefijo = $this->input->post('prefijo');
            $id = $this->input->post('id');
            $this->load->model('recibo_cajam');
            $recibo_caja = $this->recibo_cajam->recibo_caja_prefijo_id($prefijo, $id);
            if ($recibo_caja == TRUE) {
                if ($recibo_caja->vigente == 1) {
                    $response = array(
                        'respuesta' => 'OK',
                        'filasTabla' => ''
                    );
                    $response['filasTabla'] .= '<tr>
                            <td class="text-center">' . $recibo_caja->matricula . '</td>
                            <td class="text-center">$' . number_format($recibo_caja->subtotal + $recibo_caja->int_mora - $recibo_caja->descuento, 2, '.', ',') . '</td>
                            <td class="text-center">' . $recibo_caja->sede_caja . '-' . $recibo_caja->tipo_caja . '</td>
                            <td class="text-center">$' . number_format($recibo_caja->efectivo_ingresado, 2, '.', ',') . '</td>
                            <td class="text-center">' . $recibo_caja->cuenta_destino . '</td>
                            <td class="text-center">$' . number_format($recibo_caja->valor_consignado, 2, '.', ',') . '</td> 
                            <td class="text-center">' . $recibo_caja->responsable . '</td>                                
                            <td class="text-center">' . date("Y-m-d", strtotime($recibo_caja->fecha_trans)) . '</td>
                        </tr>';
                    echo json_encode($response);
                    return false;
                } else {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><strong><center>El recibo de caja, ya se encuentra anulado.</center></strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong><center>El recibo de caja, no existe en la base de datos.</center></strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

}
