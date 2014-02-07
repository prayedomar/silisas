<?php

class Despachar_placa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function crear() {
        $data["tab"] = "crear_despachar_placa";
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['action_validar'] = base_url() . "despachar_placa/validar";
        $data['action_crear'] = base_url() . "despachar_placa/insertar";
        $data['action_llenar_placas'] = base_url() . "despachar_placa/llena_solicitud_placa";
        $this->parser->parse('despachar_placa/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
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
        //si se ha pulsado el botón submit validamos el formulario con codeIgniter
        //Esto es muy importante, porq de lo contrario, podrian haber accedido aqui por la url directamente y daria error porq no vienen datos.
        if ($this->input->post('submit')) {
            $placas_checkbox = $this->input->post('placas_checkbox');
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $data["tab"] = "crear_despachar_placa";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "despachar_placa/crear";
            $data['msn_recrear'] = "Despachar otra Placa";
            $bandera_error = 0;
            foreach ($placas_checkbox as $fila) {
                $error = $this->insert_model->despachar_placa($fila, $observacion, $fecha_trans, $id_responsable, $dni_responsable);
                if (isset($error)) {
                    $data['trans_error'] = $error;
                    $this->parser->parse('trans_error', $data);
                    $bandera_error = 1;
                } else {
                    //SI no hubo error, entonces quito el pendiente de de la solicitud despachada.
                    $this->update_model->solicitud_placa_pendiente($fila, 0);
                }
            }
            if ($bandera_error == 0) {
                $this->parser->parse('trans_success', $data);
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
    
    //Metodos para Consultar    

}
