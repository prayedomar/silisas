<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index_directivo extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    public function index() {
        $data = $this->navbar();
        $this->parser->parse('welcome', $data);
        $this->load->view('footer');
    }

    //este metodo es para quitar el mensaje de bienvenida al index
    public function navbar() {
        if ($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != 'directivo') {
            redirect(base_url() . 'login');
        }
        $usuario = $this->select_model->empleado($this->session->userdata('id'), $this->session->userdata('dni'));
        if (file_exists("images/photos/" . $this->session->userdata('id') . $this->session->userdata('dni') . ".jpg")) {
            $data['rutaImg'] = base_url() . "images/photos/" . $this->session->userdata('id') . $this->session->userdata('dni') . ".jpg";
        } else {
            if ($usuario->genero == 'M') {
                $data['rutaImg'] = base_url() . "images/photos/default_men.png";
            } else {
                $data['rutaImg'] = base_url() . "images/photos/default_woman.png";
            }
        }
        if ($usuario->genero == 'M') {
            $data['welcome'] = ucwords('Bienvenido ' . $usuario->nombre1 . ' ' . $usuario->nombre2 . '!');
        } else {
            $data['welcome'] = ucwords('Bienvenida ' . $usuario->nombre1 . ' ' . $usuario->nombre2 . '!');
        }
        $data['base_url'] = base_url();
        $data['id_responsable'] = $usuario->id;
        $data['dni_responsable'] = $usuario->dni;
        $this->parser->parse('index_admon_sistema', $data);
        return $data;
    }

    //Crear: Sede
    function crear_sede() {
        $data = $this->navbar();
        $data['pais'] = $this->select_model->pais();
        $data['provincia'] = $this->select_model->provincia();
        $data['ciudad'] = $this->select_model->ciudad();
        $data['est_sede'] = $this->select_model->est_sede();
        $data['action_validar'] = base_url() . "index_admon_sistema/validar_sede";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_sede";
        $this->parser->parse('crear_sede', $data);
    }

    function validar_sede() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('direccion', 'Direccion', 'required|trim|xss_clean|max_length[80]');
            $this->form_validation->set_rules('pais', 'País', 'required|callback_select_default');
            $this->form_validation->set_rules('estado', 'Estado', 'required|callback_select_default');
            $this->form_validation->set_rules('tel1', 'Telefono 1', 'trim|xss_clean|min_length[7]|max_length[40]');
            $this->form_validation->set_rules('tel2', 'Telefono 2', 'trim|xss_clean|min_length[7]|max_length[40]');
            $this->form_validation->set_rules('observacion', 'observacion', 'trim|xss_clean|max_length[255]');
            if ($this->form_validation->run() == FALSE) {
                echo form_error('nombre') . form_error('pais') . form_error('estado') . form_error('direccion') . form_error('tel1') . form_error('tel2') . form_error('observacion');
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
            $nombre = strtolower($this->input->post('nombre'));
            $pais = $this->input->post('pais');
            $provincia = $this->input->post('provincia');
            $ciudad = $this->input->post('ciudad');
            $direccion = strtolower($this->input->post('direccion'));
            $tel1 = strtolower($this->input->post('tel1'));
            $tel2 = strtolower($this->input->post('tel2'));
            $estado = $this->input->post('estado');
            $observacion = strtolower($this->input->post('observacion'));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $error = $this->insert_model->new_sede($nombre, $pais, $provincia, $ciudad, $direccion, $tel1, $tel2, $estado, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "index_admon_sistema/crear_sede";
            $data['msn_recrear'] = "Crear otra sede";
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

    //Crear: Salon
    function crear_salon() {
        $data = $this->navbar();
        $data['sede'] = $this->select_model->sede_activa();
        $data['action_validar'] = base_url() . "index_admon_sistema/validar_salon";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_salon";
        $this->parser->parse('crear_salon', $data);
    }

    function validar_salon() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('capacidad', 'Capacidad', 'required|trim|max_length[2]|integer');
            $this->form_validation->set_rules('sede', 'Sede', 'required|callback_select_default');
            $this->form_validation->set_rules('vigente', 'Vigente', 'required|callback_select_default');
            $this->form_validation->set_rules('observacion', 'observacion', 'trim|xss_clean|max_length[255]');

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
            $nombre = strtolower($this->input->post('nombre'));
            $capacidad = $this->input->post('capacidad');
            $sede = $this->input->post('sede');
            $vigente = $this->input->post('vigente');
            $observacion = strtolower($this->input->post('observacion'));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $error = $this->insert_model->new_salon($nombre, $capacidad, $sede, $vigente, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "index_admon_sistema/crear_salon";
            $data['msn_recrear'] = "Crear otro salón";
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

    //Crear: Empleado
    function crear_empleado() {
        $data = $this->navbar();
        $data['dni'] = $this->select_model->t_dni_empleado();
        $data['est_civil'] = $this->select_model->t_est_civil();
        $data['pais'] = $this->select_model->pais();
        $data['provincia'] = $this->select_model->provincia();
        $data['ciudad'] = $this->select_model->ciudad();
        $data['t_domicilio'] = $this->select_model->t_domicilio();
        $data['cargo'] = $this->select_model->t_cargo();
        $data['jefe'] = $this->select_model->empleado_activo();
        $data['estado'] = $this->select_model->est_empleado();
        $data['sede_ppal'] = $this->select_model->sede_activa();
        $data['action_validar'] = base_url() . "index_admon_sistema/validar_empleado";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_empleado";
        $this->parser->parse('crear_empleado', $data);
    }

    function validar_empleado() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('dni', 'Tipo de Documento', 'required|callback_select_default');
            $this->form_validation->set_rules('id', 'Número de Documento', 'required|trim|min_length[5]|max_length[13]|integer');
            $this->form_validation->set_rules('nombre1', 'Primer Nombre', 'required|trim|xss_clean|max_length[30]');
            $this->form_validation->set_rules('nombre2', 'Segundo Nombre', 'trim|xss_clean|max_length[30]');
            $this->form_validation->set_rules('apellido1', 'Primer Apellido', 'required|trim|xss_clean|max_length[30]');
            $this->form_validation->set_rules('apellido2', 'Segundo Apellido', 'trim|xss_clean|max_length[30]');
            $this->form_validation->set_rules('fecha_nacimiento', 'Fecha de Nacimiento', 'required|xss_clean|exact_length[10]');
            $this->form_validation->set_rules('genero', 'Genero', 'required|callback_select_default');
            $this->form_validation->set_rules('est_civil', 'Estado Civil', 'required|callback_select_default');
            $this->form_validation->set_rules('pais', 'País', 'required|callback_select_default');
            $this->form_validation->set_rules('t_domicilio', 'Tipo de Domicilio', 'required|callback_select_default');
            $this->form_validation->set_rules('direccion', 'Direccion', 'required|trim|xss_clean|max_length[80]');
            $this->form_validation->set_rules('barrio', 'Barrio/Sector', 'required|trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('telefono', 'Telefono', 'required|trim|xss_clean|min_length[7]|max_length[40]');
            $this->form_validation->set_rules('celular', 'Celular', 'trim|xss_clean|min_length[10]|max_length[40]');
            $this->form_validation->set_rules('email', 'Correo Electrónico', 'required|valid_email|trim|xss_clean|max_length[80]');
            $this->form_validation->set_rules('cuenta', 'Cuenta Bancaria', 'trim|min_length[12]|max_length[12]|integer');
            $this->form_validation->set_rules('cargo', 'Cargo', 'required|callback_select_default');
            $this->form_validation->set_rules('jefe', 'Jefe', 'required|callback_select_default');
            $this->form_validation->set_rules('estado', 'Estado', 'required|callback_select_default');
            $this->form_validation->set_rules('sede_ppal', 'Sede Principal', 'required|callback_select_default');
            $this->form_validation->set_rules('observacion', 'observacion', 'trim|xss_clean|max_length[255]');

            //Validamos que la clave primaria no este repetida
            $duplicate_key = "";
            if (($this->input->post('id')) && ($this->input->post('dni'))) {
                $check_usuario = $this->select_model->usuario_id_dni($this->input->post('id'), $this->input->post('dni'));
                if ($check_usuario == TRUE) {
                    $duplicate_key = "El Documento ingresado ya existe en la Base de Datos.";
                }
            }
            if (($this->form_validation->run() == FALSE) || ($check_usuario == TRUE)) {
                echo $duplicate_key . form_error('dni') . form_error('id') . form_error('nombre1') . form_error('nombre2') . form_error('apellido1') . form_error('apellido2') . form_error('fecha_nacimiento') . form_error('genero') . form_error('est_civil') . form_error('pais') . form_error('t_domicilio') . form_error('direccion') . form_error('barrio') . form_error('telefono') . form_error('celular') . form_error('email') . form_error('cuenta') . form_error('cargo') . form_error('jefe') . form_error('estado') . form_error('sede_ppal') . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    function new_empleado() {
        //si se ha pulsado el botón submit validamos el formulario con codeIgniter
        //Esto es muy importante, porq de lo contrario, podrian haber accedido aqui por la url directamente y daria error porq no vienen datos.
        if ($this->input->post('submit')) {
            $dni = $this->input->post('dni');
            $id = $this->input->post('id');
            $nombre1 = strtolower($this->input->post('nombre1'));
            $nombre2 = strtolower($this->input->post('nombre2'));
            $apellido1 = strtolower($this->input->post('apellido1'));
            $apellido2 = strtolower($this->input->post('apellido2'));
            $fecha_nacimiento = $this->input->post('fecha_nacimiento');
            $genero = $this->input->post('genero');
            $est_civil = $this->input->post('est_civil');
            $pais = $this->input->post('pais');
            $provincia = $this->input->post('provincia');
            $ciudad = $this->input->post('ciudad');
            $t_domicilio = $this->input->post('t_domicilio');
            $direccion = strtolower($this->input->post('direccion'));
            $barrio = strtolower($this->input->post('barrio'));
            $telefono = strtolower($this->input->post('telefono'));
            $celular = $this->input->post('celular');
            $email = strtolower($this->input->post('email'));
            $cuenta = $this->input->post('cuenta');
            list($cargo, $perfil) = explode("-", $this->input->post('cargo'));
            list($id_jefe, $dni_jefe) = explode("-", $this->input->post('jefe'));
            $estado = $this->input->post('estado');
            $sede_ppal = $this->input->post('sede_ppal');
            $observacion = strtolower($this->input->post('observacion'));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $password = $this->encrypt->encode($id);
            if ($estado == 3) {
                $vigente = 0;
            } else {
                $vigente = 1;
            }
            $error1 = $this->insert_model->new_usuario($id, $dni, $password, $perfil, $vigente);
            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "index_admon_sistema/crear_empleado";
            $data['msn_recrear'] = "Crear otro Empleado";
            //No se pudo crear el usuario
            if (isset($error1)) {
                $data['trans_error'] = $error1;
                $this->parser->parse('trans_error', $data);
            } else {
                $error2 = $this->insert_model->new_empleado($id, $dni, $nombre1, $nombre2, $apellido1, $apellido2, $fecha_nacimiento, $genero, $est_civil, $pais, $provincia, $ciudad, $t_domicilio, $direccion, $barrio, $telefono, $celular, $email, $cuenta, $cargo, $id_jefe, $dni_jefe, $estado, $sede_ppal, $observacion, $fecha_trans, $id_responsable, $dni_responsable);
                //No se pudo crear el empleado
                if (isset($error2)) {
                    $data['trans_error'] = $error2;
                    $this->parser->parse('trans_error', $data);
                } else {
                    $this->parser->parse('trans_success', $data);
                }
                $this->parser->parse('welcome', $data);
                $this->load->view('footer');
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    //Crear: Salario
    function crear_salario() {
        $data = $this->navbar();
        $data['t_salario'] = $this->select_model->t_salario();
        $data['action_validar'] = base_url() . "index_admon_sistema/validar_salario";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_salario";
        $this->parser->parse('crear_salario', $data);
    }

    function validar_salario() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('t_salario', 'Tipo de Salario', 'required|callback_select_default');
            $this->form_validation->set_rules('vigente', 'Vigente', 'required|callback_select_default');

            if ($this->form_validation->run() == FALSE) {
                echo form_error('nombre') . form_error('t_salario') . form_error('vigente');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    function new_salario() {
        //si se ha pulsado el botón submit validamos el formulario con codeIgniter
        //Esto es muy importante, porq de lo contrario, podrian haber accedido aqui por la url directamente y daria error porq no vienen datos.
        if ($this->input->post('submit')) {
            $nombre = strtolower($this->input->post('nombre'));
            $t_salario = strtolower($this->input->post('t_salario'));
            $vigente = $this->input->post('vigente');
            $observacion = strtolower($this->input->post('observacion'));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            //se calcula con el ultimo id
            $id_salario = ($this->select_model->nextId_salario()->id) + 1;

            $error1 = $this->insert_model->new_salario($id_salario, $nombre, $t_salario, $vigente, $observacion, $fecha_trans, $id_responsable, $dni_responsable);
            $data = $this->navbar();
            $data['url_recrear'] = base_url() . "index_admon_sistema/crear_empleado";
            $data['msn_recrear'] = "Crear otro Empleado";
            if (isset($error1)) {
                $data['trans_error'] = $error1;
                $this->parser->parse('trans_error', $data);
            } else {
                //los siguientes son arrays de los inputs dinamicos
                $values_conceptos = $this->input->post('values_conceptos');
                $conceptos = $this->input->post('conceptos');
                $i = 0;
                foreach ($conceptos as $fila) {
                    $error2 = $this->insert_model->new_concepto_base($id_salario, $values_conceptos[$i], str_replace(".", "", $fila));
                    if (isset($error2)) {
                        $data['trans_error'] = $error2;
                        $this->parser->parse('trans_error', $data);
                        $this->parser->parse('welcome', $data);
                        $this->load->view('footer');
                        return;
                    }
                    $i++;
                }
                $this->parser->parse('trans_success', $data);
            }
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }
    
    //Crear: Asignar Sede
    function crear_asignar_sede() {
        $data = $this->navbar();
        $data['empleado'] = $this->select_model->empleado_activo();
        $data['sede'] = $this->select_model->sede_activa();

        $data['action_validar'] = base_url() . "index_admon_sistema/validar_asignar_sede";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_asignar_sede";
        $data['action_editar_ppal'] = base_url() . "index_admon_sistema/editar_sede_ppal";
        $data['action_anular_secundaria'] = base_url() . "index_admon_sistema/anular_sede_secundaria";
        $data['action_agregar_secundaria'] = base_url() . "index_admon_sistema/agregar_sede_secundaria";
        $this->parser->parse('crear_asignar_sede', $data);
    }

    public function editar_sede_ppal() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('sede_ppal', 'sede_ppal', 'required|callback_select_default');
            if ($this->form_validation->run() == FALSE) {
                //de esta forma devolvemos los errores de formularios
                //con ajax desde codeigniter, aunque con php es lo mismo
                $errors = array(
                    'mensaje' => '<p>' . form_error('sede_ppal') . '</p>',
                    'respuesta' => 'error'
                );
                //y lo devolvemos así para parsearlo con JSON.parse
                echo json_encode($errors);
                return FALSE;
            } else {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $sede_ppal = $this->input->post('sede_ppal');
                $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
                $id_responsable = $this->input->post('id_responsable');
                $dni_responsable = $this->input->post('dni_responsable');

                $error = $this->update_model->empleado_sede_ppal($id_empleado, $dni_empleado, $sede_ppal);

                if (isset($error)) {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p>' . $error . '</p>'
                    );
                } else {
                    //Para la historica no atrapo el error, si hubo error no me importa, con tal que se haya hecho la transaccion verdadera
                    $this->insert_model->modificar_sede_empleado($id_empleado, $dni_empleado, $sede_ppal, $fecha_trans, $id_responsable, $dni_responsable);
                    $response = array(
                        'respuesta' => 'OK',
                        'mensaje' => '<p>La sede principal se actualizó correctamente.</p>'
                    );
                }
                echo json_encode($response);
                return FALSE;
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    public function anular_sede_secundaria() {
        if ($this->input->is_ajax_request()) {
            list($sede_secundaria, $id_empleado, $dni_empleado) = explode("-", $this->input->post('id_empleado_sede'));
            $sede_ppal = $this->input->post('sede_ppal');
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $vigente = 0;

            $error = $this->update_model->empleado_x_sede($id_empleado, $dni_empleado, $sede_secundaria, $vigente);

            if (isset($error)) {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p>' . $error . '</p>'
                );
            } else {
                //Para la historica no atrapo el error, si hubo error no me importa, con tal que se haya hecho la transaccion verdadera
                $this->insert_model->anular_empleado_x_sede($id_empleado, $dni_empleado, $sede_secundaria, $fecha_trans, $id_responsable, $dni_responsable);
                $response = array(
                    'respuesta' => 'OK',
                    'mensaje' => '<p>La sede Secundaria se eliminó correctamente.</p>'
                );
            }
            echo json_encode($response);
            return FALSE;
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    public function agregar_sede_secundaria() {
        if ($this->input->is_ajax_request()) {
            list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $checkbox = $this->input->post('sede_checkbox');

            foreach ($checkbox as $fila) {
                $error = $this->insert_model->empleado_x_sede($id_empleado, $dni_empleado, $fila);
                $this->insert_model->asignar_empleado_x_sede($id_empleado, $dni_empleado, $fila, $fecha_trans, $id_responsable, $dni_responsable);
                if (isset($error)) {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p>' . $error . '</p>'
                    );
                    echo json_encode($response);
                    return FALSE;
                }
            }
            $response = array(
                'respuesta' => 'OK',
                'mensaje' => '<p>La sede Secundaria se eliminó correctamente.</p>'
            );
            echo json_encode($response);
            return FALSE;
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    //Editar: Cargo y Jefe
    function editar_cargo_jefe() {
        $data = $this->navbar();
        $data['empleado'] = $this->select_model->empleado_activo();
        $data['sede'] = $this->select_model->sede_activa();

        $data['action_validar'] = base_url() . "index_admon_sistema/validar_asignar_sede";
        $data['action_crear'] = base_url() . "index_admon_sistema/new_asignar_sede";
        $data['action_editar_ppal'] = base_url() . "index_admon_sistema/editar_sede_ppal";
        $data['action_anular_secundaria'] = base_url() . "index_admon_sistema/anular_sede_secundaria";
        $data['action_agregar_secundaria'] = base_url() . "index_admon_sistema/agregar_sede_secundaria";
        $this->parser->parse('editar_cargo_jefe', $data);
    }    
    
    
    //Llenar elementos html dinamicamente
    public function llena_provincia() {
        if ($this->input->post('pais')) {
            $pais = $this->input->post('pais');
            $provincias = $this->select_model->provincia_pais($pais);
            if ($provincias == TRUE) {
                foreach ($provincias as $fila) {
                    echo '<option value="' . $fila->id . '">' . ucwords($fila->nombre) . '</option>';
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    public function llena_ciudad() {
        if ($this->input->post('provincia')) {
            $provincia = $this->input->post('provincia');
            $ciudades = $this->select_model->ciudad_provincia($provincia);
            if ($ciudades == TRUE) {
                foreach ($ciudades as $fila) {
                    echo '<option value="' . $fila->id . '">' . ucwords($fila->nombre) . '</option>';
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    public function llena_t_concepto_salario() {
        if ($this->input->post('t_salario')) {
            $t_salario = $this->input->post('t_salario');
            $conceptos = $this->select_model->t_concepto_nomina_base($t_salario);
            if ($conceptos == TRUE) {
                foreach ($conceptos as $fila) {
                    echo '<div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="margin_label">' . ucwords($fila->tipo) . '</label>   
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input name="values_conceptos[]" type="hidden" value="' . $fila->id . '">
                                        <input type="text" name="conceptos[]" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12">
                                    </div>
                                </div>
                            </div>
                        </div>';
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    public function llena_sede_ppal_empleado() {
        $options = "";
        if ($this->input->post('empleado')) {
            list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
            $sede_ppal = $this->select_model->sede_ppal_empleado($id_empleado, $dni_empleado);
            if ($sede_ppal == TRUE) {
                echo '<tr>
                        <td>' . ucwords($sede_ppal->nombre) . '</td>
                        <td class="text-center">
                        <button type="button" class="btn btn-primary btn-xs editar_sede" id="' . $id_empleado . "-" . $dni_empleado . '"><span class="glyphicon glyphicon-edit"></span> Editar </button>
                        </td>
                     </tr>';
            } else {
                echo "";
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    public function llena_sede_secundaria_empleado() {
        if ($this->input->post('empleado')) {
            list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
            $sedes_secundarias = $this->select_model->sede_secundaria_empleado($id_empleado, $dni_empleado);
            if ($sedes_secundarias == TRUE) {
                foreach ($sedes_secundarias as $fila) {
                    echo '<tr>
                            <td>' . ucwords($fila->nombre) . '</td>
                            <td class="text-center">
                            <button class="btn btn-danger btn-xs anular_sede" id="' . $fila->sede_secundaria . "-" . $id_empleado . "-" . $dni_empleado . '"><span class="glyphicon glyphicon-remove"></span> Eliminar </button>
                            </td>
                         </tr>';
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    public function llena_checkbox_secundarias() {
        if ($this->input->post('empleado')) {
            list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
            $sedes_secundarias = $this->select_model->sede_secundaria_faltante($id_empleado, $dni_empleado);
            if ($sedes_secundarias == TRUE) {
                foreach ($sedes_secundarias as $fila) {
                    echo '<div class="form-group">
                            <div class="checkbox">
                                <label><h4 class="h_negrita"><input type="checkbox" name="sede_checkbox[]" value="' . $fila->id . '"/>' . ucwords($fila->nombre) . '</h4></label>
                            </div>
                        </div>';
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    public function llena_cargo_sexo() {
        $options = "";
        if ($this->input->post('genero')) {
            $genero = $this->input->post('genero');
            $t_cargo = $this->select_model->t_cargo();
            //Validamos que las dos consultas devuelvan algo
            if (($genero == TRUE) && ($t_cargo == TRUE)) {
                if ($genero == M) {
                    foreach ($t_cargo as $fila) {
                        echo '<option value="' . $fila->id . '-' . $fila->perfil . '">' . $fila->cargo_masculino . '</option>';
                    }
                } else {
                    if ($genero == F) {
                        foreach ($t_cargo as $fila) {
                            echo '<option value="' . $fila->id . '-' . $fila->perfil . '">' . $fila->cargo_femenino . '</option>';
                        }
                    } else {
                        echo "";
                    }
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    //callback de form_validation
    function select_default($campo) {
        if ($campo == "default") {
            $this->form_validation->set_message('select_default', 'El Campo %s, es obligatorio.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
