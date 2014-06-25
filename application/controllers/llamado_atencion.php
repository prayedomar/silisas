<?php

class Llamado_atencion extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
        $this->load->model('llamado_atencionm');
    }

    function crear() {
        $data["tab"] = "crear_llamado_atencion";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
        $data['empleado'] = $this->select_model->empleado_sedes_responsable($id_responsable, $dni_responsable);
        $data['t_sancion'] = $this->select_model->t_sancion();

        $data['action_validar'] = base_url() . "llamado_atencion/validar";
        $data['action_crear'] = base_url() . "llamado_atencion/insertar";

        $data['action_llenar_faltas'] = base_url() . "llamado_atencion/llena_falta_laboral";

        $this->parser->parse('llamado_atencion/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('empleado', 'Empleado', 'required|callback_select_default');
            $this->form_validation->set_rules('t_falta_laboral', 'Falta Laboral', 'required');
            $this->form_validation->set_rules('t_sancion', 'Sanción a Imponer', 'required|callback_select_default');
            $this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[255]');

            $error_entre_fechas = "";
            //Si escogió suspencion laboral, valido los dos campos.
            if ($this->input->post('t_sancion') == '2') {
                $this->form_validation->set_rules('fecha_inicio', 'Fecha Inicial de la Suspensión', 'required|xss_clean|callback_fecha_valida');
                $this->form_validation->set_rules('fecha_fin', 'Fecha Final de la Suspensión', 'required|xss_clean|callback_fecha_valida');
                if (($this->fecha_valida($this->input->post('fecha_inicio'))) && ($this->fecha_valida($this->input->post('fecha_fin')))) {
                    if (($this->dias_entre_fechas($this->input->post('fecha_inicio'), $this->input->post('fecha_fin'))) < 0) {
                        $error_entre_fechas = "<p>La fecha final no puede ser menor que la fecha inicial.</p>";
                    }
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_entre_fechas != "")) {
                echo form_error('empleado') . form_error('t_falta_laboral') . form_error('t_sancion') . form_error('fecha_inicio') . form_error('fecha_fin') . $error_entre_fechas . form_error('descripcion');
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
            $t_falta_laboral = $this->input->post('t_falta_laboral');
            $t_sancion = $this->input->post('t_sancion');
            $descripcion = ucfirst(mb_strtolower($this->input->post('descripcion')));
            
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $id_llamado_atencion = ($this->select_model->nextId_llamado_atencion()->id) + 1;

            $data["tab"] = "crear_llamado_atencion";
            $this->isLogin($data["tab"]);            
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "llamado_atencion/crear";
            $data['msn_recrear'] = "Crear otro llamado de atención";
            
            $error1 = $this->insert_model->llamado_atencion($id_llamado_atencion, $id_empleado, $dni_empleado, $t_falta_laboral, $t_sancion, 1, $descripcion, $id_responsable, $dni_responsable);
            if (isset($error1)) {
                $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                //Si se va a realizar una suspension laboral.
                if ($t_sancion == '2') {
                    $fecha_inicio = $this->input->post('fecha_inicio');
                    $fecha_fin = $this->input->post('fecha_fin');
                    $t_ausencia = 11; //Suspension laboral (No remunerada)
                    $error2 = $this->insert_model->ausencia_laboral($id_empleado, $dni_empleado, $fecha_inicio, $fecha_fin, $t_ausencia, 1, $descripcion, $id_responsable, $dni_responsable);
                    if (isset($error2)) {
                        $data['trans_error'] = $error2 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                        $this->parser->parse('trans_error', $data);
                        return;
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
                    }
                } else {
                    //En el caso en que halla escogido anular el contrato
                    if ($t_sancion == '3') {
                        $error3 = $this->update_model->contrato_laboral_estado($id_empleado, $dni_empleado, 2);
                        if (isset($error3)) {
                            $data['trans_error'] = $error3 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                            $this->parser->parse('trans_error', $data);
                            return;
                        } else {
                            //Enviamos Correo de notificación
                            $empleado = $this->select_model->empleado($id_empleado, $dni_empleado);
                            $tipo_falta_laboral = $this->select_model->t_falta_laboral_id($t_falta_laboral);
                            if ($empleado->genero == 'M') {
                                $prefijo = "Sr.";
                            } else {
                                $prefijo = "Sra.";
                            }
                            $asunto = "Notificación de anulación del contrato laboral";
                            $email = $empleado->email;
                            $mensaje = '<p>' . $prefijo . ' ' . $empleado->nombre1 . ' ' . $empleado->nombre2 . ' ' . $empleado->apellido1 . ' ' . $empleado->apellido2 . '</p>'
                                    . '<p>Le notificamos que su contrato laboral ha sido anulado, por motivo de sanción disciplinaria por falta grave al reglamento laboral de la empresa.<br/>'
                                    . '<center>'
                                    . '<table>'
                                    . '<tr>'
                                    . '<td style="width:230px;"><b>Falta Laboral: </b></td>'
                                    . '<td>' . $tipo_falta_laboral->falta . '</td>'
                                    . '</tr>'
                                    . '<tr>'
                                    . '<td><b>Gravedad: </b></td>'
                                    . '<td>' . $tipo_falta_laboral->gravedad . '</td>'
                                    . '</tr>'
                                    . '</table>'
                                    . '</center>'
                                    . '<center><br/>¡Le agradecemos todo el tiempo que laboró para nuestra compañía!</center>';
                            $this->sendEmail("silisascolombia@gmail.com", $email, $asunto, $mensaje);
                        }
                    }
                }
                //Enviamos Correo de notificación
                $empleado = $this->select_model->empleado($id_empleado, $dni_empleado);
                $tipo_falta_laboral = $this->select_model->t_falta_laboral_id($t_falta_laboral);
                $tipo_sancion = $this->select_model->t_sancion_id($t_sancion);
                if ($empleado->genero == 'M') {
                    $prefijo = "Sr.";
                } else {
                    $prefijo = "Sra.";
                }
                $asunto = "Notificación de llamado de atención";
                $email = $empleado->email;
                $mensaje = '<p>' . $prefijo . ' ' . $empleado->nombre1 . ' ' . $empleado->nombre2 . ' ' . $empleado->apellido1 . ' ' . $empleado->apellido2 . '</p>'
                        . '<p>Le notificamos que en el sistema, fue ingresado un llamado de atención a su nombre.<br/>'
                        . '<center>'
                        . '<table>'
                        . '<tr>'
                        . '<td style="width:230px;"><b>Falta Laboral: </b></td>'
                        . '<td>' . $tipo_falta_laboral->falta . '</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td><b>Gravedad: </b></td>'
                        . '<td>' . $tipo_falta_laboral->gravedad . '</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td><b>Sanción: </b></td>'
                        . '<td>' . $tipo_sancion->tipo . '</td>'
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

    public function llena_falta_laboral() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $faltas = $this->select_model->t_falta_laboral();
            if (($faltas == TRUE)) {
                foreach ($faltas as $fila) {
                    echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="t_falta_laboral"  value="' . $fila->id . '"/></td>
                            <td>' . $fila->falta . '</td>
                            <td class="text-center">' . $fila->gravedad . '</td>
                        </tr>';
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
        $this->load->model('t_dnim');
        $this->load->model('t_sancionm');
        $this->load->model('sedem');
        $data["tab"] = "consultar_llamado_atencion";
        $this->isLogin($data["tab"]);
        $data['tipos_documentos'] = $this->t_dnim->listar_todas_los_tipos_de_documentos();
        $data['tipos_sanciones'] = $this->t_sancionm->listar_tiopos_de_sancion();
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
        $cantidad_empleados = $this->llamado_atencionm->cantidad_llamados_atencion($_GET, $inicio, $filasPorPagina);
        $cantidad_empleados = $cantidad_empleados[0]->cantidad;
        $data['cantidad_empleados'] = $cantidad_empleados;
        $data['cantidad_paginas'] = ceil($cantidad_empleados / $filasPorPagina);
        $data["lista_empleados"] = $this->llamado_atencionm->listar_llamados_atencion($_GET, $inicio, $filasPorPagina);
        $this->load->view("header", $data);
        $this->load->view("llamado_atencion/consultar");
        $this->load->view("footer");
    }

}
