<?php

class Empleado extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('empleadom');
    }

    function crear() {
        $data["tab"] = "crear_empleado";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);

        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['dni'] = $this->select_model->t_dni_empleado();
        $data['est_civil'] = $this->select_model->t_est_civil();
        $data['pais'] = $this->select_model->pais();
        $data['provincia'] = $this->select_model->provincia();
        $data['ciudad'] = $this->select_model->ciudad();
        $data['t_domicilio'] = $this->select_model->t_domicilio();
        $data['jefe'] = $this->select_model->empleado_activo();
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
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
            $this->form_validation->set_rules('barrio', 'Barrio/Sector', 'trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('telefono', 'Teléfono', 'required|trim|xss_clean|min_length[7]|max_length[40]');
            $this->form_validation->set_rules('celular', 'Celular', 'trim|xss_clean|min_length[10]|max_length[40]');
            $this->form_validation->set_rules('email', 'Correo Electrónico', 'required|valid_email|trim|xss_clean|max_length[80]');
            $this->form_validation->set_rules('cuenta', 'Cuenta Bancaria', 'trim|min_length[11]|max_length[12]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('sede_ppal', 'Sede Principal', 'required|callback_select_default');
            $this->form_validation->set_rules('fecha_ingreso', 'Fecha ingreso a la empresa', 'required|xss_clean|callback_fecha_valida');
            $this->form_validation->set_rules('depto', 'Departamento Empresarial', 'required|callback_select_default');
            $this->form_validation->set_rules('cargo', 'Cargo', 'required|callback_select_default');
            $this->form_validation->set_rules('salario', 'Salario', 'required|callback_select_default');
            $this->form_validation->set_rules('jefe', 'Jefe Inmediato', 'required|callback_select_default');
            $this->form_validation->set_rules('t_contrato', 'Tipo de Contrato Laboral', 'required|callback_select_default');
            $this->form_validation->set_rules('fecha_inicio', 'Fecha inicio último contrato laboral', 'required|xss_clean|callback_fecha_valida');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');

            if ((($this->input->post('t_contrato') != "default")) && (($this->input->post('t_contrato') != "1"))) {
                $this->form_validation->set_rules('fecha_fin', 'Fecha fin último contrato laboral', 'required|xss_clean|callback_fecha_valida');
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
            $error_entre_fechas = "";
            if (($this->fecha_valida($this->input->post('fecha_inicio'))) && ($this->fecha_valida($this->input->post('fecha_fin')))) {
                if (($this->dias_entre_fechas($this->input->post('fecha_inicio'), $this->input->post('fecha_fin'))) < 0) {
                    $error_entre_fechas = "<p>La fecha final del contrato, no puede ser menor que la fecha inicial.</p>";
                }
            }
            if (($this->form_validation->run() == FALSE) || ($duplicate_key != "") || ($error_entre_fechas != "")) {
                echo $duplicate_key . form_error('dni') . form_error('id') . form_error('nombre1') . form_error('nombre2') . form_error('apellido1') . form_error('apellido2') . form_error('fecha_nacimiento') . form_error('genero') . form_error('est_civil') . form_error('pais') . form_error('provincia') . form_error('ciudad') . form_error('t_domicilio') . form_error('direccion') . form_error('barrio') . form_error('telefono') . form_error('celular') . form_error('email') . form_error('cuenta') . form_error('sede_ppal') . form_error('depto') . form_error('fecha_ingreso') . form_error('cargo') . form_error('salario') . form_error('jefe') . form_error('t_contrato') . form_error('fecha_inicio') . form_error('fecha_fin') . $error_entre_fechas . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function validarParaEditar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('dni_old', 'Tipo de Identificación (Old)', 'required|callback_select_default');
            $this->form_validation->set_rules('id_old', 'Número de Identificación (Old)', 'required|trim|min_length[5]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('dni_new', 'Tipo de Identificación', 'required|callback_select_default');
            $this->form_validation->set_rules('id_new', 'Número de Identificación', 'required|trim|min_length[5]|max_length[13]|integer|callback_valor_positivo');
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
            $this->form_validation->set_rules('barrio', 'Barrio/Sector', 'trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('telefono', 'Teléfono', 'required|trim|xss_clean|min_length[7]|max_length[40]');
            $this->form_validation->set_rules('celular', 'Celular', 'trim|xss_clean|min_length[10]|max_length[40]');
            $this->form_validation->set_rules('email', 'Correo Electrónico', 'required|valid_email|trim|xss_clean|max_length[80]');
            $this->form_validation->set_rules('cuenta', 'Cuenta Bancaria', 'trim|min_length[11]|max_length[12]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('sede_ppal', 'Sede Principal', 'required|callback_select_default');
            $this->form_validation->set_rules('fecha_ingreso', 'Fecha ingreso a la empresa', 'required|xss_clean|callback_fecha_valida');
            $this->form_validation->set_rules('depto', 'Departamento Empresarial', 'required|callback_select_default');
            $this->form_validation->set_rules('cargo', 'Cargo', 'required|callback_select_default');
            $this->form_validation->set_rules('salario', 'Salario', 'required|callback_select_default');
            $this->form_validation->set_rules('jefe', 'Jefe Inmediato', 'required|callback_select_default');

            //Validamos que la clave primaria no este repetida
            $duplicate_key = "";
            if (($this->input->post('id_new')) && ($this->input->post('dni_new'))) {
                $t_usuario = 1; //1: Empleado
                $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_new'), $this->input->post('dni_new'), $t_usuario);
                if ($check_usuario == TRUE) {
                    $duplicate_key = "<p>La Identificación ingresada ya existe en la Base de Datos.</p>";
                }
            }
            if (($this->form_validation->run() == FALSE) || ($duplicate_key != "")) {
                echo $duplicate_key . form_error('dni_old') . form_error('id_old') . form_error('dni_new') . form_error('id_new') . form_error('nombre1') . form_error('nombre2') . form_error('apellido1') . form_error('apellido2') . form_error('fecha_nacimiento') . form_error('genero') . form_error('est_civil') . form_error('pais') . form_error('provincia') . form_error('ciudad') . form_error('t_domicilio') . form_error('direccion') . form_error('barrio') . form_error('telefono') . form_error('celular') . form_error('email') . form_error('cuenta') . form_error('sede_ppal') . form_error('depto') . form_error('fecha_ingreso') . form_error('cargo') . form_error('salario') . form_error('jefe');
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
            $this->load->model('t_cargom');
            $id = $this->input->post('id');
            $dni = $this->input->post('dni');
            $nombre1 = ucwords(mb_strtolower($this->input->post('nombre1')));
            $nombre2 = ucwords(mb_strtolower($this->input->post('nombre2')));
            $apellido1 = ucwords(mb_strtolower($this->input->post('apellido1')));
            $apellido2 = ucwords(mb_strtolower($this->input->post('apellido2')));
            $fecha_nacimiento = $this->input->post('fecha_nacimiento');
            $genero = $this->input->post('genero');
            $est_civil = $this->input->post('est_civil');
            $pais = $this->input->post('pais');
            $provincia = $this->input->post('provincia');
            $ciudad = $this->input->post('ciudad');
            $t_domicilio = $this->input->post('t_domicilio');
            $direccion = ucwords(mb_strtolower($this->input->post('direccion')));
            $barrio = ucwords(mb_strtolower($this->input->post('barrio')));
            $telefono = mb_strtolower($this->input->post('telefono'));
            $celular = $this->input->post('celular');
            $email = mb_strtolower($this->input->post('email'));
            $cuenta = $this->input->post('cuenta');
            $sede_ppal = $this->input->post('sede_ppal');
            $fecha_ingreso = $this->input->post('fecha_ingreso');
            $depto = $this->input->post('depto');
            $cargo = $this->input->post('cargo');
            $salario = $this->input->post('salario');
            list($id_jefe, $dni_jefe) = explode("-", $this->input->post('jefe'));
            $t_contrato = $this->input->post('t_contrato');
            $fecha_inicio = $this->input->post('fecha_inicio');
            $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));

            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');

            if ($this->input->post('t_contrato') != "1") {
                $fecha_fin = $this->input->post('fecha_fin');
            } else {
                $fecha_fin = NULL;
            }
            $perfil = $this->t_cargom->t_cargo_id($cargo)->perfil;

            $data["tab"] = "crear_empleado";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "empleado/crear";
            $data['msn_recrear'] = "Crear otro Empleado";

            $password = $this->encrypt->encode($id);
            $vigente = 1;
            $t_usuario = 1; //Empleado
            $nombres = $nombre1 . " " . $nombre2;
            $error1 = $this->insert_model->new_usuario($id, $dni, $genero, $nombres, $t_usuario, $password, $email, $perfil, $vigente);
            //No se pudo crear el usuario
            if (isset($error1)) {
                $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error2 = $this->insert_model->new_empleado($id, $dni, $t_usuario, $nombre1, $nombre2, $apellido1, $apellido2, $fecha_nacimiento, $genero, $est_civil, $pais, $provincia, $ciudad, $t_domicilio, $direccion, $barrio, $telefono, $celular, $email, $cuenta, $sede_ppal, $fecha_ingreso, $depto, $cargo, $salario, $id_jefe, $dni_jefe, 1, $observacion, $id_responsable, $dni_responsable);
                //No se pudo crear el empleado
                if (isset($error2)) {
                    $data['trans_error'] = $error2 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    //Creamos el contrato laboral
                    $error3 = $this->insert_model->contrato_laboral($id, $dni, $t_contrato, $fecha_inicio, $fecha_fin, 1, $observacion, $id_responsable, $dni_responsable);
                    if (isset($error3)) {
                        $data['trans_error'] = $error3 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                        $this->parser->parse('trans_error', $data);
                    } else {
                        //Enviamos el correo al usuario con 
                        $t_dni = $this->select_model->t_dni_id($dni)->tipo;
                        $tipo_contrato = $this->select_model->t_contrato_laboral_id($t_contrato)->contrato;
                        if ($fecha_fin == NULL) {
                            $fecha_fin = " -- ";
                        }
                        if ($genero == 'M') {
                            $prefijo = "Sr.";
                            $nombre_cargo = $this->select_model->t_cargo_id($cargo)->cargo_masculino;
                            $asunto = "Bienvenido a la familia SILI S.A.S";
                        } else {
                            $prefijo = "Sra.";
                            $nombre_cargo = $this->select_model->t_cargo_id($cargo)->cargo_femenino;
                            $asunto = "Bienvenida a la familia SILI S.A.S";
                        }
                        $nombre_salario = $this->select_model->t_salario_id($salario)->tipo;
                        $mensaje = '<p>' . $prefijo . ' ' . $nombre1 . ' ' . $nombre2 . ' ' . $apellido1 . ' ' . $apellido2 . '</p>'
                                . '<p>Reciba la más cordial bienvenida.</p>'
                                . '<p>A partir de este momento, usted hace parte de la familia de trabajadores, que conforma nuestra empresa SILI S.A.S.<br/>'
                                . '<br/>Para ingresar a nuestro sistema y disfrutar de todas las herramientas que hemos diseñado para facilitar sus labores cotidianas al interior de la compañía, ingrese a traves de nuestra pagina web: <a href="http://www.sili.com.co" target="_blank">www.sili.com.co</a> y seleccione la opción "Acceder".</p>'
                                . '<ul type="disc">'
                                . '<li><p>Sus datos para ingresar al sistema son:</p>'
                                . '<center>'
                                . '<table>'
                                . '<tr>'
                                . '<td style="width:230px;"><b>Tipo de usuario: </b></td>'
                                . '<td>Empleado</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td><b>Tipo de identificación: </b></td>'
                                . '<td>' . $t_dni . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td><b>Identificación de usuario: </b></td>'
                                . '<td>' . $id . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td><b>Contraseña: </b></td>'
                                . '<td>' . $id . '</td>'
                                . '</tr>'
                                . '</table>'
                                . '</center>'
                                . '<br/><p>Para garantizar la seguridad de su cuenta, una vez que ingrese por primera vez, modifique su contraseña a través de la opción: Opciones de usuario > Cambiar contraseña.</p>'
                                . '</li>'
                                . '<li><p>La información de su contrato laboral, es la siguiente:</p>'
                                . '<center>'
                                . '<table>'
                                . '<tr>'
                                . '<td style="width:230px;"><b>Tipo de contrato laboral: </b></td>'
                                . '<td>' . $tipo_contrato . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td><b>Fecha Inicial: </b></td>'
                                . '<td>' . $fecha_inicio . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td><b>Fecha Final: </b></td>'
                                . '<td>' . $fecha_fin . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td><b>Cargo: </b></td>'
                                . '<td>' . $nombre_cargo . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td><b>Salario: </b></td>'
                                . '<td>' . $nombre_salario . '</td>'
                                . '</tr>'
                                . '</table>'
                                . '</li>'
                                . '</ul>'
                                . '<center><br/>¡Gracias por darnos la oportunidad de contar con su gran talento!</center>';
                        $this->sendEmail("silisascolombia@gmail.com", $email, $asunto, $mensaje);
//                        Mostramos mensaje de notificación
                        $this->parser->parse('trans_success', $data);
                    }
                }
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_cargo_departamento() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if ($this->input->post('depto')) {
                $depto = $this->input->post('depto');
                $t_cargo = $this->select_model->cargo_depto($depto);
                //Validamos que las dos consultas devuelvan algo
                if ($t_cargo == TRUE) {
                    foreach ($t_cargo as $fila) {
                        echo '<option value="' . $fila->id . '">' . $fila->cargo_masculino . '</option>';
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

    public function llena_cargo_departamento2() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if ($this->input->post('depto')) {
                $depto = $this->input->post('depto');
                $t_cargo = $this->select_model->cargo_depto($depto);
                //Validamos que las dos consultas devuelvan algo
                if ($t_cargo == TRUE) {
                    foreach ($t_cargo as $fila) {
                        echo '<option value="' . $fila->id . '">' . $fila->cargo_masculino . '</option>';
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
            $this->escapar($_POST);
            if (($this->input->post('cargo')) && ($this->input->post('sedePpal')) && ($this->input->post('depto'))) {
                $cargo = $this->input->post('cargo');
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
            $this->escapar($_POST);
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
            $this->escapar($_POST);
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
            $this->escapar($_POST);
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
    public function consultar() {
        $this->load->model('t_dnim');
        $this->load->model('est_empleadom');
        $this->load->model('sedem');
        $this->load->model('t_deptom');
        $data["tab"] = "consultar_empleado";
        $this->isLogin($data["tab"]);
        $data['est_civil'] = $this->select_model->t_est_civil();
        $data['salarios'] = $this->select_model->salario_activo();
        $data['tipos_documentos'] = $this->select_model->t_dni_empleado();
        $data['estados_empleados'] = $this->est_empleadom->listar_todas_los_estados_de_empleado();
        $data['t_domicilio'] = $this->select_model->t_domicilio();
        $data['sede_ppal'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['lista_dptos'] = $this->t_deptom->listar_todas_los_deptos();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['action_llena_cargo_departamento'] = base_url() . "empleado/llena_cargo_departamento";
        $data['action_llena_jefe_new_empleado'] = base_url() . "empleado/llena_jefe_new_empleado";
        $data['action_llena_salario_departamento'] = base_url() . "empleado/llena_salario_departamento";
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
        $data['sede_ppal'] = $this->select_model->sede_activa_responsable($id_responsable, $dni_responsable);
        $data['t_depto'] = $this->select_model->t_depto();
        $data['t_contrato'] = $this->select_model->t_contrato_laboral();
        if (!empty($_GET["depto"])) {
            $this->load->model('t_cargom');
            $data['lista_cargos'] = $this->t_cargom->listar_todas_los_cargos_por_depto($_GET['depto']);
        }
        $filasPorPagina = 20;
        if (empty($_GET["page"])) {
            $inicio = 0;
            $paginaActual = 1;
        } else {
            $inicio = ($_GET["page"] - 1) * $filasPorPagina;
            $paginaActual = $_GET["page"];
        }
        $data['paginaActiva'] = $paginaActual;
        $cantidad_empleados = $this->empleadom->cantidad_empleados($_GET, $inicio, $filasPorPagina);
        $cantidad_empleados = $cantidad_empleados[0]->cantidad;
        $data['cantidad_empleados'] = $cantidad_empleados;
        $data['cantidad_paginas'] = ceil($cantidad_empleados / $filasPorPagina);
        $data["lista_empleados"] = $this->empleadom->listar_empleados($_GET, $inicio, $filasPorPagina);
        $this->load->view("header", $data);
        $this->load->view("empleado/consultar");
        $this->load->view("footer");
    }

    function listar_cargos() {
        $this->load->model('t_cargom');
        $this->escapar($_GET);
        echo json_encode($this->t_cargom->listar_todas_los_cargos_por_depto($_GET['idDepto']));
    }

    function actualizar() {
        $this->load->model('empleadom');
        //  var_dump($_POST);
        $this->empleadom->actualizarEmpleado($_POST);

        redirect(base_url() . "empleado/consultar");
    }

    public function excel() {
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=reporte_empleados_" . date("Y-m-d") . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $lista = $this->empleadom->listar_empleados_excel($_GET);
        ?>
        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse:collapse;">
            <thead>
                <tr>
                    <th><?= utf8_decode("Identificación") ?></th>
                    <th>Nombre</th>
                    <th>Fecha de nacimiento</th>
                    <th>Domicilio</th>
                    <th><?= utf8_decode("Teléfonos") ?></th>
                    <th>Email</th>
                    <th>Sede</th>
                </tr>
            </thead>
            <tbody id="bodyTabla">
                <?php foreach ($lista as $row) { ?>
                    <tr>
                        <td><?= $row->abreviacion . "" . $row->documento ?></td>
                        <td><?= utf8_decode($row->nombre1 . " " . $row->nombre2 . " " . $row->apellido1 . " " . $row->apellido2) ?></td>
                        <td><?= $row->fecha_nacimiento ?></td>
                        <td><?= utf8_decode($row->pais . " / " . $row->provincia . " / " . $row->ciudad . " - " . $row->tipo_domicilio . " / " . $row->direccion . " / " . $row->barrio) ?></td>
                        <td><?= $row->celular . " - " . $row->telefono ?></td>
                        <td><?= $row->email ?></td>
                        <td><?= utf8_decode($row->sede) ?></td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php
    }

    function renovar_contrato() {
        $data["tab"] = "renovar_contrato_laboral";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['empleado'] = $this->select_model->empleado_activo_sedes_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['action_validar'] = base_url() . "empleado/validar_renovar_contrato";
        $data['action_crear'] = base_url() . "empleado/insertar_renovar_contrato";
        $data['action_recargar'] = base_url() . "empleado/renovar_contrato";
        $data['action_validar_empleado_renovar_contrato'] = base_url() . "empleado/validar_empleado_renovar_contrato";
        $this->parser->parse('empleado/renovar_contrato', $data);
        $this->load->view('footer');
    }

    function validar_renovar_contrato() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('prefijo', 'Prefijo de sede', 'required|callback_select_default');
            $this->form_validation->set_rules('id', 'Consecutivo', 'required|trim|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'required|trim|xss_clean|max_length[255]');
            if ($this->form_validation->run() == FALSE) {
                echo form_error('prefijo') . form_error('id') . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function insertar_renovar_contrato() {
        if ($this->input->post('submit')) {
            $this->escapar($_POST);
            $this->load->model('update_model');
            $prefijo = $this->input->post('prefijo');
            $id = $this->input->post('id');
            $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $t_trans = '9'; //Nómina laboral
            $credito_debito = '0'; //Débito
            $vigente = '0'; //Anulado

            $data["tab"] = "renovar_contrato_laboral";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "empleado/renovar_contrato";
            $data['msn_recrear'] = "Anular otra nómina";
            $error = $this->update_model->movimiento_transaccion_vigente($t_trans, $prefijo, $id, $credito_debito, $vigente);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->update_model->empleado_vigente($prefijo, $id, $vigente);
                if (isset($error1)) {
                    $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    $conceptos_empleado = $this->empleadom->concepto_empleado_prefijo_id($prefijo, $id);
                    foreach ($conceptos_empleado as $fila) {
                        //Los conceptos que sean de comision de escalas, se pasarán a pendientes para volver a hacer la empleado
                        if (($fila->t_concepto_empleado != '28') && ($fila->t_concepto_empleado != '29')) {
                            $est_concepto = '3'; //3: Anulado
                            $error2 = $this->update_model->concepto_empleado_estado($fila->id, $est_concepto);
                            if (isset($error2)) {
                                $data['trans_error'] = $error2 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                                $this->parser->parse('trans_error', $data);
                                return;
                            }
                        } else {
                            $est_concepto = '2'; //2: Pendiente
                            $error2 = $this->update_model->concepto_empleado_estado($fila->id, $est_concepto);
                            if (isset($error2)) {
                                $data['trans_error'] = $error2 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                                $this->parser->parse('trans_error', $data);
                                return;
                            }
                        }
                    }
                    //SI se intenta renovar_contrato una factura 2 veces dará error por primary key duplicate
                    $error3 = $this->insert_model->renovar_contrato_transaccion($t_trans, $prefijo, $id, $observacion, $id_responsable, $dni_responsable);
                    if (isset($error3)) {
                        $data['trans_error'] = $error2 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                        $this->parser->parse('trans_error', $data);
                    } else {
                        $this->parser->parse('trans_success', $data);
                    }
                }
            }
        } else {
            redirect(base_url());
        }
    }

    public function validar_empleado_renovar_contrato() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            list($id_empleado, $dni_empleado) = explode("+", $this->input->post('empleado'));
            $this->load->model('empleadom');
            $empleado = $this->empleadom->empleado_prefijo_id($prefijo, $id);
            if ($empleado == TRUE) {
                if ($empleado->vigente == 1) {
                    $response = array(
                        'respuesta' => 'OK',
                        'filasTabla' => ''
                    );
                    $response['filasTabla'] .= '<tr>
                            <td class="text-center">' . $empleado->empleado . '</td>
                            <td class="text-center">Del ' . $empleado->fecha_inicio . ' al ' . $empleado->fecha_fin . '</td>                                
                            <td class="text-center">$' . number_format($empleado->total, 2, '.', ',') . '</td>
                            <td class="text-center">' . $empleado->sede_caja . '-' . $empleado->tipo_caja . '</td>
                            <td class="text-center">$' . number_format($empleado->efectivo_retirado, 2, '.', ',') . '</td>
                            <td class="text-center">' . $empleado->cuenta_origen . '</td>
                            <td class="text-center">$' . number_format($empleado->valor_retirado, 2, '.', ',') . '</td> 
                            <td class="text-center">' . $empleado->responsable . '</td>                                
                            <td class="text-center">' . date("Y-m-d", strtotime($empleado->fecha_trans)) . '</td>
                        </tr>';
                    echo json_encode($response);
                    return false;
                } else {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><strong><center>La nómina, ya se encuentra anulada.</center></strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong><center>La nómina, no existe en la base de datos.</center></strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

}
