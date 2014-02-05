<?php

if (!defined('BASEPATH'))
    exit('No esta permitido el acceso directo a este controlador. Es necesario pasar antes por el menu principal');


//Para atrapar los errores critical y notice de php
set_error_handler('exceptions_error_handler');

function exceptions_error_handler($severity, $message, $filename, $lineno) {
    if (error_reporting() == 0) {
        return;
    }
    if (error_reporting() & $severity) {
        throw new ErrorException($message, 0, $severity, $filename, $lineno);
    }
}

class salon extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    public function navbar() {
        if ($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != 'admon_sistema') {
            redirect(base_url() . 'login');
        }
        $data['rutaImg'] = $this->session->userdata('rutaImg');
        $data['msnBienvenida'] = $this->session->userdata('msnBienvenida');
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $this->parser->parse('header', $data);
        return $data;
    }

    function crear_salon() {
        $data = $this->navbar();
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['sede'] = $this->select_model->sede_activa_responsable($id_responsable, $dni_responsable);
        $data['action_validar'] = base_url() . "salon/validar_salon";
        $data['action_crear'] = base_url() . "salon/new_salon";
        $this->parser->parse('crear_salon', $data);
        $this->load->view('footer');
    }

    function validar_salon() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('capacidad', 'Capacidad', 'required|trim|max_length[2]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('sede', 'Sede', 'required|callback_select_default');
            $this->form_validation->set_rules('vigente', 'Vigente', 'required|callback_select_default');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');

            if ($this->form_validation->run() == FALSE) {
                echo form_error('nombre') . form_error('capacidad') . form_error('sede') . form_error('vigente') . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    function new_salon() {
        //si se ha pulsado el botón submit validamos el formulario con codeIgniter
        //Esto es muy importante, porq de lo contrario, podrian haber accedido aqui por la url directamente y daria error porq no vienen datos.
        if ($this->input->post('submit')) {
            $nombre = ucwords(strtolower($this->input->post('nombre')));
            $capacidad = $this->input->post('capacidad');
            $sede = $this->input->post('sede');
            $vigente = $this->input->post('vigente');
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $error = $this->insert_model->new_salon($nombre, $capacidad, $sede, $vigente, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "salon/crear_salon";
            $data['msn_recrear'] = "Crear otro Salón";
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                $this->parser->parse('trans_success', $data);
            }
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }    
    
    
}
