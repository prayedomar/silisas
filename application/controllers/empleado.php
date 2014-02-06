<?php

class Empleado extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
    }

    function crear() {
        $data["tab"] = "crear_empleado";
        $data['rutaImg'] = $this->session->userdata('rutaImg');
        $this->parser->parse("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['dni'] = $this->select_model->t_dni_empleado();
        $data['est_civil'] = $this->select_model->t_est_civil();
        $data['pais'] = $this->select_model->pais();
        $data['provincia'] = $this->select_model->provincia();
        $data['ciudad'] = $this->select_model->ciudad();
        $data['t_domicilio'] = $this->select_model->t_domicilio();
        $data['jefe'] = $this->select_model->empleado_activo();
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['sede_ppal'] = $this->select_model->sede_activa_responsable($id_responsable, $dni_responsable);
        $data['t_depto'] = $this->select_model->t_depto();
        $data['t_contrato'] = $this->select_model->t_contrato_laboral();

        $data['action_llena_cargo_departamento'] = base_url() . "empleado/llena_cargo_departamento";
        $data['action_llena_jefe_new_empleado'] = base_url() . "empleado/llena_jefe_new_empleado";
        $data['action_llena_provincia'] = base_url() . "empleado/llena_provincia";
        $data['action_llena_ciudad'] = base_url() . "empleado/llena_ciudad";
        $data['action_llena_salario_departamento'] = base_url() . "empleado/llena_salario_departamento";

        $data['action_validar'] = base_url() . "empleado/validar";
        $data['action_crear'] = base_url() . "empleado/insertar";

        $this->parser->parse('empleado/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);            
            $this->form_validation->set_rules('dni', 'Tipo de Identificación', 'required|callback_select_default');
            $this->form_validation->set_rules('id', 'Número de Identificación', 'required|trim|min_length[5]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('nombre1', 'Primer Nombre', 'required|trim|xss_clean|max_length[30]');
            $this->form_validation->set_rules('nombre2', 'Segundo Nombre', 'trim|xss_clean|max_length[30]');
            $this->form_validation->set_rules('apellido1', 'Primer Apellido', 'required|trim|xss_clean|max_length[30]');
            $this->form_validation->set_rules('apellido2', 'Segundo Apellido', 'trim|xss_clean|max_length[30]');
            $this->form_validation->set_rules('fecha_nacimiento', 'Fecha de Nacimiento', 'required|xss_clean|callback_fecha_valida');
            $this->form_validation->set_rules('genero', 'Genero', 'required|callback_select_default');
            $this->form_validation->set_rules('est_civil', 'Estado Civil', 'required|callback_select_default');
            $this->form_validation->set_rules('pais', 'País', 'required|callback_select_default');
            $this->form_validation->set_rules('provincia', 'Departamento', 'required|callback_select_default');
            $this->form_validation->set_rules('ciudad', 'Ciudad', 'required|callback_select_default');
            $this->form_validation->set_rules('t_domicilio', 'Tipo de Domicilio', 'required|callback_select_default');
            $this->form_validation->set_rules('direccion', 'Direccion', 'required|trim|xss_clean|max_length[80]');
            $this->form_validation->set_rules('barrio', 'Barrio/Sector', 'required|trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('telefono', 'Telefono', 'required|trim|xss_clean|min_length[7]|max_length[40]');
            $this->form_validation->set_rules('celular', 'Celular', 'trim|xss_clean|min_length[10]|max_length[40]');
            $this->form_validation->set_rules('email', 'Correo Electrónico', 'required|valid_email|trim|xss_clean|max_length[80]');
            $this->form_validation->set_rules('cuenta', 'Cuenta Bancaria', 'trim|min_length[12]|max_length[12]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('sede_ppal', 'Sede Principal', 'required|callback_select_default');
            $this->form_validation->set_rules('depto', 'Departamento Empresarial', 'required|callback_select_default');
            $this->form_validation->set_rules('cargo', 'Cargo', 'required|callback_select_default');
            $this->form_validation->set_rules('salario', 'Salario', 'required|callback_select_default');
            $this->form_validation->set_rules('jefe', 'Jefe Inmediato', 'required|callback_select_default');
            $this->form_validation->set_rules('t_contrato', 'Tipo de Contrato Laboral', 'required|callback_select_default');
            $this->form_validation->set_rules('fecha_inicio', 'Fecha Inicio de Labores', 'required|xss_clean|callback_fecha_valida');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');

            if ((($this->input->post('t_contrato') != "default")) && (($this->input->post('t_contrato') != "1"))) {
                $this->form_validation->set_rules('cant_meses', 'Duración en Meses', 'required|callback_select_default');
            }

            //Validamos que la clave primaria no este repetida
            $duplicate_key = "";
            if (($this->input->post('id')) && ($this->input->post('dni'))) {
                $t_usuario = 1; //1: Empleado
                $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id'), $this->input->post('dni'), $t_usuario);
                if ($check_usuario == TRUE) {
                    $duplicate_key = "<p>La Identificación ingresada ya existe en la Base de Datos.</p>";
                }
            }
            if (($this->form_validation->run() == FALSE) || ($duplicate_key != "")) {
                echo $duplicate_key . form_error('dni') . form_error('id') . form_error('nombre1') . form_error('nombre2') . form_error('apellido1') . form_error('apellido2') . form_error('fecha_nacimiento') . form_error('genero') . form_error('est_civil') . form_error('pais') . form_error('provincia') . form_error('ciudad') . form_error('t_domicilio') . form_error('direccion') . form_error('barrio') . form_error('telefono') . form_error('celular') . form_error('email') . form_error('cuenta') . form_error('sede_ppal') . form_error('depto') . form_error('cargo') . form_error('salario') . form_error('jefe') . form_error('t_contrato') . form_error('fecha_inicio') . form_error('cant_meses') . form_error('observacion');
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
            $id = $this->input->post('id');
            $dni = $this->input->post('dni');
            $nombre1 = ucwords(strtolower($this->input->post('nombre1')));
            $nombre2 = ucwords(strtolower($this->input->post('nombre2')));
            $apellido1 = ucwords(strtolower($this->input->post('apellido1')));
            $apellido2 = ucwords(strtolower($this->input->post('apellido2')));
            $fecha_nacimiento = $this->input->post('fecha_nacimiento');
            $genero = $this->input->post('genero');
            $est_civil = $this->input->post('est_civil');
            $pais = $this->input->post('pais');
            $provincia = $this->input->post('provincia');
            $ciudad = $this->input->post('ciudad');
            $t_domicilio = $this->input->post('t_domicilio');
            $direccion = ucwords(strtolower($this->input->post('direccion')));
            $barrio = ucwords(strtolower($this->input->post('barrio')));
            $telefono = strtolower($this->input->post('telefono'));
            $celular = $this->input->post('celular');
            $email = strtolower($this->input->post('email'));
            $cuenta = $this->input->post('cuenta');
            $sede_ppal = $this->input->post('sede_ppal');
            $depto = $this->input->post('depto');
            list($cargo, $perfil) = explode("-", $this->input->post('cargo'));
            $salario = $this->input->post('salario');
            list($id_jefe, $dni_jefe) = explode("-", $this->input->post('jefe'));
            $t_contrato = $this->input->post('t_contrato');
            $fecha_inicio = $this->input->post('fecha_inicio');
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            if ($this->input->post('t_contrato') != "1") {
                $cant_meses = $this->input->post('cant_meses');
                $fecha_fin = date("Y-m-d", strtotime("$fecha_inicio +$cant_meses month"));
            } else {
                $cant_meses = NULL;
                $fecha_fin = NULL;
            }

            $password = $this->encrypt->encode($id);
            $vigente = 1;
            $t_usuario = 1; //Empleado
            $error1 = $this->insert_model->new_usuario($id, $dni, $t_usuario, $password, $perfil, $vigente);

            $data['rutaImg'] = $this->session->userdata('rutaImg');
            $data['msnBienvenida'] = $this->session->userdata('msnBienvenida');
            $data['textoBienvenida'] = $this->session->userdata('textoBienvenida');
            $data['base_url'] = base_url();
            $this->parser->parse('header', $data);            
            
            $data['url_recrear'] = base_url() . "empleado/crear";
            $data['msn_recrear'] = "Crear otro Empleado";
            //No se pudo crear el usuario
            if (isset($error1)) {
                $data['trans_error'] = $error1;
                $this->parser->parse('trans_error', $data);
            } else {
                $error2 = $this->insert_model->new_empleado($id, $dni, $t_usuario, $nombre1, $nombre2, $apellido1, $apellido2, $fecha_nacimiento, $genero, $est_civil, $pais, $provincia, $ciudad, $t_domicilio, $direccion, $barrio, $telefono, $celular, $email, $cuenta, $sede_ppal, $depto, $cargo, $salario, $id_jefe, $dni_jefe, 1, $observacion, $fecha_trans, $id_responsable, $dni_responsable);
                //No se pudo crear el empleado
                if (isset($error2)) {
                    $data['trans_error'] = $error2;
                    $this->parser->parse('trans_error', $data);
                } else {
                    //Creamos el contrato laboral
                    $error3 = $this->insert_model->contrato_laboral($id, $dni, $t_contrato, $cant_meses, $fecha_inicio, $fecha_fin, 1, $observacion, $fecha_trans, $id_responsable, $dni_responsable);
                    if (isset($error3)) {
                        $data['trans_error'] = $error3;
                        $this->parser->parse('trans_error', $data);
                    } else {
                        $this->parser->parse('trans_success', $data);
                    }
                }
            }
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url());
        }
    }

    public function llena_cargo_departamento() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('depto')) {
                $depto = $this->input->post('depto');
                $t_cargo = $this->select_model->cargo_depto($depto);
                //Validamos que las dos consultas devuelvan algo
                if ($t_cargo == TRUE) {
                    foreach ($t_cargo as $fila) {
                        echo '<option value="' . $fila->id . '-' . $fila->perfil . '">' . $fila->cargo_masculino . '</option>';
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

    public function llena_jefe_new_empleado() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('cargo')) && ($this->input->post('sedePpal')) && ($this->input->post('depto'))) {
                list($cargo, $perfil) = explode("-", $this->input->post('cargo'));
                $sede_ppal = $this->input->post('sedePpal');
                $depto = $this->input->post('depto');
                $jefes = $this->select_model->empleado_jefe_faltante_sede_depto_cargo($sede_ppal, $depto, $cargo);
                //Validamos que la consulta devuelva algo
                if ($jefes == TRUE) {
                    foreach ($jefes as $fila) {
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

    //Llenar elementos html dinamicamente
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

    public function llena_salario_departamento() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('depto')) {
                $depto = $this->input->post('depto');
                $salarios = $this->select_model->salario_t_salario_x_t_depto($depto);
                if ($salarios == TRUE) {
                    foreach ($salarios as $fila) {
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

//A continuación: Metodos para consultar  
}