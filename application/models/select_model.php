<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Select_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function login_user($id, $dni, $t_usuario, $password) {
        $this->db->where('id', $id);
        $this->db->where('dni', $dni);
        $this->db->where('t_usuario', $t_usuario);
        $this->db->where('vigente', 1);
        $query = $this->db->get('usuario');
        if ($query->num_rows() == 1) {
            $user = $query->row();
            $password_decode = $this->encrypt->decode($user->password);
            if ($password_decode == $password) {
                return $query->row();
            }
        }
    }

    public function t_usuario_login() {
        $this->db->where('visible_login', 1);
        $query = $this->db->get('t_usuario');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_usuario_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('t_usuario');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function t_usuario_prestamo() {
        $this->db->where('visible_prestamo', 1);
        $query = $this->db->get('t_usuario');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_usuario_ingreso_egreso() {
        $this->db->where('visible_ingreso_egreso', 1);
        $query = $this->db->get('t_usuario');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_egreso() {
        $this->db->order_by('tipo', 'asc');
        $query = $this->db->get('t_egreso');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_ingreso() {
        $this->db->order_by('tipo', 'asc');
        $query = $this->db->get('t_ingreso');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_ingreso_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('t_ingreso');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function t_egreso_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('t_egreso');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function t_plan_activo() {
        $this->db->where('vigente', 1);
        $this->db->order_by('cant_alumnos', 'asc');
        $this->db->order_by('valor_total', 'asc');
        $query = $this->db->get('t_plan');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_plan_igual_cantAlumnos($id_matricula) {
        $SqlInfo = "SELECT * FROM t_plan WHERE (cant_alumnos=(SELECT cant_alumnos from t_plan where (id=(SELECT plan FROM matricula WHERE (contrato='" . $id_matricula . "'))))) ORDER BY cant_alumnos ASC, valor_total ASC";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_plan_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('t_plan');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function t_detalle($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('t_detalle');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function t_cuenta() {
        $query = $this->db->get('t_cuenta');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function banco_pais($pais) {
        $this->db->where('pais', $pais);
        $this->db->order_by('nombre', 'asc');
        $query = $this->db->get('banco');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function cuenta_banco() {
        $SqlInfo = "SELECT DISTINCT cu.id, t.tipo AS t_cuenta, b.nombre AS banco, cu.nombre_cuenta, cu.observacion, cu.fecha_trans FROM cuenta AS cu, t_cuenta AS t, banco AS b WHERE ((cu.t_cuenta=t.id) AND (cu.banco=b.id) AND (cu.vigente=1)) ORDER BY cu.fecha_trans";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    //MAtriculas no nulas (est<>5), iliquidadas, que pertenezcan a la sede principal del responsable (cada administrador se encarga de las escalas de sus matriculas.
    public function matricula_iliquida_responsable($id_responsable, $dni_responsable) {
        $where = "(estado != '5') AND (liquidacion_escalas='0') AND (sede IN(SELECT sede_ppal FROM empleado WHERE (id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "')))";
        $this->db->where($where);
        $this->db->order_by('contrato', 'asc');
        $query = $this->db->get('matricula');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function cuenta_banco_responsable_ingresar($id_responsable, $dni_responsable) {
        $SqlInfo = "SELECT DISTINCT cu.id, t.tipo AS t_cuenta, b.nombre AS banco, cu.nombre_cuenta, cu.observacion, cu.fecha_trans FROM cuenta AS cu, t_cuenta AS t, banco AS b WHERE ((cu.t_cuenta=t.id) AND (cu.banco=b.id) AND (cu.vigente=1) AND (cu.id IN (SELECT cuenta FROM cuenta_x_sede_x_empleado WHERE ((id_encargado='" . $id_responsable . "') AND (dni_encargado='" . $dni_responsable . "') AND (permiso_ingresar=1))))) ORDER BY cu.fecha_trans";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function cuenta_banco_responsable_retirar($id_responsable, $dni_responsable) {
        $SqlInfo = "SELECT DISTINCT cu.id, t.tipo AS t_cuenta, b.nombre AS banco, cu.nombre_cuenta, cu.observacion, cu.fecha_trans FROM cuenta AS cu, t_cuenta AS t, banco AS b WHERE ((cu.t_cuenta=t.id) AND (cu.banco=b.id) AND (cu.vigente=1) AND (cu.id IN (SELECT cuenta FROM cuenta_x_sede_x_empleado WHERE ((id_encargado='" . $id_responsable . "') AND (dni_encargado='" . $dni_responsable . "') AND (permiso_retirar=1))))) ORDER BY cu.fecha_trans";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function cuenta_banco_responsable_consultar($id_responsable, $dni_responsable) {
        $SqlInfo = "SELECT DISTINCT cu.id, t.tipo AS t_cuenta, b.nombre AS banco, cu.nombre_cuenta, cu.observacion, cu.fecha_trans FROM cuenta AS cu, t_cuenta AS t, banco AS b WHERE ((cu.t_cuenta=t.id) AND (cu.banco=b.id) AND (cu.vigente=1) AND (cu.id IN (SELECT cuenta FROM cuenta_x_sede_x_empleado WHERE ((id_encargado='" . $id_responsable . "') AND (dni_encargado='" . $dni_responsable . "') AND (permiso_consultar=1))))) ORDER BY cu.fecha_trans";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function cuenta_banco_sede($sede_autorizada) {
        $SqlInfo = "SELECT DISTINCT cu.id, t.tipo AS t_cuenta, b.nombre AS banco, cu.nombre_cuenta, cu.observacion, cu.fecha_trans FROM cuenta AS cu, t_cuenta AS t, banco AS b WHERE ((cu.t_cuenta=t.id) AND (cu.banco=b.id) AND (cu.vigente=1) AND (cu.id IN (SELECT cuenta FROM cuenta_x_sede WHERE ((sede='" . $sede_autorizada . "') AND (vigente=1))))) ORDER BY cu.fecha_trans";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function caja_responsable($id_responsable, $dni_responsable) {
        $SqlInfo = "SELECT DISTINCT c.sede, c.t_caja, c.id_encargado, c.dni_encargado, c.observacion, c.fecha_trans, s.nombre AS name_sede, t.tipo AS name_t_caja FROM caja AS c, t_caja AS t, sede AS s WHERE ((c.t_caja=t.id) AND (c.sede=s.id) AND (c.vigente=1) AND ((c.id_encargado='" . $id_responsable . "') AND (dni_encargado='" . $dni_responsable . "'))) ORDER BY c.fecha_trans";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function caja_sede($sede_autorizada) {
        $SqlInfo = "SELECT DISTINCT c.sede, c.t_caja, c.id_encargado, c.dni_encargado, c.observacion, c.fecha_trans, s.nombre AS name_sede, t.tipo AS name_t_caja FROM caja AS c, t_caja AS t, sede AS s WHERE ((c.t_caja=t.id) AND (c.sede=s.id) AND (c.vigente=1) AND (c.sede='" . $sede_autorizada . "')) ORDER BY c.fecha_trans";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function sede_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('sede');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function sede_prefijo($prefijo) {
        $this->db->where('prefijo_trans', $prefijo);
        $query = $this->db->get('sede');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function sedes_cuenta_bancaria($cuenta) {
        $SqlInfo = "SELECT DISTINCT s.id, s.nombre FROM cuenta_x_sede AS c, sede AS s WHERE ((c.sede=s.id) AND (c.cuenta='" . $cuenta . "') AND (c.vigente=1))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function empleados_cuenta_bancaria_ingresar($cuenta) {
        $SqlInfo = "SELECT DISTINCT e.id, e.dni, e.nombre1, e.nombre2, e.apellido1, e.apellido2 FROM cuenta_x_sede_x_empleado AS c, empleado AS e WHERE ((c.id_encargado=e.id) AND (c.dni_encargado=e.dni) AND (c.cuenta='" . $cuenta . "') AND (c.permiso_ingresar=1) AND (NOT(e.id='1' AND e.dni='1')) AND (e.estado!='3'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function empleados_cuenta_bancaria_retirar($cuenta) {
        $SqlInfo = "SELECT DISTINCT e.id, e.dni, e.nombre1, e.nombre2, e.apellido1, e.apellido2 FROM cuenta_x_sede_x_empleado AS c, empleado AS e WHERE ((c.id_encargado=e.id) AND (c.dni_encargado=e.dni) AND (c.cuenta='" . $cuenta . "') AND (c.permiso_retirar=1) AND (NOT(e.id='1' AND e.dni='1')) AND (e.estado!='3'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function empleados_cuenta_bancaria_consultar($cuenta) {
        $SqlInfo = "SELECT DISTINCT e.id, e.dni, e.nombre1, e.nombre2, e.apellido1, e.apellido2 FROM cuenta_x_sede_x_empleado AS c, empleado AS e WHERE ((c.id_encargado=e.id) AND (c.dni_encargado=e.dni) AND (c.cuenta='" . $cuenta . "') AND (c.permiso_consultar=1) AND (NOT(e.id='1' AND e.dni='1')) AND (e.estado!='3'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function cuenta_banco_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('cuenta');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function matricula_id($id) {
        $this->db->where('contrato', $id);
        $query = $this->db->get('matricula');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function matricula_titular_idMatricula($id) {
        $SqlInfo = "SELECT CONCAT(t.nombre1, ' ', t.nombre2, ' ', t.apellido1, ' ', t.apellido2) AS titular, t_p.* FROM matricula AS m, titular AS t, t_plan AS t_p WHERE ((m.contrato='" . $id . "') AND (m.id_titular=t.id) AND (m.dni_titular=t.dni) AND (m.plan=t_p.id))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function detalle_matricula_liquidar($contrato) {
        $SqlInfo = "SELECT CONCAT(t.nombre1, ' ', t.nombre2, ' ', t.apellido1, ' ', t.apellido2) AS titular, CONCAT(t_p.nombre, ' - ', t_p.anio) AS plan, m.observacion, CONCAT(e.nombre1, ' ', e.nombre2, ' ', e.apellido1, ' ', e.apellido2) AS ejecutivo, e.id, e.dni, e.cargo, CASE e.genero WHEN 'F' THEN t_c.cargo_femenino ELSE t_c.cargo_masculino END AS name_cargo, m.fecha_matricula FROM matricula AS m, titular AS t, empleado AS e, t_cargo AS t_c, t_plan AS t_p WHERE ((m.contrato='" . $contrato . "') AND (m.id_titular=t.id) AND (m.dni_titular=t.dni) AND (m.id_ejecutivo=e.id) AND (m.dni_ejecutivo=e.dni) AND (e.cargo=t_c.id) AND (m.plan=t_p.id))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function contrato_matricula_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('contrato_matricula');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    //Estado 1: Vacío
    public function contrato_matricula_vacio_id($id) {
        $this->db->where('id', $id);
        $this->db->where('estado', 1);
        $query = $this->db->get('contrato_matricula');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function contrato_matricula_id_sede($contrato, $sede) {
        $this->db->where('id', $contrato);
        $this->db->where('sede_actual', $sede);
        $query = $this->db->get('contrato_matricula');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function matricula_id_sede($contrato, $sede) {
        $this->db->where('contrato', $contrato);
        $this->db->where('sede', $sede);
        $query = $this->db->get('matricula');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function matricula_id_sedes_responsable($contrato, $id_responsable, $dni_responsable) {
        $SqlInfo = "SELECT * FROM matricula where ((contrato='" . $contrato . "') AND ((sede IN(SELECT sede_ppal FROM empleado WHERE (id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "'))) OR (sede IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $id_responsable . "') AND (dni_empleado='" . $dni_responsable . "') AND (vigente=1)))))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    //Matriculas vigente con saldo incluido > 0
    public function matricula_vigente_titular($id_titular, $dni_titular) {
        $SqlInfo = "SELECT DISTINCT ma.contrato, ma.fecha_matricula, t_p.nombre as nombre_plan, t_p.valor_total,  s.nombre AS sede, (t_p.valor_total - ((SELECT COALESCE(SUM(subtotal), 0) FROM factura WHERE ((matricula=ma.contrato) AND (vigente=1)))+(SELECT COALESCE(SUM(subtotal), 0) FROM recibo_caja WHERE ((matricula=ma.contrato) AND (vigente=1)))+(SELECT COALESCE(SUM(subtotal), 0) FROM abono_matricula WHERE ((matricula=ma.contrato) AND (vigente=1)))+(SELECT COALESCE(SUM(valor), 0) FROM descuento_matricula WHERE ((matricula=ma.contrato) AND (vigente=1))))+(SELECT COALESCE(SUM(total), 0) FROM nota_credito WHERE ((matricula=ma.contrato) AND (vigente=1)))) AS saldo from matricula AS ma, t_plan as t_p, sede AS s  where ((ma.id_titular = '" . $id_titular . "') AND (ma.dni_titular = '" . $dni_titular . "') AND ((ma.estado=1)||(ma.estado=2)) AND (ma.plan = t_p.id) AND (ma.sede=s.id))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    //Matriculas vigente con saldo incluido > 0
    public function total_abonos_matricula($matricula) {
        $SqlInfo = "SELECT (((SELECT COALESCE(SUM(subtotal), 0) FROM factura WHERE ((matricula='" . $matricula . "') AND (vigente=1)))+(SELECT COALESCE(SUM(valor), 0) FROM descuento_matricula WHERE ((matricula='" . $matricula . "') AND (vigente=1)))+(SELECT COALESCE(SUM(subtotal), 0) FROM recibo_caja WHERE ((matricula='" . $matricula . "') AND (vigente=1)))+(SELECT COALESCE(SUM(subtotal), 0) FROM abono_matricula WHERE ((matricula='" . $matricula . "') AND (vigente=1))))-(SELECT COALESCE(SUM(total), 0) FROM nota_credito WHERE ((matricula='" . $matricula . "') AND (vigente=1)))) AS total";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function t_caja() {
        $query = $this->db->get('t_caja');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_curso() {
        $query = $this->db->get('t_curso');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_caja_faltante($sede) {
        $where = "(id NOT IN(SELECT t_caja FROM caja WHERE (sede='" . $sede . "')))";
        $this->db->where($where);
        $query = $this->db->get('t_caja');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    //empleados que coincidan sede_ppal con la sede escogida y que no cuenten con una caja asignada.
    public function empleado_sede_caja($sede) {
        $where = "(sede_ppal='" . $sede . "') AND (NOT(id='1' AND dni='1')) AND estado!='3'";
        $this->db->where($where);
        $where2 = "(id NOT IN(SELECT DISTINCT c.id_encargado FROM caja AS c WHERE (c.vigente=1) AND (c.dni_encargado=dni)))";
        $this->db->where($where2);
        $this->db->order_by('nombre1', 'asc');
        $query = $this->db->get('empleado');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_dni() {
        $query = $this->db->get('t_dni');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_dni_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('t_dni');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function t_dni_empleado() {
        $this->db->where('visible_empleado', 1);
        $query = $this->db->get('t_dni');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_dni_cliente() {
        $this->db->where('visible_cliente', 1);
        $query = $this->db->get('t_dni');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_dni_alumno() {
        $this->db->where('visible_alumno', 1);
        $query = $this->db->get('t_dni');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_dni_proveedor() {
        $this->db->where('visible_proveedor', 1);
        $query = $this->db->get('t_dni');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_dni_titular() {
        $this->db->where('visible_titular', 1);
        $query = $this->db->get('t_dni');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_dni_todos() {
        $this->db->where('visible_todos', 1);
        $query = $this->db->get('t_dni');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_domicilio() {
        $query = $this->db->get('t_domicilio');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_cargo() {
        $query = $this->db->get('t_cargo');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

        public function t_cargo_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('t_cargo');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    //Devuelve los cargos de rrpp (depto:3) superiores a un cargo ingresado.
    public function t_cargo_superior_rrpp($cargo) {
        $where = "((depto=3)and (nivel_jerarquico < (SELECT nivel_jerarquico FROM t_cargo WHERE (id='" . $cargo . "'))));";
        $this->db->where($where);
        $query = $this->db->get('t_cargo');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    //Devuelve los empleados de rrpp (depto:3) con un cargo superior a un cargo ingresado.
    //Si el cargo tiene un nivel jerarquico menor que el administrador de sede, solo devolverá los que perstenecen a la sede principal del administrador.
    public function empleado_rrpp_cargo_superior($cargo, $id_responsable, $dni_responsable) {
        $SqlInfo = "(SELECT * FROM empleado AS e WHERE ((cargo IN (SELECT id FROM t_cargo WHERE depto=3)) AND (NOT(id='1' AND dni='1')) AND (estado!='3') AND ((SELECT nivel_jerarquico FROM t_cargo WHERE (id=e.cargo))<=8) AND ((SELECT nivel_jerarquico FROM t_cargo WHERE (id=e.cargo))<=(SELECT nivel_jerarquico FROM t_cargo WHERE (id='" . $cargo . "')))))union(SELECT * FROM empleado AS e WHERE ((cargo IN (SELECT id FROM t_cargo WHERE depto=3)) AND (NOT(id='1' AND dni='1')) AND (estado!='3') AND ((SELECT nivel_jerarquico FROM t_cargo WHERE (id=e.cargo))>8) AND ((SELECT nivel_jerarquico FROM t_cargo WHERE (id=e.cargo))<=(SELECT nivel_jerarquico FROM t_cargo WHERE (id='" . $cargo . "'))) AND (sede_ppal=(SELECT sede_ppal FROM empleado WHERE (id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "')))))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_sancion() {
        $query = $this->db->get('t_sancion');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_sancion_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('t_sancion');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function t_contrato_laboral() {
        $query = $this->db->get('t_contrato_laboral');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_contrato_laboral_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('t_contrato_laboral');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function contrato_laboral_empleado($id_empleado, $dni_empleado) {
        $SqlInfo = "SELECT c.*, s.nombre AS nombre_salario, t_c.contrato as tipo_contrato, CASE e.genero WHEN 'F' THEN t_ca.cargo_femenino ELSE t_ca.cargo_masculino END AS cargo FROM contrato_laboral AS c, empleado AS e, salario as s, t_contrato_laboral as t_c, t_cargo as t_ca WHERE ((c.id_empleado='" . $id_empleado . "') AND (c.dni_empleado='" . $dni_empleado . "') AND (c.t_contrato=t_c.id) AND (e.id='" . $id_empleado . "') AND (e.dni='" . $dni_empleado . "')  AND (e.salario=s.id) AND (e.cargo=t_ca.id))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function t_cargo_faltante_rrpp($cargo) {
        $this->db->where('id !=', $cargo);
        $this->db->where('depto', 3);
        $query = $this->db->get('t_cargo');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function pais() {
        $this->db->order_by('nombre', 'asc');
        $query = $this->db->get('pais');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function provincia() {
        $this->db->order_by('nombre', 'asc');
        $query = $this->db->get('provincia');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function ciudad() {
        $this->db->order_by('nombre', 'asc');
        $query = $this->db->get('ciudad');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function est_sede() {
        $query = $this->db->get('est_sede');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function est_alumno() {
        $query = $this->db->get('est_alumno');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function est_empleado() {
        $query = $this->db->get('est_empleado');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function cliente() {
        $this->db->order_by('nombre1', 'asc');
        $query = $this->db->get('cliente');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function cliente_id_dni($id, $dni) {
        $this->db->where('id', $id);
        $this->db->where('dni', $dni);
        $query = $this->db->get('cliente');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function cliente_prestamo($id_responsable, $dni_responsable) {
        $where = "((id IN(SELECT id_beneficiario FROM prestamo WHERE (((estado=1)||(estado=2)) AND (sede IN(SELECT sede_ppal FROM empleado WHERE (id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "')) OR (sede IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $id_responsable . "') AND (dni_empleado='" . $dni_responsable . "') AND (vigente=1))))))) AND (dni IN(SELECT dni_beneficiario FROM prestamo WHERE (((estado=1)||(estado=2)) AND (sede IN(SELECT sede_ppal FROM empleado WHERE (id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "')) OR (sede IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $id_responsable . "') AND (dni_empleado='" . $dni_responsable . "') AND (vigente=1))))))))";
        $this->db->where($where);
        $this->db->order_by('nombre1', 'asc');
        $query = $this->db->get('cliente');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function sede() {
        $query = $this->db->get('sede');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function sede_activa() {
        $this->db->where('estado  !=', 3);
        $query = $this->db->get('sede');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function sede_activa_faltante($sede) {
        $this->db->where('id  !=', $sede);
        $this->db->where('estado  !=', 3);
        $query = $this->db->get('sede');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function sede_activa_faltante_responsable($sede, $id_responsable, $dni_responsable) {
        $where = "((id IN(SELECT sede_ppal FROM empleado WHERE (id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "'))) OR (id IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $id_responsable . "') AND (dni_empleado='" . $dni_responsable . "') AND (vigente=1)))) AND (estado!='3') AND (id!='" . $sede . "')";
        $this->db->where($where);
        $query = $this->db->get('sede');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function sede_activa_responsable($id_responsable, $dni_responsable) {
        $where = "((id IN(SELECT sede_ppal FROM empleado WHERE (id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "'))) OR (id IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $id_responsable . "') AND (dni_empleado='" . $dni_responsable . "') AND (vigente=1)))) AND (estado!='3') ORDER BY prefijo_trans";
        $this->db->where($where);
        $query = $this->db->get('sede');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function salario_activo() {
        $this->db->where('vigente', 1);
        $query = $this->db->get('salario');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_depto() {
        $query = $this->db->get('t_depto');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function cargo_depto($depto) {
        $this->db->where('depto', $depto);
        $query = $this->db->get('t_cargo');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function salario_t_salario_x_t_depto($depto) {
        $where = "(t_salario IN (SELECT t_salario FROM t_salario_x_t_depto WHERE (t_depto='" . $depto . "')))";
        $this->db->where($where);
        $this->db->where('vigente', 1);
        $query = $this->db->get('salario');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_est_civil() {
        $query = $this->db->get('t_est_civil');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_ausencia() {
        $SqlInfo = "SELECT id, tipo, CASE salarial WHEN '0' THEN 'No Remunerada' ELSE 'Remunerada' END AS salarial FROM t_ausencia where visible=1";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_ausencia_id($id) {
        $SqlInfo = "SELECT id, tipo, CASE salarial WHEN '0' THEN 'No Remunerada' ELSE 'Remunerada' END AS salarial FROM t_ausencia where id='" . $id . "'";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    //Cuando vamos a pasar fechas desde php a mysql, hay que hacerlo concatenando '', ya que de lo contratio quedaria en la consulta la fecha sin comillas y daria error: between 2014-02-03 en vez de between '2014-02-03'
    public function ausencia_entre_fechas($id_empleado, $dni_empleado, $fecha_inicio, $fecha_fin) {
        $SqlInfo = "SELECT a.*, t.tipo, t.salarial FROM `ausencia_laboral` AS a, `t_ausencia` AS t WHERE (((a.fecha_inicio between '" . $fecha_inicio . "' AND '" . $fecha_fin . "') OR (a.fecha_fin between '" . $fecha_inicio . "' AND '" . $fecha_fin . "') OR ('" . $fecha_inicio . "' between a.fecha_inicio AND a.fecha_fin)) AND (a.vigente=1) AND (a.id_empleado='" . $id_empleado . "') AND (a.dni_empleado='" . $dni_empleado . "') AND (a.t_ausencia=t.id))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function empleado($id, $dni) {
        $this->db->where('id', $id);
        $this->db->where('dni', $dni);
        $query = $this->db->get('empleado');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function titular($id, $dni) {
        $this->db->where('id', $id);
        $this->db->where('dni', $dni);
        $query = $this->db->get('titular');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function alumno($id, $dni) {
        $this->db->where('id', $id);
        $this->db->where('dni', $dni);
        $query = $this->db->get('alumno');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    //excluimos al empleado 1 1 porq es el sistema y nadie lo debería ver.
    public function empleado_activo() {
        $where = "(NOT(id='1' AND dni='1')) AND estado!='3'";
        $this->db->where($where);
        $this->db->order_by('nombre1', 'asc');
        $query = $this->db->get('empleado');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    //Devuelve una lista con los empleados que pertenecen a la sede principal y a las secundarias de un responsable.
    public function empleado_activo_sedes_responsable($id_responsable, $dni_responsable) {
        $where = "((sede_ppal IN(SELECT sede_ppal FROM empleado WHERE (id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "'))) OR (sede_ppal IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $id_responsable . "') AND (dni_empleado='" . $dni_responsable . "') AND (vigente=1)))) AND ( NOT(id='1' AND dni='1')) AND (estado!='3')";
        $this->db->where($where);
        $this->db->order_by('nombre1', 'asc');
        $query = $this->db->get('empleado');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    //Devuelve una lista con los empleados de RRPPque pertenecen a la sede principal de un responsable.
    public function empleado_RRPP_sede_ppal($id_responsable, $dni_responsable) {
        $where = "(sede_ppal IN(SELECT sede_ppal FROM empleado WHERE ((id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "')))) AND (NOT(id='1' AND dni='1')) AND (estado!='3')";
        $this->db->where($where);
        $where2 = "cargo IN (SELECT id FROM t_cargo WHERE depto=3)";
        $this->db->where($where2);
        $this->db->order_by('nombre1', 'asc');
        $query = $this->db->get('empleado');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    //Devuelve una lista con los empleados de RRPPque pertenecen a la sede principal de un responsable.
    public function empleado_RRPP_sedes_responsable($id_responsable, $dni_responsable) {
        $where = "((sede_ppal IN(SELECT sede_ppal FROM empleado WHERE ((id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "')))) OR (sede_ppal IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $id_responsable . "') AND (dni_empleado='" . $dni_responsable . "') AND (vigente=1)))) AND (NOT(id='1' AND dni='1')) AND (estado!='3')";
        $this->db->where($where);
        $where2 = "cargo IN (SELECT id FROM t_cargo WHERE depto=3)";
        $this->db->where($where2);
        $this->db->order_by('nombre1', 'asc');
        $query = $this->db->get('empleado');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    //Devuelve una lista con los empleados que tienen adelantos  vigentes y que pertenecen a la sede principal y a las secundarias de un responsable.
    public function empleado_sedes_responsable_adelantos($id_responsable, $dni_responsable) {
        $where = "((sede_ppal IN(SELECT sede_ppal FROM empleado WHERE (id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "'))) OR (sede_ppal IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $id_responsable . "') AND (dni_empleado='" . $dni_responsable . "') AND (vigente=1)))) AND ( NOT(id='1' AND dni='1')) AND (estado!='3')";
        $this->db->where($where);
        $where2 = "((id IN(SELECT id_empleado FROM adelanto WHERE (((estado=1)||(estado=2))))) AND (dni IN(SELECT dni_empleado FROM adelanto WHERE (vigente=1))))";
        $this->db->where($where2);
        $this->db->order_by('nombre1', 'asc');
        $query = $this->db->get('empleado');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    //Devuelve una lista con los empleados que tienen prestamos vigentes y que pertenecen a la sede principal y a las secundarias de un responsable.
    public function empleado_sedes_responsable_prestamos($id_responsable, $dni_responsable) {
        $where = "((sede_ppal IN(SELECT sede_ppal FROM empleado WHERE (id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "'))) OR (sede_ppal IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $id_responsable . "') AND (dni_empleado='" . $dni_responsable . "') AND (vigente=1)))) AND ( NOT(id='1' AND dni='1')) AND (estado!='3')";
        $this->db->where($where);
        $where2 = "((id IN(SELECT id_beneficiario FROM prestamo WHERE (((estado=1)||(estado=2))))) AND (dni IN(SELECT dni_beneficiario FROM prestamo WHERE (((estado=1)||(estado=2))))))";
        $this->db->where($where2);
        $this->db->order_by('nombre1', 'asc');
        $query = $this->db->get('empleado');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    //Devuelve una lista con los empleados que pertenecen a la sede principal y a las secundarias de un responsable y que no tienen contrato.
    public function empleado_sedes_responsable_sinContrato($id_responsable, $dni_responsable) {
        $where = "((sede_ppal IN(SELECT sede_ppal FROM empleado WHERE (id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "'))) OR (sede_ppal IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $id_responsable . "') AND (dni_empleado='" . $dni_responsable . "') AND (vigente=1)))) AND ( NOT(id='1' AND dni='1')) AND (estado!='3')  AND (NOT((id IN(SELECT id_empleado FROM contrato_laboral)) AND (dni IN(SELECT dni_empleado FROM contrato_laboral))))";
        $this->db->where($where);
        $this->db->order_by('nombre1', 'asc');
        $query = $this->db->get('empleado');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function usuario_id_dni_t_usuario($id, $dni, $t_usuario) {
        $this->db->where('id', $id);
        $this->db->where('dni', $dni);
        $this->db->where('t_usuario', $t_usuario);
        $query = $this->db->get('usuario');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function proveedor_id_dni($id, $dni) {
        $this->db->where('id', $id);
        $this->db->where('dni', $dni);
        $query = $this->db->get('proveedor');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function proveedor() {
        $this->db->order_by('razon_social', 'asc');
        $query = $this->db->get('proveedor');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function ciudad_provincia($provincia) {
        $this->db->where('provincia', $provincia);
        $this->db->order_by('nombre', 'asc');
        $query = $this->db->get('ciudad');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function provincia_pais($pais) {
        $this->db->where('pais', $pais);
        $this->db->order_by('nombre', 'asc');
        $query = $this->db->get('provincia');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_salario() {
        $query = $this->db->get('t_salario');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_salario_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('t_salario');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function t_falta_laboral() {
        $SqlInfo = "SELECT id, falta, CASE gravedad WHEN '0' THEN 'Leve' ELSE 'Grave' END AS gravedad FROM t_falta_laboral";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_falta_laboral_id($id) {
        $SqlInfo = "SELECT id, falta, CASE gravedad WHEN '0' THEN 'Leve' ELSE 'Grave' END AS gravedad FROM t_falta_laboral where id='" . $id . "'";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function t_concepto_nomina_base($t_salario) {
        $this->db->where('concepto_base_nomina', 1);
        $this->db->where('t_salario', $t_salario);
        $query = $this->db->get('t_concepto_nomina');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_concepto_nomina_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('t_concepto_nomina');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function concepto_nomina_matricula($matricula) {
        $SqlInfo = "SELECT c_n.*, t_c.cargo_masculino as escala, CONCAT(em.nombre1, ' ', em.nombre2, ' ', em.apellido1, ' ', em.apellido2) AS ejecutivo, t_co.tipo as tipo_concepto from concepto_nomina as c_n, t_cargo as t_c, empleado as em, t_concepto_nomina as t_co where ((c_n.matricula = '" . $matricula . "') AND (c_n.estado = 1) AND (c_n.escala_matricula = t_c.id) AND ((em.id=c_n.id_empleado) AND (em.dni=c_n.dni_empleado)) AND (c_n.t_concepto_nomina = t_co.id))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function total_concepto_nomina_matricula($matricula) {
        $SqlInfo = "SELECT COALESCE(SUM(valor_unitario), 0) total from concepto_nomina where ((matricula = '" . $matricula . "') AND (estado = 1))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function t_concepto_nomina_depto_empleado($id_empleado, $dni_empleado) {
        $SqlInfo = "SELECT * FROM t_concepto_nomina WHERE ((visible_nomina=1) AND (t_salario IN(SELECT t_salario FROM salario WHERE (id=(SELECT salario FROM empleado WHERE ((id='" . $id_empleado . "') AND (dni='" . $dni_empleado . "')))))))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function t_concepto_nomina_cotidiano_empleado($id_empleado, $dni_empleado) {
        $SqlInfo = "SELECT * FROM t_concepto_nomina WHERE ((visible_nomina=1) AND (cotidiano=1) AND (t_salario IN(SELECT t_salario FROM salario WHERE (id=(SELECT salario FROM empleado WHERE ((id='" . $id_empleado . "') AND (dni='" . $dni_empleado . "')))))))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function concepto_nomina_pdte_rrpp($id_empleado, $dni_empleado) {
        $SqlInfo = "SELECT c.*, t_c.tipo as tipo_concepto, t_ca.cargo_masculino as escala FROM concepto_nomina as c, t_concepto_nomina as t_c, t_cargo as t_ca WHERE ((c.id_empleado='" . $id_empleado . "') AND (c.dni_empleado='" . $dni_empleado . "') AND (c.estado=2) AND ((c.t_concepto_nomina=28) OR (c.t_concepto_nomina=29)) AND (c.t_concepto_nomina=t_c.id) AND (c.escala_matricula=t_ca.id))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function concepto_nomina_seguridad_social($id_empleado, $dni_empleado) {
        $SqlInfo = "SELECT c.*, s.nombre AS nombre_sede FROM concepto_nomina AS c, sede as s WHERE ((c.id_empleado='" . $id_empleado . "') AND (c.dni_empleado='" . $dni_empleado . "') AND (c.estado='1') AND ((c.t_concepto_nomina='23') OR (c.t_concepto_nomina='43')) AND (c.sede=s.id))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function concepto_base_nomina_empleado($id_empleado, $dni_empleado, $t_concepto_nomina) {
        $SqlInfo = "SELECT * FROM concepto_base_nomina WHERE ((t_concepto_nomina='" . $t_concepto_nomina . "') AND (salario=(SELECT salario FROM empleado WHERE((id='" . $id_empleado . "') AND (dni='" . $dni_empleado . "')))))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function ultimas_nominas_empleado($id_empleado, $dni_empleado) {
        $SqlInfo = "SELECT n.*, s.nombre AS nombre_sede FROM nomina AS n, sede AS s WHERE ((vigente=1) AND (id_empleado='" . $id_empleado . "') AND (dni_empleado='" . $dni_empleado . "')  AND (n.sede=s.id)) order by fecha_trans desc limit 4";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function nextId_sede() {
        $this->db->select_max('id');
        $query = $this->db->get('sede');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_salario() {
        $this->db->select_max('id');
        $query = $this->db->get('salario');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_llamado_atencion() {
        $this->db->select_max('id');
        $query = $this->db->get('llamado_atencion');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_prestamo($prefijo) {
        $this->db->select_max('id');
        $this->db->where('prefijo', $prefijo);
        $query = $this->db->get('prestamo');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_abono_prestamo($prefijo) {
        $this->db->select_max('id');
        $this->db->where('prefijo', $prefijo);
        $query = $this->db->get('abono_prestamo');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_adelanto($prefijo) {
        $this->db->select_max('id');
        $this->db->where('prefijo', $prefijo);
        $query = $this->db->get('adelanto');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_abono_adelanto($prefijo) {
        $this->db->select_max('id');
        $this->db->where('prefijo', $prefijo);
        $query = $this->db->get('abono_adelanto');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_ingreso($prefijo) {
        $this->db->select_max('id');
        $this->db->where('prefijo', $prefijo);
        $query = $this->db->get('ingreso');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_egreso($prefijo) {
        $this->db->select_max('id');
        $this->db->where('prefijo', $prefijo);
        $query = $this->db->get('egreso');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_nomina($prefijo) {
        $this->db->select_max('id');
        $this->db->where('prefijo', $prefijo);
        $query = $this->db->get('nomina');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_factura($prefijo) {
        $this->db->select_max('id');
        $this->db->where('prefijo', $prefijo);
        $query = $this->db->get('factura');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_recibo_caja($prefijo) {
        $this->db->select_max('id');
        $this->db->where('prefijo', $prefijo);
        $query = $this->db->get('recibo_caja');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_abono_matricula($prefijo) {
        $this->db->select_max('id');
        $this->db->where('prefijo', $prefijo);
        $query = $this->db->get('abono_matricula');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_retefuente_compras($prefijo) {
        $this->db->select_max('id');
        $this->db->where('prefijo', $prefijo);
        $query = $this->db->get('retefuente_compras');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_retefuente_ventas($prefijo) {
        $this->db->select_max('id');
        $this->db->where('prefijo', $prefijo);
        $query = $this->db->get('retefuente_ventas');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_nota_credito($prefijo) {
        $this->db->select_max('id');
        $this->db->where('prefijo', $prefijo);
        $query = $this->db->get('nota_credito');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_pago_proveedor($prefijo) {
        $this->db->select_max('id');
        $this->db->where('prefijo', $prefijo);
        $query = $this->db->get('pago_proveedor');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_transferencia($prefijo) {
        $this->db->select_max('id');
        $this->db->where('prefijo', $prefijo);
        $query = $this->db->get('transferencia');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function nextId_reporte_alumno() {
        $this->db->select_max('id');
        $query = $this->db->get('reporte_alumno');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function empleado_sede_ppal($id, $dni) {
        $this->db->where('id', $id);
        $this->db->where('dni', $dni);
        $query = $this->db->get('empleado');
        if ($query->num_rows() == 1) {
            $this->db->where('id', $query->row()->sede_ppal);
            $query2 = $this->db->get('sede');
            if ($query2->num_rows() == 1) {
                return $query2->row();
            }
        }
    }

    public function adelanto_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT DISTINCT ad.*, (ad.total - (SELECT COALESCE(SUM(total), 0) FROM abono_adelanto WHERE ((prefijo_adelanto=ad.prefijo) AND (id_adelanto=ad.id) AND (vigente=1)))) AS saldo FROM adelanto AS ad WHERE ((ad.prefijo='" . $prefijo . "') AND (ad.id='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function abono_adelanto_prefijo_id($prefijo, $id) {
        $this->db->where('prefijo', $prefijo);
        $this->db->where('id', $id);
        $query = $this->db->get('abono_adelanto');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function factura_prefijo_id($prefijo, $id) {
        $this->db->where('prefijo', $prefijo);
        $this->db->where('id', $id);
        $query = $this->db->get('factura');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function abono_matricula_prefijo_id($prefijo, $id) {
        $this->db->where('prefijo', $prefijo);
        $this->db->where('id', $id);
        $query = $this->db->get('abono_matricula');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function detalle_factura_prefijo_id($prefijo, $id) {
        $this->db->where('prefijo_factura', $prefijo);
        $this->db->where('id_factura', $id);
        $query = $this->db->get('detalle_factura');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function recibo_caja_prefijo_id($prefijo, $id) {
        $this->db->where('prefijo', $prefijo);
        $this->db->where('id', $id);
        $query = $this->db->get('recibo_caja');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function ingreso_prefijo_id($prefijo, $id) {
        $this->db->where('prefijo', $prefijo);
        $this->db->where('id', $id);
        $query = $this->db->get('ingreso');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function egreso_prefijo_id($prefijo, $id) {
        $this->db->where('prefijo', $prefijo);
        $this->db->where('id', $id);
        $query = $this->db->get('egreso');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function pago_proveedor_prefijo_id($prefijo, $id) {
        $this->db->where('prefijo', $prefijo);
        $this->db->where('id', $id);
        $query = $this->db->get('pago_proveedor');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function retefuente_compras_prefijo_id($prefijo, $id) {
        $this->db->where('prefijo', $prefijo);
        $this->db->where('id', $id);
        $query = $this->db->get('retefuente_compras');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function retefuente_ventas_prefijo_id($prefijo, $id) {
        $this->db->where('prefijo', $prefijo);
        $this->db->where('id', $id);
        $query = $this->db->get('retefuente_ventas');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function detalle_recibo_caja_prefijo_id($prefijo, $id) {
        $this->db->where('prefijo_recibo_caja', $prefijo);
        $this->db->where('id_recibo_caja', $id);
        $query = $this->db->get('detalle_recibo_caja');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function nota_credito_prefijo_id($prefijo, $id) {
        $this->db->where('prefijo', $prefijo);
        $this->db->where('id', $id);
        $query = $this->db->get('nota_credito');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function empleado_sede_secundaria($id, $dni) {
        $SqlInfo = "SELECT * FROM empleado_x_sede AS a, sede AS b WHERE (a.sede_secundaria=b.id) AND (a.dni_empleado='" . $dni . "')AND (a.id_empleado='" . $id . "')AND (a.vigente=1)";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function sede_secundaria_faltante_empleado_responsable($id_empleado, $dni_empleado, $id_responsable, $dni_responsable) {
        $where1 = "((id IN(SELECT sede_ppal FROM empleado WHERE (id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "'))) OR (id IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $id_responsable . "') AND (dni_empleado='" . $dni_responsable . "') AND (vigente=1))))";
        $this->db->where($where1);
        $where2 = "id not IN (SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $id_empleado . "') AND (dni_empleado='" . $dni_empleado . "') AND (vigente=1))";
        $this->db->where($where2);
        $this->db->where('estado  !=', 3);
        $query = $this->db->get('sede');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function sede_faltante_cuenta_bancaria_responsable($cuenta, $id_responsable, $dni_responsable) {
        $where1 = "((id IN(SELECT sede_ppal FROM empleado WHERE (id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "'))) OR (id IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $id_responsable . "') AND (dni_empleado='" . $dni_responsable . "') AND (vigente=1))))";
        $this->db->where($where1);
        $where2 = "id not IN (SELECT sede FROM cuenta_x_sede WHERE ((cuenta='" . $cuenta . "')  AND (vigente=1)))";
        $this->db->where($where2);
        $this->db->where('estado  !=', 3);
        $query = $this->db->get('sede');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    //necesitamos los empleados activos.
    //Solo puede autorizar a empleados que esten en las sedes del responsable
    //empleados cuya sede principal conincida con las sedes autorizadas para dicha cuenta.
    //empleados cuya sede principal o secundaria coinciden con la sede del responsable.
    //Empleados que no esten autorizados para esa sede, porq no tendria sentido volverlos a autorizar.
    public function empleado_faltante_cuenta_bancaria_ingresar($cuenta, $id_responsable, $dni_responsable) {
        $where1 = "((sede_ppal IN(SELECT sede_ppal FROM empleado WHERE ((id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "')))) OR (sede_ppal IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $id_responsable . "') AND (dni_empleado='" . $dni_responsable . "') AND (vigente=1)))) AND (NOT(id='1' AND dni='1')) AND (estado!='3')";
        $this->db->where($where1);
        $where2 = "sede_ppal IN(SELECT sede FROM cuenta_x_sede WHERE ((cuenta='" . $cuenta . "')  AND (vigente=1)))";
        $this->db->where($where2);
        $where3 = "not((id IN(SELECT id_encargado FROM cuenta_x_sede_x_empleado WHERE ((cuenta='" . $cuenta . "')  AND (permiso_ingresar=1)))) AND (dni IN (SELECT dni_encargado FROM cuenta_x_sede_x_empleado WHERE ((cuenta='" . $cuenta . "')  AND (permiso_ingresar=1)))))";
        $this->db->where($where3);
        $where4 = '(NOT(id=1 AND dni=1))';
        $this->db->where($where4);
        $this->db->where('estado  !=', 3);
        $query = $this->db->get('empleado');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    //necesitamos los empleados activos.
    //Solo puede autorizar a empleados que esten en las sedes del responsable
    //empleados cuya sede principal conincida con las sedes autorizadas para dicha cuenta.
    //empleados cuya sede principal o secundaria coinciden con la sede del responsable.
    //Empleados que no esten autorizados para esa sede, porq no tendria sentido volverlos a autorizar.
    public function empleado_faltante_cuenta_bancaria_retirar($cuenta, $id_responsable, $dni_responsable) {
        $where1 = "((sede_ppal IN(SELECT sede_ppal FROM empleado WHERE ((id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "')))) OR (sede_ppal IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $id_responsable . "') AND (dni_empleado='" . $dni_responsable . "') AND (vigente=1)))) AND (NOT(id='1' AND dni='1')) AND (estado!='3')";
        $this->db->where($where1);
        $where2 = "sede_ppal IN(SELECT sede FROM cuenta_x_sede WHERE ((cuenta='" . $cuenta . "')  AND (vigente=1)))";
        $this->db->where($where2);
        $where3 = "not((id IN(SELECT id_encargado FROM cuenta_x_sede_x_empleado WHERE ((cuenta='" . $cuenta . "')  AND (permiso_retirar=1)))) AND (dni IN (SELECT dni_encargado FROM cuenta_x_sede_x_empleado WHERE ((cuenta='" . $cuenta . "')  AND (permiso_retirar=1)))))";
        $this->db->where($where3);
        $where4 = '(NOT(id=1 AND dni=1))';
        $this->db->where($where4);
        $this->db->where('estado  !=', 3);
        $query = $this->db->get('empleado');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    //necesitamos los empleados activos.
    //Solo puede autorizar a empleados que esten en las sedes del responsable
    //empleados cuya sede principal conincida con las sedes autorizadas para dicha cuenta.
    //empleados cuya sede principal o secundaria coinciden con la sede del responsable.
    //Empleados que no esten autorizados para esa sede, porq no tendria sentido volverlos a autorizar.
    public function empleado_faltante_cuenta_bancaria_consultar($cuenta, $id_responsable, $dni_responsable) {
        $where1 = "((sede_ppal IN(SELECT sede_ppal FROM empleado WHERE ((id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "')))) OR (sede_ppal IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $id_responsable . "') AND (dni_empleado='" . $dni_responsable . "') AND (vigente=1)))) AND (NOT(id='1' AND dni='1')) AND (estado!='3')";
        $this->db->where($where1);
        $where2 = "sede_ppal IN(SELECT sede FROM cuenta_x_sede WHERE ((cuenta='" . $cuenta . "')  AND (vigente=1)))";
        $this->db->where($where2);
        $where3 = "not((id IN(SELECT id_encargado FROM cuenta_x_sede_x_empleado WHERE ((cuenta='" . $cuenta . "')  AND (permiso_consultar=1)))) AND (dni IN (SELECT dni_encargado FROM cuenta_x_sede_x_empleado WHERE ((cuenta='" . $cuenta . "')  AND (permiso_consultar=1)))))";
        $this->db->where($where3);
        $where4 = '(NOT(id=1 AND dni=1))';
        $this->db->where($where4);
        $this->db->where('estado  !=', 3);
        $query = $this->db->get('empleado');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function empleado_cargo($id, $dni) {
        $this->db->where('id', $id);
        $this->db->where('dni', $dni);
        $query = $this->db->get('empleado');
        if ($query->num_rows() == 1) {
            $this->db->where('id', $query->row()->cargo);
            $query2 = $this->db->get('t_cargo');
            if ($query2->num_rows() == 1) {
                return $query2->row();
            }
        }
    }

    public function empleado_jefe($id, $dni) {
        $this->db->where('id', $id);
        $this->db->where('dni', $dni);
        $query = $this->db->get('empleado');
        if ($query->num_rows() == 1) {
            $this->db->where('id', $query->row()->id_jefe);
            $this->db->where('dni', $query->row()->dni_jefe);
            $query2 = $this->db->get('empleado');
            if ($query2->num_rows() == 1) {
                return $query2->row();
            }
        }
    }

    //Quitamos de la consulta al empleado, al jefe y al sistema y solo cargamos a los jefes con un nivel jerarquico superior que pertenecen al mismo departamento.
    //Y a la consulta le unimos el administrador de sede del responsable y el director nacional y el general de la empresa.
    //Como juan jose (directo de RRPP) es a la vez director general, entonces vamos a mostrar a juan jose con su documento. Cuando sea solo director general, se quitará esta condicion.
    //Si el cargo del jefe es un nivel jerarquico <= 8 (son jefes al interior de la misma sede), entonces debe cargar solo los empleados de la misma sede ppal.    
    public function empleado_jefe_faltante_rrpp($id_empleado, $dni_empleado) {
        $SqlInfo = "(SELECT * FROM empleado AS e WHERE (((SELECT nivel_jerarquico FROM t_cargo WHERE (id=e.cargo))<(SELECT nivel_jerarquico FROM t_cargo WHERE (id=(SELECT cargo FROM empleado WHERE ((id='" . $id_empleado . "') AND (dni='" . $dni_empleado . "')))))) AND (e.depto=3) AND (estado!='3') AND (NOT(id='1' AND dni='1')) AND ((SELECT nivel_jerarquico FROM t_cargo WHERE (id=e.cargo))>=(8)) AND (sede_ppal=(SELECT sede_ppal FROM empleado WHERE (id='" . $id_empleado . "') AND (dni='" . $dni_empleado . "'))))) union (SELECT * FROM empleado AS e WHERE (((SELECT nivel_jerarquico FROM t_cargo WHERE (id=e.cargo))<(SELECT nivel_jerarquico FROM t_cargo WHERE (id=(SELECT cargo FROM empleado WHERE ((id='" . $id_empleado . "') AND (dni='" . $dni_empleado . "')))))) AND (e.depto=3) AND (estado!='3') AND (NOT(id='1' AND dni='1')) AND ((SELECT nivel_jerarquico FROM t_cargo WHERE (id=e.cargo))<(8))))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    //Quitamos de la consulta al sistema y solo cargamos a los jefes con un nivel jerarquico superior que pertenecen al mismo departamento.
    //Y a la consulta le unimos el administrador de sede del responsable y el director nacional y el general de la empresa.
    //Como juan jose (directo de RRPP) es a la vez director general, entonces vamos a mostrar tambien el cargo de directivo de RRPP, pero con el tiepo
    //Cuando Juan Jose solo Director General, entonces se quitaria la condicion "or (cargo=12) or"
    //Si el cargo del jefe es un nivel jerarquico <= 8 (son jefes al interior de la misma sede), entonces debe cargar solo los empleados de la misma sede ppal.
    public function empleado_jefe_faltante_sede_depto_cargo($sede_ppal, $depto, $cargo) {
        $SqlInfo = "(SELECT * FROM empleado AS e WHERE (((SELECT nivel_jerarquico FROM t_cargo WHERE (id=e.cargo))<(SELECT nivel_jerarquico FROM t_cargo WHERE (id='" . $cargo . "'))) AND (e.depto='" . $depto . "') AND (estado!='3') AND (NOT(id='1' AND dni='1'))) AND (((SELECT nivel_jerarquico FROM t_cargo WHERE (id=e.cargo))>=(8))) AND (sede_ppal='" . $sede_ppal . "')) union (SELECT * FROM empleado AS e WHERE (((SELECT nivel_jerarquico FROM t_cargo WHERE (id=e.cargo))<(SELECT nivel_jerarquico FROM t_cargo WHERE (id='" . $cargo . "'))) AND (e.depto='" . $depto . "') AND (estado!='3') AND (NOT(id='1' AND dni='1')) AND ((SELECT nivel_jerarquico FROM t_cargo WHERE (id=e.cargo))<(8)))) union (SELECT * FROM empleado AS e WHERE (((cargo=1) OR (cargo=2) OR ((cargo=5) AND (sede_ppal ='" . $sede_ppal . "'))) AND (estado!='3') AND (NOT(id='1' AND dni='1')) AND ((SELECT nivel_jerarquico FROM t_cargo WHERE (id=e.cargo))<(SELECT nivel_jerarquico FROM t_cargo WHERE (id='" . $cargo . "')))))  union (SELECT * FROM empleado WHERE ((id=98667633) AND (dni=1)))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function solicitud_placa() {
        $SqlInfo = "SELECT DISTINCT so.id AS id_solicitud, e.nombre1, e.nombre2, e.apellido1, e.apellido2, CASE e.genero WHEN 'F' THEN t.cargo_femenino ELSE t.cargo_masculino END AS cargo, se.nombre AS sede, so.observacion, so.fecha_trans FROM solicitud_placa AS so, empleado AS e, t_cargo AS t, sede AS se WHERE (so.id_empleado=e.id) AND (so.dni_empleado=e.dni) AND (so.pendiente=1) AND (so.cargo_obtenido=t.id) AND (so.sede=se.id) ORDER BY so.fecha_trans";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function despacho_placa($sede_responsable) {
        $SqlInfo = "SELECT DISTINCT d.id AS id_despacho, e.nombre1, e.nombre2, e.apellido1, e.apellido2, CASE e.genero WHEN 'F' THEN t.cargo_femenino ELSE t.cargo_masculino END AS cargo, se.nombre AS sede, d.observacion, d.fecha_trans AS fecha_despacho FROM despachar_placa AS d, solicitud_placa AS so, empleado AS e, t_cargo AS t, sede AS se WHERE (d.solicitud_placa=so.id) AND (so.id_empleado=e.id) AND (so.dni_empleado=e.dni) AND (d.pendiente=1) AND (so.cargo_obtenido=t.id) AND (so.sede='" . $sede_responsable . "') AND (so.sede=se.id) ORDER BY d.fecha_trans";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    //adelantos vigente con saldo incluido > 0
    public function adelanto_vigente_empleado($id_empleado, $dni_empleado) {
        $SqlInfo = "SELECT DISTINCT ad.prefijo AS prefijo_adelanto, ad.id AS id_adelanto, ad.total, s.nombre AS sede, ad.autoriza, ad.motivo, ad.forma_descuento, ad.fecha_trans, (ad.total - (SELECT COALESCE(SUM(total), 0) FROM abono_adelanto WHERE ((prefijo_adelanto=ad.prefijo) AND (id_adelanto=ad.id) AND (vigente=1)))) AS saldo FROM adelanto AS ad, sede AS s WHERE ((ad.id_empleado='" . $id_empleado . "') AND (ad.dni_empleado='" . $dni_empleado . "') AND (ad.vigente=1) AND (ad.sede=s.id))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    //Prestamos vigente con saldo incluido > 0
    public function prestamo_vigente_beneficiario($id_beneficiario, $dni_beneficiario) {
        $SqlInfo = "SELECT DISTINCT pr.prefijo AS prefijo_prestamo, pr.id AS id_prestamo, pr.total, pr.cant_cuotas, pr.tasa_interes, pr.cuota_fija, s.nombre AS sede, pr.fecha_trans, pr.observacion FROM prestamo AS pr, sede AS s WHERE ((pr.id_beneficiario='" . $id_beneficiario . "') AND (pr.dni_beneficiario='" . $dni_beneficiario . "') AND ((pr.estado=1)||(pr.estado=2)) AND (pr.sede=s.id))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function prestamo_prefijo_id($prefijo, $id) {
        $this->db->where('prefijo', $prefijo);
        $this->db->where('id', $id);
        $query = $this->db->get('prestamo');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function abono_prestamo_prestamo($prefijo_prestamo, $id_prestamo) {
        $this->db->where('prefijo_prestamo', $prefijo_prestamo);
        $this->db->where('id_prestamo', $id_prestamo);
        $this->db->where('vigente', 1);
        $this->db->order_by('fecha_trans', 'asc');
        $query = $this->db->get('abono_prestamo');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function interes_mora() {
        $this->db->where('vigente', 1);
        $this->db->limit(1);
        $query = $this->db->get('interes_mora');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function comision_matricula($plan, $cargo) {
        $this->db->where('plan', $plan);
        $this->db->where('cargo', $cargo);
        $query = $this->db->get('comision_matricula');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function comision_escala($plan, $cargo) {
        $this->db->where('plan', $plan);
        $this->db->where('cargo', $cargo);
        $query = $this->db->get('comision_escala');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function periodicidad_nomina($id_empleado, $dni_empleado) {
        $SqlInfo = "select * from t_periodicidad_nomina where (id IN(select t_periodicidad_nomina from t_periodicidad_x_t_salario where (t_salario=(select t_salario from salario where (id=(select salario from empleado where ((id='" . $id_empleado . "') AND (dni='" . $dni_empleado . "'))))))))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

}
