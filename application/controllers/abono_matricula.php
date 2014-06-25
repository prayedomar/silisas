<?php

class Abono_matricula extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

//Crear: Nomina
    function crear() {
        $data["tab"] = "crear_abono_matricula";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
        $data['dni'] = $this->select_model->t_dni_titular();
        $data['dni_a_nombre_de'] = $this->select_model->t_dni_todos();
        $data['empleado'] = $this->select_model->empleado_sede_ppal_responsable($id_responsable, $dni_responsable);
        $data['action_validar'] = base_url() . "abono_matricula/validar";
        $data['action_crear'] = base_url() . "abono_matricula/insertar";
        $data['action_recargar'] = base_url() . "abono_matricula/crear";

        $data['action_validar_titular_llena_matriculas'] = base_url() . "abono_matricula/validar_titular_llena_matriculas";
        $data['action_llena_saldo_matricula'] = base_url() . "abono_matricula/llena_saldo_matricula";

        $data['action_llena_cuenta_responsable'] = base_url() . "abono_matricula/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "abono_matricula/llena_caja_responsable";
        $this->parser->parse('abono_matricula/crear', $data);
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
            $this->form_validation->set_rules('total', 'Valor del abono', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('valor_consignado', 'Valor Consignado a la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_ingresado', 'Efectivo Ingresado a la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
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
                $saldo = round(str_replace(",", "", $this->input->post('saldo')), 2);
                if ($total > $saldo) {
                    $error_valores = "<p>El valor del abono, no puede ser mayor al saldo de la matrícula</p>";
                } else {
                    if (round(($valor_consignado + $efectivo_ingresado), 2) != $total) {
                        $error_valores = "<p>La suma del valor consignado a la cuenta y el efectivo ingresado a la caja, deben sumar exactamente: $" . $this->input->post('total') . ", en vez de: $" . number_format(($valor_consignado + $efectivo_ingresado), 2, '.', ',') . ".</p>";
                    }
                }
            }

            if (($this->form_validation->run() == FALSE) || ($error_valores != "")) {
                echo form_error('dni') . form_error('id') . form_error('dni_a_nombre_de') . form_error('id_a_nombre_de') . form_error('a_nombre_de') . form_error('direccion_a_nombre_de') . form_error('matricula') . form_error('total') . form_error('valor_consignado') . form_error('efectivo_ingresado') . $error_valores . form_error('observacion');
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
            $subtotal = round(str_replace(",", "", $this->input->post('total')), 2);
            $int_mora = '0';
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
            $prefijo_abono_matricula = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_abono_matricula = ($this->select_model->nextId_abono_matricula($prefijo_abono_matricula)->id) + 1;
            $t_trans = 15; //Abono a matricula
            $credito_debito = 1; //Credito            

            $data["tab"] = "crear_abono_matricula";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "abono_matricula/crear";
            $data['msn_recrear'] = "Crear otro abono a matrícula";
            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_abono_matricula, $id_abono_matricula, $credito_debito, ($subtotal + $int_mora), $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $cuenta_destino, $valor_consignado, 1, $sede, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->abono_matricula($prefijo_abono_matricula, $id_abono_matricula, $t_trans, $matricula, $id_a_nombre_de, $dni_a_nombre_de, $d_v_a_nombre_de, $a_nombre_de, $direccion_a_nombre_de, $subtotal, $int_mora, 0, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $cuenta_destino, $valor_consignado, $sede, $vigente, $observacion, $id_responsable, $dni_responsable);
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
                            <td class="text-center"><input type="radio" class="exit_caution" name="matricula" id="matricula" value="' . $fila->contrato . '"/></td>
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
                        'mensaje' => '<p><strong>El titular no tiene matrículas vigentes.</strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong>El titular no existe en la base de datos.</strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_saldo_matricula() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if ($this->input->post('matricula')) {
                $id_matricula = $this->input->post('matricula');
                $saldo_matricula = $this->select_model->saldo_matricula($id_matricula);
                if ($saldo_matricula) {
                    $response = array(
                        'respuesta' => 'OK',
                        'saldo' => $saldo_matricula->saldo
                    );
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

}
