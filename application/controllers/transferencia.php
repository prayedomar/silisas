<?php

class Transferencia extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function crear() {
        $data["tab"] = "crear_transferencia";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
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
            $observacion = ucfirst(strtolower($this->input->post('observacion')));

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
                $this->parser->parse('trans_success_print', $data);
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
        $data['base_url'] = base_url();
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
            $transferencia = $this->select_model->transferencia_prefijo_id($prefijo_transferencia, $id_transferencia);
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $credito_debito_origen = 0; //Débito            
            $credito_debito_destino = 1; //Crédito   
            $est_traslado = 1; //OK
            $t_trans = 13; //Transferencia intersede

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
                $error1 = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_transferencia, $id_transferencia, $credito_debito_origen, $transferencia->total, $transferencia->sede_caja_origen, $transferencia->t_caja_origen, $transferencia->efectivo_retirado, $transferencia->cuenta_origen, $transferencia->valor_retirado, 1, $transferencia->sede_origen, $transferencia->id_responsable, $transferencia->dni_responsable);
                if (isset($error1)) {
                    $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    //Hacemos el movimiento de la sede destino
                    $error2 = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_transferencia, $id_transferencia, $credito_debito_destino, $transferencia->total, $transferencia->sede_caja_destino, $transferencia->t_caja_destino, $transferencia->efectivo_ingresado, $transferencia->cuenta_destino, $transferencia->valor_consignado, 1, $transferencia->sede_destino, $id_responsable, $dni_responsable);
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
            $transferencias = $this->select_model->transferencia_pdte_responsable($id_responsable, $dni_responsable);
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
            $transferencia = $this->select_model->transferencia_prefijo_id($prefijo, $id);
            if ($transferencia == TRUE) {
                if ($transferencia->vigente == 0) {
                    $error_transaccion = "La transferencia intersede, se encuentra anulada.";
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
        $transferencia = $this->select_model->transferencia_prefijo_id($prefijo, $id);
        if ($transferencia == TRUE) {
            $reponsable = $this->select_model->empleado($transferencia->id_responsable, $transferencia->dni_responsable);
            $dni_abreviado_beneficiario = $this->select_model->t_dni_id($transferencia->dni_beneficiario)->abreviacion;
            if (($transferencia->d_v) == (NULL)) {
                $d_v = "";
            } else {
                $d_v = " - " . $transferencia->d_v;
            }
            $t_transferencia = $this->select_model->t_transferencia_id($transferencia->t_transferencia)->tipo;
            $t_beneficiario = $this->select_model->t_usuario_id($transferencia->t_beneficiario)->tipo;
            if ($transferencia->t_beneficiario == '1') {
                $beneficiario = $this->select_model->empleado($transferencia->id_beneficiario, $transferencia->dni_beneficiario);
                $nombre_beneficiario = $beneficiario->nombre1 . " " . $beneficiario->nombre2 . " " . $beneficiario->apellido1 . " " . $beneficiario->apellido2;
            } else {
                if ($transferencia->t_beneficiario == '2') {
                    $beneficiario = $this->select_model->titular($transferencia->id_beneficiario, $transferencia->dni_beneficiario);
                    $nombre_beneficiario = $beneficiario->nombre1 . " " . $beneficiario->nombre2 . " " . $beneficiario->apellido1 . " " . $beneficiario->apellido2;
                } else {
                    if ($transferencia->t_beneficiario == '3') {
                        $beneficiario = $this->select_model->alumno($transferencia->id_beneficiario, $transferencia->dni_beneficiario);
                        $nombre_beneficiario = $beneficiario->nombre1 . " " . $beneficiario->nombre2 . " " . $beneficiario->apellido1 . " " . $beneficiario->apellido2;
                    } else {
                        if ($transferencia->t_beneficiario == '4') {
                            $beneficiario = $this->select_model->cliente_id_dni($transferencia->id_beneficiario, $transferencia->dni_beneficiario);
                            $nombre_beneficiario = $beneficiario->nombre1 . " " . $beneficiario->nombre2 . " " . $beneficiario->apellido1 . " " . $beneficiario->apellido2;
                        } else {
                            if ($transferencia->t_beneficiario == '5') {
                                $nombre_beneficiario = $this->select_model->proveedor_id_dni($transferencia->id_beneficiario, $transferencia->dni_beneficiario)->razon_social;
                            } else {
                                $nombre_beneficiario = $transferencia->nombre_beneficiario;
                            }
                        }
                    }
                }
            }


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
            $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:10px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:13px;font-weight: bold;line-height:0px;text-align:center;}';
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $html .= 'p.b4{line-height:23px;}';
            $html .= 'p.b5{font-size:14px;}';
            $html .= 'p.b6{line-height:26px;}';
            $html .= 'td.c1{width:420px;line-height:20px;}td.c1000{line-height:85px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:150px;}';
            $html .= 'td.c4{width:270px;}';
            $html .= 'td.c5{width:160px;}';
            $html .= 'td.c6{width:150px;}';
            $html .= 'td.c7{font-size:16px;}';
            $html .= 'td.c8{line-height:50px;}';
            $html .= 'td.c9{background-color:#F5F5F5;}';
            $html .= 'td.c10{font-size:4px;line-height:5px;}';
            $html .= 'td.c11{font-size:12px;}';
            $html .= 'td.c12{line-height:25px;}';
            $html .= 'td.c13{width:580px;}';
            $html .= 'td.c14{width:365px;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;line-height:15px;line-height:35px;border-top-color:#FFFFFF;border-left-color:#FFFFFF;border-right-color:#FFFFFF;}';
            $html .= 'td.c25{border-top-color:#000000;}';
            $html .= 'td.c26{border-bottom-color:#000000;}';
            $html .= 'td.c27{border-left-color:#000000;}';
            $html .= 'td.c28{border-right-color:#000000;}';
            $html .= 'td.a1{text-align:left;}';
            $html .= 'td.a2{text-align:center;}';
            $html .= 'th.a1{text-align:left;}';
            $html .= 'th.a2{text-align:center;}';
            $html .= 'table{border-spacing: 0;}';
            $html .= '</style>';
            $html .= '<table width="100%"><tr>'
                    . '<td class="c1 a2" rowspan="5" colspan="2"><h2></h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p><p class="b2">Resolución DIAN No. 110000497290 del 16/08/2012</p>'
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
                    . '<td class="c24 a2" colspan="2">COMPROBANTE DE EGRESO</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Número:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $id_transferencia_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Fecha de emisión:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . date("Y-m-d", strtotime($transferencia->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Responsable empresa:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $reponsable->nombre1 . " " . $reponsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c12"></td><td class="c4 c12"></td>'
                    . '<td rowspan="2" class="c23 c7 c5 c8 c9 c25 c26  c27 c28" rowspan="2"><b> Valor del transferencia:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8 c9"><b>$ ' . number_format($transferencia->total, 1, '.', ',') . '</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c25 c26 c27 c28 c12"><b>Tipo beneficiario:</b></td><td class="c23 c25 c26  c27 c28 c12">' . $t_beneficiario . '</td>'
                    . '</tr></table>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Tipo de transferencia:</b></td><td  colspan="3" class="c23 c25 c26  c27 c28 c12 c13">' . $t_transferencia . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Nombre beneficiario:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $nombre_beneficiario . '</td>'
                    . '<td class="c5 c23 c12 c25 c26 c27 c28"><b>Documento beneficiario:</b></td><td class="c6 c23 c12 c25 c26 c27 c28">' . $dni_abreviado_beneficiario . ' ' . $transferencia->id_beneficiario . $d_v . '</td>'
                    . '</tr>';
            if (($transferencia->descripcion) != "") {
                $html .= '<tr>'
                        . '<td colspan="4" class="c23 c25 c26 c27 c28">'
                        . '<table>'
                        . '<tr><td class="c10"> </td></tr><tr>'
                        . '<td><b>Descripción del transferencia: </b>' . $transferencia->descripcion . '.</td>'
                        . '</tr><tr><td class="c10"> </td></tr>'
                        . '</table>'
                        . '</td>'
                        . '</tr>';
            }
            $html .= '<tr><td colspan="2" class="c14 c25 c26 c27 c28"><br><br><p class="b5 b6">Firma beneficiario: _____________________________</p></td>'
                    . '<td colspan="2" class="c14 c25 c26 c27 c28"><br><br><p class="b5 b6">Firma y sello empresa: __________________________</p></td></tr>'
                    . '</table><p class="b3">- Copia para el beneficiario -</p>';

            // Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

            $pdf->lastPage();
            $pdf->AddPage();
            $html = '';
            $html .= '<style type=text/css>';
            $html .= 'h2{font-family: "times new roman", times, serif;font-size:22px;font-weight: bold;font-style: italic;line-height:20px;}';
            $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:10px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:13px;font-weight: bold;line-height:0px;text-align:center;}';
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $html .= 'p.b4{line-height:23px;}';
            $html .= 'p.b5{font-size:14px;}';
            $html .= 'p.b6{line-height:26px;}';
            $html .= 'td.c1{width:420px;line-height:20px;}td.c1000{line-height:85px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:150px;}';
            $html .= 'td.c4{width:270px;}';
            $html .= 'td.c5{width:160px;}';
            $html .= 'td.c6{width:150px;}';
            $html .= 'td.c7{font-size:16px;}';
            $html .= 'td.c8{line-height:50px;}';
            $html .= 'td.c9{background-color:#F5F5F5;}';
            $html .= 'td.c10{font-size:4px;line-height:5px;}';
            $html .= 'td.c11{font-size:12px;}';
            $html .= 'td.c12{line-height:25px;}';
            $html .= 'td.c13{width:580px;}';
            $html .= 'td.c14{width:365px;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;line-height:15px;line-height:35px;border-top-color:#FFFFFF;border-left-color:#FFFFFF;border-right-color:#FFFFFF;}';
            $html .= 'td.c25{border-top-color:#000000;}';
            $html .= 'td.c26{border-bottom-color:#000000;}';
            $html .= 'td.c27{border-left-color:#000000;}';
            $html .= 'td.c28{border-right-color:#000000;}';
            $html .= 'td.a1{text-align:left;}';
            $html .= 'td.a2{text-align:center;}';
            $html .= 'th.a1{text-align:left;}';
            $html .= 'th.a2{text-align:center;}';
            $html .= 'table{border-spacing: 0;}';
            $html .= '</style>';
            $html .= '<table width="100%"><tr>'
                    . '<td class="c1 a2" rowspan="5" colspan="2"><h2></h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p><p class="b2">Resolución DIAN No. 110000497290 del 16/08/2012</p>'
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
                    . '<td class="c24 a2" colspan="2">COMPROBANTE DE EGRESO</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Número:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $id_transferencia_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Fecha de emisión:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . date("Y-m-d", strtotime($transferencia->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Responsable empresa:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $reponsable->nombre1 . " " . $reponsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c12"></td><td class="c4 c12"></td>'
                    . '<td rowspan="2" class="c23 c7 c5 c8 c9 c25 c26  c27 c28" rowspan="2"><b> Valor del transferencia:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8 c9"><b>$ ' . number_format($transferencia->total, 1, '.', ',') . '</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c25 c26 c27 c28 c12"><b>Tipo beneficiario:</b></td><td class="c23 c25 c26  c27 c28 c12">' . $t_beneficiario . '</td>'
                    . '</tr></table>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Tipo de transferencia:</b></td><td  colspan="3" class="c23 c25 c26  c27 c28 c12 c13">' . $t_transferencia . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Nombre beneficiario:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $nombre_beneficiario . '</td>'
                    . '<td class="c5 c23 c12 c25 c26 c27 c28"><b>Documento beneficiario:</b></td><td class="c6 c23 c12 c25 c26 c27 c28">' . $dni_abreviado_beneficiario . ' ' . $transferencia->id_beneficiario . $d_v . '</td>'
                    . '</tr>';
            if (($transferencia->descripcion) != "") {
                $html .= '<tr>'
                        . '<td colspan="4" class="c23 c25 c26 c27 c28">'
                        . '<table>'
                        . '<tr><td class="c10"> </td></tr><tr>'
                        . '<td><b>Descripción del transferencia: </b>' . $transferencia->descripcion . '.</td>'
                        . '</tr><tr><td class="c10"> </td></tr>'
                        . '</table>'
                        . '</td>'
                        . '</tr>';
            }
            $html .= '<tr><td colspan="2" class="c14 c25 c26 c27 c28"><br><br><p class="b5 b6">Firma beneficiario: _____________________________</p></td>'
                    . '<td colspan="2" class="c14 c25 c26 c27 c28"><br><br><p class="b5 b6">Firma y sello empresa: __________________________</p></td></tr>'
                    . '</table><p class="b3">- Copia para la empresa -</p>';
//
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

}
