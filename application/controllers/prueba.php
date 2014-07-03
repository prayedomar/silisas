<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
            $id = '12372';
            $this->load->model('contrato_matriculam');
            $contrato_matricula = $this->contrato_matriculam->contrato_matricula_id($id);
            if ($contrato_matricula == TRUE) {
                if ($contrato_matricula->estado != 3) {
                    $response = array(
                        'respuesta' => 'OK',
                        'filasTabla' => ''
                    );
                    $response['filasTabla'] .= '<tr>
                            <td class="text-center">' . $contrato_matricula->id . '</td>
                            <td class="text-center">' . $contrato_matricula->sede . '</td>
                            <td class="text-center">' . $contrato_matricula->estado_contrato . '</td>                              
                            <td class="text-center">' . $contrato_matricula->responsable . '</td>       
                            <td class="text-center">' . date("Y-m-d", strtotime($contrato_matricula->fecha_trans)) . '</td>
                        </tr>';
                    echo json_encode($response);
                    return false;
                } else {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><strong><center>El contrato físico de matrícula, ya se encuentra anulada.</center></strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong><center>El contrato físico de matrícula, no existe en la base de datos.</center></strong></p>'
                );
                echo json_encode($response);
                return false;
            }
    }

}
