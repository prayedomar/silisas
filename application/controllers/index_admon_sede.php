<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index_admon_sede extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
    }

    public function index() {
        $data = $this->navbar();
        $this->parser->parse('welcome', $data);
        $this->load->view('footer');
    }

    //este metodo es para quitar el mensaje de bienvenida al index
    public function navbar() {
        if ($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != 'admon_sede') {
            redirect(base_url() . 'login');
        }
        $usuario = $this->select_model->empleado($this->session->userdata('dni'), $this->session->userdata('id'));
        if (file_exists("images/photos/" . $this->session->userdata('dni') . $this->session->userdata('id') . ".jpg")) {
            $data['rutaImg'] = base_url() . "images/photos/" . $this->session->userdata('dni') . $this->session->userdata('id') . ".jpg";
        } else {
            if ($usuario->genero == 'M') {
                $data['rutaImg'] = base_url() . "images/photos/default_men.png";
            } else {
                $data['rutaImg'] = base_url() . "images/photos/default_woman.png";
            }
        }
        if ($usuario->genero == 'M') {
            $data['welcome'] = 'Bienvenido ' . $usuario->nombre1 . ' ' . $usuario->nombre2 . '!';
        } else {
            $data['welcome'] = 'Bienvenida ' . $usuario->nombre1 . ' ' . $usuario->nombre2 . '!';
        }
        $data['base_url'] = base_url();
        $data['id_responsable'] = $usuario->id;
        $data['dni_responsable'] = $usuario->dni;
        $this->parser->parse('index_admon_sede', $data);
        return $data;
    }

    function crear_sede() {
        $data = $this->navbar();
        $data['nombre_old'] = $this->session->flashdata('nombre_old');
        $data['direccion_old'] = $this->session->flashdata('direccion_old');
        $data['tel1_old'] = $this->session->flashdata('tel1_old');
        $data['tel2_old'] = $this->session->flashdata('tel2_old');
        $data['observacion_old'] = $this->session->flashdata('observacion_old');
        $data['pais'] = $this->select_model->pais();
        $data['provincia'] = $this->select_model->provincia();
        $data['ciudad'] = $this->select_model->ciudad();
        $data['est_sede'] = $this->select_model->est_sede();
        $data['action'] = base_url()."index_admon_sede/new_sede";        
        $this->parser->parse('crear_sede', $data);
        if ($this->session->flashdata('usuario_incorrecto')) {
            $error_flash['error'] = $this->session->flashdata('usuario_incorrecto');
            $this->parser->parse('login_alert', $error_flash);
        }
        $this->parser->parse('crear_sede_2', $data);
    }

    //funcion para procesar el formulario
    function new_sede() {
        //si se ha pulsado el botón submit validamos el formulario con codeIgniter
        //Esto es muy importante, porq de lo contrario, podrian haber accedido aqui por la url directamente y daria error porq no vienen datos.
        if ($this->input->post('submit')) {
            $this->session->set_flashdata('nombre_old', $this->input->post('nombre'));
            $this->session->set_flashdata('direccion_old', $this->input->post('direccion'));
            $this->session->set_flashdata('tel1_old', $this->input->post('tel1'));
            $this->session->set_flashdata('tel2_old', $this->input->post('tel2'));
            $this->session->set_flashdata('observacion_old', $this->input->post('observacion'));

            //hacemos las comprobaciones que deseemos en nuestro formulario
            $this->form_validation->set_rules('nombre', 'nombre', 'required|trim|xss_clean');
            $this->form_validation->set_rules('direccion', 'direccion', 'required|trim|xss_clean');
            $this->form_validation->set_rules('tel1', 'tel1', 'trim|xss_clean|min_length[7]');
            $this->form_validation->set_rules('tel2', 'tel2', 'trim|xss_clean|min_length[7]');
            $this->form_validation->set_rules('observacion', 'observacion', 'trim|xss_clean');


            if ($this->form_validation->run() == FALSE) {
                echo "entro al error form_validation";
                //si no pasamos la validación volvemos al formulario mostrando los errores
                $this->session->set_flashdata('usuario_incorrecto', form_error('nombre') . form_error('direccion') . form_error('tel1') . form_error('tel2') . form_error('observacion'));
                redirect(base_url() . 'index_admon_sede/crear_sede');
                //$this->index();
            }
            //si pasamos la validación correctamente pasamos a hacer la inserción en la base de datos
            else {
                $this->session->set_flashdata('nombre_old', $this->input->post('nombre'));
                $this->session->set_flashdata('direccion_old', $this->input->post('direccion'));
                $this->session->set_flashdata('tel1_old', $this->input->post('tel1'));
                $this->session->set_flashdata('tel2_old', $this->input->post('tel2'));
                $this->session->set_flashdata('observacion_old', $this->input->post('observacion'));
                $id = 
                $nombre = $this->input->post('nombre');
                $pais = $this->input->post('pais');
                $provincia = $this->input->post('provincia');
                $ciudad = $this->input->post('ciudad');
                $direccion = $this->input->post('direccion');
                $tel1 = $this->input->post('tel1');
                $tel2 = $this->input->post('tel2');
                $estado = $this->input->post('estado');
                $observacion = $this->input->post('observacion');
                $fecha_trans = date('Y-m-d')." ".date("H:i:s");
                $id_responsable = $this->input->post('id_responsable');
                $dni_responsable = $this->input->post('dni_responsable');
 
                $check_new_sede = $this->insert_model->new_sede($id, $nombre, $pais, $provincia, $ciudad, $direccion, $tel1, $tel2, $estado, $observacion, $fecha_trans, $id_responsable, $dni_responsable);
                
                
            }
        }
    }

}
