<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
        $this->load->model('matriculam');
        $matricula = $this->matriculam->matricula_id('11897');
        if ($matricula == TRUE) {
            if ($matricula->estado != '5') {
                $response = array(
                    'respuesta' => 'OK',
                    'filasTabla' => ''
                );
                $response['filasTabla'] .= '<tr>
                            <td class="text-center">' . $matricula->titular . '</td>
                            <td>' . $matricula->nombre_plan . '</td>
                            <td class="text-center">$' . number_format($matricula->valor_total, 2, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($matricula->saldo, 2, '.', ',') . '</td>                             
                            <td class="text-center">' . $matricula->sede . '</td>                         
                            <td class="text-center">' . date("Y-m-d", strtotime($matricula->fecha_trans)) . '</td>  
                        </tr>';
                echo json_encode($response);
                return false;
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong>La matrícula se encuentra anulada.</strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            $response = array(
                'respuesta' => 'error',
                'mensaje' => '<p><strong>La matrícula no existe en la base de datos.</strong></p>'
            );
            echo json_encode($response);
            return false;
        }
    }

}
