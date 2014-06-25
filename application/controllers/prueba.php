<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
        echo $total_abonos = $this->select_model->total_abonos_matricula(17715)->total;
    }

    public function validar_alumno() {
        $dni_alumno = '1';
        $id_alumno = '95042409961';
        $this->load->model('alumnom');
        $alumno = $this->alumnom->alumno_id_dni($id_alumno, $dni_alumno);
            if ($alumno == TRUE) {
                $this->load->model('reporte_alumnom');
                $this->load->model('ejercicio_ensenanzam');
                $reportes_anteriores = $this->reporte_alumnom->reporte_alumno($id_alumno, $dni_alumno);
                if ($reportes_anteriores == TRUE) {
                    $html_reportes = '<div class="col-xs-12 separar_div"><legend>Reportes anteriores</legend><div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Fecha de la clase</th>                                            
                                                <th class="text-center">¿Asistió?</th>
                                                <th class="text-center">Etapa</th>
                                                <th class="text-center">Fase</th>
                                                <th class="text-center"># Prácticas</th>
                                                <th class="text-center">lectura</th>
                                                <th class="text-center">V M</th>
                                                <th class="text-center">V V</th>
                                                <th class="text-center">C</th>
                                                <th class="text-center">R</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($reportes_anteriores as $fila) {
                        $ejercicios = $this->ejercicio_ensenanzam->ejercicio_reporte_ensenanza($fila->id);
                        $lista_ejercicios = "";
                        foreach ($ejercicios as $fila2) {
                            $lista_ejercicios .= $fila2->habilidad . " - " . $fila2->ejercicio . " <br>";
                        }
                        $html_reportes .= '<tr>
                                <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_clase)) . '</td>                             
                                <td class="text-center">' . $fila->asistio . '</td>                            
                                <td class="text-center">' . $fila->etapa . '</td>
                                <td>' . $fila->fase . '</td>  
                                <td class="text-center">' . $fila->cant_practicas . '</td>                                
                                <td>' . $fila->lectura . '</td> 
                                <td class="text-center">' . $fila->vlm . '</td> 
                                <td class="text-center">' . $fila->vlv . '</td>
                                <td class="text-center">' . $fila->c . '</td> 
                                <td class="text-center">' . $fila->r . '</td> 
                                <td><button class="ver-detalles btn  btn-primary btn-sm" 
                                                    data-meta_v="' . $fila->meta_v . '"
                                                    data-meta_c="' . $fila->meta_c . '"
                                                    data-meta_r="' . $fila->meta_r . '"
                                                    data-observacion="' . $fila->observacion . '"
                                                    data-reponsable="' . $fila->responsable . '"
                                                    data-fecha_trans="' . $fila->fecha_trans . '"
                                                    >Ver detalles</button></td>
                            </tr>';
                    }
                $html_reportes .= '</tbody>
                        </table>
                    </div>';
            } else {
                $html_reportes = "";
            }
            //Buscamos si tiene reportes vigente anteriores.
            $response = array(
                'respuesta' => 'OK',
                'nombre_alumno' => $alumno->nombre_alumno,
                'tipo_curso' => $alumno->tipo_curso,
                't_curso' => $alumno->t_curso,
                'html_reportes' => $html_reportes
            );
            echo json_encode($response);
            return false;
        } else {
            $response = array(
                'respuesta' => 'error',
                'mensaje' => '<p><strong><center>El alumno no existe en la base de datos.</center></strong></p>'
            );
            echo json_encode($response);
            return false;
        }
    }

}
