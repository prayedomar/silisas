<?php

class Sede_secundaria extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function crear() {
        $data["tab"] = "crear_sede_secundaria";
        $this->isLogin($data["tab"]);        
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['empleado'] = $this->select_model->empleado_activo();
        $data['action_llena_empleado_sede_secundaria'] = base_url() . "sede_secundaria/llena_empleado_sede_secundaria";
        $data['action_anular_secundaria'] = base_url() . "sede_secundaria/anular";
        $data['action_agregar_secundaria'] = base_url() . "sede_secundaria/insertar";
        $data['action_llena_checkbox_secundarias'] = base_url() . "sede_secundaria/llena_checkbox_secundarias";
        $this->parser->parse('sede_secundaria/crear', $data);
        $this->load->view('footer');
    }

    public function insertar() {
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

    public function anular() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
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
    
    //Metodos para Consultar    

}
