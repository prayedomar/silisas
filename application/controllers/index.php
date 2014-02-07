<?php

if (!defined('BASEPATH'))
    exit('No esta permitido el acceso directo a este controlador. Es necesario pasar antes por el menu principal');

class Index extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    public function index() {
        $data["tab"] = "crear_titular";
        $this->load->view("header", $data);
        $this->load->view('welcome');
        $this->load->view('footer');
    }

    //Crear: Sede Secundaria
    function crear_sede_secundaria() {
        $data = $this->navbar();
        $data['empleado'] = $this->select_model->empleado_activo();
        $data['action_llena_empleado_sede_secundaria'] = base_url() . "index_admon_sistema/llena_empleado_sede_secundaria";
        $data['action_anular_secundaria'] = base_url() . "index_admon_sistema/anular_sede_secundaria";
        $data['action_agregar_secundaria'] = base_url() . "index_admon_sistema/new_sede_secundaria";
        $data['action_llena_checkbox_secundarias'] = base_url() . "index_admon_sistema/llena_checkbox_secundarias";
        $this->parser->parse('crear_sede_secundaria', $data);
        $this->load->view('footer');
    }

    public function new_sede_secundaria() {
        if ($this->input->is_ajax_request()) {
            //Validamos que haya seleccionado al menos una sede
            $checkbox = $this->input->post('sede_checkbox');
            if ($checkbox != TRUE) {
                $errors = array(
                    'mensaje' => '<p>Seleccione al menos una sede secundaria.</p>',
                    'respuesta' => 'error'
                );
                echo json_encode($errors);
                return FALSE;
            } else {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
                $id_responsable = $this->input->post('id_responsable');
                $dni_responsable = $this->input->post('dni_responsable');

                foreach ($checkbox as $fila) {
                    $error = $this->insert_model->empleado_x_sede($id_empleado, $dni_empleado, $fila);
                    $this->insert_model->asignar_empleado_x_sede($id_empleado, $dni_empleado, $fila, $fecha_trans, $id_responsable, $dni_responsable);
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

//Crear: Cuenta Bancaria
    function crear_cuenta() {
        $data = $this->navbar();
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['empleado'] = $this->select_model->empleado_sedes_responsable($id_responsable, $dni_responsable);
        $data['sede'] = $this->select_model->sede_activa_responsable($id_responsable, $dni_responsable);
        $data['t_cuenta'] = $this->select_model->t_cuenta();
        $data['pais'] = $this->select_model->pais();

        $data['action_validar'] = base_url() . "index_admon_sistema/validar_cuenta";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_cuenta";
        $data['action_llena_banco_pais'] = base_url() . "index_admon_sistema/llena_banco_pais";
        $this->parser->parse('crear_cuenta', $data);
        $this->load->view('footer');
    }

    function validar_cuenta() {
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

    function new_cuenta() {
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

            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "index_admon_sistema/crear_cuenta";
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
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url());
        }
    }

    //Editar: Sede Empleado
    function editar_sedes_empleado() {
        $data = $this->navbar();
        $data['empleado'] = $this->select_model->empleado_activo();
        $data['action_editar_ppal'] = base_url() . "index_admon_sistema/editar_sede_ppal";
        $data['action_llena_empleado_sede_ppal'] = base_url() . "index_admon_sistema/llena_empleado_sede_ppal";
        $data['action_llena_empleado_sede_secundaria'] = base_url() . "index_admon_sistema/llena_empleado_sede_secundaria";
        $data['action_llena_checkbox_secundarias'] = base_url() . "index_admon_sistema/llena_checkbox_secundarias";
        $data['action_llena_sede_ppal_faltante'] = base_url() . "index_admon_sistema/llena_sede_ppal_faltante";
        $data['action_anular_secundaria'] = base_url() . "index_admon_sistema/anular_sede_secundaria";
        $data['action_agregar_secundaria'] = base_url() . "index_admon_sistema/new_sede_secundaria";
        $this->parser->parse('editar_sedes_empleado', $data);
        $this->load->view('footer');
    }

    public function editar_sede_ppal() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('sede_ppal', 'sede_ppal', 'required|callback_select_default');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
            if ($this->form_validation->run() == FALSE) {
                //de esta forma devolvemos los errores de formularios
                //con ajax desde codeigniter, aunque con php es lo mismo
                $errors = array(
                    'mensaje' => '<p>' . form_error('sede_ppal') . form_error('observacion') . '</p>',
                    'respuesta' => 'error'
                );
                //y lo devolvemos así para parsearlo con JSON.parse
                echo json_encode($errors);
                return FALSE;
            } else {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $sede_ppal = $this->input->post('sede_ppal');
                $observacion = ucfirst(strtolower($this->input->post('observacion')));
                $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
                $id_responsable = $this->input->post('id_responsable');
                $dni_responsable = $this->input->post('dni_responsable');

                $error = $this->update_model->empleado_sede_ppal($id_empleado, $dni_empleado, $sede_ppal);

                if (isset($error)) {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p>' . $error . '</p>'
                    );
                } else {
                    //Para la historica no atrapo el error, si hubo error no me importa, con tal que se haya hecho la transaccion verdadera
                    $this->insert_model->cambio_sede_empleado($id_empleado, $dni_empleado, $sede_ppal, $observacion, $fecha_trans, $id_responsable, $dni_responsable);
                    $response = array(
                        'respuesta' => 'OK'
                    );
                }
                echo json_encode($response);
                return FALSE;
            }
        } else {
            redirect(base_url());
        }
    }

    public function anular_sede_secundaria() {
        if ($this->input->is_ajax_request()) {
            list($sede_secundaria, $id_empleado, $dni_empleado) = explode("-", $this->input->post('id_empleado_sede'));
            $sede_ppal = $this->input->post('sede_ppal');
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $vigente = 0;

            $error = $this->update_model->empleado_x_sede($id_empleado, $dni_empleado, $sede_secundaria, $vigente);

            if (isset($error)) {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p>' . $error . '</p>'
                );
            } else {
                //Para la historica no atrapo el error, si hubo error no me importa, con tal que se haya hecho la transaccion verdadera
                $this->insert_model->anular_empleado_x_sede($id_empleado, $dni_empleado, $sede_secundaria, $fecha_trans, $id_responsable, $dni_responsable);
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

    //Editar: Cargo y Jefe
    function editar_cargo_jefe() {
        $data = $this->navbar();
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['empleado'] = $this->select_model->empleado_RRPP_sedes_responsable($id_responsable, $dni_responsable);
        $data['cargo'] = $this->select_model->t_cargo();

        $data['action_editar_cargo'] = base_url() . "index_admon_sistema/editar_cargo_empleado";
        $data['action_editar_jefe'] = base_url() . "index_admon_sistema/editar_jefe_empleado";

        $data['action_llena_empleado_rrpp_sedes_responsable'] = base_url() . "index_admon_sistema/llena_empleado_rrpp_sedes_responsable";
        $data['action_llena_cargo_empleado'] = base_url() . "index_admon_sistema/llena_cargo_empleado";
        $data['action_llena_jefe_empleado'] = base_url() . "index_admon_sistema/llena_jefe_empleado";
        $data['action_llena_jefe_faltante'] = base_url() . "index_admon_sistema/llena_jefe_faltante";
        $data['action_llena_cargo_genero_cargo_old'] = base_url() . "index_admon_sistema/llena_cargo_genero_cargo_old";

        $this->parser->parse('editar_cargo_jefe', $data);
        $this->load->view('footer');
    }

    public function editar_cargo_empleado() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('cargo', 'Nuevo Cargo', 'required|callback_select_default');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
            if ($this->form_validation->run() == FALSE) {
                //de esta forma devolvemos los errores de formularios
                //con ajax desde codeigniter, aunque con php es lo mismo
                $errors = array(
                    'mensaje' => '<p>' . form_error('cargo') . form_error('observacion') . '</p>',
                    'respuesta' => 'error'
                );
                //y lo devolvemos así para parsearlo con JSON.parse
                echo json_encode($errors);
                return FALSE;
            } else {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $cargo = $this->input->post('cargo');

                $error = $this->update_model->empleado_cargo($id_empleado, $dni_empleado, $cargo);

                if (isset($error)) {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p>' . $error . '</p>'
                    );
                } else {
                    list($genero, $cargo_old) = explode("-", $this->input->post('genero_cargo'));
                    $empleado = $this->select_model->empleado($id_empleado, $dni_empleado);
                    $sede = $empleado->sede_ppal;
                    $observacion = ucfirst(strtolower($this->input->post('observacion')));
                    $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
                    $id_responsable = $this->input->post('id_responsable');
                    $dni_responsable = $this->input->post('dni_responsable');
                    //comprobamos si seleccionó el cheked de la placa
                    $check_placa = $this->input->post('checkbox_placa');
                    if ($check_placa == TRUE) {
                        $solicitar_placa = 1;
                        $error1 = $this->insert_model->solicitar_placa($id_empleado, $dni_empleado, $cargo, $sede, $observacion, $fecha_trans, $id_responsable, $dni_responsable);
                        if (isset($error1)) {
                            $response = array(
                                'respuesta' => 'error',
                                'mensaje' => '<p>' . $error1 . '</p>'
                            );
                            echo json_encode($response);
                            return FALSE;
                        }
                    } else {
                        $solicitar_placa = 0;
                    }
                    $this->insert_model->cambio_cargo($id_empleado, $dni_empleado, $cargo_old, $cargo, $solicitar_placa, $sede, $observacion, $fecha_trans, $id_responsable, $dni_responsable);
                    $response = array(
                        'respuesta' => 'OK'
                    );
                }
                echo json_encode($response);
                return FALSE;
            }
        } else {
            redirect(base_url());
        }
    }

    public function editar_jefe_empleado() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('jefe', 'Nuevo Jefe', 'required|callback_select_default');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
            if ($this->form_validation->run() == FALSE) {
                //de esta forma devolvemos los errores de formularios
                //con ajax desde codeigniter, aunque con php es lo mismo
                $errors = array(
                    'mensaje' => '<p>' . form_error('jefe') . form_error('observacion') . '</p>',
                    'respuesta' => 'error'
                );
                //y lo devolvemos así para parsearlo con JSON.parse
                echo json_encode($errors);
                return FALSE;
            } else {
                list($id_empleado, $dni_empleado, $id_jefe_old, $dni_jefe_old) = explode("-", $this->input->post('empleado_jefe'));
                list($id_jefe_new, $dni_jefe_new) = explode("-", $this->input->post('jefe'));

                $error = $this->update_model->empleado_jefe($id_empleado, $dni_empleado, $id_jefe_new, $dni_jefe_new);

                if (isset($error)) {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p>' . $error . '</p>'
                    );
                } else {
                    $observacion = ucfirst(strtolower($this->input->post('observacion')));
                    $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
                    $id_responsable = $this->input->post('id_responsable');
                    $dni_responsable = $this->input->post('dni_responsable');

                    $this->insert_model->cambio_jefe($id_empleado, $dni_empleado, $id_jefe_old, $dni_jefe_old, $id_jefe_new, $dni_jefe_new, $observacion, $fecha_trans, $id_responsable, $dni_responsable);
                    $response = array(
                        'respuesta' => 'OK'
                    );
                }
                echo json_encode($response);
                return FALSE;
            }
        } else {
            redirect(base_url());
        }
    }

    //Llenar elementos html dinamicamente
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

    public function llena_t_caja_sede() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('sede')) && ($this->input->post('sede') != '{id}') && ($this->input->post('sede') != 'default')) {
                $sede = $this->input->post('sede');
                $t_cajas = $this->select_model->t_caja_faltante($sede);
                if ($t_cajas == TRUE) {
                    foreach ($t_cajas as $fila) {
                        echo '<option value="' . $fila->id . '">' . $fila->tipo . '</option>';
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

    public function llena_encargado_sede() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('sede')) && ($this->input->post('sede') != '{id}') && ($this->input->post('sede') != 'default')) {
                $sede = $this->input->post('sede');
                $t_cajas = $this->select_model->empleado_sede_caja($sede);
                if ($t_cajas == TRUE) {
                    foreach ($t_cajas as $fila) {
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

    public function llena_t_concepto_salario() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('t_salario')) {
                $t_salario = $this->input->post('t_salario');
                $conceptos = $this->select_model->t_concepto_nomina_base($t_salario);
                if ($conceptos == TRUE) {
                    foreach ($conceptos as $fila) {
                        echo '<div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="margin_label">' . $fila->tipo . '</label>   
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input name="values_conceptos[]" type="hidden" value="' . $fila->id . '">
                                        <input type="text" name="conceptos[]" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12">
                                    </div>
                                </div>
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

    public function llena_cargo_comision_faltante() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable')) && ($this->input->post('ejecutivoDirecto')) && (($this->input->post('ejecutivoDirecto')) != "default")) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                list($id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo) = explode("-", $this->input->post('ejecutivoDirecto'));
                $t_cargos = $this->select_model->t_cargo_superior_rrpp($cargo_ejecutivo);
                if ($t_cargos == TRUE) {
                    foreach ($t_cargos as $fila) {
                        echo '<div class="form-group">
                            <label>Escala: ' . $fila->cargo_masculino . '<em class="required_asterisco">*</em></label>
                            <input name="cargos_escalas[]" type="hidden" value="' . $fila->id . "-" . $fila->cargo_masculino . '">
                            <select name="escalas[]" class="form-control exit_caution">
                            <option value="default">Seleccione Ejecutivo para la escala</option>';
                        $ejecutivos = $this->select_model->empleado_rrpp_cargo_superior($fila->id, $id_responsable, $dni_responsable);
                        if ($ejecutivos == TRUE) {
                            foreach ($ejecutivos as $registro) {
                                echo '<option value="' . $registro->id . "-" . $registro->dni . "-" . $registro->cargo . '">' . $registro->nombre1 . " " . $registro->nombre2 . " " . $registro->apellido1 . " " . $registro->apellido2 . '</option>';
                            }
                        }
                        echo '<option value="nula">ÉSTA ESCALA NO SE PAGARÁ A NADIE</option>
                        </select>
                        </div>  ';
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

    public function llena_adelanto_empleado() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('empleado')) && ($this->input->post('empleado') != "default")) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $adelantos = $this->select_model->adelanto_vigente_empleado($id_empleado, $dni_empleado);
                if ($adelantos == TRUE) {
                    foreach ($adelantos as $fila) {
                        echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="adelanto" id="adelanto" value="' . $fila->prefijo_adelanto . "-" . $fila->id_adelanto . "-" . $fila->saldo . '"/></td>
                            <td class="text-center">$' . number_format($fila->total, 2, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($fila->saldo, 2, '.', ',') . '</td>
                            <td class="text-center">' . $fila->sede . '</td>
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

    public function llena_detalle_matricula_liquidar() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('matricula')) && ($this->input->post('matricula') != "default")) {
                $contrato = $this->input->post('matricula');
                $detalle = $this->select_model->detalle_matricula_liquidar($contrato);
                if ($detalle == TRUE) {
                    $response = array(
                        'respuesta' => 'OK',
                        'detalleMatricula' => '',
                        'IdDniEjecutivo' => $detalle->id . "-" . $detalle->dni . "-" . $detalle->cargo,
                        'CargoEjecutivo' => $detalle->cargo
                    );
                    $response['detalleMatricula'] = '<tr>
                            <td class="text-center">' . $detalle->titular . '</td>
                            <td class="text-center">' . $detalle->plan . '</td>
                            <td class="text-center">' . $detalle->observacion . '</td>
                            <td class="text-center">' . $detalle->ejecutivo . '</td>                                
                            <td class="text-center">' . $detalle->name_cargo . '</td>
                            <td class="text-center">' . $detalle->fecha_matricula . '</td>
                        </tr>';
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

    public function llena_empleado_rrpp_sedePpal() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $empleados = $this->select_model->empleado_RRPP_sede_ppal($id_responsable, $dni_responsable);
                //Validamos que la consulta devuelva algo
                if ($empleados == TRUE) {
                    foreach ($empleados as $fila) {
                        echo '<option value="' . $fila->id . "-" . $fila->dni . "-" . $fila->cargo . '">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</option>';
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

    public function llena_empleado_rrpp_sedes_responsable() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $empleados = $this->select_model->empleado_RRPP_sede_ppal($id_responsable, $dni_responsable);
                //Validamos que la consulta devuelva algo
                if ($empleados == TRUE) {
                    foreach ($empleados as $fila) {
                        echo '<option value="' . $fila->id . "-" . $fila->dni . "-" . $fila->cargo . '">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</option>';
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

    public function llena_cargo_ejecutivo_directo() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('ejecutivoDirecto')) && ($this->input->post('ejecutivoDirecto') != "default")) {
                list($id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo) = explode("-", $this->input->post('ejecutivoDirecto'));
                $t_cargo = $this->select_model->t_cargo_id($cargo_ejecutivo);
                //Validamos que la consulta devuelva algo
                if ($t_cargo == TRUE) {
                    echo '<label>Cargo: ' . $t_cargo->cargo_masculino . '</label><em class="required_asterisco">*</em>';
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

    public function llena_plan_comercial() {
        if ($this->input->is_ajax_request()) {
            $planes = $this->select_model->t_plan_activo();
            if ($planes == TRUE) {
                foreach ($planes as $fila) {
                    echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="plan" id="plan" value="' . $fila->id . '"/></td>
                            <td class="text-center">' . $fila->nombre . '</td>
                            <td class="text-center">' . $fila->anio . '</td>                                
                            <td class="text-center">' . $fila->cant_alumnos . '</td>
                            <td class="text-center">$' . number_format($fila->valor_total, 0, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($fila->valor_inicial, 0, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($fila->valor_cuota, 0, '.', ',') . '</td>                                
                            <td class="text-center">' . $fila->cant_cuotas . '</td>
                        </tr>';
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

    public function llena_empleado_sede_ppal() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $sede_ppal = $this->select_model->empleado_sede_ppal($id_empleado, $dni_empleado);
                if ($sede_ppal == TRUE) {
                    echo '<tr>
                        <td>' . $sede_ppal->nombre . '</td>
                        <td class="text-center">
                        <button type="button" class="btn btn-primary btn-xs editar_sede" id="' . $sede_ppal->id . '"><span class="glyphicon glyphicon-edit"></span> Editar </button>
                        </td>
                     </tr>';
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

    public function llena_empleado_sede_secundaria() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $sedes_secundarias = $this->select_model->empleado_sede_secundaria($id_empleado, $dni_empleado);
                if ($sedes_secundarias == TRUE) {
                    foreach ($sedes_secundarias as $fila) {
                        echo '<tr>
                            <td>' . $fila->nombre . '</td>
                            <td class="text-center">
                            <button class="btn btn-danger btn-xs anular_sede" id="' . $fila->sede_secundaria . "-" . $id_empleado . "-" . $dni_empleado . '"><span class="glyphicon glyphicon-remove"></span> Eliminar </button>
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

    public function llena_sede_ppal_faltante() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('sede_ppal')) {
                $sede_ppal = $this->input->post('sede_ppal');
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $sedes = $this->select_model->sede_activa_faltante_responsable($sede_ppal, $id_responsable, $dni_responsable);
                //Validamos que las dos consultas devuelvan algo
                if ($sedes == TRUE) {
                    foreach ($sedes as $fila) {
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

    public function llena_checkbox_secundarias() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $sedes_secundarias = $this->select_model->sede_secundaria_faltante_empleado_responsable($id_empleado, $dni_empleado, $id_responsable, $dni_responsable);
                if ($sedes_secundarias == TRUE) {
                    foreach ($sedes_secundarias as $fila) {
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

    public function llena_cargo_departamento() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('depto')) {
                $depto = $this->input->post('depto');
                $t_cargo = $this->select_model->cargo_depto($depto);
                //Validamos que las dos consultas devuelvan algo
                if ($t_cargo == TRUE) {
                    foreach ($t_cargo as $fila) {
                        echo '<option value="' . $fila->id . '-' . $fila->perfil . '">' . $fila->cargo_masculino . '</option>';
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

    public function llena_cargo_genero_cargo_old() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('genero_cargo')) {
                list($genero, $cargo_old) = explode("-", $this->input->post('genero_cargo'));
                //Sacamos el cargo que ya tiene para no mostrarlo
                $t_cargo = $this->select_model->t_cargo_faltante_rrpp($cargo_old);

                //Validamos que las dos consultas devuelvan algo
                if (($genero == TRUE) && ($t_cargo == TRUE)) {
                    if ($genero == 'M') {
                        foreach ($t_cargo as $fila) {
                            echo '<option value="' . $fila->id . '-' . $fila->perfil . '">' . $fila->cargo_masculino . '</option>';
                        }
                    } else {
                        if ($genero == 'F') {
                            foreach ($t_cargo as $fila) {
                                echo '<option value="' . $fila->id . '-' . $fila->perfil . '">' . $fila->cargo_femenino . '</option>';
                            }
                        } else {
                            echo "";
                        }
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

    public function llena_salario_departamento() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('depto')) {
                $depto = $this->input->post('depto');
                $salarios = $this->select_model->salario_t_salario_x_t_depto($depto);
                if ($salarios == TRUE) {
                    foreach ($salarios as $fila) {
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

    public function llena_cargo_empleado() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $empleado = $this->select_model->empleado($id_empleado, $dni_empleado);
                $cargo = $this->select_model->empleado_cargo($id_empleado, $dni_empleado);
                if (($cargo == TRUE) AND ($empleado == TRUE)) {
                    if ($empleado->genero == 'M') {
                        echo '<tr>
                            <td>' . $cargo->cargo_masculino . '</td>
                            <td class="text-center">
                            <button type="button" class="btn btn-primary btn-xs editar_cargo" id="' . $empleado->genero . '-' . $cargo->id . '"><span class="glyphicon glyphicon-edit"></span> Editar </button>
                            </td>
                     </tr>';
                    } else {
                        echo '<tr>
                            <td>' . $cargo->cargo_femenino . '</td>
                            <td class="text-center">
                            <button type="button" class="btn btn-primary btn-xs editar_cargo" id="' . $empleado->genero . '-' . $cargo->id . '"><span class="glyphicon glyphicon-edit"></span> Editar </button>
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

    public function llena_jefe_empleado() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $jefe = $this->select_model->empleado_jefe($id_empleado, $dni_empleado);
                if (($jefe == TRUE)) {
                    echo '<tr>
                        <td>' . $jefe->nombre1 . " " . $jefe->nombre2 . " " . $jefe->apellido1 . " " . $jefe->apellido2 . '</td>
                        <td class="text-center">
                        <button type="button" class="btn btn-primary btn-xs editar_jefe" id="' . $id_empleado . "-" . $dni_empleado . "-" . $jefe->id . "-" . $jefe->dni . '"><span class="glyphicon glyphicon-edit"></span> Editar </button>
                        </td>
                     </tr>';
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

    public function llena_jefe_faltante() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado_jefe')) {
                list($id_empleado, $dni_empleado, $id_jefe, $dni_jefe) = explode("-", $this->input->post('empleado_jefe'));
                //Sacamos el empleado de las lista de jefes
                $jefes = $this->select_model->empleado_jefe_faltante_rrpp($id_empleado, $dni_empleado);

                //Validamos que la consulta devuelva algo
                if ($jefes == TRUE) {
                    foreach ($jefes as $fila) {
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

    public function llena_jefe_new_empleado() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('cargo')) && ($this->input->post('sedePpal')) && ($this->input->post('depto'))) {
                list($cargo, $perfil) = explode("-", $this->input->post('cargo'));
                $sede_ppal = $this->input->post('sedePpal');
                $depto = $this->input->post('depto');
                $jefes = $this->select_model->empleado_jefe_faltante_sede_depto_cargo($sede_ppal, $depto, $cargo);
                //Validamos que la consulta devuelva algo
                if ($jefes == TRUE) {
                    foreach ($jefes as $fila) {
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

    public function llena_matricula_iliquidada() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $matriculas = $this->select_model->matricula_iliquida_responsable($id_responsable, $dni_responsable);
                //Validamos que la consulta devuelva algo
                if ($matriculas == TRUE) {
                    foreach ($matriculas as $fila) {
                        echo '<option value="' . $fila->contrato . '">' . $fila->contrato . '</option>';
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

    public function llena_solicitud_placa() {
        if ($this->input->is_ajax_request()) {
            $solicitudes = $this->select_model->solicitud_placa();
            if ($solicitudes == TRUE) {
                foreach ($solicitudes as $fila) {
                    echo '<tr>
                            <td class="text-center"><input type="checkbox" class="exit_caution" name="placas_checkbox[]"  value="' . $fila->id_solicitud . '"/></td>
                            <td>' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</td>
                            <td>' . $fila->cargo . '</td>
                            <td class="text-center">' . $fila->sede . '</td>
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

    public function llena_empleado_adelanto() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $empleados = $this->select_model->empleado_sedes_responsable_adelantos($id_responsable, $dni_responsable);
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

    public function llena_empleado_prestamo() {
        if ($this->input->is_ajax_request()) {
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

    public function llena_clientes() {
        if ($this->input->is_ajax_request()) {
            $clientes = $this->select_model->cliente();
            if ($clientes == TRUE) {
                foreach ($clientes as $fila) {
                    echo '<option value="' . $fila->id . "-" . $fila->dni . '">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</option>';
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

    public function llena_despacho_placa() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $empleado = $this->select_model->empleado($id_responsable, $dni_responsable);
                $sede_responsable = $empleado->sede_ppal;
                $despachos = $this->select_model->despacho_placa($sede_responsable);
                if (($despachos == TRUE)) {
                    foreach ($despachos as $fila) {
                        echo '<tr>
                            <td class="text-center"><input type="checkbox" class="exit_caution" name="placas_checkbox[]"  value="' . $fila->id_despacho . '"/></td>
                            <td>' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</td>
                            <td>' . $fila->cargo . '</td>
                            <td class="text-center">' . $fila->sede . '</td>
                            <td>' . $fila->observacion . '</td>
                            <td class="text-center">' . $fila->fecha_despacho . '</td>
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

    public function llena_cuenta_responsable() {
        if ($this->input->is_ajax_request()) {
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

    public function llena_info_contrato_laboral() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $contrato = $this->select_model->contrato_laboral_empleado($id_empleado, $dni_empleado);
                if (($contrato == TRUE)) {
                    if ($contrato->cant_meses == NULL) {
                        $duracion = "Indefinido";
                    } else {
                        if ($contrato->cant_meses == 1) {
                            $duracion = $contrato->cant_meses . " mes";
                        } else {
                            $duracion = $contrato->cant_meses . " meses";
                        }
                    }
                    echo '<div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Fecha Inicio</th>
                                                <th class="text-center">Tipo Contrato</th>
                                                <th class="text-center">Duración</th>
                                                <th class="text-center">Cargo</th>                                                
                                                <th class="text-center">Salario</th>
                                                <th class="text-center">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>                         
                                            <td class="text-center">' . $contrato->fecha_inicio . '</td>
                                            <td class="text-center">' . $contrato->tipo_contrato . '</td>
                                            <td class="text-center">' . $duracion . '</td> 
                                            <td class="text-center">' . $contrato->cargo . '</td>                                                
                                            <td class="text-center">' . $contrato->nombre_salario . '</td>                                  
                                            <td>' . $contrato->observacion . '</td>  
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>';
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

    public function llena_info_ultimas_nominas() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $nominas = $this->select_model->ultimas_nominas_empleado($id_empleado, $dni_empleado);
                if (($nominas == TRUE)) {
                    echo '<div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Id. Nómina</th>
                                                <th class="text-center">Fecha Inicial</th>
                                                <th class="text-center">Fecha Final</th>
                                                <th class="text-center">Sede</th>                                                
                                                <th class="text-center">Total Nómina</th>
                                                <th class="text-center">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($nominas as $fila) {
                        echo '<tr>
                                <td class="text-center">' . $fila->prefijo . "-" . $fila->id . '</td>                            
                                <td class="text-center">' . $fila->fecha_inicio . '</td>
                                <td class="text-center">' . $fila->fecha_fin . '</td>  
                                <td class="text-center">' . $fila->nombre_sede . '</td>                                
                                <td class="text-center">$' . number_format($fila->total, 2, '.', ',') . '</td>                                   
                                <td>' . $fila->observacion . '</td>  
                            </tr>';
                    }
                    echo '</tbody>
                        </table>
                    </div>';
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

    public function llena_info_adelantos() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $adelantos = $this->select_model->adelanto_vigente_empleado($id_empleado, $dni_empleado);
                if (($adelantos == TRUE)) {
                    echo '<div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Id. Adelanto</th>
                                                <th class="text-center">Fecha</th>
                                                <th class="text-center">Sede</th>
                                                <th class="text-center">Valor Inicial</th>                                            
                                                <th class="text-center">Saldo Pdte.</th>
                                                <th class="text-center">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($adelantos as $fila) {
                        echo '<tr>
                                <td class="text-center">' . $fila->prefijo_adelanto . "-" . $fila->id_adelanto . '</td>       
                                <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>                                
                                <td class="text-center">' . $fila->sede . '</td>
                                <td class="text-center">$' . number_format($fila->total, 2, '.', ',') . '</td>  
                                <td class="text-center">$' . number_format($fila->saldo, 2, '.', ',') . '</td>                                
                                <td>' . $fila->observacion . '</td>  
                            </tr>';
                    }
                    echo '</tbody>
                        </table>
                    </div>';
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

    public function llena_info_prestamos() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $prestamos = $this->select_model->prestamo_vigente_beneficiario($id_empleado, $dni_empleado);
                if (($prestamos == TRUE)) {
                    echo '<p class="help-block"><B>> </B>Los abonos a préstamos no se realizan por la nomina, sino por la opción: Crear->Abono a Préstamo.</p>
                        <div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Id. Préstamo</th>
                                                <th class="text-center">Fecha</th>
                                                <th class="text-center">Sede</th>
                                                <th class="text-center">Valor Inicial</th>                                        
                                                <th class="text-center">Cant Cuotas</th>
                                                <th class="text-center">Cuota Fija</th>
                                                <th class="text-center">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($prestamos as $fila) {
                        echo '<tr>
                                <td class="text-center">' . $fila->prefijo_prestamo . "-" . $fila->id_prestamo . '</td>   
                                <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>                                
                                <td class="text-center">' . $fila->sede . '</td>
                                <td class="text-center">$' . number_format($fila->total, 2, '.', ',') . '</td>
                                <td class="text-center">' . $fila->cant_cuotas . '</td>                                
                                <td class="text-center">$' . number_format($fila->cuota_fija, 2, '.', ',') . '</td>                               
                                <td>' . $fila->observacion . '</td>
                            </tr>';
                    }
                    echo '</tbody>
                        </table>
                    </div>';
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

    public function llena_info_ausencias() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('empleado')) && ($this->input->post('fechaInicio')) && ($this->input->post('fechaFin'))) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $fecha_inicio_nomina = $this->input->post('fechaInicio');
                $fecha_fin_nomina = $this->input->post('fechaFin');
                $ausencias = $this->select_model->ausencia_entre_fechas($id_empleado, $dni_empleado, $fecha_inicio_nomina, $fecha_fin_nomina);
                $response = array(
                    'respuesta' => 'OK',
                    'html_ausencias' => '',
                    'cant_nomina' => $this->dias_entre_fechas($fecha_inicio_nomina, $fecha_fin_nomina) + 1,
                    'cant_ausencias' => 0,
                    'cant_incapacidad' => 0
                );
                if (($ausencias == TRUE)) {
                    $response['html_ausencias'] = '<p class="help-block"><B>> </B>Sólo aparecerán las ausencias ocurridas entre el rango de fechas de la Nómina.</p>
                        <div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Fecha Inicial</th>
                                                <th class="text-center">Fecha Final</th>                                        
                                                <th class="text-center">Días de Ausencia en Nómina</th>                                                 
                                                <th class="text-center">Tipo de Ausencia</th>
                                                <th class="text-center">Remuneración</th>
                                                <th class="text-center">Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($ausencias as $fila) {
                        //Calculamos la cantidad de dias de ausencia dentro de la nomina
                        if ((($fila->fecha_inicio >= $fecha_inicio_nomina) and ($fila->fecha_inicio <= $fecha_fin_nomina)) && (($fila->fecha_fin >= $fecha_inicio_nomina) and ($fila->fecha_fin <= $fecha_fin_nomina))) {
                            $cant_ausencia = $this->dias_entre_fechas($fila->fecha_inicio, $fila->fecha_fin) + 1;
                        } else {
                            if ((($fila->fecha_inicio >= $fecha_inicio_nomina) and ($fila->fecha_inicio <= $fecha_fin_nomina))) {
                                $cant_ausencia = $this->dias_entre_fechas($fila->fecha_inicio, $fecha_fin_nomina) + 1;
                            } else {
                                $cant_ausencia = $this->dias_entre_fechas($fecha_inicio_nomina, $fila->fecha_fin) + 1;
                            }
                        }
                        if ($fila->t_ausencia == 2) {
                            $response['cant_incapacidad'] += $cant_ausencia;
                        }
                        $response['cant_ausencias'] += $cant_ausencia;
                        $response['html_ausencias'] .= '<tr>
                                <td class="text-center">' . $fila->fecha_inicio . '</td>   
                                <td class="text-center">' . $fila->fecha_fin . '</td>                                 
                                <td class="text-center">' . $cant_ausencia . '</td>                                
                                <td class="text-center">' . $fila->tipo . '</td>
                                <td class="text-center">' . $fila->salarial . '</td>                                                              
                                <td>' . $fila->descripcion . '</td>
                            </tr>';
                    }
                    $response['html_ausencias'] .= '</tbody>
                        </table>
                    </div>';
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
            redirect(base_url());
        }
    }

    public function llena_info_seguridad_social() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $seguridades = $this->select_model->concepto_nomina_seguridad_social($id_empleado, $dni_empleado);
                if (($seguridades == TRUE)) {
                    echo '<div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Id. Nomina</th>
                                                <th class="text-center">Sede</th>                                             
                                                <th class="text-center">Fecha del Concepto</th>
                                                <th class="text-center">Valor del Pago SS.SS</th>
                                                <th class="text-center">Detalle</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($seguridades as $fila) {
                        echo '<tr>
                                <td class="text-center">' . $fila->prefijo_nomina . "-" . $fila->id_nomina . '</td>
                                <td class="text-center">' . $fila->nombre_sede . '</td>                                
                                <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>
                                <td class="text-center">$' . number_format(($fila->cantidad * $fila->valor_unitario), 2, '.', ',') . '</td>                                    
                                <td class="text-center">' . $fila->detalle . '</td>
                            </tr>';
                    }
                    echo '</tbody>
                        </table>
                    </div>';
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

    public function llena_concepto_pdtes_rrpp() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $conceptos = $this->select_model->concepto_nomina_pdte_rrpp($id_empleado, $dni_empleado);
                if ($conceptos == TRUE) {
                    echo '<label>Conceptos Pendientes de RRPP</label>';
                    $i = 1;
                    foreach ($conceptos as $fila) {
                        echo '<div class="div_input_group renglon_concepto_pdte" id="div_concepto_pdte_' . $i . '">
                                <div class="row">
                                    <input type="hidden" name="t_concepto_nomina[]" id="t_concepto_nomina" value="' . $fila->t_concepto_nomina . '">
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Tipo de Concepto</label>
                                            <input name="nombre_concepto[]" id="nombre_concepto" type="text" class="form-control text-center" readonly value="' . $fila->tipo_concepto . '">
                                        </div>                            
                                    </div>     
                                    <input type="hidden" name="debito_credito[]" id="debito_credito" value="1">
                                    <div class="col-xs-3 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label class="required">Nombre Escala</label>
                                            <input name="escala[]" id="escala" type="text" class="form-control text-center" readonly value="' . $fila->escala . '">
                                        </div>                            
                                    </div>                                    
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label class="required">Detalle</label>
                                            <input name="detalle[]" id="detalle" type="text" class="form-control text-center" readonly value="' . $fila->detalle . '">
                                        </div>                            
                                    </div>
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label class="required">Fecha</label>
                                            <input name="fecha_concepto[]" id="fecha_concepto" type="text" class="form-control text-center" readonly value="' . date("Y-m-d", strtotime($fila->fecha_trans)) . '">
                                        </div>                            
                                    </div>                                    
                                    <input type="hidden" name="cantidad[]" id="cantidad" value="' . number_format($fila->cantidad, 2, '.', ',') . '">
                                    <input type="hidden" name="valor_unitario[]" id="valor_unitario" value="' . number_format($fila->valor_unitario, 2, '.', ',') . '">                                    
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Devengado</label>                            
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" name="total_concepto[]" id="total_concepto" class="form-control decimal2 miles text-center" readonly>
                                            </div>
                                        </div>                          
                                    </div>
                                    <div class="col-xs-1 padding_remove">
                                        <label class="label_btn_remove">. </label>                                
                                        <div class="form-group sin_margin_bottom text-center">
                                            <button class="btn btn-default drop_concepto_pdte" id="' . $i . '" type="button"><span class="glyphicon glyphicon-remove"></span></button>  
                                        </div>
                                    </div>
                                </div>      
                            </div>';
                        $i++;
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

    public function llena_agregar_concepto() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('idUltimoConcepto')) && ($this->input->post('empleado'))) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $i = $this->input->post('idUltimoConcepto') + 1;
                $t_concepto = $this->select_model->t_concepto_nomina_depto_empleado($id_empleado, $dni_empleado);
                echo '<div class="div_input_group renglon_concepto_pdte" id="div_concepto_new_' . $i . '">
                                <div class="row">
                                    <div class="col-xs-3 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Tipo de Concepto<em class="required_asterisco">*</em></label>
                                            <select name="t_concepto_nomina[]" id="t_concepto_nomina" class="form-control exit_caution">
                                                <option value="default">T. de Concepto Nómina</option>';
                if (($t_concepto == TRUE)) {
                    foreach ($t_concepto as $fila) {
                        echo '                  <option value="' . $fila->id . '">' . $fila->tipo . '</option>';
                    }
                }
                echo '                       </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="debito_credito[]" id="debito_credito">                                    
                                    <div class="col-xs-3 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label class="required">Detalle Adicional</label>
                                            <input name="detalle[]" id="detalle" type="text" class="form-control exit_caution letras_numeros" placeholder="Detalle Adicional" maxlength="50" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xs-1 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Cantidad<em class="required_asterisco">*</em></label>
                                            <input name="cantidad[]" id="cantidad" type="text" class="form-control exit_caution numerico input_center" placeholder="Cantidad" maxlength="3" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Valor Unitario<em class="required_asterisco">*</em></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" name="valor_unitario[]" id="valor_unitario" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12" disabled>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <div id="label_total_concepto"><label>Total Concepto</label></div>
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" name="total_concepto[]" id="total_concepto" class="form-control decimal decimal2 miles text-center" placeholder="0.00" maxlength="12" readonly>
                                            </div>
                                        </div>  
                                    </div>                                    
                                    <div class="col-xs-1  padding_remove">
                                        <label class="label_btn_remove">. </label>                                
                                        <div class="form-group sin_margin_bottom text-center">
                                            <button class="btn btn-default drop_concepto_new" id="' . $i . '" type="button"><span class="glyphicon glyphicon-remove"></span></button>  
                                        </div>
                                    </div>
                                </div>
                            </div>';
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_info_t_concepto() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('tConceptoNomina')) && ($this->input->post('empleado'))) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $id_t_concepto = $this->input->post('tConceptoNomina');
                $concepto_base = $this->select_model->concepto_base_nomina_empleado($id_empleado, $dni_empleado, $id_t_concepto);
                $t_concepto = $this->select_model->t_concepto_nomina_id($id_t_concepto);
                if ($t_concepto == TRUE) {
                    //Si existe un concpeto base para el t_conepto y el salario del empleado, pasamos su valor unitario.
                    if ($concepto_base == TRUE) {
                        $concepto_base = number_format($concepto_base->valor_unitario, 2, '.', ',');
                    } else {
                        $concepto_base = 0.00;
                    }
                    $response = array(
                        'respuesta' => 'OK',
                        'valor_unitario' => $concepto_base,
                        'debito_credito' => $t_concepto->debito_credito,
                        't_cantidad_dias' => $t_concepto->t_cantidad_dias
                    );
                    echo json_encode($response);
                    return FALSE;
                } else {
                    $response = array(
                        'respuesta' => 'error'
                    );
                    echo json_encode($response);
                    return FALSE;
                }
            } else {
                $response = array(
                    'respuesta' => 'error'
                );
                echo json_encode($response);
                return FALSE;
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
