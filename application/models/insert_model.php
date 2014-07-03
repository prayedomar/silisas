<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Insert_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function new_sede($id_sede, $nombre, $pais, $provincia, $ciudad, $direccion, $tel1, $tel2, $prefijo_trans, $estado, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'id' => $id_sede,
            'nombre' => $nombre,
            'pais' => $pais,
            'provincia' => $provincia,
            'ciudad' => $ciudad,
            'direccion' => $direccion,
            'tel1' => $tel1,
            'tel2' => $tel2,
            'prefijo_trans' => $prefijo_trans,
            'estado' => $estado,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        //para devolver el error que mande la inserccion
        $this->db->insert('sede');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function new_salon($nombre, $capacidad, $sede, $vigente, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'nombre' => $nombre,
            'capacidad' => $capacidad,
            'sede' => $sede,
            'vigente' => $vigente,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('salon');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function new_empleado($id, $dni, $t_usuario, $nombre1, $nombre2, $apellido1, $apellido2, $fecha_nacimiento, $genero, $est_civil, $pais, $provincia, $ciudad, $t_domicilio, $direccion, $barrio, $telefono, $celular, $email, $cuenta, $sede_ppal, $depto, $cargo, $salario, $id_jefe, $dni_jefe, $estado, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'id' => $id,
            'dni' => $dni,
            't_usuario' => $t_usuario,
            'nombre1' => $nombre1,
            'nombre2' => $nombre2,
            'apellido1' => $apellido1,
            'apellido2' => $apellido2,
            'fecha_nacimiento' => $fecha_nacimiento,
            'genero' => $genero,
            'est_civil' => $est_civil,
            'pais' => $pais,
            'provincia' => $provincia,
            'ciudad' => $ciudad,
            't_domicilio' => $t_domicilio,
            'direccion' => $direccion,
            'barrio' => $barrio,
            'telefono' => $telefono,
            'celular' => $celular,
            'email' => $email,
            'cuenta' => $cuenta,
            'sede_ppal' => $sede_ppal,
            'depto' => $depto,
            'cargo' => $cargo,
            'salario' => $salario,
            'id_jefe' => $id_jefe,
            'dni_jefe' => $dni_jefe,
            'estado' => $estado,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('empleado');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cliente($id, $dni, $t_usuario, $nombre1, $nombre2, $apellido1, $apellido2, $fecha_nacimiento, $genero, $pais, $provincia, $ciudad, $t_domicilio, $direccion, $barrio, $telefono, $celular, $email, $sede_ppal, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'id' => $id,
            'dni' => $dni,
            't_usuario' => $t_usuario,
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
            'sede_ppal' => $sede_ppal,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('cliente');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function alumno($id, $dni, $t_usuario, $nombre1, $nombre2, $apellido1, $apellido2, $fecha_nacimiento, $genero, $pais, $provincia, $ciudad, $t_domicilio, $direccion, $barrio, $telefono, $celular, $email, $matricula, $velocidad_ini, $comprension_ini, $t_curso, $estado, $grados, $cant_clases, $sede_ppal, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'id' => $id,
            'dni' => $dni,
            't_usuario' => $t_usuario,
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
            'matricula' => $matricula,
            'velocidad_ini' => $velocidad_ini,
            'comprension_ini' => $comprension_ini,
            't_curso' => $t_curso,
            'estado' => $estado,
            'grados' => $grados,
            'cant_clases' => $cant_clases,
            'sede_ppal' => $sede_ppal,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('alumno');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function titular($id, $dni, $t_usuario, $nombre1, $nombre2, $apellido1, $apellido2, $fecha_nacimiento, $genero, $pais, $provincia, $ciudad, $t_domicilio, $direccion, $barrio, $telefono, $celular, $email, $vigente, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'id' => $id,
            'dni' => $dni,
            't_usuario' => $t_usuario,
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
            'vigente' => $vigente,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('titular');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function new_proveedor($id, $dni, $d_v, $razon_social, $pais, $provincia, $ciudad, $t_domicilio, $direccion, $telefono, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'id' => $id,
            'dni' => $dni,
            'd_v' => $d_v,
            'razon_social' => $razon_social,
            'pais' => $pais,
            'provincia' => $provincia,
            'ciudad' => $ciudad,
            't_domicilio' => $t_domicilio,
            'direccion' => $direccion,
            'telefono' => $telefono,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('proveedor');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function new_usuario($id, $dni, $genero, $nombres, $t_usuario, $password, $email, $perfil, $vigente) {
        $data = array(
            'id' => $id,
            'dni' => $dni,
            'genero' => $genero,
            'nombres' => $nombres,
            't_usuario' => $t_usuario,
            'password' => $password,
            'email' => $email,
            'perfil' => $perfil,
            'vigente' => $vigente
        );
        $this->db->insert('usuario', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function new_salario($id_salario, $nombre, $t_salario, $vigente, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'id' => $id_salario,
            'nombre' => $nombre,
            't_salario' => $t_salario,
            'vigente' => $vigente,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('salario');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function new_concepto_base($salario, $t_concepto_nomina, $valor_unitario) {
        $data = array(
            'salario' => $salario,
            't_concepto_nomina' => $t_concepto_nomina,
            'valor_unitario' => $valor_unitario
        );
        $this->db->insert('concepto_base_nomina', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cambio_sede_empleado($id_empleado, $dni_empleado, $sede_ppal, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'sede_ppal' => $sede_ppal,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('cambio_sede_empleado');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function empleado_x_sede($id_empleado, $dni_empleado, $sede_secundaria) {
        $this->db->where('id_empleado', $id_empleado);
        $this->db->where('dni_empleado', $dni_empleado);
        $this->db->where('sede_secundaria', $sede_secundaria);
        $query = $this->db->get('empleado_x_sede');
        if ($query->num_rows() == 1) {
            //Si la sede ya existe actualizo el vigente a 1
            $data = array(
                'vigente' => 1
            );
            $this->db->where('id_empleado', $id_empleado);
            $this->db->where('dni_empleado', $dni_empleado);
            $this->db->where('sede_secundaria', $sede_secundaria);
            $this->db->update('empleado_x_sede', $data);
        } else {
            $data = array(
                'id_empleado' => $id_empleado,
                'dni_empleado' => $dni_empleado,
                'sede_secundaria' => $sede_secundaria,
                'vigente' => 1
            );
            $this->db->insert('empleado_x_sede', $data);
        }
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cuenta_x_sede($cuenta, $sede, $vigente) {
        $this->db->where('cuenta', $cuenta);
        $this->db->where('sede', $sede);
        $query = $this->db->get('cuenta_x_sede');
        if ($query->num_rows() == 1) {
            //Si la sede ya existe actualizo el vigente a 1
            $data = array(
                'vigente' => $vigente
            );
            $this->db->where('cuenta', $cuenta);
            $this->db->where('sede', $sede);
            $this->db->update('cuenta_x_sede', $data);
        } else {
            $data = array(
                'cuenta' => $cuenta,
                'sede' => $sede,
                'vigente' => $vigente
            );
            $this->db->insert('cuenta_x_sede', $data);
        }
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cuenta_x_sede_x_empleado_ingresar($cuenta, $sede, $id_encargado, $dni_encargado, $vigente) {
        $this->db->where('cuenta', $cuenta);
        $this->db->where('sede', $sede);
        $this->db->where('id_encargado', $id_encargado);
        $this->db->where('dni_encargado', $dni_encargado);
        $query = $this->db->get('cuenta_x_sede_x_empleado');
        if ($query->num_rows() == 1) {
            //Si la sede ya existe actualizo el vigente a 1
            $data = array(
                'permiso_ingresar' => $vigente
            );
            $this->db->where('cuenta', $cuenta);
            $this->db->where('sede', $sede);
            $this->db->where('id_encargado', $id_encargado);
            $this->db->where('dni_encargado', $dni_encargado);
            $this->db->update('cuenta_x_sede_x_empleado', $data);
        } else {
            $data = array(
                'cuenta' => $cuenta,
                'sede' => $sede,
                'id_encargado' => $id_encargado,
                'dni_encargado' => $dni_encargado,
                'permiso_ingresar' => $vigente
            );
            $this->db->insert('cuenta_x_sede_x_empleado', $data);
        }
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cuenta_x_sede_x_empleado_retirar($cuenta, $sede, $id_encargado, $dni_encargado, $vigente) {
        $this->db->where('cuenta', $cuenta);
        $this->db->where('sede', $sede);
        $this->db->where('id_encargado', $id_encargado);
        $this->db->where('dni_encargado', $dni_encargado);
        $query = $this->db->get('cuenta_x_sede_x_empleado');
        if ($query->num_rows() == 1) {
            //Si la sede ya existe actualizo el vigente a 1
            $data = array(
                'permiso_retirar' => $vigente
            );
            $this->db->where('cuenta', $cuenta);
            $this->db->where('sede', $sede);
            $this->db->where('id_encargado', $id_encargado);
            $this->db->where('dni_encargado', $dni_encargado);
            $this->db->update('cuenta_x_sede_x_empleado', $data);
        } else {
            $data = array(
                'cuenta' => $cuenta,
                'sede' => $sede,
                'id_encargado' => $id_encargado,
                'dni_encargado' => $dni_encargado,
                'permiso_retirar' => $vigente
            );
            $this->db->insert('cuenta_x_sede_x_empleado', $data);
        }
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cuenta_x_sede_x_empleado_consultar($cuenta, $sede, $id_encargado, $dni_encargado, $vigente) {
        $this->db->where('cuenta', $cuenta);
        $this->db->where('sede', $sede);
        $this->db->where('id_encargado', $id_encargado);
        $this->db->where('dni_encargado', $dni_encargado);
        $query = $this->db->get('cuenta_x_sede_x_empleado');
        if ($query->num_rows() == 1) {
            //Si la sede ya existe actualizo el vigente a 1
            $data = array(
                'permiso_consultar' => $vigente
            );
            $this->db->where('cuenta', $cuenta);
            $this->db->where('sede', $sede);
            $this->db->where('id_encargado', $id_encargado);
            $this->db->where('dni_encargado', $dni_encargado);
            $this->db->update('cuenta_x_sede_x_empleado', $data);
        } else {
            $data = array(
                'cuenta' => $cuenta,
                'sede' => $sede,
                'id_encargado' => $id_encargado,
                'dni_encargado' => $dni_encargado,
                'permiso_consultar' => $vigente
            );
            $this->db->insert('cuenta_x_sede_x_empleado', $data);
        }
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function anular_empleado_x_sede($id_empleado, $dni_empleado, $sede_secundaria, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'sede_secundaria' => $sede_secundaria,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('anular_empleado_x_sede');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function asignar_empleado_x_sede($id_empleado, $dni_empleado, $sede_secundaria, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'sede_secundaria' => $sede_secundaria,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('asignar_empleado_x_sede');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function asignar_cuenta_x_sede($cuenta, $sede, $id_responsable, $dni_responsable) {
        $data = array(
            'cuenta' => $cuenta,
            'sede' => $sede,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('asignar_cuenta_x_sede');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function anular_cuenta_x_sede($cuenta, $sede, $id_responsable, $dni_responsable) {
        $data = array(
            'cuenta' => $cuenta,
            'sede' => $sede,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('anular_cuenta_x_sede');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function asignar_cuenta_x_sede_x_empleado($cuenta, $sede, $id_encargado, $dni_encargado, $tipo_permiso, $id_responsable, $dni_responsable) {
        $data = array(
            'cuenta' => $cuenta,
            'sede' => $sede,
            'id_encargado' => $id_encargado,
            'dni_encargado' => $dni_encargado,
            'tipo_permiso' => $tipo_permiso,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('asignar_cuenta_x_sede_x_empleado');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function anular_cuenta_x_sede_x_empleado($cuenta, $sede, $id_encargado, $dni_encargado, $tipo_permiso, $id_responsable, $dni_responsable) {
        $data = array(
            'cuenta' => $cuenta,
            'sede' => $sede,
            'id_encargado' => $id_encargado,
            'dni_encargado' => $dni_encargado,
            'tipo_permiso' => $tipo_permiso,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('anular_cuenta_x_sede_x_empleado');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cambio_cargo($id_empleado, $dni_empleado, $cargo_old, $cargo_new, $solicitar_placa, $sede, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'cargo_old' => $cargo_old,
            'cargo_new' => $cargo_new,
            'solicitar_placa' => $solicitar_placa,
            'sede' => $sede,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('cambio_cargo');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function solicitar_placa($id_empleado, $dni_empleado, $cargo_obtenido, $sede, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'cargo_obtenido' => $cargo_obtenido,
            'sede' => $sede,
            'pendiente' => 1,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('solicitud_placa');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cambio_jefe($id_empleado, $dni_empleado, $id_jefe_old, $dni_jefe_old, $id_jefe_new, $dni_jefe_new, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'id_jefe_old' => $id_jefe_old,
            'dni_jefe_old' => $dni_jefe_old,
            'id_jefe_new' => $id_jefe_new,
            'dni_jefe_new' => $dni_jefe_new,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('cambio_jefe');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function despachar_placa($solicitud_placa, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'solicitud_placa' => $solicitud_placa,
            'pendiente' => 1,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('despachar_placa');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function recibir_placa($despacho_placa, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'despacho_placa' => $despacho_placa,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('recibir_placa');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function ausencia_laboral($id_empleado, $dni_empleado, $fecha_inicio, $fecha_fin, $t_ausencia, $vigente, $descripcion, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            't_ausencia' => $t_ausencia,
            'vigente' => $vigente,
            'descripcion' => $descripcion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('ausencia_laboral');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function llamado_atencion($id, $id_empleado, $dni_empleado, $t_falta_laboral, $t_sancion, $vigente, $descripcion, $id_responsable, $dni_responsable) {
        $data = array(
            'id' => $id,
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            't_falta_laboral' => $t_falta_laboral,
            't_sancion' => $t_sancion,
            'vigente' => $vigente,
            'descripcion' => $descripcion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('llamado_atencion');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function contrato_laboral($id_empleado, $dni_empleado, $t_contrato, $cant_meses, $fecha_inicio, $fecha_fin, $estado, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            't_contrato' => $t_contrato,
            'cant_meses' => $cant_meses,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'estado' => $estado,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('contrato_laboral');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function caja($sede, $t_caja, $id_encargado, $dni_encargado, $vigente, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'sede' => $sede,
            't_caja' => $t_caja,
            'id_encargado' => $id_encargado,
            'dni_encargado' => $dni_encargado,
            'vigente' => $vigente,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('caja');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cuenta($cuenta, $t_cuenta, $banco, $nombre_cuenta, $vigente, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'id' => $cuenta,
            't_cuenta' => $t_cuenta,
            'banco' => $banco,
            'nombre_cuenta' => $nombre_cuenta,
            'vigente' => $vigente,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('cuenta');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function adelanto($prefijo, $id, $id_empleado, $dni_empleado, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $autoriza, $motivo, $forma_descuento, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            't_trans' => '1',
            'credito_debito' => '0',
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'total' => $total,
            'cuenta_origen' => $cuenta_origen,
            'valor_retirado' => $valor_retirado,
            'sede_caja_origen' => $sede_caja_origen,
            't_caja_origen' => $t_caja_origen,
            'efectivo_retirado' => $efectivo_retirado,
            'sede' => $sede,
            'vigente' => 1,
            'autoriza' => $autoriza,
            'motivo' => $motivo,
            'forma_descuento' => $forma_descuento,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('adelanto');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function nota_credito($prefijo, $id, $matricula, $autoriza, $motivo, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            't_trans' => '10',
            'credito_debito' => '0',
            'matricula' => $matricula,
            'autoriza' => $autoriza,
            'motivo' => $motivo,
            'total' => $total,
            'cuenta_origen' => $cuenta_origen,
            'valor_retirado' => $valor_retirado,
            'sede_caja_origen' => $sede_caja_origen,
            't_caja_origen' => $t_caja_origen,
            'efectivo_retirado' => $efectivo_retirado,
            'sede' => $sede,
            'vigente' => 1,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('nota_credito');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function prestamo($prefijo, $id, $t_beneficiario, $id_beneficiario, $dni_beneficiario, $total, $tasa_interes, $cant_cuotas, $cuota_fija, $fecha_desembolso, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $estado, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            't_trans' => '2',
            'credito_debito' => '0',
            't_beneficiario' => $t_beneficiario,
            'id_beneficiario' => $id_beneficiario,
            'dni_beneficiario' => $dni_beneficiario,
            'total' => $total,
            'tasa_interes' => $tasa_interes,
            'cant_cuotas' => $cant_cuotas,
            'cuota_fija' => $cuota_fija,
            'fecha_desembolso' => $fecha_desembolso,
            'cuenta_origen' => $cuenta_origen,
            'valor_retirado' => $valor_retirado,
            'sede_caja_origen' => $sede_caja_origen,
            't_caja_origen' => $t_caja_origen,
            'efectivo_retirado' => $efectivo_retirado,
            'sede' => $sede,
            'estado' => $estado,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('prestamo');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function abono_adelanto($prefijo, $id, $prefijo_adelanto, $id_adelanto, $total, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $sede, $vigente, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            't_trans' => '3',
            'credito_debito' => '1',
            'prefijo_adelanto' => $prefijo_adelanto,
            'id_adelanto' => $id_adelanto,
            'total' => $total,
            'cuenta_destino' => $cuenta_destino,
            'valor_consignado' => $valor_consignado,
            'sede_caja_destino' => $sede_caja_destino,
            't_caja_destino' => $t_caja_destino,
            'efectivo_ingresado' => $efectivo_ingresado,
            'sede' => $sede,
            'vigente' => $vigente,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('abono_adelanto');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function abono_prestamo($prefijo, $id, $prefijo_prestamo, $id_prestamo, $subtotal, $cant_dias_mora, $int_mora, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $sede, $vigente, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            't_trans' => '4',
            'credito_debito' => '1',
            'prefijo_prestamo' => $prefijo_prestamo,
            'id_prestamo' => $id_prestamo,
            'subtotal' => $subtotal,
            'cant_dias_mora' => $cant_dias_mora,
            'int_mora' => $int_mora,
            'cuenta_destino' => $cuenta_destino,
            'valor_consignado' => $valor_consignado,
            'sede_caja_destino' => $sede_caja_destino,
            't_caja_destino' => $t_caja_destino,
            'efectivo_ingresado' => $efectivo_ingresado,
            'sede' => $sede,
            'vigente' => $vigente,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('abono_prestamo');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function egreso($prefijo, $id, $t_egreso, $t_beneficiario, $id_beneficiario, $dni_beneficiario, $d_v, $nombre_beneficiario, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $vigente, $descripcion, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            't_trans' => '6',
            'credito_debito' => '0',
            't_egreso' => $t_egreso,
            't_beneficiario' => $t_beneficiario,
            'id_beneficiario' => $id_beneficiario,
            'dni_beneficiario' => $dni_beneficiario,
            'd_v' => $d_v,
            'nombre_beneficiario' => $nombre_beneficiario,
            'total' => $total,
            'cuenta_origen' => $cuenta_origen,
            'valor_retirado' => $valor_retirado,
            'sede_caja_origen' => $sede_caja_origen,
            't_caja_origen' => $t_caja_origen,
            'efectivo_retirado' => $efectivo_retirado,
            'sede' => $sede,
            'vigente' => $vigente,
            'descripcion' => $descripcion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('egreso');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function transferencia($prefijo, $id, $credito_debito_origen, $credito_debito_destino, $total, $sede_origen, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $tipo_destino, $sede_destino, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $observacion, $est_traslado, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            't_trans' => '13',
            'credito_debito_origen' => $credito_debito_origen,
            'credito_debito_destino' => $credito_debito_destino,
            'total' => $total,
            'sede_origen' => $sede_origen,
            'cuenta_origen' => $cuenta_origen,
            'valor_retirado' => $valor_retirado,
            'sede_caja_origen' => $sede_caja_origen,
            't_caja_origen' => $t_caja_origen,
            'efectivo_retirado' => $efectivo_retirado,
            'tipo_destino' => $tipo_destino,
            'sede_destino' => $sede_destino,
            'cuenta_destino' => $cuenta_destino,
            'valor_consignado' => $valor_consignado,
            'sede_caja_destino' => $sede_caja_destino,
            't_caja_destino' => $t_caja_destino,
            'efectivo_ingresado' => $efectivo_ingresado,
            'observacion' => $observacion,
            'est_traslado' => $est_traslado,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('transferencia');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function pago_proveedor($prefijo, $id, $id_proveedor, $dni_proveedor, $t_egreso, $factura, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            't_trans' => '11',
            'credito_debito' => '0',
            'id_proveedor' => $id_proveedor,
            'dni_proveedor' => $dni_proveedor,
            't_egreso' => $t_egreso,
            'factura' => $factura,
            'total' => $total,
            'cuenta_origen' => $cuenta_origen,
            'valor_retirado' => $valor_retirado,
            'sede_caja_origen' => $sede_caja_origen,
            't_caja_origen' => $t_caja_origen,
            'efectivo_retirado' => $efectivo_retirado,
            'sede' => $sede,
            'vigente' => 1,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('pago_proveedor');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function retefuente_compras($prefijo, $id, $id_proveedor, $dni_proveedor, $factura, $total, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $sede, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            't_trans' => '12',
            'credito_debito' => '1',
            'id_proveedor' => $id_proveedor,
            'dni_proveedor' => $dni_proveedor,
            'factura' => $factura,
            'total' => $total,
            'cuenta_destino' => $cuenta_destino,
            'valor_consignado' => $valor_consignado,
            'sede_caja_destino' => $sede_caja_destino,
            't_caja_destino' => $t_caja_destino,
            'efectivo_ingresado' => $efectivo_ingresado,
            'sede' => $sede,
            'vigente' => 1,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('retefuente_compras');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function retefuente_ventas($prefijo, $id, $prefijo_factura, $id_factura, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            't_trans' => '14',
            'credito_debito' => '0',
            'prefijo_factura' => $prefijo_factura,
            'id_factura' => $id_factura,
            'total' => $total,
            'cuenta_origen' => $cuenta_origen,
            'valor_retirado' => $valor_retirado,
            'sede_caja_origen' => $sede_caja_origen,
            't_caja_origen' => $t_caja_origen,
            'efectivo_retirado' => $efectivo_retirado,
            'sede' => $sede,
            'vigente' => 1,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('retefuente_ventas');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function ingreso($prefijo, $id, $t_ingreso, $t_depositante, $id_depositante, $dni_depositante, $d_v, $nombre_depositante, $total, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $sede, $vigente, $descripcion, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            't_trans' => '5',
            'credito_debito' => '1',
            't_ingreso' => $t_ingreso,
            't_depositante' => $t_depositante,
            'id_depositante' => $id_depositante,
            'dni_depositante' => $dni_depositante,
            'd_v' => $d_v,
            'nombre_depositante' => $nombre_depositante,
            'total' => $total,
            'cuenta_destino' => $cuenta_destino,
            'valor_consignado' => $valor_consignado,
            'sede_caja_destino' => $sede_caja_destino,
            't_caja_destino' => $t_caja_destino,
            'efectivo_ingresado' => $efectivo_ingresado,
            'sede' => $sede,
            'vigente' => $vigente,
            'descripcion' => $descripcion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('ingreso');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function matricula($contrato, $fecha_matricula, $id_titular, $dni_titular, $id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo, $plan, $cant_cupos_enseñanza, $cant_alumnos_registrados, $cant_materiales_entregados, $datacredito, $juridico, $liquidacion_escalas, $sede, $estado, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'contrato' => $contrato,
            'fecha_matricula' => $fecha_matricula,
            'id_titular' => $id_titular,
            'dni_titular' => $dni_titular,
            'id_ejecutivo' => $id_ejecutivo,
            'dni_ejecutivo' => $dni_ejecutivo,
            'cargo_ejecutivo' => $cargo_ejecutivo,
            'plan' => $plan,
            'cant_cupos_enseñanza' => $cant_cupos_enseñanza,
            'cant_alumnos_registrados' => $cant_alumnos_registrados,
            'cant_materiales_entregados' => $cant_materiales_entregados,
            'datacredito' => $datacredito,
            'juridico' => $juridico,
            'liquidacion_escalas' => $liquidacion_escalas,
            'sede' => $sede,
            'estado' => $estado,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('matricula');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function contrato_matricula($id, $sede_actual, $estado, $id_responsable, $dni_responsable) {
        $data = array(
            'id' => $id,
            'sede_actual' => $sede_actual,
            'estado' => $estado,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('contrato_matricula');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function traslado_contrato($contrato, $sede_actual, $sede_destino, $est_traslado, $id_responsable, $dni_responsable) {
        $data = array(
            'contrato' => $contrato,
            'sede_actual' => $sede_actual,
            'sede_destino' => $sede_destino,
            'est_traslado' => $est_traslado,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('traslado_contrato');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function concepto_nomina($id_empleado, $dni_empleado, $prefijo_nomina, $id_nomina, $t_concepto_nomina, $detalle, $matricula, $plan_matricula, $escala_matricula, $cargo_ejecutivo, $cantidad, $valor_unitario, $estado, $sede, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'prefijo_nomina' => $prefijo_nomina,
            'id_nomina' => $id_nomina,
            't_concepto_nomina' => $t_concepto_nomina,
            'detalle' => $detalle,
            'matricula' => $matricula,
            'plan_matricula' => $plan_matricula,
            'escala_matricula' => $escala_matricula,
            'cargo_ejecutivo' => $cargo_ejecutivo,
            'cantidad' => $cantidad,
            'valor_unitario' => $valor_unitario,
            'estado' => $estado,
            'sede' => $sede,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('concepto_nomina');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function nomina($prefijo, $id, $id_empleado, $dni_empleado, $depto, $cargo, $t_periodicidad, $fecha_inicio, $fecha_fin, $dias_nomina, $dias_remunerados, $ausencias, $total_devengado, $total_deducido, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $vigente, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            't_trans' => '9',
            'credito_debito' => '0',
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'depto' => $depto,
            'cargo' => $cargo,
            't_periodicidad' => $t_periodicidad,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'dias_nomina' => $dias_nomina,
            'dias_remunerados' => $dias_remunerados,
            'ausencias' => $ausencias,
            'total_devengado' => $total_devengado,
            'total_deducido' => $total_deducido,
            'total' => $total,
            'cuenta_origen' => $cuenta_origen,
            'valor_retirado' => $valor_retirado,
            'sede_caja_origen' => $sede_caja_origen,
            't_caja_origen' => $t_caja_origen,
            'efectivo_retirado' => $efectivo_retirado,
            'sede' => $sede,
            'vigente' => $vigente,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('nomina');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function factura($prefijo, $id, $matricula, $id_a_nombre_de, $dni_a_nombre_de, $d_v_a_nombre_de, $a_nombre_de, $direccion_a_nombre_de, $subtotal, $int_mora, $descuento, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $cuenta_destino, $valor_consignado, $sede, $vigente, $retefuente, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            't_trans' => '7',
            'credito_debito' => '1',
            'matricula' => $matricula,
            'id_a_nombre_de' => $id_a_nombre_de,
            'dni_a_nombre_de' => $dni_a_nombre_de,
            'd_v_a_nombre_de' => $d_v_a_nombre_de,
            'a_nombre_de' => $a_nombre_de,
            'direccion_a_nombre_de' => $direccion_a_nombre_de,
            'subtotal' => $subtotal,
            'int_mora' => $int_mora,
            'descuento' => $descuento,
            'sede_caja_destino' => $sede_caja_destino,
            't_caja_destino' => $t_caja_destino,
            'efectivo_ingresado' => $efectivo_ingresado,
            'cuenta_destino' => $cuenta_destino,
            'valor_consignado' => $valor_consignado,
            'sede' => $sede,
            'vigente' => $vigente,
            'retefuente' => $retefuente,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('factura');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function detalle_factura($prefijo_factura, $id_factura, $matricula, $t_detalle, $num_cuota, $subtotal, $fecha_esperada, $cant_dias_mora, $int_mora) {
        $data = array(
            'prefijo_factura' => $prefijo_factura,
            'id_factura' => $id_factura,
            'matricula' => $matricula,
            't_detalle' => $t_detalle,
            'num_cuota' => $num_cuota,
            'subtotal' => $subtotal,
            'fecha_esperada' => $fecha_esperada,
            'cant_dias_mora' => $cant_dias_mora,
            'int_mora' => $int_mora
        );
        $this->db->insert('detalle_factura', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function recibo_caja($prefijo, $id, $t_trans, $matricula, $id_a_nombre_de, $dni_a_nombre_de, $d_v_a_nombre_de, $a_nombre_de, $direccion_a_nombre_de, $subtotal, $int_mora, $descuento, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $cuenta_destino, $valor_consignado, $sede, $vigente, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            't_trans' => $t_trans,
            'credito_debito' => '1',
            'matricula' => $matricula,
            'id_a_nombre_de' => $id_a_nombre_de,
            'dni_a_nombre_de' => $dni_a_nombre_de,
            'd_v_a_nombre_de' => $d_v_a_nombre_de,
            'a_nombre_de' => $a_nombre_de,
            'direccion_a_nombre_de' => $direccion_a_nombre_de,
            'subtotal' => $subtotal,
            'int_mora' => $int_mora,
            'descuento' => $descuento,
            'sede_caja_destino' => $sede_caja_destino,
            't_caja_destino' => $t_caja_destino,
            'efectivo_ingresado' => $efectivo_ingresado,
            'cuenta_destino' => $cuenta_destino,
            'valor_consignado' => $valor_consignado,
            'sede' => $sede,
            'vigente' => $vigente,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('recibo_caja');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function abono_matricula($prefijo, $id, $t_trans, $matricula, $id_a_nombre_de, $dni_a_nombre_de, $d_v_a_nombre_de, $a_nombre_de, $direccion_a_nombre_de, $subtotal, $int_mora, $descuento, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $cuenta_destino, $valor_consignado, $sede, $vigente, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            't_trans' => $t_trans,
            'credito_debito' => '1',
            'matricula' => $matricula,
            'id_a_nombre_de' => $id_a_nombre_de,
            'dni_a_nombre_de' => $dni_a_nombre_de,
            'd_v_a_nombre_de' => $d_v_a_nombre_de,
            'a_nombre_de' => $a_nombre_de,
            'direccion_a_nombre_de' => $direccion_a_nombre_de,
            'subtotal' => $subtotal,
            'int_mora' => $int_mora,
            'descuento' => $descuento,
            'sede_caja_destino' => $sede_caja_destino,
            't_caja_destino' => $t_caja_destino,
            'efectivo_ingresado' => $efectivo_ingresado,
            'cuenta_destino' => $cuenta_destino,
            'valor_consignado' => $valor_consignado,
            'sede' => $sede,
            'vigente' => $vigente,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('abono_matricula');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function detalle_recibo_caja($prefijo_recibo_caja, $id_recibo_caja, $matricula, $t_detalle, $num_cuota, $subtotal, $fecha_esperada, $cant_dias_mora, $int_mora) {
        $data = array(
            'prefijo_recibo_caja' => $prefijo_recibo_caja,
            'id_recibo_caja' => $id_recibo_caja,
            'matricula' => $matricula,
            't_detalle' => $t_detalle,
            'num_cuota' => $num_cuota,
            'subtotal' => $subtotal,
            'fecha_esperada' => $fecha_esperada,
            'cant_dias_mora' => $cant_dias_mora,
            'int_mora' => $int_mora
        );
        $this->db->insert('detalle_recibo_caja', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cambio_ejecutivo_matricula($matricula, $id_ejecutivo_old, $dni_ejecutivo_old, $id_ejecutivo_new, $dni_ejecutivo_new, $id_responsable, $dni_responsable) {
        $data = array(
            'matricula' => $matricula,
            'id_ejecutivo_old' => $id_ejecutivo_old,
            'dni_ejecutivo_old' => $dni_ejecutivo_old,
            'id_ejecutivo_new' => $id_ejecutivo_new,
            'dni_ejecutivo_new' => $dni_ejecutivo_new,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('cambio_ejecutivo_matricula');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function movimiento_transaccion($t_trans, $prefijo, $id, $credito_debito, $total, $sede_caja, $t_caja, $efectivo_caja, $cuenta, $valor_cuenta, $vigente, $sede, $id_responsable, $dni_responsable) {
        $data = array(
            't_trans' => $t_trans,
            'prefijo' => $prefijo,
            'id' => $id,
            'credito_debito' => $credito_debito,
            'total' => $total,
            'sede_caja' => $sede_caja,
            't_caja' => $t_caja,
            'efectivo_caja' => $efectivo_caja,
            'cuenta' => $cuenta,
            'valor_cuenta' => $valor_cuenta,
            'vigente' => $vigente,
            'sede' => $sede,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('movimiento_transaccion');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cambio_plan_matricula($matricula, $plan_old, $plan_new, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'matricula' => $matricula,
            'plan_old' => $plan_old,
            'plan_new' => $plan_new,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('cambio_plan_matricula');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function aprobar_transferencia($prefijo_transferencia, $id_transferencia, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo_transferencia' => $prefijo_transferencia,
            'id_transferencia' => $id_transferencia,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('aprobar_transferencia');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function reporte_alumno($id_reporte, $id_alumno, $dni_alumno, $fecha_clase, $asistencia, $etapa, $fase, $meta_v, $meta_c, $meta_r, $cant_practicas, $lectura, $vlm, $vlv, $c, $r, $vigente, $observacion_interna, $observacion_titular_alumno, $id_responsable, $dni_responsable) {
        $data = array(
            'id' => $id_reporte,
            'id_alumno' => $id_alumno,
            'dni_alumno' => $dni_alumno,
            'fecha_clase' => $fecha_clase,
            'asistencia' => $asistencia,
            'etapa' => $etapa,
            'fase' => $fase,
            'meta_v' => $meta_v,
            'meta_c' => $meta_c,
            'meta_r' => $meta_r,
            'cant_practicas' => $cant_practicas,
            'lectura' => $lectura,
            'vlm' => $vlm,
            'vlv' => $vlv,
            'c' => $c,
            'r' => $r,
            'vigente' => $vigente,
            'observacion_interna' => $observacion_interna,
            'observacion_titular_alumno' => $observacion_titular_alumno,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('reporte_alumno');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function ejercicio_ensenanza($id_reporte, $t_habilidad, $t_ejercicio) {
        $data = array(
            'id_reporte' => $id_reporte,
            't_habilidad' => $t_habilidad,
            't_ejercicio' => $t_ejercicio,
            'vigente' => '1'
        );
        $this->db->insert('ejercicio_ensenanza', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function descuento_matricula($id_matricula, $valor, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'matricula' => $id_matricula,
            'valor' => $valor,
            'observacion' => $observacion,
            'vigente' => '1',
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('descuento_matricula');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cod_autorizacion($tabla_autorizada, $registro_autorizado, $id_empleado_autorizado, $dni_empleado_autorizado, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'tabla_autorizada' => $tabla_autorizada,
            'registro_autorizado' => $registro_autorizado,
            'id_empleado_autorizado' => $id_empleado_autorizado,
            'dni_empleado_autorizado' => $dni_empleado_autorizado,
            'observacion' => $observacion,
            'vigente' => '1',
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('cod_autorizacion');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function anular_transaccion($t_trans, $prefijo_transaccion, $id_transaccion, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            't_trans' => $t_trans,
            'prefijo_transaccion' => $prefijo_transaccion,
            'id_transaccion' => $id_transaccion,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('anular_transaccion');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function anular_matricula($id_matricula, $cant_materiales_devueltos, $observacion, $id_responsable, $dni_responsable) {
        $data = array(
            'matricula' => $id_matricula,
            'cant_materiales_devueltos' => $cant_materiales_devueltos,
            'observacion' => $observacion,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->set($data);
        $this->db->set('fecha_trans', 'DATE_ADD(now(), INTERVAL -5 HOUR)', FALSE);
        $this->db->insert('anular_matricula');
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }    

}
