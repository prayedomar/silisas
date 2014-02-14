<?php

class Ingreso extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

//Crear: Ingreso
    function crear() {
        $data["tab"] = "crear_ingreso";
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['t_ingreso'] = $this->select_model->t_ingreso();
        $data['t_depositante'] = $this->select_model->t_usuario_ingreso_egreso();
        $data['dni'] = $this->select_model->t_dni_todos();
        $data['action_validar'] = base_url() . "ingreso/validar";
        $data['action_crear'] = base_url() . "ingreso/insertar";
        $data['action_llena_cuenta_responsable'] = base_url() . "ingreso/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "ingreso/llena_caja_responsable";

        $this->parser->parse('ingreso/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
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

    function insertar() {
        if ($this->input->post('submit')) {
            $this->escapar($_POST);
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
            $t_trans = 5; //Ingreso
            $credito_debito = 1; //Credito

            $data["tab"] = "crear_ingreso";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "ingreso/crear";
            $data['msn_recrear'] = "Crear otro Ingreso";

            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_ingreso, $id_ingreso, $credito_debito, $total, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $cuenta_destino, $valor_consignado, 1, $sede, $fecha_trans, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->ingreso($prefijo_ingreso, $id_ingreso, $t_ingreso, $t_depositante, $id_depositante, $dni_depositante, $d_v, $nombre_depositante, $total, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $sede, $vigente, $descripcion, $fecha_trans, $id_responsable, $dni_responsable);
                if (isset($error1)) {
                    $data['trans_error'] = $error1;
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

}
