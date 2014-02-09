<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Transaccionesm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function total_transacciones($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT (select COALESCE(SUM(ad.total),0) FROM abono_adelanto ad) +
    (select COALESCE(SUM(ap.subtotal+ap.int_mora),0) FROM abono_prestamo ap)+
    (select COALESCE(SUM(ing.total),0) FROM ingreso ing)+
       (select COALESCE(SUM(f.total),0) FROM factura f)+
       (select COALESCE(SUM(rc.total),0) FROM recibo_caja rc)+
    (select COALESCE(SUM(rf.total),0) FROM retefuente rf)-
    (select COALESCE(SUM(nc.total),0) FROM nota_credito nc)-
    (select COALESCE(SUM(ad.total),0) FROM adelanto ad)-
     (select COALESCE(SUM(pre.total),0) FROM prestamo pre)-
     (select COALESCE(SUM(egr.total),0) FROM egreso egr)-
     (select COALESCE(SUM(n.total),0) FROM nomina n)-
     (select COALESCE(SUM(pp.total),0) FROM pago_proveedor pp)";
    }

    public function cantidad_transacciones($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad
                  FROM alumno a
                  JOIN t_dni td ON a.dni=td.id
                  JOIN pais pa ON a.pais=pa.id
                  JOIN provincia pro ON a.provincia=pro.id
                  JOIN ciudad ciu ON a.ciudad=ciu.id
                  JOIN t_domicilio tdom ON a.t_domicilio=tdom.id
                  JOIN est_alumno ealum ON a.estado=ealum.id
                  JOIN sede s ON a.sede_ppal=s.id
                  where true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND a.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND a.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(a.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(a.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(a.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(a.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND a.fecha_nacimiento = '{$criterios['fecha_nacimiento']}'" : "";
        $query.=(isset($criterios['matricula'])) ? "AND a.matricula = '{$criterios['matricula']}'" : "";
        $query.=(isset($criterios['curso'])) ? "AND a.t_curso = '{$criterios['curso']}'" : "";
        $query.=(isset($criterios['sede_ppal'])) ? "AND a.sede_ppal = '{$criterios['sede_ppal']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_transacciones($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT ab.total,ab.fecha_trans,s.nombre sede,ab.prefijo,ab.id,ab.observacion,tc.tipo caja,'Abono adelanto' nombre_tabla,ab.efectivo_ingresado efectivo,ab.valor_consignado consignado,ab.vigente FROM abono_adelanto ab JOIN sede s ON ab.sede=s.id JOIN t_caja tc ON ab.t_caja_destino=tc.id 
                  UNION
                  SELECT (ap.subtotal+ap.int_mora) total,ap.fecha_trans,s.nombre sede,ap.prefijo,ap.id,ap.observacion,tc.tipo caja,'Abono prestamo' nombre_tabla,ap.efectivo_ingresado efectivo,ap.valor_consignado consignado,ap.vigente FROM abono_prestamo ap JOIN sede s ON ap.sede=s.id JOIN t_caja tc ON ap.t_caja_destino=tc.id 
                  UNION
                  SELECT ing.total,ing.fecha_trans,s.nombre sede,ing.prefijo,ing.id,ing.descripcion,tc.tipo caja,'Ingreso' nombre_tabla,ing.efectivo_ingresado efectivo,ing.valor_consignado consignado,ing.vigente FROM ingreso ing JOIN sede s ON ing.sede=s.id JOIN t_caja tc ON ing.t_caja_destino=tc.id 
                  UNION
                  SELECT f.total,f.fecha_trans,s.nombre sede,f.prefijo,f.id,f.observacion,tc.tipo caja,'Factura' nombre_tabla,f.efectivo_ingresado efectivo,f.valor_consignado consignado,f.vigente FROM factura f JOIN sede s ON f.sede=s.id JOIN t_caja tc ON f.t_caja_destino=tc.id 
                  UNION
                  SELECT nc.total,nc.fecha_trans,s.nombre sede,nc.prefijo,nc.id,nc.observacion,tc.tipo caja,'Nota credito' nombre_tabla,nc.efectivo_retirado efectivo ,nc.valor_retirado consignado,nc.vigente FROM nota_credito nc JOIN sede s ON nc.sede=s.id JOIN t_caja tc ON nc.t_caja_origen=tc.id 
                  UNION
                  SELECT rf.total,rf.fecha_trans,s.nombre sede,rf.prefijo,rf.id,rf.observacion,tc.tipo caja,'Rete. fuente' nombre_tabla,rf.efectivo_ingresado efectivo,rf.valor_consignado consignado,rf.vigente FROM retefuente rf JOIN sede s ON rf.sede=s.id JOIN t_caja tc ON rf.t_caja_destino=tc.id 
                  UNION
                  SELECT ad.total,ad.fecha_trans,s.nombre sede,ad.prefijo,ad.id,ad.observacion,tc.tipo caja,'Adelanto' nombre_tabla,ad.efectivo_retirado efectivo,ad.valor_retirado consignado,ad.estado vigente FROM adelanto ad JOIN sede s ON ad.sede=s.id JOIN t_caja tc ON ad.t_caja_origen=tc.id 
                  UNION
                  SELECT pre.total,pre.fecha_trans,s.nombre sede,pre.prefijo,pre.id,pre.observacion,tc.tipo caja,'Prestamo' nombre_tabla,pre.efectivo_retirado efectivo,pre.valor_retirado consignado,pre.estado vigente FROM prestamo pre JOIN sede s ON pre.sede=s.id JOIN t_caja tc ON pre.t_caja_origen=tc.id 
                  UNION
                  SELECT egr.total,egr.fecha_trans,s.nombre sede,egr.prefijo,egr.id,egr.descripcion,tc.tipo caja,'Egreso' nombre_tabla,egr.efectivo_retirado efectivo,egr.valor_retirado consignado,egr.vigente FROM egreso egr JOIN sede s ON egr.sede=s.id JOIN t_caja tc ON egr.t_caja_origen=tc.id 
                  UNION
                  SELECT n.total,n.fecha_trans,s.nombre sede,n.prefijo,n.id,n.observacion,tc.tipo caja,'Nomina' nombre_tabla,n.efectivo_retirado efectivo,n.valor_retirado consignado,n.vigente FROM nomina n JOIN sede s ON n.sede=s.id JOIN t_caja tc ON n.t_caja_origen=tc.id 
                  UNION
                  SELECT pp.total,pp.fecha_trans,s.nombre sede,pp.prefijo,pp.id,pp.observacion,tc.tipo caja,'Pago proveedor' nombre_tabla,pp.efectivo_retirado efectivo,pp.valor_retirado consignado,pp.vigente FROM pago_proveedor pp JOIN sede s ON pp.sede=s.id JOIN t_caja tc ON pp.t_caja_origen=tc.id 
                  UNION
                  SELECT rc.total,rc.fecha_trans,s.nombre sede,rc.prefijo,rc.id,rc.observacion,tc.tipo caja,'Recibo caja' nombre_tabla,rc.efectivo_ingresado efectivo,rc.valor_consignado consignado,rc.vigente FROM recibo_caja rc JOIN sede s ON rc.sede=s.id JOIN t_caja tc ON rc.t_caja_destino=tc.id 
                  UNION
                  SELECT rc.total,rc.fecha_trans,s.nombre sede,rc.prefijo,rc.id,rc.observacion,tc.tipo caja,'Recibo caja' nombre_tabla,rc.efectivo_ingresado efectivo,rc.valor_consignado consignado,rc.vigente FROM recibo_caja rc JOIN sede s ON rc.sede=s.id JOIN t_caja tc ON rc.t_caja_destino=tc.id ";

        $query.=(!empty($criterios['tipo_documento'])) ? "AND a.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND a.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(a.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(a.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(a.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(a.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND a.fecha_nacimiento = '{$criterios['fecha_nacimiento']}'" : "";
        $query.=(isset($criterios['matricula'])) ? "AND a.matricula = '{$criterios['matricula']}'" : "";
        $query.=(isset($criterios['curso'])) ? "AND a.t_curso = '{$criterios['curso']}'" : "";
        $query.=(isset($criterios['sede_ppal'])) ? "AND a.sede_ppal = '{$criterios['sede_ppal']}'" : "";
        $query.=" LIMIT $inicio,$filasPorPagina";
        echo $query;
        return $this->db->query($query)->result();
    }

}
