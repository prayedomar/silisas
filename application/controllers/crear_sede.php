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

class crear_sede extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
        $this->load->view('header');
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['pais'] = $this->select_model->pais();
        $data['provincia'] = $this->select_model->provincia();
        $data['ciudad'] = $this->select_model->ciudad();
        $data['est_sede'] = $this->select_model->est_sede();
        $data['action_validar'] = base_url() . "crear_sede/validar_sede";
        $data['action_crear'] = base_url() . "crear_sede/new_sede";
        $data['action_llena_provincia'] = base_url() . "crear_sede/llena_provincia";
        $data['action_llena_ciudad'] = base_url() . "crear_sede/llena_ciudad";
        $this->parser->parse('crear_sede', $data);
        $this->load->view('footer');
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
    

    function validar_sede() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('nombre', 'Nombre de la Sede', 'required|trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('direccion', 'Direccion', 'required|trim|xss_clean|max_length[80]');
            $this->form_validation->set_rules('pais', 'País', 'required|callback_select_default');
            $this->form_validation->set_rules('provincia', 'Departamento', 'required|callback_select_default');
            $this->form_validation->set_rules('ciudad', 'Ciudad', 'required|callback_select_default');
            $this->form_validation->set_rules('estado', 'Estado', 'required|callback_select_default');
            $this->form_validation->set_rules('tel1', 'Telefono 1', 'trim|xss_clean|min_length[7]|max_length[40]');
            $this->form_validation->set_rules('tel2', 'Telefono 2', 'trim|xss_clean|min_length[7]|max_length[40]');
            $this->form_validation->set_rules('prefijo_trans', 'Prefijo para Transacciones', 'required|trim|xss_clean|max_length[4]');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');

            //Validamos que el prefijo no exista
            $duplicate_key = "";
            if ($this->input->post('prefijo_trans')) {
                $check_prefijo = $this->select_model->sede_prefijo(strtoupper($this->input->post('prefijo_trans')));
                if ($check_prefijo == TRUE) {
                    $duplicate_key = "<p>El Prefijo ingresado, ya existe en la Base de Datos.</p>";
                }
            }
            if (($this->form_validation->run() == FALSE) || ($duplicate_key != "")) {
                echo form_error('nombre') . form_error('pais') . form_error('provincia') . form_error('ciudad') . form_error('estado') . form_error('direccion') . form_error('tel1') . form_error('tel2') . form_error('prefijo_trans') . $duplicate_key . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    function new_sede() {
        //si se ha pulsado el botón submit validamos el formulario con codeIgniter
        //Esto es muy importante, porq de lo contrario, podrian haber accedido aqui por la url directamente y daria error porq no vienen datos.
        if ($this->input->post('submit')) {
            $id_sede = ($this->select_model->nextId_sede()->id) + 1;
            $nombre = ucwords(strtolower($this->input->post('nombre')));
            $pais = $this->input->post('pais');
            $provincia = $this->input->post('provincia');
            $ciudad = $this->input->post('ciudad');
            $direccion = ucwords(strtolower($this->input->post('direccion')));
            $tel1 = strtolower($this->input->post('tel1'));
            $tel2 = strtolower($this->input->post('tel2'));
            $prefijo_trans = strtoupper($this->input->post('prefijo_trans'));
            $estado = $this->input->post('estado');
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $error = $this->insert_model->new_sede($id_sede, $nombre, $pais, $provincia, $ciudad, $direccion, $tel1, $tel2, $prefijo_trans, $estado, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "crear_sede";
            $data['msn_recrear'] = "Crear otra Sede";
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                //Hay que autorizar las sedes que se creen al sistema para que las pueda autorizar
                $this->insert_model->empleado_x_sede(1, 1, $id_sede);
                $this->parser->parse('trans_success', $data);
            }
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    public function llena_provincia() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('pais')) && ($this->input->post('pais') != '{id}') && ($this->input->post('pais') != 'default')) {
                $pais = $this->input->post('pais');
                $provincias = $this->select_model->provincia_pais($pais);
                if ($provincias == TRUE) {
                    foreach ($provincias as $fila) {
                        echo '<option value="' . $fila->id . '">' . $fila->nombre . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    public function llena_ciudad() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('provincia')) && ($this->input->post('provincia') != '{id}') && ($this->input->post('provincia') != 'default')) {
                $provincia = $this->input->post('provincia');
                $ciudades = $this->select_model->ciudad_provincia($provincia);
                if ($ciudades == TRUE) {
                    foreach ($ciudades as $fila) {
                        echo '<option value="' . $fila->id . '">' . $fila->nombre . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

}
