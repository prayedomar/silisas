<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
        $tal_cosa = $this->valida_llena_pagos();
        var_dump($tal_cosa);
    }

    public function valida_llena_pagos() {
        $this->load->model('matriculam');
        $id_matricula = '12297';
        $matricula = $this->select_model->matricula_id($id_matricula);
        if ($matricula == TRUE) {
            $pagos = $this->matriculam->pagos_matricula_id($id_matricula);
            if ($pagos == TRUE) {
                $response = array(
                    'respuesta' => 'OK',
                    'html_pagos' => ''
                );
                $response['html_pagos'] = '<div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Tipo</th>
                                                <th class="text-center">Id</th>
                                                <th class="text-center">Subtotal</th>
                                                <th class="text-center">Int. Mora</th>                                                
                                                <th class="text-center">Descuento</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">Reponsable</th>
                                                <th class="text-center">Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                foreach ($pagos as $fila) {
                    if ($fila->t_trans == '8') {
                        $t_trans = 'F.V.';
                    } else {
                        $t_trans = 'R.C.';
                    }
                    $response['html_pagos'] .= '<tr>
                                <td class="text-center">' . $t_trans . '</td>
                                <td class="text-center">' . $fila->prefijo . " " . $fila->id . '</td> 
                                <td class="text-center">$' . number_format($fila->subtotal, 2, '.', ',') . '</td>
                                <td class="text-center">$' . number_format($fila->int_mora, 2, '.', ',') . '</td>
                                <td class="text-center">$' . number_format($fila->descuento, 2, '.', ',') . '</td>
                                    <td class="text-center">$' . number_format(($fila->subtotal + $fila->int_mora - $fila->descuento), 2, '.', ',') . '</td>
                                <td class="text-center">' . $fila->responsable . '</td>
                                <td class="text-center">' . $fila->fecha_trans . '</td>
                            </tr>';
                }
                $response['html_pagos'] .= '</tbody>
                        </table>
                    </div>';
                echo json_encode($response);
                return false;
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong><center>La matrícula no tiene ningún pago vigente.</center></strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            $response = array(
                'respuesta' => 'error',
                'mensaje' => '<p><strong><center>La matrícula no existe en la base de datos.</center></strong></p>'
            );
            echo json_encode($response);
            return false;
        }
    }

}
