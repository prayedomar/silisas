<?php

class Abono_prestamo extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

//Crear: Abono a Prestamo
    function crear() {
        $data["tab"] = "crear_abono_prestamo";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['t_beneficiario'] = $this->select_model->t_usuario_prestamo();
        $data['empleado'] = $this->select_model->empleado_sedes_responsable($id_responsable, $dni_responsable);
        $data['dni'] = $this->select_model->t_dni_cliente();

        $data['action_validar'] = base_url() . "abono_prestamo/validar";
        $data['action_crear'] = base_url() . "abono_prestamo/insertar";
        $data['action_llena_empleado_prestamo'] = base_url() . "abono_prestamo/llena_empleado_prestamo";
        $data['action_llena_cliente_prestamo'] = base_url() . "abono_prestamo/llena_cliente_prestamo";
        $data['action_llena_prestamo_beneficiario'] = base_url() . "abono_prestamo/llena_prestamo_beneficiario";
        $data['action_llena_cuotas_prestamo_pdtes'] = base_url() . "abono_prestamo/llena_cuotas_prestamo_pdtes";
        $data['action_llena_cuenta_responsable'] = base_url() . "abono_prestamo/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "abono_prestamo/llena_caja_responsable";

        $this->parser->parse('abono_prestamo/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
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

    function insertar() {
        if ($this->input->post('submit')) {
            $this->escapar($_POST);
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
            
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_abono = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_abono = ($this->select_model->nextId_abono_prestamo($prefijo_abono)->id) + 1;
            $t_trans = 4; //Abono a prestamo
            $credito_debito = 1; //Credito            

            $data["tab"] = "crear_abono_prestamo";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "abono_prestamo/crear";
            $data['msn_recrear'] = "Crear otro Abono a Prestamo";

            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_abono, $id_abono, $credito_debito, ($subtotal + $int_mora), $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $cuenta_destino, $valor_consignado, 1, $sede, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->abono_prestamo($prefijo_abono, $id_abono, $prefijo_prestamo, $id_prestamo, $subtotal, $cant_dias_mora, $int_mora, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $sede, $vigente, $observacion, $id_responsable, $dni_responsable);
                if (isset($error1)) {
                    $data['trans_error'] = $error1;
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
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_empleado_prestamo() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
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

    public function llena_cliente_prestamo() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
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

    public function llena_prestamo_beneficiario() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
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
            $tasa_mora_anual = $this->select_model->interes_mora()->tasa_mora_anual;

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

}
