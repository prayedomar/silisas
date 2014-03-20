<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Update_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function cambiar_contraseña($id, $dni, $t_usuario, $new_password) {
        $data = array(
            'password' => $new_password
        );
        $this->db->where('id', $id);
        $this->db->where('dni', $dni);
        $this->db->where('t_usuario', $t_usuario);
        $this->db->update('usuario', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }    
    
    public function empleado_sede_ppal($id, $dni, $sede_ppal) {
        $data = array(
            'sede_ppal' => $sede_ppal
        );
        $this->db->where('id', $id);
        $this->db->where('dni', $dni);
        $this->db->update('empleado', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function empleado_x_sede($id_empleado, $dni_empleado, $sede_secundaria, $vigente) {
        $data = array(
            'vigente' => $vigente
        );
        $this->db->where('id_empleado', $id_empleado);
        $this->db->where('dni_empleado', $dni_empleado);
        $this->db->where('sede_secundaria', $sede_secundaria);
        $this->db->update('empleado_x_sede', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cuenta_x_sede($cuenta, $sede, $vigente) {
        $data = array(
            'vigente' => $vigente
        );
        $this->db->where('cuenta', $cuenta);
        $this->db->where('sede', $sede);
        $this->db->update('cuenta_x_sede', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cuenta_x_sede_x_empleado_todos($cuenta, $sede, $vigente) {
        $data = array(
            'vigente' => $vigente
        );
        $this->db->where('cuenta', $cuenta);
        $this->db->where('sede', $sede);
        $this->db->update('cuenta_x_sede_x_empleado', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cuenta_x_sede_x_empleado($cuenta, $sede, $id_encargado, $dni_encargado, $vigente) {
        $data = array(
            'vigente' => $vigente
        );
        $this->db->where('cuenta', $cuenta);
        $this->db->where('sede', $sede);
        $this->db->where('id_encargado', $id_encargado);
        $this->db->where('dni_encargado', $dni_encargado);
        $this->db->update('cuenta_x_sede_x_empleado', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function matricula_cant_alumnos_mermar($matricula) {
        $this->db->set('cant_alumnos_disponibles', 'cant_alumnos_disponibles-1', FALSE);
        $this->db->where('contrato', $matricula);
        $this->db->update('matricula');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function empleado_cargo($id, $dni, $cargo) {
        $data = array(
            'cargo' => $cargo
        );
        $this->db->where('id', $id);
        $this->db->where('dni', $dni);
        $this->db->update('empleado', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function empleado_jefe($id, $dni, $id_jefe, $dni_jefe) {
        $data = array(
            'id_jefe' => $id_jefe,
            'dni_jefe' => $dni_jefe
        );
        $this->db->where('id', $id);
        $this->db->where('dni', $dni);
        $this->db->update('empleado', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function solicitud_placa_pendiente($id, $pendiente) {
        $data = array(
            'pendiente' => $pendiente
        );
        $this->db->where('id', $id);
        $this->db->update('solicitud_placa', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function despachar_placa_pendiente($id, $pendiente) {
        $data = array(
            'pendiente' => $pendiente
        );
        $this->db->where('id', $id);
        $this->db->update('despachar_placa', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function contrato_laboral_estado($id_contrato, $dni_contrato, $new_estado) {
        $data = array(
            'estado' => $new_estado
        );
        $this->db->where('id_empleado', $id_contrato);
        $this->db->where('dni_empleado', $dni_contrato);
        $this->db->update('contrato_laboral', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function adelanto_estado($prefijo_adelanto, $id_adelanto, $new_estado) {
        $data = array(
            'estado' => $new_estado
        );
        $this->db->where('prefijo', $prefijo_adelanto);
        $this->db->where('id', $id_adelanto);
        $this->db->update('adelanto', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function prestamo_estado($prefijo_prestamo, $id_prestamo, $new_estado) {
        $data = array(
            'estado' => $new_estado
        );
        $this->db->where('prefijo', $prefijo_prestamo);
        $this->db->where('id', $id_prestamo);
        $this->db->update('prestamo', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }
    
    public function contrato_matricula_estado($id, $new_estado) {
        $data = array(
            'estado' => $new_estado
        );
        $this->db->where('id', $id);
        $this->db->update('contrato_matricula', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }    
    
    public function contrato_matricula_sede_actual($id, $sede_actual) {
        $data = array(
            'sede_actual' => $sede_actual
        );
        $this->db->where('id', $id);
        $this->db->update('contrato_matricula', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    } 
    
    
    public function matricula_liquidacion_escalas($id_matricula, $liquidada) {
        $data = array(
            'liquidacion_escalas' => $liquidada
        );
        $this->db->where('contrato', $id_matricula);
        $this->db->update('matricula', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }
    
    public function ejecutivo_matricula($id_matricula, $id_ejecutivo, $dni_ejecutivo) {
        $data = array(
            'id_ejecutivo' => $id_ejecutivo,
            'dni_ejecutivo' => $dni_ejecutivo
        );
        $this->db->where('contrato', $id_matricula);
        $this->db->update('matricula', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }  
    
    public function concepto_nomina_rrpp_ok($id, $prefijo_nomina, $id_nomina) {
        $data = array(
            'prefijo_nomina' => $prefijo_nomina,
            'id_nomina' => $id_nomina,
            'estado' => 1
        );
        $this->db->where('id', $id);
        $this->db->update('concepto_nomina', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }     
    
    public function alumno($id, $dni, $nombre1, $nombre2, $apellido1, $apellido2, $fecha_nacimiento, $genero, $pais, $provincia, $ciudad, $t_domicilio, $direccion, $barrio, $telefono, $celular, $email, $velocidad_ini, $comprension_ini, $t_curso, $cant_clases, $est_alumno, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'nombre1' => $nombre1,
            'nombre2' => $nombre2,
            'apellido1' => $apellido1,
            'apellido2' => $apellido2,
            'fecha_nacimiento' => $fecha_nacimiento,
            'genero' => $genero,
            'pais' => $pais,
            'provincia' => $provincia,
            'ciudad' => $ciudad,
            't_domicilio' => $t_domicilio,
            'direccion' => $direccion,
            'barrio' => $barrio,
            'telefono' => $telefono,
            'celular' => $celular,
            'email' => $email,
            'velocidad_ini' => $velocidad_ini,
            'comprension_ini' => $comprension_ini,
            't_curso' => $t_curso,
            'cant_clases' => $cant_clases,
            'estado' => $est_alumno,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->where('id', $id);
        $this->db->where('dni', $dni);        
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->update('alumno');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    } 
    
    public function cambio_plan_matricula($id_matricula, $plan_new) {
        $data = array(
            'plan' => $plan_new
        );
        $this->db->where('contrato', $id_matricula);
        $this->db->update('matricula', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }      

}
