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
        $this->isLogin($data["tab"]);        
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');

        $data['empleado'] = $this->select_model->empleado_sedes_responsable($id_responsable, $dni_responsable);
        $data['t_ausencia'] = $this->select_model->t_ausencia();


        $data['action_validar'] = base_url() . "ausencia_laboral/validar";
        $data['action_crear'] = base_url() . "ausencia_laboral/insertar";
        $this->parser->parse('ausencia_laboral/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
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
                echo form_error('empleado') . form_error('fecha_inicio') . form_error('fecha_fin') . $error_entre_fechas . form_error('t_ausencia') . form_error('descripcion');
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
            list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin = $this->input->post('fecha_fin');
            $t_ausencia = $this->input->post('t_ausencia');
            $descripcion = ucfirst(mb_strtolower($this->input->post('descripcion')));
            
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');

            $data["tab"] = "crear_ausencia_laboral";
            $this->isLogin($data["tab"]);            
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "ausencia_laboral/crear";
            $data['msn_recrear'] = "Crear otra Ausencia Laboral";

            $error = $this->insert_model->ausencia_laboral($id_empleado, $dni_empleado, $fecha_inicio, $fecha_fin, $t_ausencia, 1, $descripcion, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                //Enviamos Correo de notificación
                $empleado = $this->select_model->empleado($id_empleado, $dni_empleado);
                $tipo_ausencia = $this->select_model->t_ausencia_id($t_ausencia);
                if ($empleado->genero == 'M') {
                    $prefijo = "Sr.";
                } else {
                    $prefijo = "Sra.";
                }
                $asunto = "Notificación de ausencia laboral";
                $email = $empleado->email;
                $mensaje = '<p>' . $prefijo . ' ' . $empleado->nombre1 . ' ' . $empleado->nombre2 . ' ' . $empleado->apellido1 . ' ' . $empleado->apellido2 . '</p>'
                        . '<p>Le notificamos que en el sistema, fue ingresada una ausencia laboral a su nombre.<br/>'
                        . '<center>'
                        . '<table>'
                        . '<tr>'
                        . '<td style="width:170px;"><b>Fecha inicial: </b></td>'
                        . '<td>' . $fecha_inicio . '</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td><b>Fecha final: </b></td>'
                        . '<td>' . $fecha_fin . '</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td><b>Tipo de ausencia: </b></td>'
                        . '<td>' . $tipo_ausencia->tipo . ' (' . $tipo_ausencia->salarial . ')</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td><b>Descipción: </b></td>'
                        . '<td>' . $descripcion . '</td>'
                        . '</tr>'
                        . '</table>'
                        . '</center>'
                        . '<br/><p>Para garantizar la seguridad de su cuenta, recuerde modificar periódicamente su contraseña de ingreso al sistema, a través de la opción: Opciones de usuario > Cambiar contraseña.</p>'
                        . '<center><br/>¡Gracias por estar con nosostros!</center>';
                $this->sendEmail("silisascolombia@gmail.com", $email, $asunto, $mensaje);
                //Cargamos mensaje de Ok                 
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
        $data["tab"] = "consultar_ausencia_laboral";
        $this->isLogin($data["tab"]);        
        $data['tipos_documentos'] = $this->t_dnim->listar_todas_los_tipos_de_documentos();
        $data['tipos_ausencias'] = $this->t_ausenciam->listar_tiopos_de_ausencia();
        $data['lista_sedes'] = $this->sedem->listar_todas_las_sedes();


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
