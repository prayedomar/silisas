<?php

class Caja extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
    }

    function crear() {
        $data["tab"] = "crear_caja";
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['empleado'] = $this->select_model->empleado_sedes_responsable($id_responsable, $dni_responsable);
        $data['sede'] = $this->select_model->sede_activa_responsable($id_responsable, $dni_responsable);
        $data['t_caja'] = $this->select_model->t_caja();

        $data['action_validar'] = base_url() . "caja/validar";
        $data['action_crear'] = base_url() . "caja/insertar";
        $data['action_llena_t_caja_sede'] = base_url() . "caja/llena_t_caja_sede";
        $data['action_llena_encargado_sede'] = base_url() . "caja/llena_encargado_sede";
        $this->parser->parse('caja/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('sede', 'Sede', 'required|callback_select_default');
            $this->form_validation->set_rules('t_caja', 'Tipo de Caja', 'required|callback_select_default');
            $this->form_validation->set_rules('empleado', 'Empleado Encargado', 'required|callback_select_default');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
            if ($this->form_validation->run() == FALSE) {
                echo form_error('sede') . form_error('t_caja') . form_error('empleado') . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function insertar() {
        if ($this->input->post('submit')) {
            $sede = $this->input->post('sede');
            $t_caja = $this->input->post('t_caja');
            list($id_encargado, $dni_encargado) = explode("-", $this->input->post('empleado'));
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');


            $error = $this->insert_model->caja($sede, $t_caja, $id_encargado, $dni_encargado, 1, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            $data["tab"] = "crear_caja";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "caja/crear";
            $data['msn_recrear'] = "Crear otra Caja (Punto de Venta)";
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                $this->parser->parse('trans_success', $data);
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

}