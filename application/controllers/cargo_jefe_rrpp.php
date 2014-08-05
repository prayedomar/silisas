<?php

class Cargo_jefe_rrpp extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    //Editar: Cargo y Jefe
    function editar() {
        $data["tab"] = "editar_cargo_jefe_rrpp";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);

        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
        $data['empleado'] = $this->select_model->empleado_RRPP_sedes_responsable($id_responsable, $dni_responsable);
        $data['cargo'] = $this->select_model->t_cargo();

        $data['action_editar_cargo'] = base_url() . "cargo_jefe_rrpp/editar_cargo_empleado";
        $data['action_editar_jefe'] = base_url() . "cargo_jefe_rrpp/editar_jefe_empleado";

        $data['action_llena_empleado_rrpp_sedes_responsable'] = base_url() . "cargo_jefe_rrpp/llena_empleado_rrpp_sedes_responsable";
        $data['action_llena_cargo_empleado'] = base_url() . "cargo_jefe_rrpp/llena_cargo_empleado";
        $data['action_llena_jefe_empleado'] = base_url() . "cargo_jefe_rrpp/llena_jefe_empleado";
        $data['action_llena_jefe_faltante'] = base_url() . "cargo_jefe_rrpp/llena_jefe_faltante";
        $data['action_llena_cargo_genero_cargo_old'] = base_url() . "cargo_jefe_rrpp/llena_cargo_genero_cargo_old";

        $this->parser->parse('cargo_jefe_rrpp/editar', $data);
        $this->load->view('footer');
    }

    public function editar_cargo_empleado() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('cargo', 'Nuevo Cargo', 'required|callback_select_default');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
            $check_placa = $this->input->post('checkbox_placa');
            if ($check_placa == TRUE) {
                $this->form_validation->set_rules('fecha_ascenso', 'Fecha del ascenso', 'required|xss_clean|callback_fecha_valida');
            }
            if ($this->form_validation->run() == FALSE) {
                //de esta forma devolvemos los errores de formularios
                //con ajax desde codeigniter, aunque con php es lo mismo
                $errors = array(
                    'mensaje' => '<p>' . form_error('cargo') . form_error('observacion') . form_error('fecha_ascenso') . '</p>',
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
                    $fecha_ascenso = $this->input->post('fecha_ascenso');
                    $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));

                    $id_responsable = $this->session->userdata('idResponsable');
                    $dni_responsable = $this->session->userdata('dniResponsable');
                    //comprobamos si seleccionó el cheked de la placa
                    $check_placa = $this->input->post('checkbox_placa');
                    if ($check_placa == TRUE) {
                        $solicitar_placa = 1;
                        $error1 = $this->insert_model->solicitar_placa($id_empleado, $dni_empleado, $cargo, $fecha_ascenso, $sede, $observacion, $id_responsable, $dni_responsable);
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
                    $this->insert_model->cambio_cargo($id_empleado, $dni_empleado, $cargo_old, $cargo, $solicitar_placa, $sede, $observacion, $id_responsable, $dni_responsable);
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
            $this->escapar($_POST);
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
                    $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));

                    $id_responsable = $this->session->userdata('idResponsable');
                    $dni_responsable = $this->session->userdata('dniResponsable');

                    $this->insert_model->cambio_jefe($id_empleado, $dni_empleado, $id_jefe_old, $dni_jefe_old, $id_jefe_new, $dni_jefe_new, $observacion, $id_responsable, $dni_responsable);
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

    public function llena_empleado_rrpp_sedes_responsable() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $empleados = $this->select_model->empleado_RRPP_sedes_responsable($id_responsable, $dni_responsable);
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

    public function llena_cargo_empleado() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
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
            $this->escapar($_POST);
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
            $this->escapar($_POST);
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

    public function llena_cargo_genero_cargo_old() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
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

}
