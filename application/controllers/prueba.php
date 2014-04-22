<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
        $id_matricula = 15548;
        $matriz_matricula = $this->matriz_matricula($id_matricula);
        if ($matriz_matricula) {
            $matricula = $this->select_model->matricula_id($id_matricula);
            $plan = $this->select_model->t_plan_id($matricula->plan);
            $cant_cuotas = $plan->cant_cuotas;
            $response = array(
                'respuesta' => 'OK',
                'filasTabla' => ''
            );
            //Solo mostrará las cuotas pendientes de pago
            for ($i = 0; $i <= $cant_cuotas; $i++) {
                //Solo se mostraran las cuotas que no se han cancelado
                if ($matriz_matricula[$i][6] == 0) {
                    $num_cuota = $matriz_matricula[$i][1];
                    $id_t_detalle = $matriz_matricula[$i][2];
                    $t_detalle = $this->select_model->t_detalle($id_t_detalle)->tipo;
                    $valor_pendiente = $matriz_matricula[$i][5];
                    $fecha_esperada = $matriz_matricula[$i][7];
                    $cant_dias_mora = $matriz_matricula[$i][8];
                    $int_mora = $matriz_matricula[$i][9];

                    $response['filasTabla'] .= '<tr>
                            <td class="text-center"><input type="checkbox" class="exit_caution" name="cuotas[]" id="cuotas"  value="' . $num_cuota . "_" . $id_t_detalle . "_" . $t_detalle . "_" . $valor_pendiente . "_" . $fecha_esperada . "_" . $cant_dias_mora . "_" . $int_mora . '" data-num_cuota="' . $num_cuota . '" data-t_detalle="' . $t_detalle . '" data-valor_pendiente="' . $valor_pendiente . '" data-fecha_esperada="' . $fecha_esperada . '" data-cant_dias_mora="' . $cant_dias_mora . '" data-int_mora="' . $int_mora . '" /></td>
                            <td class="text-center">' . $num_cuota . '</td>
                            <td class="text-center">' . $t_detalle . '</td>
                            <td class="text-center">$' . number_format($valor_pendiente, 2, '.', ',') . '</td>                            
                            <td class="text-center">' . $fecha_esperada . '</td> 
                            <td class="text-center">' . $cant_dias_mora . '</td>                                
                            <td class="text-center">$' . number_format($int_mora, 2, '.', ',') . '</td>
                        </tr>';
                }
            }
//                    echo $response['filasTabla'];
            echo json_encode($response);
            return false;
        } else {
            $response = array(
                'respuesta' => 'error'
            );
            echo json_encode($response);
            return false;
        }
    }
    
    function matriz_matricula($id_matricula) {
        $matricula = $this->select_model->matricula_id($id_matricula);
        $plan = $this->select_model->t_plan_id($matricula->plan);
        $fecha_inicio = date("Y-m-d", strtotime($matricula->fecha_matricula));
        $fecha_hoy = date('Y-m-d');
        $valor_total = $plan->valor_total;
        $cant_cuotas = $plan->cant_cuotas;
        $valor_inicial = $plan->valor_inicial;
        $valor_cuota = $plan->valor_cuota;
        $total_abonos = $this->select_model->total_abonos_matricula($id_matricula)->total;
        $tasa_mora_anual = $this->select_model->interes_mora()->tasa_mora_anual;

        //Llenamos la primer fila
        $matriz_matricula = array();
        $matriz_matricula[0][1] = 0; //Numero de cuota
        if ($cant_cuotas == 0) { //Plan contado
            $matriz_matricula[0][2] = 3; //T_detalle: pago total 
        } else {
            $matriz_matricula[0][2] = 1; //T_detalle: pago inicial
        }
        $matriz_matricula[0][3] = $valor_inicial; //Valor esperado
        //Miramos si se canceló esta cuota con el total de abonos
        if ($total_abonos >= $valor_inicial) {
            $total_abonos = $total_abonos - $valor_inicial; //Restamos del total de abonos esta cuota
            $matriz_matricula[0][4] = $valor_inicial; //Abonado
        } else {
            $matriz_matricula[0][4] = $total_abonos; //Abonado
            $total_abonos = 0; //Gastamos todo el total de abonados en este pago
        }
        $matriz_matricula[0][5] = $matriz_matricula[0][3] - $matriz_matricula[0][4]; //Valor pendiente de pago
        if ($matriz_matricula[0][5] <= 0) { //Puede que sea negativo en el caso en que halla abonado mas de lo esperado
            $matriz_matricula[0][6] = 1; //1: Cuota cancelada
        } else {
            $matriz_matricula[0][6] = 0; //0: Cuota no cancelada
        }
        $matriz_matricula[0][7] = $fecha_inicio; //Fecha esperada
        $valor_esperado = $matriz_matricula[0][3];
        $cuota_cancelada = $matriz_matricula[0][6];
        $fecha_esperada = $matriz_matricula[0][7];
        $matriz_matricula[0][8] = 0; //dias mora
        $matriz_matricula[0][9] = 0; //int mora            
        if (($cuota_cancelada == 0) && ($fecha_esperada < $fecha_hoy)) {
            $dias_mora = $this->dias_entre_fechas($fecha_esperada, $fecha_hoy);
            //Descartamos una mora inferior a 4 dias de gracia.   
            //Pero si es mayor a 4 la contamos completa sin descartar los 4 dias.
            if ($dias_mora > 4) {
                $matriz_matricula[0][8] = $dias_mora;
                if ($tasa_mora_anual) {
                    $int_mora = round(((((pow((1 + ($tasa_mora_anual / 100)), (1 / 360))) - 1) * $dias_mora) * $matriz_matricula[0][5]), 2);
                    $matriz_matricula[0][9] = $int_mora;
                }
            }
        }

        //Calculamos el resto de cuotas
        for ($i = 1; $i <= $cant_cuotas; $i++) {
            $matriz_matricula[$i][1] = $i; //Numero de cuota
            $matriz_matricula[$i][2] = 2; //T_detalle: Abono total 
            $matriz_matricula[$i][3] = $valor_cuota; //valor esperado
            $valor_esperado = $matriz_matricula[$i][3];
            if ($total_abonos >= $valor_esperado) {
                $total_abonos = $total_abonos - $valor_esperado; //Restamos del total de abonos esta cuota
                $matriz_matricula[$i][4] = $valor_esperado; //Abonado
            } else {
                $matriz_matricula[$i][4] = $total_abonos; //Abonado
                $total_abonos = 0; //Gastamos todo el total de abonados en este pago
            }
            $valor_abonado = $matriz_matricula[$i][4];
            $matriz_matricula[$i][5] = $valor_esperado - $valor_abonado; //valor pendiente
            $valor_pendiente = $matriz_matricula[$i][5];
            if ($valor_pendiente <= 0) {
                $matriz_matricula[$i][6] = 1; //0: Cuota cancelada
            } else {
                $matriz_matricula[$i][6] = 0;  //0: Cuota no cancelada
            }
            $matriz_matricula[$i][7] = date("Y-m-d", strtotime("$fecha_inicio +$i month")); //Fecha esperada
            $cuota_cancelada = $matriz_matricula[$i][6];
            $fecha_esperada = $matriz_matricula[$i][7];
            $matriz_matricula[$i][8] = 0; //dias mora
            $matriz_matricula[$i][9] = 0; //int mora         
            if (($cuota_cancelada == 0) && ($fecha_esperada < $fecha_hoy)) {
                $dias_mora = $this->dias_entre_fechas($fecha_esperada, $fecha_hoy);
                //Descartamos una mora inferior a 4 dias de gracia.   
                //Pero si es mayor a 4 la contamos completa sin descartar los 4 dias.
                if ($dias_mora > 4) {
                    $matriz_matricula[$i][8] = $dias_mora;
                    if ($tasa_mora_anual) {
                        $Int_mora = round(((((pow((1 + ($tasa_mora_anual / 100)), (1 / 360))) - 1) * $dias_mora) * $matriz_matricula[$i][5]), 2);
                        $matriz_matricula[$i][9] = $Int_mora;
                    }
                }
            }
        }
        return $matriz_matricula;
    }

    

}
