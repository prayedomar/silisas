<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
//        $data['base_url'] = base_url();
//        $this->parser->parse('iconos', $data);
//        echo ($this->select_model->nextId_salario()->id) + 1;
//        $SqlInfo = 'select * from empleado_x_sede AS a, sede AS b where (a.sede_secundaria = b.id) AND (a.dni_empleado=' . 1 . ')AND (a.id_empleado=' . 1 . ')AND (a.vigente=1)';
//        $query = $this->db->query($SqlInfo);
//        var_dump($query->result());
//        foreach ($query as $fila) {
//            echo $fila->sede_secundaria . " - " . $fila->nombre . "<br>";
//        }       
//        $where = "( NOT(id='1' AND dni='1')) AND estado!='3'";
//        $this->db->where($where);
//        $this->db->order_by('nombre1', 'asc');
//        $query = $this->db->get('empleado');
//        if ($query->num_rows() > 0) {
//            foreach ($query->result() as $fila){
//                echo $fila->nombre1 . "<br>";
//            }
//        }
//        $id_empleado =1;
//        $dni_empleado = 1;
//        $id_jefe = 98667633;
//        $dni_jefe = 1;
//        $where = "(NOT(id=' . $id_empleado . ' AND dni=' . $dni_empleado . ')) AND (estado!='3') AND (NOT(id=' . $id_jefe . ' AND dni=' . $dni_jefe . '))";
//        $this->db->where('$where');
//        $this->db->order_by('nombre1', 'asc');
//        $query = $this->db->get('empleado');
//        if ($query->num_rows() > 0) {
//            return $query->result();
//        }        
//        $where = "((sede_ppal IN(select sede_ppal from empleado where (id = 1128478351) and (dni = 1))) or (sede_ppal IN(select sede_secundaria from empleado_x_sede where (id_empleado = 1128478351) and (dni_empleado = 1) and (vigente = 1)))) and ( NOT(id='1' AND dni='1')) AND (estado!='3')";
//        $this->db->where($where);
//        $this->db->order_by('nombre1', 'asc');
//        $query = $this->db->get('empleado');
//        if ($query->num_rows() > 0) {
//            foreach ($query->result() as $fila){
//                echo $fila->nombre1 . "<br>";
//            }
//        }
        //Cantidad de dias entre dos fechas
//        $SqlInfo = "SELECT DATEDIFF('1997-12-31 23:59:59','1997-12-30') as cant_dias";
//        $query = $this->db->query($SqlInfo);
//        echo $query->row()->cant_dias;
//        echo $this->diasEntreFechas("2011-05-02", "2012-05-31");
//        try {
//            list($anyoStart, $mesStart, $diaStart) = explode("-", "2013-05-89");
//            echo $anyoStart . " - " . $mesStart . " - " . $diaStart;
//        } catch (Exception $e) {
//             echo $e->getMessage();
//            echo "El campo %s es inválido.";
////        }
//        
//        list($anyoStart, $mesStart, $diaStart) = explode("-", "2013-05-89");
//            echo $anyoStart . " - " . $mesStart . " - " . $diaStart;
//        
        //Sumar meses a una fecha
//        
//        $fecha = "2013-05-06";
//        $cantidad = 4;
//        echo date("Y-m-d", strtotime("$fecha +$cantidad month"));
//            $abonos = $this->select_model->adelanto_vigente_empleado(1128478351, 1);
//            if ($abonos == TRUE) {
//                foreach ($abonos as $fila) {
//                    echo '<tr>
//                            <td class="text-center"><input type="radio" class="exit_caution" name="cuenta" id="cuenta" value="' . $fila->id_adelanto . '"/></td>
//                            <td>' . $fila->total . '</td>
//                            <td class="text-center">' . $fila->saldo . '</td>
//                            <td>' . $fila->sede . '</td>
//                            <td>' . $fila->observacion . '</td>                                
//                            <td>' . $fila->fecha_trans . '</td>       
//                            <td class="text-center">' . $fila->fecha_trans . '</td>    
//                        </tr>';
//                }
//            } else {
//                echo "";
//            }
        //Variables que deben ser ingresadas por el usuario desde un formulario
