<?php

class Cuenta extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
    }

    function crear() {
        $data["tab"] = "crear_cuenta";
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['empleado'] = $this->select_model->empleado_sedes_responsable($id_responsable, $dni_responsable);
        $data['sede'] = $this->select_model->sede_activa_responsable($id_responsable, $dni_responsable);
        $data['t_cuenta'] = $this->select_model->t_cuenta();
        $data['pais'] = $this->select_model->pais();

        $data['action_validar'] = base_url() . "cuenta/validar";
        $data['action_crear'] = base_url() . "cuenta/insertar";
        $data['action_llena_banco_pais'] = base_url() . "cuenta/llena_banco_pais";
        $this->parser->parse('cuenta/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('cuenta', 'Cuenta Bancaria', 'required|trim|min_length[12]|max_length[12]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('t_cuenta', 'Tipo de Cuenta', 'required|callback_select_default');
            $this->form_validation->set_rules('pais', 'País del Banco', 'required|callback_select_default');
            $this->form_validation->set_rules('banco', 'Banco', 'required|callback_select_default');
            $this->form_validation->set_rules('nombre_cuenta', 'Nombre de la Cuenta', 'required|trim|xss_clean|max_length[60]');

            //Validamos que la clave primaria no este repetida
            $duplicate_key = "";
            if ($this->input->post('cuenta')) {
                $check_usuario = $this->select_model->cuenta_banco_id($this->input->post('cuenta'));
                if ($check_usuario == TRUE) {
                    $duplicate_key = "<p>La Cuenta ingresada ya existe en la Base de Datos.</p>";
                }
            }

            if (($this->form_validation->run() == FALSE) || ($duplicate_key != "")) {
                echo form_error('cuenta') . $duplicate_key . form_error('t_cuenta') . form_error('pais') . form_error('banco') . form_error('nombre_cuenta');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function insertar() {
        if ($this->input->post('submit')) {
            $cuenta = $this->input->post('cuenta');
            $t_cuenta = $this->input->post('t_cuenta');
            $banco = $this->input->post('banco');
            $nombre_cuenta = ucwords(strtolower($this->input->post('nombre_cuenta')));
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $error = $this->insert_model->cuenta($cuenta, $t_cuenta, $banco, $nombre_cuenta, 1, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            $data["tab"] = "crear_cuenta";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "cuenta/crear";
            $data['msn_recrear'] = "Crear otra Cuenta";
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                //Cuando cree una cuenta, automaticamente se la debe asignar al sistema, para que tenga acceso a todo.
                //Colocamos vigente en cero porq al momento de crear una cuenta logicamente no esta asignada a ninguna sede.
                $this->insert_model->cuenta_x_sede($cuenta, 1, 0);
                $this->insert_model->cuenta_x_sede_x_empleado($cuenta, 1, 1, 1, 1);
                $this->parser->parse('trans_success', $data);
            }
        } else {
            redirect(base_url());
        }
    }
    
    public function llena_banco_pais() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('pais')) && ($this->input->post('pais') != '{id}') && ($this->input->post('pais') != 'default')) {
                $pais = $this->input->post('pais');
                $bancos = $this->select_model->banco_pais($pais);
                if ($bancos == TRUE) {
                    foreach ($bancos as $fila) {
                        echo '<option value="' . $fila->id . '">' . $fila->nombre . '</option>';
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
    
    //Asignar Cuenta a sedes    
    function asignar_empleado() {
        $data["tab"] = "crear_asignar_cuenta_empeado";
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['cuenta'] = $this->select_model->cuenta_banco();
        $data['action_agregar_empleado_cuenta'] = base_url() . "cuenta/insertar_asignar_empleado";
        $data['action_anular_empleado_cuenta'] = base_url() . "cuenta/anular_asignar_empleado";        

        $data['action_llena_cuenta_bancaria'] = base_url() . "cuenta/llena_cuenta_bancaria";
        $data['action_llena_checkbox_empleados_cuenta'] = base_url() . "cuenta/llena_checkbox_empleados_cuenta";

        $data['action_llena_empleados_cuenta'] = base_url() . "cuenta/llena_empleados_cuenta_banco";

        $this->parser->parse('cuenta/asignar_empleado', $data);
        $this->load->view('footer');
    }

    public function insertar_asignar_empleado() {
        if ($this->input->is_ajax_request()) {
            //Validamos que haya seleccionado al menos una sede
            $checkbox = $this->input->post('empleados_checkbox');
            if ($checkbox != TRUE) {
                $errors = array(
                    'mensaje' => '<p>Seleccione al menos un empleado.</p>',
                    'respuesta' => 'error'
                );
                echo json_encode($errors);
                return FALSE;
            } else {
                $cuenta = $this->input->post('cuenta');
                $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
                $id_responsable = $this->input->post('id_responsable');
                $dni_responsable = $this->input->post('dni_responsable');

                foreach ($checkbox as $fila) {
                    list($id_encargado, $dni_encargado) = explode("-", $fila);
                    $sede = $this->select_model->empleado($id_encargado, $dni_encargado)->sede_ppal;
                    $error = $this->insert_model->cuenta_x_sede_x_empleado($cuenta, $sede, $id_encargado, $dni_encargado, 1);
                    $this->insert_model->asignar_cuenta_x_sede_x_empleado($cuenta, $sede, $id_encargado, $dni_encargado, $fecha_trans, $id_responsable, $dni_responsable);
                    if (isset($error)) {
                        $response = array(
                            'respuesta' => 'error',
                            'mensaje' => '<p>' . $error . '</p>'
                        );
                        echo json_encode($response);
                        return FALSE;
                    }
                }
                $response = array(
                    'respuesta' => 'OK'
                );
                echo json_encode($response);
                return FALSE;
            }
        } else {
            redirect(base_url());
        }
    }

    public function anular_asignar_empleado() {
        if ($this->input->is_ajax_request()) {
            list($id_encargado, $dni_encargado, $cuenta) = explode("-", $this->input->post('empleado_cuenta'));
            $sede = $this->select_model->empleado($id_encargado, $dni_encargado)->sede_ppal;
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $error = $this->update_model->cuenta_x_sede_x_empleado($cuenta, $sede, $id_encargado, $dni_encargado, 0);

            if (isset($error)) {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p>' . $error . '</p>'
                );
            } else {
                //Para la historica no atrapo el error, si hubo error no me importa, con tal que se haya hecho la transaccion verdadera
                $this->insert_model->anular_cuenta_x_sede_x_empleado($cuenta, $sede, $id_encargado, $dni_encargado, $fecha_trans, $id_responsable, $dni_responsable);
                $response = array(
                    'respuesta' => 'OK'
                );
            }

            $response = array(
                'respuesta' => 'OK'
            );

            echo json_encode($response);
            return FALSE;
        } else {
            redirect(base_url());
        }
    }
    
    public function llena_cuenta_bancaria() {
        if ($this->input->is_ajax_request()) {
            $cuentas = $this->select_model->cuenta_banco();
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
            redirect(base_url());
        }
    }    
    
    public function llena_checkbox_empleados_cuenta() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('cuenta')) && ($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $cuenta = $this->input->post('cuenta');
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $empleados = $this->select_model->empleado_faltante_cuenta_bancaria_responsable($cuenta, $id_responsable, $dni_responsable);
                if ($empleados == TRUE) {
                    foreach ($empleados as $fila) {
                        echo '<div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" name="empleados_checkbox[]" class="input_modal_3" value="' . $fila->id . "-" . $fila->dni . '"/><h4 class="h_negrita">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</h4></label>
                            </div>
                        </div>';
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
    
    public function llena_empleados_cuenta_banco() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('cuenta')) {
                $cuenta = $this->input->post('cuenta');
                $empleados = $this->select_model->empleados_cuenta_bancaria($cuenta);
                if ($empleados == TRUE) {
                    foreach ($empleados as $fila) {
                        echo '<tr>
                            <td>' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</td>
                            <td class="text-center">
                            <button class="btn btn-danger btn-xs anular_empleado_cuenta" id="' . $fila->id . "-" . $fila->dni . "-" . $cuenta . '"><span class="glyphicon glyphicon-remove"></span> Desautorizar </button>
                            </td>
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
    
    //Asignar Cuenta a sedes
    function asignar_sede() {
        $data["tab"] = "crear_asignar_cuenta_sede";
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['cuenta'] = $this->select_model->cuenta_banco();
        $data['action_agregar_sede_cuenta'] = base_url() . "cuenta/insertar_asignar_sede";
        $data['action_anular_sede_cuenta'] = base_url() . "cuenta/anular_asignar_sede";        
        $data['action_llena_cuenta_bancaria'] = base_url() . "cuenta/llena_cuenta_bancaria";
        $data['action_llena_sedes_cuenta'] = base_url() . "cuenta/llena_sedes_cuenta_banco";
        $data['action_llena_checkbox_sedes_cuenta'] = base_url() . "cuenta/llena_checkbox_sedes_cuenta";

        $this->parser->parse('cuenta/asignar_sede', $data);
        $this->load->view('footer');
    }

    public function insertar_asignar_sede() {
        if ($this->input->is_ajax_request()) {
            //Validamos que haya seleccionado al menos una sede
            $checkbox = $this->input->post('sede_checkbox');
            if ($checkbox != TRUE) {
                $errors = array(
                    'mensaje' => '<p>Seleccione al menos una sede.</p>',
                    'respuesta' => 'error'
                );
                echo json_encode($errors);
                return FALSE;
            } else {
                $cuenta = $this->input->post('cuenta');
                $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
                $id_responsable = $this->input->post('id_responsable');
                $dni_responsable = $this->input->post('dni_responsable');

                foreach ($checkbox as $fila) {
                    $error = $this->insert_model->cuenta_x_sede($cuenta, $fila, 1);
                    $this->insert_model->asignar_cuenta_x_sede($cuenta, $fila, $fecha_trans, $id_responsable, $dni_responsable);
                    if (isset($error)) {
                        $response = array(
                            'respuesta' => 'error',
                            'mensaje' => '<p>' . $error . '</p>'
                        );
                        echo json_encode($response);
                        return FALSE;
                    }
                }
                $response = array(
                    'respuesta' => 'OK'
                );
                echo json_encode($response);
                return FALSE;
            }
        } else {
            redirect(base_url());
        }
    }

    public function anular_asignar_sede() {
        if ($this->input->is_ajax_request()) {
            list($sede, $cuenta) = explode("-", $this->input->post('sede_cuenta'));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $error = $this->update_model->cuenta_x_sede($cuenta, $sede, 0);
            $error = $this->update_model->cuenta_x_sede_x_empleado_todos($cuenta, $sede, 0);

            if (isset($error)) {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p>' . $error . '</p>'
                );
            } else {
                //Para la historica no atrapo el error, si hubo error no me importa, con tal que se haya hecho la transaccion verdadera
                $this->insert_model->anular_cuenta_x_sede($cuenta, $sede, $fecha_trans, $id_responsable, $dni_responsable);
                $response = array(
                    'respuesta' => 'OK'
                );
            }
            echo json_encode($response);
            return FALSE;
        } else {
            redirect(base_url());
        }
    } 
    
    public function llena_sedes_cuenta_banco() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('cuenta')) {
                $cuenta = $this->input->post('cuenta');
                $sedes = $this->select_model->sedes_cuenta_bancaria($cuenta);
                if ($sedes == TRUE) {
                    foreach ($sedes as $fila) {
                        echo '<tr>
                            <td>' . $fila->nombre . '</td>
                            <td class="text-center">
                            <button class="btn btn-danger btn-xs anular_sede_cuenta" id="' . $fila->id . "-" . $cuenta . '"><span class="glyphicon glyphicon-remove"></span> Desautorizar </button>
                            </td>
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
    
    public function llena_checkbox_sedes_cuenta() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('cuenta')) && ($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $cuenta = $this->input->post('cuenta');
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $sedes = $this->select_model->sede_faltante_cuenta_bancaria_responsable($cuenta, $id_responsable, $dni_responsable);
                if ($sedes == TRUE) {
                    foreach ($sedes as $fila) {
                        echo '<div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" name="sede_checkbox[]" class="input_modal_3" value="' . $fila->id . '"/><h4 class="h_negrita">' . $fila->nombre . '</h4></label>
                            </div>
                        </div>';
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
