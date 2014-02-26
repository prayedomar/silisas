<?php

class Alumno extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
        $this->load->model('alumnom');
    }

    function crear() {
        $data["tab"] = "crear_alumno";
        $this->isLogin($data["tab"]);        
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['dni'] = $this->select_model->t_dni_alumno();
        $data['pais'] = $this->select_model->pais();
        $data['provincia'] = $this->select_model->provincia();
        $data['ciudad'] = $this->select_model->ciudad();
        $data['t_domicilio'] = $this->select_model->t_domicilio();
        $data['t_curso'] = $this->select_model->t_curso();
        $data['action_validar'] = base_url() . "alumno/validar";
        $data['action_crear'] = base_url() . "alumno/insertar";
        $data['action_llena_provincia'] = base_url() . "alumno/llena_provincia";
        $data['action_llena_ciudad'] = base_url() . "alumno/llena_ciudad";
        $this->parser->parse('alumno/crear', $data);
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
            $this->form_validation->set_rules('pais', 'País', 'required|callback_select_default');
            $this->form_validation->set_rules('provincia', 'Departamento', 'required|callback_select_default');
            $this->form_validation->set_rules('ciudad', 'Ciudad', 'required|callback_select_default');
            $this->form_validation->set_rules('t_domicilio', 'Tipo de Domicilio', 'required|callback_select_default');
            $this->form_validation->set_rules('direccion', 'Direccion', 'required|trim|xss_clean|max_length[80]');
            $this->form_validation->set_rules('barrio', 'Barrio/Sector', 'required|trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('telefono', 'Telefono', 'required|trim|xss_clean|min_length[7]|max_length[40]');
            $this->form_validation->set_rules('celular', 'Celular', 'trim|xss_clean|min_length[10]|max_length[40]');
            $this->form_validation->set_rules('email', 'Correo Electrónico', 'required|valid_email|trim|xss_clean|max_length[80]');
            $this->form_validation->set_rules('matricula', 'Número de Matrícula', 'required|trim|min_length[3]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('velocidad_ini', 'Velocidad Inicial', 'required|trim|xss_clean|max_length[6]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('comprension_ini', 'Comprensión Inicial', 'required|trim|xss_clean|callback_miles_numeric|callback_porcentaje');
            $this->form_validation->set_rules('t_curso', 'Tipo de Curso', 'required|callback_select_default');
            $this->form_validation->set_rules('cant_clases', 'Cantidad de Clases', 'required|callback_select_default');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');

            //Validamos que la clave primaria no este repetida
            $duplicate_key = "";
            if (($this->input->post('id')) && ($this->input->post('dni'))) {
                $t_usuario = 3; //3: Alumno
                $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id'), $this->input->post('dni'), $t_usuario);
                if ($check_usuario == TRUE) {
                    $duplicate_key = "<p>La Identificación ingresada ya existe en la Base de Datos.</p>";
                }
            }
            //Validamos que la matrícula insertada si exista y tenga disponibilidad de alumnos
            $error_matricula = "";
            if ($this->input->post('matricula')) {
                $matricula = $this->select_model->matricula_id($this->input->post('matricula'));
                if ($matricula != TRUE) {
                    $error_matricula = "<p>El Número de Matrícula, no existe en la base de datos.</p>";
                } else {
                    $cant_alumnos_disponibles = $matricula->cant_alumnos_disponibles;
                    if ($cant_alumnos_disponibles <= 0) {
                        $error_matricula = "<p>Ya se registró la cantidad de alumnos disponibles para la matrícula.</p>";
                    }
                }
            }
            if (($this->form_validation->run() == FALSE) || ($duplicate_key != "") || ($error_matricula != "")) {
                echo $duplicate_key . form_error('dni') . form_error('id') . form_error('nombre1') . form_error('nombre2') . form_error('apellido1') . form_error('apellido2') . form_error('fecha_nacimiento') . form_error('genero') . form_error('pais') . form_error('provincia') . form_error('ciudad') . form_error('t_domicilio') . form_error('direccion') . form_error('barrio') . form_error('telefono') . form_error('celular') . form_error('email') . form_error('matricula') . $error_matricula . form_error('velocidad_ini') . form_error('comprension_ini') . form_error('t_curso') . form_error('cant_clases') . form_error('observacion');
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
            $dni = $this->input->post('dni');
            $id = $this->input->post('id');
            $t_usuario = 3; //Alumno
            $nombre1 = ucwords(strtolower($this->input->post('nombre1')));
            $nombre2 = ucwords(strtolower($this->input->post('nombre2')));
            $apellido1 = ucwords(strtolower($this->input->post('apellido1')));
            $apellido2 = ucwords(strtolower($this->input->post('apellido2')));
            $fecha_nacimiento = $this->input->post('fecha_nacimiento');
            $genero = $this->input->post('genero');
            $pais = $this->input->post('pais');
            $provincia = $this->input->post('provincia');
            $ciudad = $this->input->post('ciudad');
            $t_domicilio = $this->input->post('t_domicilio');
            $direccion = ucwords(strtolower($this->input->post('direccion')));
            $barrio = ucwords(strtolower($this->input->post('barrio')));
            $telefono = strtolower($this->input->post('telefono'));
            $celular = $this->input->post('celular');
            $email = strtolower($this->input->post('email'));
            $matricula = $this->input->post('matricula');
            $velocidad_ini = str_replace(",", "", $this->input->post('velocidad_ini')); //No siempre es necesario redondear.
            $comprension_ini = round(str_replace(",", "", $this->input->post('comprension_ini')), 2); //Redondeamos cuando es decimal lo que viene
            $t_curso = $this->input->post('t_curso');
            $estado = 1; //Activo
            $grados = NULL;
            $cant_clases = $this->input->post('cant_clases');
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $sede_ppal = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;

            $password = $this->encrypt->encode($id); //Encriptamos el numero de identificacion            
            $perfil = 'alumno';
            $vigente = 1;
            $nombres = $nombre1 . " " . $nombre2;            

            $data["tab"] = "crear_alumno";            
            $this->isLogin($data["tab"]);                
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "alumno/crear";
            $data['msn_recrear'] = "Crear otro Alumno";
            
            $error1 = $this->insert_model->new_usuario($id, $dni, $genero, $nombres, $t_usuario, $password, $email, $perfil, $vigente);
            //No se pudo crear el usuario
            if (isset($error1)) {
                $data['trans_error'] = $error1;
                $this->parser->parse('trans_error', $data);
            } else {
                $error2 = $this->insert_model->alumno($id, $dni, $t_usuario, $nombre1, $nombre2, $apellido1, $apellido2, $fecha_nacimiento, $genero, $pais, $provincia, $ciudad, $t_domicilio, $direccion, $barrio, $telefono, $celular, $email, $matricula, $velocidad_ini, $comprension_ini, $t_curso, $estado, $grados, $cant_clases, $sede_ppal, $observacion, $id_responsable, $dni_responsable);
                //No se pudo crear el empleado
                if (isset($error2)) {
                    $data['trans_error'] = $error2;
                    $this->parser->parse('trans_error', $data);
                } else {
                    //Si se crea el alumno, entonces merma la cantidad de alumnos disponibles para esa matrícula
                    $error3 = $this->update_model->matricula_cant_alumnos_mermar($matricula);
                    if (isset($error3)) {
                        $data['trans_error'] = $error3;
                        $this->parser->parse('trans_error', $data);
                    } else {
//                    //Enviamos Correo de Bienvenida
//                        $t_dni = $this->select_model->t_dni_id($dni)->tipo;
//                        if($genero == 'M'){
//                            $prefijo = "Sr.";
//                            $asunto = "Bienvenido a la familia SILI S.A.S";                            
//                        }else{
//                            $prefijo = "Sra.";
//                            $asunto = "Bienvenida a la familia SILI S.A.S";                                                        
//                        }
//                        $email = "prayedomar@hotmail.com";
//                        $mensaje = '<p>' . $prefijo . ' ' . $nombre1 . ' ' . $nombre2 . ' ' . $apellido1 . ' ' . $apellido2 . '</p>'
//                                . '<p>Reciba la más cordial bienvenida.</p>'
//                                . '<p>A partir de este momento, usted hace parte del privilegiado grupo de alumnos, que emprenderán el camino para convertirsen prontamente en lectores profesionales.<br/>'
//                                . '<br/>Para ingresar a nuestro sistema y disfrutar de todas las herramientas que hemos diseñado para facilitar la interacción con nuestra compañía, ingrese a traves de nuestra pagina web: <a href="http://www.sili.com.co" target="_blank">www.sili.com.co</a> y seleccione la opción "Acceder".</p>'
//                                . '<ul type="disc">'
//                                    . '<li><p>Sus datos para ingresar al sistema son:</p>'
//                                        . '<center>'
//                                        . '<table>'
//                                            . '<tr>'
//                                                . '<td style="width:230px;"><b>Tipo de usuario: </b></td>'
//                                                . '<td>Titular</td>'
//                                            . '</tr>'
//                                            . '<tr>'
//                                                . '<td><b>Tipo de identificación: </b></td>'
//                                                . '<td>' . $t_dni . '</td>'
//                                            . '</tr>'
//                                            . '<tr>'
//                                                . '<td><b>Identificación de usuario: </b></td>'
//                                                . '<td>' . $id . '</td>'
//                                            . '</tr>'
//                                            . '<tr>'
//                                                . '<td><b>Contraseña: </b></td>'
//                                                . '<td>' . $id . '</td>'
//                                            . '</tr>'
//                                        . '</table>'
//                                        . '</center>'
//                                        . '<br/><p>Para garantizar la seguridad de su cuenta, una vez que ingrese por primera vez, modifique su contraseña a través de la opción: Opciones de usuario > Cambiar contraseña.</p>'
//                                    . '</li>'
//                                . '</ul>'
//                                . '<center><br/>¡Gracias por elegirnos y darnos la oportunidad de servirle!</center>';
//                        $this->sendEmail("silisascolombia@gmail.com", $email, $asunto, $mensaje);               
//                        //Cargamos mensaje de Ok                        
                        $this->parser->parse('trans_success', $data);
                    }
                }
            }
        } else {
            redirect(base_url());
        }
    }

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

    public function consultar() {
        $this->load->model('t_dnim');
        $this->load->model('est_alumnom');
        $this->load->model('sedem');
        $this->load->model('t_cursom');
        $data["tab"] = "consultar_alumno";
        $this->isLogin($data["tab"]);        
        $data['tipos_documentos'] = $this->t_dnim->listar_todas_los_tipos_de_documentos();
        $data['tipos_cursos'] = $this->t_cursom->listar_todas_los_tipos_curso();
        $data['estados_alumnos'] = $this->est_alumnom->listar_todas_los_estados_de_alumno();
        $data['lista_sedes'] = $this->sedem->listar_todas_las_sedes();
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
        $cantidad_empleados = $this->alumnom->cantidad_alumnos($_GET, $inicio, $filasPorPagina);
        $cantidad_empleados = $cantidad_empleados[0]->cantidad;
        $data['cantidad_empleados'] = $cantidad_empleados;
        $data['cantidad_paginas'] = ceil($cantidad_empleados / $filasPorPagina);
        $data["lista_alumnos"] = $this->alumnom->listar_alumnos($_GET, $inicio, $filasPorPagina);
        $this->load->view("header", $data);
        $this->load->view("alumno/consultar");
        $this->load->view("footer");
    }

}
