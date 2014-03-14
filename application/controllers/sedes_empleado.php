<?php

class Sedes_empleado extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    //Editar: Sede Empleado
    function editar() {
        $data["tab"] = "editar_sedes_empleado";
        $this->isLogin($data["tab"]);        
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['empleado'] = $this->select_model->empleado_activo();
        $data['action_editar_ppal'] = base_url() . "sedes_empleado/editar_sede_ppal";
        $data['action_llena_empleado_sede_ppal'] = base_url() . "sedes_empleado/llena_empleado_sede_ppal";
        $data['action_llena_empleado_sede_secundaria'] = base_url() . "sedes_empleado/llena_empleado_sede_secundaria";
        $data['action_llena_checkbox_secundarias'] = base_url() . "sedes_empleado/llena_checkbox_secundarias";
        $data['action_llena_sede_ppal_faltante'] = base_url() . "sedes_empleado/llena_sede_ppal_faltante";
        $data['action_anular_secundaria'] = base_url() . "sedes_empleado/anular_sede_secundaria";
        $data['action_agregar_secundaria'] = base_url() . "sedes_empleado/insertar_sede_secundaria";
        $this->parser->parse('sedes_empleado/editar', $data);
        $this->load->view('footer');
    }

    public function insertar_sede_secundaria() {
        if ($this->input->is_ajax_request()) {
        $this->escapar($_POST);            
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
                
                $id_responsable = $this->session->userdata('idResponsable');
                $dni_responsable = $this->session->userdata('dniResponsable');

                foreach ($checkbox as $fila) {
                    $error = $this->insert_model->empleado_x_sede($id_empleado, $dni_empleado, $fila);
                    $this->insert_model->asignar_empleado_x_sede($id_empleado, $dni_empleado, $fila, $id_responsable, $dni_responsable);
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

    public function editar_sede_ppal() {
        if ($this->input->is_ajax_request()) {
        $this->escapar($_POST);            
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
                
                $id_responsable = $this->session->userdata('idResponsable');
                $dni_responsable = $this->session->userdata('dniResponsable');

                $error = $this->update_model->empleado_sede_ppal($id_empleado, $dni_empleado, $sede_ppal);

                if (isset($error)) {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p>' . $error . '</p>'
                    );
                } else {
                    //Para la historica no atrapo el error, si hubo error no me importa, con tal que se haya hecho la transaccion verdadera
                    $this->insert_model->cambio_sede_empleado($id_empleado, $dni_empleado, $sede_ppal, $observacion, $id_responsable, $dni_responsable);
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
        $this->escapar($_POST);            
            list($sede_secundaria, $id_empleado, $dni_empleado) = explode("-", $this->input->post('id_empleado_sede'));
            $sede_ppal = $this->input->post('sede_ppal');
            
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $vigente = 0;

            $error = $this->update_model->empleado_x_sede($id_empleado, $dni_empleado, $sede_secundaria, $vigente);

            if (isset($error)) {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p>' . $error . '</p>'
                );
            } else {
                //Para la historica no atrapo el error, si hubo error no me importa, con tal que se haya hecho la transaccion verdadera
                $this->insert_model->anular_empleado_x_sede($id_empleado, $dni_empleado, $sede_secundaria, $id_responsable, $dni_responsable);
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

    public function llena_empleado_sede_ppal() {
        if ($this->input->is_ajax_request()) {
        $this->escapar($_POST);            
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

    public function llena_empleado_sede_secundaria() {
        if ($this->input->is_ajax_request()) {
        $this->escapar($_POST);            
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

    public function llena_checkbox_secundarias() {
        if ($this->input->is_ajax_request()) {
        $this->escapar($_POST);            
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

    public function llena_sede_ppal_faltante() {
        if ($this->input->is_ajax_request()) {
        $this->escapar($_POST);            
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

}
