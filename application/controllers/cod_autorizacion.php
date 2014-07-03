<?php

class Cod_autorizacion extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    function crear() {
        $data["tab"] = "crear_cod_autorizacion";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $this->load->model('tabla_autorizacionm');
        $this->load->model('select_model');
        $data['tabla_autorizacion'] = $this->tabla_autorizacionm->tabla_autorizacion_perfil($_SESSION["perfil"]);
        $data['empleado'] = $this->select_model->empleado_activo();
        $data['action_validar'] = base_url() . "cod_autorizacion/validar";
        $data['action_crear'] = base_url() . "cod_autorizacion/insertar";
        $this->parser->parse('cod_autorizacion/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('empleado', 'Empleado a autorizar', 'required|callback_select_default');
            $this->form_validation->set_rules('tabla_autorizada', 'Tipo de permiso a autorizar', 'required|callback_select_default');
            $this->form_validation->set_rules('registro_autorizado', 'Código del registro que se va a modificar', 'required|trim|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'required|trim|xss_clean|max_length[255]');
            $error_entre_fechas = "";
            if ($this->form_validation->run() == FALSE) {
                echo form_error('empleado') . form_error('tabla_autorizada') . form_error('registro_autorizado') . form_error('observacion');
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
            $this->load->model('insert_model');
            list($id_empleado_autorizado, $dni_empleado_autorizado) = explode("_", $this->input->post('empleado'));
            $tabla_autorizada = $this->input->post('tabla_autorizada');
            $registro_autorizado = $this->input->post('registro_autorizado');
            $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');

            $data["tab"] = "crear_cod_autorizacion";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "cod_autorizacion/consultar";
            $data['msn_recrear'] = "Consultar códigos de autorización";

            $error = $this->insert_model->cod_autorizacion($tabla_autorizada, $registro_autorizado, $id_empleado_autorizado, $dni_empleado_autorizado, $observacion, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                //Cargamos mensaje de Ok                 
                $this->parser->parse('trans_success', $data);
            }
        } else {
            redirect(base_url());
        }
    }

//A continuación: Metodos para consultar    
    public function consultar() {
        $this->load->model('tabla_autorizacionm');
        $this->load->model('cod_autorizacionm');
        $data["tab"] = "consultar_cod_autorizacion";
        $this->isLogin($data["tab"]);
        $data['tabla_autorizacion'] = $this->tabla_autorizacionm->listar_todas_las_tablas();
        $filasPorPagina = 20;
        if (empty($_GET["page"])) {
            $inicio = 0;
            $paginaActual = 1;
        } else {
            $inicio = ($_GET["page"] - 1) * $filasPorPagina;
            $paginaActual = $_GET["page"];
        }
        $data['paginaActiva'] = $paginaActual;
        $cantidadCodigos = $this->cod_autorizacionm->cantidad_cod_criterios($_GET);
        $cantCodigos = $cantidadCodigos[0]->cantidad;
        $data['cantidadCodigos'] = $cantCodigos;
        $data['cantidadPaginas'] = ceil($cantCodigos / $filasPorPagina);
        $data["listaCodigos"] = $this->cod_autorizacionm->listar_cod_criterios($_GET, $inicio, $filasPorPagina);
        $this->load->view("header", $data);
        $this->load->view("cod_autorizacion/consultar");
        $this->load->view("footer");
    }

}
