<?php

class Salon extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('salonm');
    }

    function crear() {
        $data["tab"] = "crear_salon";
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['sede'] = $this->select_model->sede_activa_responsable($id_responsable, $dni_responsable);
        $data['action_validar'] = base_url() . "salon/validar";
        $data['action_crear'] = base_url() . "salon/insertar";
        $this->parser->parse('salon/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
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
            redirect(base_url());
        }
    }

    function insertar() {
        //si se ha pulsado el botón submit validamos el formulario con codeIgniter
        //Esto es muy importante, porq de lo contrario, podrian haber accedido aqui por la url directamente y daria error porq no vienen datos.
        if ($this->input->post('submit')) {
            $this->escapar($_POST);
            $nombre = ucwords(strtolower($this->input->post('nombre')));
            $capacidad = $this->input->post('capacidad');
            $sede = $this->input->post('sede');
            $vigente = $this->input->post('vigente');
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $error = $this->insert_model->new_salon($nombre, $capacidad, $sede, $vigente, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            $data["tab"] = "crear_salon";
            $this->load->view("header", $data);

            $data['url_recrear'] = base_url() . "salon/crear";
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
            redirect(base_url());
        }
    }

//A continuación: Metodos para consultar    
    public function consultar() {
        $this->load->model('sedem');
        $data["tab"] = "consultar_salon";
        $data['listaSedes'] = $this->sedem->listar_todas_las_sedes();
        $filasPorPagina = 20;
        if (empty($_GET["page"])) {
            $inicio = 0;
            $paginaActual = 1;
        } else {
            $inicio = ($_GET["page"] - 1) * $filasPorPagina;
            $paginaActual = $_GET["page"];
        }
        $data['paginaActiva'] = $paginaActual;
        $cantidadSalones = $this->salonm->cantidad_salones($_GET, $inicio, $filasPorPagina);
        $cantidadSalones = $cantidadSalones[0]->cantidad;
        $data['cantidadSalones'] = $cantidadSalones;
        $data['cantidadPaginas'] = ceil($cantidadSalones / $filasPorPagina);
        $data["listaSalones"] = $this->salonm->listar_salones($_GET, $inicio, $filasPorPagina);
        $this->load->view("header", $data);
        $this->load->view("salon/consultar");
        $this->load->view("footer");
    }

}
