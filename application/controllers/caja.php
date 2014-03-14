<?php

class Caja extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('cajam');
    }

    function crear() {
        $data["tab"] = "crear_caja";
        $this->isLogin($data["tab"]);        
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
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
            $this->escapar($_POST);
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
            $this->escapar($_POST);
            $sede = $this->input->post('sede');
            $t_caja = $this->input->post('t_caja');
            list($id_encargado, $dni_encargado) = explode("-", $this->input->post('empleado'));
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');

            $data["tab"] = "crear_caja";
            $this->isLogin($data["tab"]);               
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "caja/crear";
            $data['msn_recrear'] = "Crear otra Caja (Punto de Venta)";

            $error = $this->insert_model->caja($sede, $t_caja, $id_encargado, $dni_encargado, 1, $observacion, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
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
            $this->escapar($_POST);
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
        $this->escapar($_POST);
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

    public function consultar() {
        $this->load->model('t_dnim');
        $this->load->model('sedem');
        $this->load->model('t_cajam');

        $data["tab"] = "consultar_caja";
        $this->isLogin($data["tab"]);        
        $data['lista_sedes'] = $this->sedem->listar_todas_las_sedes();
        $data['tipos_documentos'] = $this->t_dnim->listar_todas_los_tipos_de_documentos();
        $data['tipos_cajas'] = $this->t_cajam->listar_tipos_de_caja();

        $filasPorPagina = 20;
        if (empty($_GET["page"])) {
            $inicio = 0;
            $paginaActual = 1;
        } else {
            $inicio = ($_GET["page"] - 1) * $filasPorPagina;
            $paginaActual = $_GET["page"];
        }
        $data['paginaActiva'] = $paginaActual;
        $cantidad = $this->cajam->cantidad_cajas($_GET, $inicio, $filasPorPagina);
        $cantidad = $cantidad[0]->cantidad;
        $data['cantidad'] = $cantidad;
        $data['cantidad_paginas'] = ceil($cantidad / $filasPorPagina);
        $data["lista"] = $this->cajam->listar_cajas($_GET, $inicio, $filasPorPagina);
        $this->load->view("header", $data);
        $this->load->view("caja/consultar");
        $this->load->view("footer");
    }

}
