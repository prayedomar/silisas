<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Insert_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function new_sede($id_sede, $nombre, $pais, $provincia, $ciudad, $direccion, $tel1, $tel2, $prefijo_trans, $estado, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
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
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
//para devolver el error que mande la inserccion
        $this->db->insert('sede', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function new_salon($nombre, $capacidad, $sede, $vigente, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'nombre' => $nombre,
            'capacidad' => $capacidad,
            'sede' => $sede,
            'vigente' => $vigente,
            'observacion' => $observacion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('salon', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function new_empleado($id, $dni, $t_usuario, $nombre1, $nombre2, $apellido1, $apellido2, $fecha_nacimiento, $genero, $est_civil, $pais, $provincia, $ciudad, $t_domicilio, $direccion, $barrio, $telefono, $celular, $email, $cuenta, $sede_ppal, $depto, $cargo, $salario, $id_jefe, $dni_jefe, $estado, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
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
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('empleado', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cliente($id, $dni, $t_usuario, $nombre1, $nombre2, $apellido1, $apellido2, $fecha_nacimiento, $genero, $pais, $provincia, $ciudad, $t_domicilio, $direccion, $barrio, $telefono, $celular, $email, $sede_ppal, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
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
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('cliente', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function alumno($id, $dni, $t_usuario, $nombre1, $nombre2, $apellido1, $apellido2, $fecha_nacimiento, $genero, $pais, $provincia, $ciudad, $t_domicilio, $direccion, $barrio, $telefono, $celular, $email, $matricula, $velocidad_ini, $comprension_ini, $t_curso, $estado, $grados, $cant_clases, $sede_ppal, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
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
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('alumno', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function titular($id, $dni, $t_usuario, $nombre1, $nombre2, $apellido1, $apellido2, $fecha_nacimiento, $genero, $pais, $provincia, $ciudad, $t_domicilio, $direccion, $barrio, $telefono, $celular, $email, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
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
            'observacion' => $observacion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('titular', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function new_proveedor($id, $dni, $d_v, $razon_social, $pais, $provincia, $ciudad, $t_domicilio, $direccion, $telefono, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
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
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('proveedor', $data);
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

    public function new_salario($id_salario, $nombre, $t_salario, $vigente, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'id' => $id_salario,
            'nombre' => $nombre,
            't_salario' => $t_salario,
            'vigente' => $vigente,
            'observacion' => $observacion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('salario', $data);
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

    public function cambio_sede_empleado($id_empleado, $dni_empleado, $sede_ppal, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'sede_ppal' => $sede_ppal,
            'observacion' => $observacion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('cambio_sede_empleado', $data);
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

    public function cuenta_x_sede_x_empleado($cuenta, $sede, $id_encargado, $dni_encargado, $vigente) {
        $this->db->where('cuenta', $cuenta);
        $this->db->where('sede', $sede);
        $this->db->where('id_encargado', $id_encargado);
        $this->db->where('dni_encargado', $dni_encargado);
        $query = $this->db->get('cuenta_x_sede_x_empleado');
        if ($query->num_rows() == 1) {
            //Si la sede ya existe actualizo el vigente a 1
            $data = array(
                'vigente' => $vigente
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
                'vigente' => $vigente
            );
            $this->db->insert('cuenta_x_sede_x_empleado', $data);
        }
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function anular_empleado_x_sede($id_empleado, $dni_empleado, $sede_secundaria, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'sede_secundaria' => $sede_secundaria,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('anular_empleado_x_sede', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function asignar_empleado_x_sede($id_empleado, $dni_empleado, $sede_secundaria, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'sede_secundaria' => $sede_secundaria,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('asignar_empleado_x_sede', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function asignar_cuenta_x_sede($cuenta, $sede, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'cuenta' => $cuenta,
            'sede' => $sede,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('asignar_cuenta_x_sede', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function anular_cuenta_x_sede($cuenta, $sede, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'cuenta' => $cuenta,
            'sede' => $sede,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('anular_cuenta_x_sede', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function asignar_cuenta_x_sede_x_empleado($cuenta, $sede, $id_encargado, $dni_encargado, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'cuenta' => $cuenta,
            'sede' => $sede,
            'id_encargado' => $id_encargado,
            'dni_encargado' => $dni_encargado,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('asignar_cuenta_x_sede_x_empleado', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function anular_cuenta_x_sede_x_empleado($cuenta, $sede, $id_encargado, $dni_encargado, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'cuenta' => $cuenta,
            'sede' => $sede,
            'id_encargado' => $id_encargado,
            'dni_encargado' => $dni_encargado,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('anular_cuenta_x_sede_x_empleado', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cambio_cargo($id_empleado, $dni_empleado, $cargo_old, $cargo_new, $solicitar_placa, $sede, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'cargo_old' => $cargo_old,
            'cargo_new' => $cargo_new,
            'solicitar_placa' => $solicitar_placa,
            'sede' => $sede,
            'observacion' => $observacion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('cambio_cargo', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function solicitar_placa($id_empleado, $dni_empleado, $cargo_obtenido, $sede, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'cargo_obtenido' => $cargo_obtenido,
            'sede' => $sede,
            'pendiente' => 1,
            'observacion' => $observacion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('solicitud_placa', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cambio_jefe($id_empleado, $dni_empleado, $id_jefe_old, $dni_jefe_old, $id_jefe_new, $dni_jefe_new, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'id_jefe_old' => $id_jefe_old,
            'dni_jefe_old' => $dni_jefe_old,
            'id_jefe_new' => $id_jefe_new,
            'dni_jefe_new' => $dni_jefe_new,
            'observacion' => $observacion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('cambio_jefe', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function despachar_placa($solicitud_placa, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'solicitud_placa' => $solicitud_placa,
            'pendiente' => 1,
            'observacion' => $observacion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('despachar_placa', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function recibir_placa($despacho_placa, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'despacho_placa' => $despacho_placa,
            'observacion' => $observacion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('recibir_placa', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function ausencia_laboral($id_empleado, $dni_empleado, $fecha_inicio, $fecha_fin, $t_ausencia, $vigente, $descripcion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            't_ausencia' => $t_ausencia,
            'vigente' => $vigente,
            'descripcion' => $descripcion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('ausencia_laboral', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function llamado_atencion($id, $id_empleado, $dni_empleado, $t_falta_laboral, $t_sancion, $vigente, $descripcion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'id' => $id,
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            't_falta_laboral' => $t_falta_laboral,
            't_sancion' => $t_sancion,
            'vigente' => $vigente,
            'descripcion' => $descripcion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('llamado_atencion', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function contrato_laboral($id_empleado, $dni_empleado, $t_contrato, $cant_meses, $fecha_inicio, $fecha_fin, $estado, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            't_contrato' => $t_contrato,
            'cant_meses' => $cant_meses,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'estado' => $estado,
            'observacion' => $observacion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('contrato_laboral', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function caja($sede, $t_caja, $id_encargado, $dni_encargado, $vigente, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'sede' => $sede,
            't_caja' => $t_caja,
            'id_encargado' => $id_encargado,
            'dni_encargado' => $dni_encargado,
            'vigente' => $vigente,
            'observacion' => $observacion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('caja', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cuenta($cuenta, $t_cuenta, $banco, $nombre_cuenta, $vigente, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'id' => $cuenta,
            't_cuenta' => $t_cuenta,
            'banco' => $banco,
            'nombre_cuenta' => $nombre_cuenta,
            'vigente' => $vigente,
            'observacion' => $observacion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('cuenta', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function adelanto($prefijo, $id, $id_empleado, $dni_empleado, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $estado, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            'total' => $total,
            'cuenta_origen' => $cuenta_origen,
            'valor_retirado' => $valor_retirado,
            'sede_caja_origen' => $sede_caja_origen,
            't_caja_origen' => $t_caja_origen,
            'efectivo_retirado' => $efectivo_retirado,
            'sede' => $sede,
            'estado' => $estado,
            'observacion' => $observacion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('adelanto', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function prestamo($prefijo, $id, $t_beneficiario, $id_beneficiario, $dni_beneficiario, $total, $tasa_interes, $cant_cuotas, $cuota_fija, $fecha_desembolso, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $estado, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
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
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('prestamo', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function abono_adelanto($prefijo, $id, $prefijo_adelanto, $id_adelanto, $total, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $sede, $vigente, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
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
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('abono_adelanto', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function abono_prestamo($prefijo, $id, $prefijo_prestamo, $id_prestamo, $subtotal, $cant_dias_mora, $int_mora, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $sede, $vigente, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
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
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('abono_prestamo', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function egreso($prefijo, $id, $t_egreso, $t_beneficiario, $id_beneficiario, $dni_beneficiario, $d_v, $nombre_beneficiario, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $vigente, $descripcion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
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
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('egreso', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function ingreso($prefijo, $id, $t_ingreso, $t_depositante, $id_depositante, $dni_depositante, $d_v, $nombre_depositante, $total, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $sede, $vigente, $descripcion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
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
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('ingreso', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function matricula($contrato, $fecha_matricula, $id_titular, $dni_titular, $id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo, $plan, $cant_alumnos_disponibles, $cant_materiales_disponibles, $datacredito, $juridico, $liquidacion_escalas, $sede, $estado, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'contrato' => $contrato,
            'fecha_matricula' => $fecha_matricula,
            'id_titular' => $id_titular,
            'dni_titular' => $dni_titular,
            'id_ejecutivo' => $id_ejecutivo,
            'dni_ejecutivo' => $dni_ejecutivo,
            'cargo_ejecutivo' => $cargo_ejecutivo,
            'plan' => $plan,
            'cant_alumnos_disponibles' => $cant_alumnos_disponibles,
            'cant_materiales_disponibles' => $cant_materiales_disponibles,
            'datacredito' => $datacredito,
            'juridico' => $juridico,
            'liquidacion_escalas' => $liquidacion_escalas,
            'sede' => $sede,
            'estado' => $estado,
            'observacion' => $observacion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('matricula', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function contrato_matricula($id, $sede_actual, $estado, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'id' => $id,
            'sede_actual' => $sede_actual,
            'estado' => $estado,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('contrato_matricula', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function traslado_contrato($contrato, $sede_actual, $sede_destino, $est_traslado, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'contrato' => $contrato,
            'sede_actual' => $sede_actual,
            'sede_destino' => $sede_destino,
            'est_traslado' => $est_traslado,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('traslado_contrato', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function concepto_nomina($id_empleado, $dni_empleado, $prefijo_nomina, $id_nomina, $t_concepto_nomina, $detalle, $matricula, $plan_matricula, $escala_matricula, $cargo_ejecutivo, $cantidad, $valor_unitario, $estado, $sede, $fecha_trans, $id_responsable, $dni_responsable) {
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
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('concepto_nomina', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function nomina($prefijo, $id, $id_empleado, $dni_empleado, $t_periodicidad, $fecha_inicio, $fecha_fin, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $vigente, $observacion, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'prefijo' => $prefijo,
            'id' => $id,
            'id_empleado' => $id_empleado,
            'dni_empleado' => $dni_empleado,
            't_periodicidad' => $t_periodicidad,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'total' => $total,
            'cuenta_origen' => $cuenta_origen,
            'valor_retirado' => $valor_retirado,
            'sede_caja_origen' => $sede_caja_origen,
            't_caja_origen' => $t_caja_origen,
            'efectivo_retirado' => $efectivo_retirado,
            'sede' => $sede,
            'vigente' => $vigente,
            'observacion' => $observacion,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('nomina', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function cambio_ejecutivo_matricula($matricula, $id_ejecutivo_old, $dni_ejecutivo_old, $id_ejecutivo_new, $dni_ejecutivo_new, $fecha_trans, $id_responsable, $dni_responsable) {
        $data = array(
            'matricula' => $matricula,
            'id_ejecutivo_old' => $id_ejecutivo_old,
            'dni_ejecutivo_old' => $dni_ejecutivo_old,
            'id_ejecutivo_new' => $id_ejecutivo_new,
            'dni_ejecutivo_new' => $dni_ejecutivo_new,
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('cambio_ejecutivo_matricula', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

    public function movimiento_transaccion($t_trans, $prefijo, $id, $credito_debito, $total, $sede_caja, $t_caja, $efectivo_caja, $cuenta, $valor_cuenta, $vigente, $sede, $fecha_trans, $id_responsable, $dni_responsable) {
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
            'fecha_trans' => $fecha_trans,
            'id_responsable' => $id_responsable,
            'dni_responsable' => $dni_responsable
        );
        $this->db->insert('movimiento_transaccion', $data);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

}
