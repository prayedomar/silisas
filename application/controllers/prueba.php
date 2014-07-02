<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
        $prefijo = 'flst';
        $id = '1';
        $this->load->model('transferenciam');
        $transferencia = $this->transferenciam->transferencia_prefijo_id($prefijo, $id);
        if ($transferencia == TRUE) {
            if ($transferencia->est_traslado == 3) {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong><center>La transferencia intersede, ya se encuentra anulada.</center></strong></p>'
                );
                echo json_encode($response);
                return false;
            } else {

                if ($transferencia->est_traslado == 2) {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><strong><center>La transferencia intersede, está pendiente por aprobar.</center></strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                } else {

                    $response = array(
                        'respuesta' => 'OK',
                        'filasTabla' => ''
                    );
                    $response['filasTabla'] .= '<tr>
                            <td class="text-center">' . $transferencia->total . '</td>
                            <td>
                            <p><b>Sede origen:</b> ' . $transferencia->nombre_sede_origen . '</p>
                            <p><b>Remitente:</b> ' . $transferencia->nombre_remitente . '</p>';
                    if ($transferencia->sede_caja_origen != NULL) {
                        $response['filasTabla'] .= '<p><b>Nombre de la caja:</b> ' . $transferencia->nombre_caja_origen . '</p>
                            <p><b>Efectivo retirado de caja:</b> $' . number_format($transferencia->efectivo_retirado, 2, '.', ',') . '</p>';
                    }
                    if ($transferencia->cuenta_origen != NULL) {
                        $response['filasTabla'] .= '<p><b>Número de la cuenta:</b> ' . $transferencia->cuenta_origen . '</p>
                            <p><b>Valor retirado de cuenta:</b> $' . number_format($transferencia->valor_retirado, 2, '.', ',') . '</p>';
                    }
                    $response['filasTabla'] .= '</td>
                            <td>
                            <p><b>Sede destino:</b> ' . $transferencia->nombre_sede_destino . '</p>';
                    if ($transferencia->tipo_destino == 1) {
                        $response['filasTabla'] .= '<p><b>Tipo destino:</b> Caja</p>
                              <p><b>Nombre de la caja:</b> ' . $transferencia->nombre_caja_destino . '</p>
                              <p><b>Efectivo enviado a la caja:</b> $' . number_format($transferencia->efectivo_ingresado, 2, '.', ',') . '</p>';
                    } else {
                        $response['filasTabla'] .= '<p><b>Tipo destino:</b> Cuenta</p>
                              <p><b>Número de la cuenta:</b> ' . $transferencia->cuenta_destino . '</p>
                              <p><b>Valor enviado a la cuenta:</b> $' . number_format($transferencia->valor_consignado, 2, '.', ',') . '</p>';
                    }
                    $response['filasTabla'] .= '</td>
                            <td class="text-center">' . $transferencia->observacion . '</td>                                
                            <td class="text-center">' . date("Y-m-d", strtotime($transferencia->fecha_trans)) . '</td>
                        </tr>';
                    echo json_encode($response);
                    return false;
                }
            }
        } else {
            $response = array(
                'respuesta' => 'error',
                'mensaje' => '<p><strong><center>La transferencia intersede, no existe en la base de datos.</center></strong></p>'
            );
            echo json_encode($response);
            return false;
        }
    }

}