//$valor = 6000000;
//$plazo = 36;
////Valor de la tasa de interes, debe ser ingresada por el administrador
//$tasa = 22.5;
//
//
//$anual = $tasa/100;
//$mes = round(($anual/12), 6);
//
//$cuota = $valor / ((pow((1+$mes), $plazo)-1)/($mes*pow((1+$mes), $plazo))); 
//
//$cpm = ($cuota/($valor/1000000));
//$cuota = number_format($cuota, 0, '.', ',');
//
//
//print '
//Valor: $' .$valor. '<br/>
//Tasa Anual: ' .$tasa. '%<br/>
//Tasa Mensual: ' .round(($tasa/12), 2). '%<br/>
//Cuota: $'.$cuota. '<br />
//Cuota por Millon: $'.number_format($cpm, 0, '.', ',');
//$total = 5000000;
//$cant_cuotas = 36;
//$tasa_interes = 0.025;
//echo round($total / ((1 - (pow((1 + 0.025), -$cant_cuotas))) / $tasa_interes),2);
//        Matriz de plan de pagos sistema frances
//        $id_prestamo = 1;
//        $prestamo = $this->select_model->prestamo_id($id_prestamo);
//        $fecha_prestamo = $prestamo->fecha_trans;
//        $total_prestamo = $prestamo->total;
//        $tasa_interes = $prestamo->tasa_interes / 100;
//        $cant_cuotas = $prestamo->cant_cuotas;
//        $cuota_fija = $prestamo->cuota_fija;
//
//        //La primera fila la hacemos manual para que la formula funciones.
//        $matriz_prestamo = array();
//        $matriz_prestamo[0][1] = 0;
//        $matriz_prestamo[0][2] = 0;
//        $matriz_prestamo[0][3] = 0;
//        $matriz_prestamo[0][4] = 0;
//        $matriz_prestamo[0][5] = 0;
//        $matriz_prestamo[0][6] = 0;
//        $matriz_prestamo[0][7] = 0;
//        $matriz_prestamo[0][8] = 0;
//        $matriz_prestamo[0][9] = $total_prestamo;
//        $matriz_prestamo[0][10] = $fecha_prestamo;
//        $matriz_prestamo[0][11] = "";
//        $matriz_prestamo[0][12] = 0;
//
//        //Llenamos de ceros todas las columnas de ceros que seran llenadas con pagos
//        for ($i = 1; $i <= $cant_cuotas; $i++) {
//            $matriz_prestamo[$i][4] = 0;
//            $matriz_prestamo[$i][5] = 0;
//            $matriz_prestamo[$i][6] = 0;
//            $matriz_prestamo[$i][11] = "";
//            $matriz_prestamo[$i][12] = 0;
//        }
//
//        //Llenamos los pagos realizados al prestamo
//        $abonos = $this->select_model->abono_prestamo_prestamo($id_prestamo);
//        if ($abonos == TRUE) {
//            $i = 1;
//            foreach ($abonos as $fila) {
//                $matriz_prestamo[$i][4] = $fila->subtotal;
//                $matriz_prestamo[$i][5] = $fila->cant_dias_mora;
//                $matriz_prestamo[$i][6] = $fila->int_mora;
//                $matriz_prestamo[$i][11] = date("Y-m-d", strtotime($fila->fecha_trans));
//                $matriz_prestamo[$i][12] = 1;
//                $i++;
//            }
//        }
//
//        //Llenamos las columnas que se calculan a partir de los pagos realizados
//        for ($i = 1; $i <= $cant_cuotas; $i++) {
//            $saldo_anterior = $matriz_prestamo[$i - 1][9];
//            $intereses = round($saldo_anterior * $tasa_interes, 2);
//            if (($saldo_anterior + $intereses) >= $cuota_fija) {
//                $cuota_minima = $cuota_fija;
//            } else {
//                $cuota_minima = round($saldo_anterior + $intereses, 2);
//            }
//            $cuota_maxima = round($saldo_anterior + $intereses, 2);
//            $cuota_pagada = $matriz_prestamo[$i][4];
//            if ($cuota_pagada != 0) {
//                $abono_capital = round($cuota_pagada - $intereses, 2);
//            } else {
//                $abono_capital = round($cuota_minima - $intereses, 2);
//            }
//            $saldo_prestamo = round($saldo_anterior - $abono_capital, 2);
//            //Si el saldo es mejor a 1 pesos se perdona. Por errores de aproximacion pueden quedar saldos
//            if ($saldo_prestamo < 1) {
//                $saldo_prestamo = 0.00;
//            }
//            $fecha_pago = date("Y-m-d", strtotime("$fecha_prestamo +$i month"));
//
//            $matriz_prestamo[$i][1] = $i;
//            $matriz_prestamo[$i][2] = $cuota_minima;
//            $matriz_prestamo[$i][3] = $cuota_maxima;
//            $matriz_prestamo[$i][7] = $abono_capital;
//            $matriz_prestamo[$i][8] = $intereses;
//            $matriz_prestamo[$i][9] = $saldo_prestamo;
//            $matriz_prestamo[$i][10] = $fecha_pago;
//
//            echo $matriz_prestamo[$i][1] . " - ";
//            echo $matriz_prestamo[$i][2] . " - ";
//            echo $matriz_prestamo[$i][3] . " - ";
//            echo $matriz_prestamo[$i][4] . " - ";
//            echo $matriz_prestamo[$i][5] . " - ";
//            echo $matriz_prestamo[$i][6] . " - ";
//            echo $matriz_prestamo[$i][7] . " - ";
//            echo $matriz_prestamo[$i][8] . " - ";
//            echo $matriz_prestamo[$i][9] . " - ";
//            echo $matriz_prestamo[$i][10] . " - ";
//            echo $matriz_prestamo[$i][11] . " - ";
//            echo $matriz_prestamo[$i][12];
//            echo "<br>";
//        }
//        //Recorrer matriz con foreach
//        $a = array();
//        $a[0][0] = "a";
//        $a[0][1] = "b";
//        $a[1][0] = "y";
//        $a[1][1] = "z";
//        foreach ($a as $v1) {
//            foreach ($v1 as $v2) {
//                echo "$v2\n";
//            }
//        }
//        $tasa_mora_anual = 72;
//        $dias_mora = 10;
//        $cuota_minima = 409.09;
//        echo ((((pow((1 + ($tasa_mora_anual / 100)), (1 / 360))) - 1) * $dias_mora) * $cuota_minima);
//        
//        $response = array(
//                            "respuesta" => 'OK',
//                            'mensaje' => ''
//                        );
//        $response['mensaje'] .= " joder";
//        $response['calor'] = "lobo";
//        
//        echo $response['mensaje'] . $response['calor'];
//        $data = array(
//            'cant_alumnos_disponibles' => '+1'
//        );
//        $this->db->set('cant_alumnos_disponibles', 'cant_alumnos_disponibles-1', FALSE);
//        $this->db->where('contrato', 12345);
////        $this->db->insert('mitabla');
//        $this->db->update('matricula');
////        $this->db->update('matricula', $data);
//        echo "ok";
//        echo date("d",strtotime("2013-02-3"));

        $periodicidad = '2';
        $fecha_inicio = "2013-02-16";
        $fecha_fin = "2013-02-28";
        if ($periodicidad == '1') {
            echo "OK";
        } else {
            if ($periodicidad == '2') {
                if ((date("m", strtotime($fecha_inicio)) == (date("m", strtotime($fecha_fin))))) {
                    if ((((date("d", strtotime($fecha_inicio))) == '01') && ((date("d", strtotime($fecha_fin))) == '15')) || (((date("d", strtotime($fecha_inicio))) == '16') && ((date("d", strtotime($fecha_fin)) == date("d", (mktime(0, 0, 0, date("m", strtotime($fecha_fin)) + 1, 1, date("Y", strtotime($fecha_fin))) - 1)))))) {
                        echo "OK";
                    } else {
                        echo "error";
                    }
                } else {
                    echo "error";
                }
            } else {
                if ($periodicidad == '3') {
                    
                } else {
                    if ($periodicidad == '4') {
                        
                    }
                }
            }
        }
    }

    function prueba_ajax() {
        $var1 = $this->input->post('variable1');
        $var2 = $this->input->post('variable2');

        $suma = $var1 + $var2;
        $producto = $var1 * $var2;

// Array con las respuestas
        $respuesta['suma'] = $suma;
        $respuesta['producto'] = $producto;

        echo json_encode($respuesta);
    }

    function diasEntreFechas($fechaStart, $fechaEnd) {
        list($anyoStart, $mesStart, $diaStart) = explode("-", $fechaStart);
        list($anyoEnd, $mesEnd, $diaEnd) = explode("-", $fechaEnd);

        $diasStartJuliano = gregoriantojd($mesStart, $diaStart, $anyoStart);
        $diasEndJuliano = gregoriantojd($mesEnd, $diaEnd, $anyoEnd);

        return $diasEndJuliano - $diasStartJuliano;
    }

    function exceptions_error_handler($severity, $message, $filename, $lineno) {
        if (error_reporting() == 0) {
            return;
        }
        if (error_reporting() & $severity) {
            throw new ErrorException($message, 0, $severity, $filename, $lineno);
        }
    }

}
