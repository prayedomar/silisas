<?php

class Ausencia_laboral extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function crear() {
        $data["tab"] = "crear_ausencia_laboral";
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];

        $data['empleado'] = $this->select_model->empleado_sedes_responsable($id_responsable, $dni_responsable);
        $data['t_ausencia'] = $this->select_model->t_ausencia();


        $data['action_validar'] = base_url() . "ausencia_laboral/validar";
        $data['action_crear'] = base_url() . "ausencia_laboral/insertar";
        $this->parser->parse('ausencia_laboral/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('empleado', 'Empleado', 'required|callback_select_default');
            $this->form_validation->set_rules('fecha_inicio', 'Fecha Inicial', 'required|xss_clean|callback_fecha_valida');
            $this->form_validation->set_rules('fecha_fin', 'Fecha Final', 'required|xss_clean|callback_fecha_valida');
            $this->form_validation->set_rules('t_ausencia', 't_ausencia', 'required|callback_select_default');
            $this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[255]');
            $error_entre_fechas = "";
            if (($this->fecha_valida($this->input->post('fecha_inicio'))) && ($this->fecha_valida($this->input->post('fecha_fin')))) {
                if (($this->dias_entre_fechas($this->input->post('fecha_inicio'), $this->input->post('fecha_fin'))) < 0) {
                    $error_entre_fechas = "<p>La fecha final no puede ser menor que la fecha inicial.</p>";
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_entre_fechas != "")) {
                echo form_error('empleado') . form_error('fecha_inicio') . form_error('fecha_fin') . $error_entre_fechas . form_error('t_ausencia') . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function insertar() {
        if ($this->input->post('submit')) {
            list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin = $this->input->post('fecha_fin');
            $t_ausencia = $this->input->post('t_ausencia');
            $descripcion = ucfirst(strtolower($this->input->post('descripcion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');


            $error = $this->insert_model->ausencia_laboral($id_empleado, $dni_empleado, $fecha_inicio, $fecha_fin, $t_ausencia, 1, $descripcion, $fecha_trans, $id_responsable, $dni_responsable);

            $data["tab"] = "crear_ausencia_laboral";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "ausencia_laboral/crear";
            $data['msn_recrear'] = "Crear otra Ausencia Laboral";
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

    //Metodos para Consultar    
    function consultar() {
         $this->load->model('ausencia_laboralm');
        $this->load->model('t_dnim');
        $this->load->model('est_empleadom');
        $this->load->model('sedem');
        $this->load->model('t_ausenciam');
        $data["tab"] = "ausencia_laboral";
        $data['tipos_documentos'] = $this->t_dnim->listar_todas_los_tipos_de_documentos();
        $data['tipos_ausencias'] = $this->t_ausenciam->listar_tiopos_de_ausencia();



        $filasPorPagina = 20;
        if (empty($_GET["page"])) {
            $inicio = 0;
            $paginaActual = 1;
        } else {
            $inicio = ($_GET["page"] - 1) * $filasPorPagina;
            $paginaActual = $_GET["page"];
        }
        $data['paginaActiva'] = $paginaActual;
        $cantidad_empleados = $this->ausencia_laboralm->cantidad_ausencias($_GET, $inicio, $filasPorPagina);
        $cantidad_empleados = $cantidad_empleados[0]->cantidad;
        $data['cantidad_empleados'] = $cantidad_empleados;
        $data['cantidad_paginas'] = ceil($cantidad_empleados / $filasPorPagina);
        $data["lista_empleados"] = $this->ausencia_laboralm->listar_ausencias($_GET, $inicio, $filasPorPagina);
        $this->load->view("header", $data);
        $this->load->view("ausencia_laboral/consultar");
        $this->load->view("footer");
    }

}
