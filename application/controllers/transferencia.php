<?php

class Transferencia extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
        $this->load->model('transferenciam');
    }

    function crear() {
        $data["tab"] = "crear_transferencia";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);

        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['sede_destino'] = $this->select_model->sede_activa();
        $data['action_validar'] = base_url() . "transferencia/validar";
        $data['action_crear'] = base_url() . "transferencia/insertar";
        $data['action_llena_cuenta_responsable'] = base_url() . "transferencia/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "transferencia/llena_caja_responsable";
        $data['action_llena_cuenta_destino'] = base_url() . "transferencia/llena_cuenta_destino";
        $data['action_llena_caja_destino'] = base_url() . "transferencia/llena_caja_destino";
        $this->parser->parse('transferencia/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('total', 'Valor a transferir', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('sede_destino_hidden', 'Sede de destino', 'trim|xss_clean');
            $this->form_validation->set_rules('tipo_destino_hidden', 'Tipo de destino', 'trim|xss_clean');
            $this->form_validation->set_rules('valor_retirado', 'Valor Retirado de la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_retirado', 'Valor Retirado de la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('valor_consignado', 'Valor Consignado a la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_ingresado', 'Efectivo Ingresado a la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
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
                    $error_valores = "<p>La suma del valor retirado de la cuenta y el efectivo retirado de la caja de origen, deben sumar exactamente: $" . $this->input->post('total') . ", en vez de: $" . number_format(($valor_retirado + $efectivo_retirado), 2, '.', ',') . ".</p>";
                }
            }
            $error_valores_destino = "";
            if ($this->input->post('btn_consultar_destino') == "1") {
                $total = round(str_replace(",", "", $this->input->post('total')), 2);
                $tipo_destino = $this->input->post('tipo_destino_hidden');
                if ($tipo_destino == "caja") {
                    $valor_consignado = 0;
                    $efectivo_ingresado = round(str_replace(",", "", $this->input->post('efectivo_ingresado')), 2);
                    if (round($efectivo_ingresado, 2) != $total) {
                        $error_valores_destino = "<p>El efectivo ingresado a la caja, deben ser igual al valor a transferir: $" . $this->input->post('total') . ", en vez de: $" . number_format($efectivo_ingresado, 2, '.', ',') . ".</p>";
                    }
                } else {
                    $efectivo_ingresado = 0;
                    $valor_consignado = round(str_replace(",", "", $this->input->post('valor_consignado')), 2);
                    if (round(($valor_consignado + $efectivo_ingresado), 2) != $total) {
                        $error_valores_destino = "<p>El valor consignado a la cuenta, deben ser igual al valor a transferir: $" . $this->input->post('total') . ", en vez de: $" . number_format($valor_consignado, 2, '.', ',') . ".</p>";
                    }
                }
            } else {
                $error_valores_destino = "<p>Seleccione la sede de destino, el tipo de destino y oprima el botón consultar información de destino.</p>";
            }
            if (($this->form_validation->run() == FALSE) || ($error_valores != "") || ($error_valores_destino != "")) {
                echo form_error('total') . form_error('sede_destino_hidden') . form_error('tipo_destino_hidden') . form_error('valor_retirado') . form_error('efectivo_retirado') . form_error('valor_consignado') . form_error('efectivo_ingresado') . $error_valores . $error_valores_destino . form_error('observacion');
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
            $total = round(str_replace(",", "", $this->input->post('total')), 2);
            $sede_destino = $this->input->post('sede_destino_hidden');
            $tipo_destino = $this->input->post('tipo_destino_hidden');
            if ($tipo_destino == "caja") {
                $tipo_destino = 1; //1 significa caja
                list($sede_caja_destino, $t_caja_destino) = explode("-", $this->input->post('caja_destino'));
                $efectivo_ingresado = round(str_replace(",", "", $this->input->post('efectivo_ingresado')), 2);
                $cuenta_destino = NULL;
                $valor_consignado = NULL;
            } else {
                $tipo_destino = 0; //0 significa cuenta
                $cuenta_destino = $this->input->post('cuenta_destino');
                $valor_consignado = round(str_replace(",", "", $this->input->post('valor_consignado')), 2);
                $sede_caja_destino = NULL;
                $t_caja_destino = NULL;
                $efectivo_ingresado = NULL;
            }
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
            $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));

            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede_origen = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_transferencia = $this->select_model->sede_id($sede_origen)->prefijo_trans;
            $id_transferencia = ($this->select_model->nextId_transferencia($prefijo_transferencia)->id) + 1;
            $credito_debito_origen = NULL; //Débito            
            $credito_debito_destino = NULL; //Crédito   
            $est_traslado = 2; //Pendiente

            $data["tab"] = "crear_transferencia";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "transferencia/crear";
            $data['msn_recrear'] = "Crear otra tranferencia";
            $data['url_imprimir'] = base_url() . "transferencia/consultar_pdf/" . $prefijo_transferencia . "_" . $id_transferencia . "/I";

            $error = $this->insert_model->transferencia($prefijo_transferencia, $id_transferencia, $credito_debito_origen, $credito_debito_destino, $total, $sede_origen, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $tipo_destino, $sede_destino, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $observacion, $est_traslado, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $this->parser->parse('trans_success', $data);
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
                $cuentas = $this->select_model->cuenta_banco_responsable_retirar($id_responsable, $dni_responsable);
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

    public function llena_cuenta_destino() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if ($this->input->post('sedeDestino')) {
                $sedeDestino = $this->input->post('sedeDestino');
                $cuentas = $this->select_model->cuenta_banco_sede($sedeDestino);
                if (($cuentas == TRUE)) {
                    foreach ($cuentas as $fila) {
                        echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="cuenta_destino" id="cuenta_destino" value="' . $fila->id . '"/></td>
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

    public function llena_caja_destino() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if ($this->input->post('sedeDestino')) {
                $sedeDestino = $this->input->post('sedeDestino');
                $cajas = $this->select_model->caja_sede($sedeDestino);
                if (($cajas == TRUE)) {
                    foreach ($cajas as $fila) {
                        $responsable = $this->select_model->empleado($fila->id_encargado, $fila->dni_encargado);
                        echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="caja_destino" id="caja_destino" value="' . $fila->sede . "-" . $fila->t_caja . '"/></td>
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

    function aprobar() {
        $data["tab"] = "aprobar_transferencia";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);

        $data['action_validar'] = base_url() . "transferencia/validar_aprobar";
        $data['action_crear'] = base_url() . "transferencia/insertar_aprobar";
        $data['action_llena_transferencias'] = base_url() . "transferencia/llena_transferencias";
        $this->parser->parse('transferencia/aprobar', $data);
        $this->load->view('footer');
    }

    function validar_aprobar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('transferencia_prefijo_id', 'Transferencia pendiente de aprobar', 'required');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
            if ($this->form_validation->run() == FALSE) {
                echo form_error('transferencia_prefijo_id') . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function insertar_aprobar() {
        if ($this->input->post('submit')) {
            $this->escapar($_POST);
            list($prefijo_transferencia, $id_transferencia) = explode("+", $this->input->post('transferencia_prefijo_id'));
            $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $credito_debito_origen = 0; //Débito            
            $credito_debito_destino = 1; //Crédito   
            $est_traslado = 1; //OK
            $t_trans = 13; //Transferencia intersede

            $transferencia = $this->transferenciam->transferencia_prefijo_id($prefijo_transferencia, $id_transferencia);
            $empleado_autoriza = $this->select_model->empleado($id_responsable, $dni_responsable);
            $detalle_array = array(
                "Sede_Origen" => $transferencia->nombre_sede_origen,
                "Remitente" => $transferencia->nombre_remitente
            );
            if ($transferencia->sede_caja_origen != NULL) {
                $detalle_array['Caja_de_origen'] = $transferencia->nombre_caja_origen;
                $detalle_array['Efectivo_retirado'] = '$' . number_format($transferencia->efectivo_retirado, 2, '.', ',');
            }
            if ($transferencia->cuenta_origen != NULL) {
                $detalle_array['Cuenta_de_origen'] = $transferencia->cuenta_origen;
                $detalle_array['Valor_retirado'] = '$' . number_format($transferencia->valor_retirado, 2, '.', ',');
            }
            $detalle_array['Fecha_envío'] = $transferencia->fecha_trans;
            $detalle_array['Observacion_transferencia'] = $transferencia->observacion;
            $detalle_array['Sede_Destino'] = $transferencia->nombre_sede_destino;
            $detalle_array['Aprueba'] = $empleado_autoriza->nombre1 . " " . $empleado_autoriza->nombre2 . " " . $empleado_autoriza->apellido1;
            if ($transferencia->tipo_destino == 1) {
                $detalle_array['Caja_destino'] = $transferencia->nombre_caja_destino;
                $detalle_array['Efectivo_ingresado'] = '$' . number_format($transferencia->efectivo_ingresado, 2, '.', ',');
            } else {
                $detalle_array['Cuenta_de_origen'] = $transferencia->cuenta_destino;
                $detalle_array['Valor_consignado'] = '$' . number_format($transferencia->valor_consignado, 2, '.', ',');
            }
            $detalle_array['Observacion_aprobación'] = $observacion;
            $detalle_json = json_encode($detalle_array);

            $data["tab"] = "aprobar_transferencia";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "transferencia/aprobar";
            $data['msn_recrear'] = "Aprobar otra transferencia";
            $data['url_imprimir'] = base_url() . "transferencia/consultar_pdf/" . $prefijo_transferencia . "_" . $id_transferencia . "/I";

            $error = $this->update_model->transferencia_estado($prefijo_transferencia, $id_transferencia, $est_traslado);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                //Hacemos el movimiento de la sede origen
                $error1 = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_transferencia, $id_transferencia, $credito_debito_origen, $transferencia->total, $transferencia->sede_caja_origen, $transferencia->t_caja_origen, $transferencia->efectivo_retirado, $transferencia->cuenta_origen, $transferencia->valor_retirado, 1, $detalle_json, $transferencia->sede_origen, $transferencia->id_responsable, $transferencia->dni_responsable);
                if (isset($error1)) {
                    $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    //Hacemos el movimiento de la sede destino
                    $error2 = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_transferencia, $id_transferencia, $credito_debito_destino, $transferencia->total, $transferencia->sede_caja_destino, $transferencia->t_caja_destino, $transferencia->efectivo_ingresado, $transferencia->cuenta_destino, $transferencia->valor_consignado, 1, $detalle_json, $transferencia->sede_destino, $id_responsable, $dni_responsable);
                    if (isset($error2)) {
                        $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                        $this->parser->parse('trans_error', $data);
                    } else {
                        $error3 = $this->insert_model->aprobar_transferencia($prefijo_transferencia, $id_transferencia, $observacion, $id_responsable, $dni_responsable);
                        if (isset($error3)) {
                            $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                            $this->parser->parse('trans_error', $data);
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

    public function llena_transferencias() {
        if ($this->input->is_ajax_request()) {
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $transferencias = $this->transferenciam->transferencia_pdte_responsable($id_responsable, $dni_responsable);
            if ($transferencias == TRUE) {
                foreach ($transferencias as $fila) {
                    echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="transferencia_prefijo_id"  value="' . $fila->prefijo . "+" . $fila->id . '"/></td>
                            <td>' . $fila->prefijo . $fila->id . '</td>
                            <td>$' . number_format($fila->total, 2, '.', ',') . '</td>
                            <td>
                            <p><b>Sede origen:</b> ' . $fila->nombre_sede_origen . '</p>
                            <p><b>Remitente:</b> ' . $fila->nombre_remitente . '</p>';
                    if ($fila->sede_caja_origen != NULL) {
                        echo '<p><b>Nombre de la caja:</b> ' . $fila->nombre_caja_origen . '</p>
                            <p><b>Efectivo retirado de caja:</b> $' . number_format($fila->efectivo_retirado, 2, '.', ',') . '</p>';
                    }
                    if ($fila->cuenta_origen != NULL) {
                        echo '<p><b>Número de la cuenta:</b> ' . $fila->cuenta_origen . '</p>
                            <p><b>Valor retirado de cuenta:</b> $' . number_format($fila->valor_retirado, 2, '.', ',') . '</p>';
                    }
                    echo '</td>
                            <td>
                            <p><b>Sede destino:</b> ' . $fila->nombre_sede_destino . '</p>';
                    if ($fila->tipo_destino == 1) {
                        echo '<p><b>Tipo destino:</b> Caja</p>
                              <p><b>Nombre de la caja:</b> ' . $fila->nombre_caja_destino . '</p>
                              <p><b>Efectivo enviado a la caja:</b> $' . number_format($fila->efectivo_ingresado, 2, '.', ',') . '</p>';
                    } else {
                        echo '<p><b>Tipo destino:</b> Cuenta</p>
                              <p><b>Número de la cuenta:</b> ' . $fila->cuenta_destino . '</p>
                              <p><b>Valor enviado a la cuenta:</b> $' . number_format($fila->valor_consignado, 2, '.', ',') . '</p>';
                    }
                    echo '</td>                        
                            <td>' . $fila->observacion . '</td>
                            <td class="text-center">' . $fila->fecha_trans . '</td>
                        </tr>';
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    function consultar() {
        $data["tab"] = "consultar_transferencia";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['sede'] = $this->select_model->sede();
        $data['error_consulta'] = "";
        $data['action_crear'] = base_url() . "transferencia/consultar_validar";
        $data['action_recargar'] = base_url() . "transferencia/consultar";
        $this->parser->parse('transferencia/consultar', $data);
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
            $transferencia = $this->transferenciam->transferencia_prefijo_id($prefijo, $id);
            if ($transferencia == TRUE) {
                if ($transferencia->est_traslado == 3) {
                    $error_transaccion = "La transferencia intersede, se encuentra anulada.";
                } else {
                    if ($transferencia->est_traslado == 2) {
                        $error_transaccion = "La transferencia intersede, se encuentra pendiente por ser autorizada.";
                    } else {
                        if (($_SESSION["perfil"] == "admon_sistema") || ($_SESSION["perfil"] == "directio") || ($_SESSION["perfil"] == "admon_sede") || ($_SESSION["perfil"] == "aux_admon")) {
                            $cantidad_transferencia = $this->transferenciam->transferencia_aurotorizada_directivos($prefijo, $id);
                            $cantidad = $cantidad_transferencia[0]->cantidad;
                            if ($cantidad == 0) {
                                $error_transaccion = "Usted no se encuentra autorizado para consultar ésta transferencia intersede.";
                            }
                        } else { //El caso de la secretaria y la cartera
                            $cantidad_transferencia = $this->transferenciam->transferencia_aurotorizada_empleados($prefijo, $id);
                            $cantidad = $cantidad_transferencia[0]->cantidad;
                            if ($cantidad == 0) {
                                $error_transaccion = "Usted no se encuentra autorizado para consultar ésta transferencia intersede.";
                            }
                        }
                    }
                }
            } else {
                $error_transaccion = "La transferencia intersede, no existe en la base de datos.";
            }
        }
        if (($this->form_validation->run() == FALSE) || ($error_transaccion != "")) {
            $data["tab"] = "consultar_transferencia";
            $this->isLogin($data["tab"]);
            $data["error_consulta"] = form_error('prefijo') . form_error('id') . $error_transaccion;
            $data["prefijo"] = $prefijo;
            $data["id"] = $id;
            $this->load->view("header", $data);
            $data['sede'] = $this->select_model->sede();
            $data['action_crear'] = base_url() . "transferencia/consultar_validar";
            $this->parser->parse('transferencia/consultar', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url() . "transferencia/consultar_pdf/" . $prefijo . "_" . $id . "/I");
        }
    }

    function consultar_pdf($id_transferencia, $salida_pdf) {
        $transferencia_prefijo_id = $id_transferencia;
        $id_transferencia_limpio = str_replace("_", " ", $transferencia_prefijo_id);
        list($prefijo, $id) = explode("_", $transferencia_prefijo_id);
        $transferencia = $this->transferenciam->transferencia_prefijo_id($prefijo, $id);
        if ($transferencia == TRUE) {
            $responsable = $this->select_model->empleado($transferencia->id_responsable, $transferencia->dni_responsable);
            $aprobacion_transferencia = $this->transferenciam->aprobar_transferencia_prefijo_id($prefijo, $id);
            $this->load->library('Pdf');
            $pdf = new Pdf('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Sili S.A.S');
            $pdf->SetTitle('Comprobante de transferencia ' . $id_transferencia_limpio . ' Sili S.A.S');
            $pdf->SetSubject('Comprobante de transferencia ' . $id_transferencia_limpio . ' Sili S.A.S');
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
            $pdf->AddPage();

            //preparamos y maquetamos el contenido a crear
            $html = '';
            $html .= '<style type=text/css>';
            $html .= 'h2{font-family: "times new roman", times, serif;font-size:22px;font-weight: bold;font-style: italic;line-height:20px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:13px;font-weight: bold;line-height:0px;text-align:center;}';
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $html .= 'td.c1{width:420px;line-height:20px;}td.c1000{line-height:100px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:170px;}';
            $html .= 'td.c4{width:195px;}';
            $html .= 'td.c5{width:160px;}';
            $html .= 'td.c6{width:150px;}';
            $html .= 'td.c7{font-size:16px;}';
            $html .= 'td.c8{line-height:40px;}';
            $html .= 'td.c9{background-color:#F5F5F5;}';
            $html .= 'td.c10{font-size:4px;line-height:5px;}';
            $html .= 'td.c12{line-height:25px;}';
            $html .= 'td.c13{line-height:25px;}';
            $html .= 'td.c14{width:365px;}';
            $html .= 'td.c15{width:420px;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;border-top-color:#FFFFFF;border-left-color:#FFFFFF;border-right-color:#FFFFFF;}';
            $html .= 'td.c25{border-top-color:#000000;}';
            $html .= 'td.c26{border-bottom-color:#000000;}';
            $html .= 'td.c27{border-left-color:#000000;}';
            $html .= 'td.c28{border-right-color:#000000;}';
            $html .= 'td.a2{text-align:center;}';
            $html .= 'th.a2{text-align:center;}';
            $html .= 'table{border-spacing: 0;}';
            $html .= '</style>';
            $html .= '<table width="100%">'
                    . '<tr>'
                    . '<td class="c1 a2" colspan="2"><h2></h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p>'
                    . '</td>'
                    . '<td class="c2 a2 c1000"  colspan="2"></td>'
                    . '<br>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c1 c24 a2 c28">COMPROBANTE DE<BR>TRANSFERENCIA INTERSEDE</td>'
                    . '<td class="c5 c8 c23 c25 c26  c27 c28"><b>Número:</b></td><td class="c6 c8 c23 c25 c26  c27 c28">' . $id_transferencia_limpio . '</td>'
                    . '</tr></table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c15 c12"></td>'
                    . '<td class="c23 c7 c5 c8 c9 c25 c26  c27 c28" rowspan="2"><b> Valor transferido:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8 c9"><b>$ ' . number_format($transferencia->total, 1, '.', ',') . '</b></td>'
                    . '</tr>'
                    . '</table>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Fecha de emisión:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . date("Y-m-d", strtotime($transferencia->fecha_trans)) . '</td>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Fecha de aprobación:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . date("Y-m-d", strtotime($aprobacion_transferencia->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="4" class="a2 c9 c13 c14 c25 c27 c28"><b>INFORMACIÓN DE ORIGEN</b></td>'
                    . '<td colspan="4" class="a2 c9 c13 c14 c25 c27 c28"><b>INFORMACIÓN DE DESTINO</b></td>'
                    . '</tr>'
                    . '<tr><td colspan="2" class="c14 c25 c26 c27 c28">'
                    . '<table>'
                    . '<tr><td class="c10"> </td></tr>'
                    . '<tr><td class="a3"><b>Sede origen: </b>' . $transferencia->nombre_sede_origen . '.</td></tr>'
                    . '<tr><td class="c10"> </td></tr>'
                    . '<tr><td class="a3"><b>Remitente: </b>' . $transferencia->nombre_remitente . '.</td></tr>';
            if ($transferencia->sede_caja_origen != NULL) {
                $html .= '<tr><td class="c10"> </td></tr>'
                        . '<tr><td class="a3"><b>Nombre de la caja: </b>' . $transferencia->nombre_caja_origen . '.</td></tr>'
                        . '<tr><td class="c10"> </td></tr>'
                        . '<tr><td class="a3"><b>Efectivo retirado de caja: </b>' . number_format($transferencia->efectivo_retirado, 2, '.', ',') . '.</td></tr>';
            }
            if ($transferencia->cuenta_origen != NULL) {
                $html .= '<tr><td class="c10"> </td></tr>'
                        . '<tr><td class="a3"><b>Número de la cuenta: </b>' . $transferencia->cuenta_origen . '.</td></tr>'
                        . '<tr><td class="c10"> </td></tr>'
                        . '<tr><td class="a3"><b>Valor retirado de cuenta: </b>' . number_format($transferencia->valor_retirado, 2, '.', ',') . '.</td></tr>';
            }
            if (($transferencia->observacion) != "") {
                $html .= '<tr><td class="c10"> </td></tr>'
                        . '<tr><td class="a3"><b>Observacion de transferencia: </b>' . $transferencia->observacion . '</td></tr>';
            }
            $html .= '<tr><td class="c10"> </td></tr></table></td>'
                    . '<td colspan="2" class="c14 c25 c26 c27 c28">'
                    . '<table>'
                    . '<tr><td class="c10"> </td></tr>'
                    . '<tr><td class="a3"><b>Sede destino: </b>' . $transferencia->nombre_sede_destino . '.</td></tr>'
                    . '<tr><td class="c10"> </td></tr>'
                    . '<tr><td class="a3"><b>Empleado que aprueba: </b>' . $aprobacion_transferencia->empleado_autoriza . '.</td></tr>';
            if ($transferencia->tipo_destino == 1) {
                $html .= '<tr><td class="c10"> </td></tr>'
                        . '<tr><td class="a3"><b>Tipo destino: </b> Caja.</td></tr>'
                        . '<tr><td class="c10"> </td></tr>'
                        . '<tr><td class="a3"><b>Nombre de la caja: </b>' . $transferencia->nombre_caja_destino . '.</td></tr>'
                        . '<tr><td class="c10"> </td></tr>'
                        . '<tr><td class="a3"><b>Efectivo enviado a la caja: </b>' . number_format($transferencia->efectivo_ingresado, 2, '.', ',') . '.</td></tr>';
            } else {
                $html .= '<tr><td class="c10"> </td></tr>'
                        . '<tr><td class="a3"><b>Tipo destino: </b> Cuenta.</td></tr>'
                        . '<tr><td class="c10"> </td></tr>'
                        . '<tr><td class="a3"><b>Número de la cuenta: </b>' . $transferencia->cuenta_destino . '.</td></tr>'
                        . '<tr><td class="c10"> </td></tr>'
                        . '<tr><td class="a3"><b>Valor enviado a la cuenta: </b>' . number_format($transferencia->valor_consignado, 2, '.', ',') . '.</td></tr>';
            }
            if (($aprobacion_transferencia->observacion) != "") {
                $html .= '<tr><td class="c10"> </td></tr>'
                        . '<tr><td class="a3"><b>Observacion de aprobación: </b>' . $aprobacion_transferencia->observacion . '</td></tr>';
            }
            $html .= '<tr><td class="c10"> </td></tr></table></td></tr>'
                    . '</table><p class="b3">- Copia para la empresa -</p>';
            // Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
            $nombre_archivo = utf8_decode('Comprobante de transferencia ' . $id_transferencia_limpio . ' Sili S.A.S.pdf');
            $pdf->Output($nombre_archivo, $salida_pdf);
        } else {
            redirect(base_url() . 'transferencia/consultar/');
        }
    }

    function anular() {
        $data["tab"] = "anular_transferencia";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        //Validamos si el responsable necesita cod de autorizacion o no. 
        if ($_SESSION["perfil"] != "admon_sistema" && $_SESSION["perfil"] != "directivo") {
            $data['cod_required'] = '1';
        }        
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['action_validar'] = base_url() . "transferencia/validar_anular";
        $data['action_crear'] = base_url() . "transferencia/insertar_anular";
        $data['action_recargar'] = base_url() . "transferencia/anular";
        $data['action_validar_transaccion_anular'] = base_url() . "transferencia/validar_transaccion_anular";
        $this->parser->parse('transferencia/anular', $data);
        $this->load->view('footer');
    }

    function validar_anular() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('prefijo', 'Prefijo de sede', 'required|callback_select_default');
            $this->form_validation->set_rules('id', 'Consecutivo', 'required|trim|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'required|trim|xss_clean|max_length[255]');
if ($_SESSION["perfil"] != "admon_sistema" && $_SESSION["perfil"] != "directivo") {
                $this->form_validation->set_rules('cod_autorizacion', 'Código de autorización', 'required|trim|max_length[13]|integer|callback_valor_positivo');
            }
            $error_cod = "";
            if (($this->input->post('id')) && ($this->input->post('cod_autorizacion'))) {
                $cod_autorizacion = $this->input->post('cod_autorizacion');
                $this->load->model('cod_autorizacionm');
                $check_codigo = $this->cod_autorizacionm->cod_autorizacion_id($cod_autorizacion);
                if ($check_codigo != TRUE) {
                    $error_cod = "<p>El código de autorización, No existe en la Base de Datos.</p>";
                } else {
                    $check_vigente = $this->cod_autorizacionm->cod_autorizacion_id_vigente($cod_autorizacion);
                    if ($check_vigente != TRUE) {
                        $error_cod = "<p>El código de autorización, No se encuentra vigente.</p>";
                    } else {
                        $check_autorizado = $this->cod_autorizacionm->cod_autorizacion_id_vigente_empleado_autorizado($cod_autorizacion);
                        if ($check_autorizado != TRUE) {
                            $error_cod = "<p>Usted no es el empleado autorizado para utilizar éste código de autorización.</p>";
                        } else {
                            $tabla_autorizada = '11'; //Transferencia intersede                   
                            $check_tabla = $this->cod_autorizacionm->cod_autorizacion_id_vigente_tabla($cod_autorizacion, $tabla_autorizada);
                            if ($check_tabla != TRUE) {
                                $error_cod = "<p>El código de autorización, no fue creado para éste tipo de transacción.</p>";
                            } else {
                                $check_tabla = $this->cod_autorizacionm->cod_autorizacion_id_vigente_registro($cod_autorizacion, $this->input->post('id'));
                                if ($check_tabla != TRUE) {
                                    $error_cod = "<p>El código de autorización, no fue creado para éste código de factura.</p>";
                                }
                            }
                        }
                    }
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_cod != "")) {
                echo form_error('prefijo') . form_error('id') . $error_cod . form_error('cod_autorizacion') . form_error('observacion');
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
            $t_trans = '13'; //Transferencia intersede    
            $credito_debito_origen = 0; //Débito            
            $credito_debito_destino = 1; //Crédito  
            $est_traslado = '3'; //Anulado
            $vigente = '0'; //Anulado

            $data["tab"] = "anular_transferencia";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "transferencia/anular";
            $data['msn_recrear'] = "Anular otra retención por compras";
            if ($this->input->post('cod_autorizacion')) {
                $this->update_model->concepto_cod_autorizacion($this->input->post('cod_autorizacion'), '0');
            }            
            $this->load->model('transaccionesm');            
            $movimiento_transaccion = $this->transaccionesm->movimiento_transaccion_id($t_trans, $prefijo, $id, $credito_debito_origen);
            //Con el segundo argumento de jsondecode el true, convierto de objeto a array
            if (is_array(json_decode($movimiento_transaccion->detalle_json, true))) {
                $array_detalles = json_decode($movimiento_transaccion->detalle_json, true);
            }
            $responsable = $this->select_model->empleado($id_responsable, $dni_responsable);
            $array_detalles['Observación_Anulación'] = $observacion;
            $array_detalles['Responsable_Anulación'] = $responsable->nombre1 . " " . $responsable->nombre2 . " " . $responsable->apellido1;
            $array_detalles['Id_Responsable_Anulación'] = $id_responsable;
            $detalle_json = json_encode($array_detalles);

            $error = $this->update_model->movimiento_transaccion_vigente($t_trans, $prefijo, $id, $credito_debito_origen, $vigente, $detalle_json);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->update_model->movimiento_transaccion_vigente($t_trans, $prefijo, $id, $credito_debito_destino, $vigente, $detalle_json);
                if (isset($error1)) {
                    $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    $error2 = $this->update_model->transferencia_est_traslado($prefijo, $id, $est_traslado);
                    if (isset($error2)) {
                        $data['trans_error'] = $error2 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                        $this->parser->parse('trans_error', $data);
                    } else {
                        $error3 = $this->insert_model->anular_transaccion($t_trans, $prefijo, $id, $observacion, $id_responsable, $dni_responsable);
                        if (isset($error3)) {
                            $data['trans_error'] = $error3 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                            $this->parser->parse('trans_error', $data);
                        } else {
                            $this->parser->parse('trans_success', $data);
                        }
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
            $this->load->model('transferenciam');
            $transferencia = $this->transferenciam->transferencia_prefijo_id($prefijo, $id);
            if ($transferencia == TRUE) {
                if ($transferencia->est_traslado == 3) {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><strong><center>La transferencia intersede, ya se encuentra anulada.</center></strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                } else {

                    if ($transferencia->est_traslado == 2) {
                        $response = array(
                            'respuesta' => 'error',
                            'mensaje' => '<p><strong><center>La transferencia intersede, está pendiente por aprobar.</center></strong></p>'
                        );
                        echo json_encode($response);
                        return false;
                    } else {

                        $response = array(
                            'respuesta' => 'OK',
                            'filasTabla' => ''
                        );
                        $response['filasTabla'] .= '<tr>
                            <td class="text-center">$' . number_format($transferencia->total, 2, '.', ',') . '</td>
                            <td>
                            <p><b>Sede origen:</b> ' . $transferencia->nombre_sede_origen . '</p>
                            <p><b>Remitente:</b> ' . $transferencia->nombre_remitente . '</p>';
                        if ($transferencia->sede_caja_origen != NULL) {
                            $response['filasTabla'] .= '<p><b>Nombre de la caja:</b> ' . $transferencia->nombre_caja_origen . '</p>
                            <p><b>Efectivo retirado de caja:</b> $' . number_format($transferencia->efectivo_retirado, 2, '.', ',') . '</p>';
                        }
                        if ($transferencia->cuenta_origen != NULL) {
                            $response['filasTabla'] .= '<p><b>Número de la cuenta:</b> ' . $transferencia->cuenta_origen . '</p>
                            <p><b>Valor retirado de cuenta:</b> $' . number_format($transferencia->valor_retirado, 2, '.', ',') . '</p>';
                        }
                        $response['filasTabla'] .= '</td>
                            <td>
                            <p><b>Sede destino:</b> ' . $transferencia->nombre_sede_destino . '</p>';
                        if ($transferencia->tipo_destino == 1) {
                            $response['filasTabla'] .= '<p><b>Tipo destino:</b> Caja</p>
                              <p><b>Nombre de la caja:</b> ' . $transferencia->nombre_caja_destino . '</p>
                              <p><b>Efectivo enviado a la caja:</b> $' . number_format($transferencia->efectivo_ingresado, 2, '.', ',') . '</p>';
                        } else {
                            $response['filasTabla'] .= '<p><b>Tipo destino:</b> Cuenta</p>
                              <p><b>Número de la cuenta:</b> ' . $transferencia->cuenta_destino . '</p>
                              <p><b>Valor enviado a la cuenta:</b> $' . number_format($transferencia->valor_consignado, 2, '.', ',') . '</p>';
                        }
                        $response['filasTabla'] .= '</td>
                            <td class="text-center">' . $transferencia->observacion . '</td>                                
                            <td class="text-center">' . date("Y-m-d", strtotime($transferencia->fecha_trans)) . '</td>
                        </tr>';
                        echo json_encode($response);
                        return false;
                    }
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong><center>La transferencia intersede, no existe en la base de datos.</center></strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

}
