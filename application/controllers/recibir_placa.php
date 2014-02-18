<?php

class Recibir_placa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function crear() {
        $data["tab"] = "crear_recibir_placa";
        $this->isLogin($data["tab"]);        
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['action_validar'] = base_url() . "recibir_placa/validar";
        $data['action_crear'] = base_url() . "recibir_placa/insertar";
        $data['action_llenar_placas'] = base_url() . "recibir_placa/llena_despacho_placa";
        $this->parser->parse('recibir_placa/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
        $this->escapar($_POST);            
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
            $placas_checkbox = $this->input->post('placas_checkbox');
            $error_check_placas = "";
            if ($placas_checkbox != TRUE) {
                $error_check_placas = "<p>Seleccione al menos una solicitud.</p>";
            }
            if (($this->form_validation->run() == FALSE) || ($error_check_placas != "")) {
                echo $error_check_placas . form_error('observacion');
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
            $despachos_checkbox = $this->input->post('placas_checkbox');
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $data["tab"] = "crear_recibir_placa";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "recibir_placa/crear";
            $data['msn_recrear'] = "Recibir otra Placa";
            $bandera_error = 0;
            foreach ($despachos_checkbox as $fila) {
                $error = $this->insert_model->recibir_placa($fila, $observacion, $fecha_trans, $id_responsable, $dni_responsable);
                if (isset($error)) {
                    $data['trans_error'] = $error;
                    $this->parser->parse('trans_error', $data);
                    $bandera_error = 1;
                } else {
                    //SI no hubo error, entonces quito el pendiente de de la solicitud despachada.
                    $this->update_model->despachar_placa_pendiente($fila, 0);
                }
            }
            if ($bandera_error == 0) {
                $this->parser->parse('trans_success', $data);
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_despacho_placa() {
        if ($this->input->is_ajax_request()) {
        $this->escapar($_POST);            
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

}
