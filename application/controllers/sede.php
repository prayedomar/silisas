<?php

class Sede extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('sedem');
        $this->load->model('paism');
        $this->load->model('provinciam');
        $this->load->model('ciudadm');
        $this->load->model('select_model');
        $this->load->model('insert_model');
    }

//    Metodos para crear
    function crear() {
        $data["tab"] = "crear_sede";
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['pais'] = $this->select_model->pais();
        $data['provincia'] = $this->select_model->provincia();
        $data['ciudad'] = $this->select_model->ciudad();
        $data['est_sede'] = $this->select_model->est_sede();
        $data['action_validar'] = base_url() . "sede/validar";
        $data['action_crear'] = base_url() . "sede/insertar";
        $data['action_llena_provincia'] = base_url() . "sede/llena_provincia";
        $data['action_llena_ciudad'] = base_url() . "sede/llena_ciudad";
        $this->parser->parse('sede/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
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
            redirect(base_url());
        }
    }

    function insertar() {
        $this->escapar($_POST);
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

            $data["tab"] = "crear_sede";
            $this->load->view("header", $data);

            $data['url_recrear'] = base_url() . "sede/crear";
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
            redirect(base_url());
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
            redirect(base_url());
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
            redirect(base_url());
        }
    }

    //Metodos para Consultar
    function consultar() {
        $this->load->model('est_sedem');
        $data["tab"] = "consultar_sede";
        $data["paises"] = $this->paism->listar_paises();
        if (!empty($_GET["pais"])) {
            $data["departamentos"] = $this->provinciam->listarProvinciasPorPais($_GET['pais']);
        }
        if (!empty($_GET["departamento"])) {
            $data["ciudades"] = $this->ciudadm->listarCiudadesPorProvicia($_GET['departamento']);
        }
        $data["estados"] = $this->est_sedem->listar_estatus();

        $filasPorPagina = 20;
        if (empty($_GET["page"])) {
            $inicio = 0;
            $paginaActual = 1;
        } else {
            $inicio = ($_GET["page"] - 1) * $filasPorPagina;
            $paginaActual = $_GET["page"];
        }
        $data['paginaActiva'] = $paginaActual;
        $cantidadSedes = $this->sedem->cantidadSedes($_GET, $inicio, $filasPorPagina);
        $cantidadSedes = $cantidadSedes[0]->cantidad;
        $data['cantidadSedes'] = $cantidadSedes;
        $data['cantidadPaginas'] = ceil($cantidadSedes / $filasPorPagina);
        $data["lista_sedes"] = $this->sedem->listar_sedes($_GET, $inicio, $filasPorPagina);
        $this->load->view("header", $data);
        $this->load->view("sede/consultar");
        $this->load->view("footer");
    }

    function listar_departamentos() {
        $this->escapar($_GET);
        echo json_encode($this->provinciam->listarProvinciasPorPais($_GET['idPais']));
    }

    function listar_ciudades() {
        $this->escapar($_GET);
        echo json_encode($this->ciudadm->listarCiudadesPorProvicia($_GET['idDepartamento']));
    }

}
