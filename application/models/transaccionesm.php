<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Transaccionesm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function total_transacciones($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT ((select COALESCE(SUM(ad.total),0) FROM abono_adelanto ad) +
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
     (select COALESCE(SUM(pp.total),0) FROM pago_proveedor pp)) total";
        return $this->db->query($query)->result();
    }

    public function cantidad_transacciones($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT *  FROM (SELECT ab.total,ab.fecha_trans,s.nombre sede,s.id id_sede,ab.prefijo,ab.id,ab.observacion,tc.tipo caja,tc.id id_caja,'Abono adelanto' nombre_tabla,ab.efectivo_ingresado efectivo,ab.valor_consignado consignado,ab.vigente,ab.dni_responsable,ab.id_responsable FROM abono_adelanto ab JOIN sede s ON ab.sede=s.id JOIN t_caja tc ON ab.t_caja_destino=tc.id 
                  UNION
                  SELECT (ap.subtotal+ap.int_mora) total,ap.fecha_trans,s.nombre sede,s.id id_sede,ap.prefijo,ap.id,ap.observacion,tc.tipo caja,tc.id id_caja,'Abono prestamo' nombre_tabla,ap.efectivo_ingresado efectivo,ap.valor_consignado consignado,ap.vigente,ap.dni_responsable,ap.id_responsable FROM abono_prestamo ap JOIN sede s ON ap.sede=s.id JOIN t_caja tc ON ap.t_caja_destino=tc.id 
                  UNION
                  SELECT ing.total,ing.fecha_trans,s.nombre sede,s.id id_sede,ing.prefijo,ing.id,ing.descripcion,tc.tipo caja,tc.id id_caja,'Ingreso' nombre_tabla,ing.efectivo_ingresado efectivo,ing.valor_consignado consignado,ing.vigente,ing.dni_responsable,ing.id_responsable FROM ingreso ing JOIN sede s ON ing.sede=s.id JOIN t_caja tc ON ing.t_caja_destino=tc.id 
                  UNION
                  SELECT f.total,f.fecha_trans,s.nombre sede,s.id id_sede,f.prefijo,f.id,f.observacion,tc.tipo caja,tc.id id_caja,'Factura' nombre_tabla,f.efectivo_ingresado efectivo,f.valor_consignado consignado,f.vigente,f.dni_responsable,f.id_responsable FROM factura f JOIN sede s ON f.sede=s.id JOIN t_caja tc ON f.t_caja_destino=tc.id 
                  UNION
                  SELECT nc.total,nc.fecha_trans,s.nombre sede,s.id id_sede,nc.prefijo,nc.id,nc.observacion,tc.tipo caja,tc.id id_caja,'Nota credito' nombre_tabla,nc.efectivo_retirado efectivo ,nc.valor_retirado consignado,nc.vigente,nc.dni_responsable,nc.id_responsable FROM nota_credito nc JOIN sede s ON nc.sede=s.id JOIN t_caja tc ON nc.t_caja_origen=tc.id 
                  UNION
                  SELECT rf.total,rf.fecha_trans,s.nombre sede,s.id id_sede,rf.prefijo,rf.id,rf.observacion,tc.tipo caja,tc.id id_caja,'Rete. fuente' nombre_tabla,rf.efectivo_ingresado efectivo,rf.valor_consignado consignado,rf.vigente,rf.dni_responsable,rf.id_responsable FROM retefuente rf JOIN sede s ON rf.sede=s.id JOIN t_caja tc ON rf.t_caja_destino=tc.id 
                  UNION
                  SELECT ad.total,ad.fecha_trans,s.nombre sede,s.id id_sede,ad.prefijo,ad.id,ad.observacion,tc.tipo caja,tc.id id_caja,'Adelanto' nombre_tabla,ad.efectivo_retirado efectivo,ad.valor_retirado consignado,ad.estado vigente,ad.dni_responsable,ad.id_responsable FROM adelanto ad JOIN sede s ON ad.sede=s.id JOIN t_caja tc ON ad.t_caja_origen=tc.id 
                  UNION
                  SELECT pre.total,pre.fecha_trans,s.nombre sede,s.id id_sede,pre.prefijo,pre.id,pre.observacion,tc.tipo caja,tc.id id_caja,'Prestamo' nombre_tabla,pre.efectivo_retirado efectivo,pre.valor_retirado consignado,pre.estado vigente,pre.dni_responsable,pre.id_responsable FROM prestamo pre JOIN sede s ON pre.sede=s.id JOIN t_caja tc ON pre.t_caja_origen=tc.id 
                  UNION
                  SELECT egr.total,egr.fecha_trans,s.nombre sede,s.id id_sede,egr.prefijo,egr.id,egr.descripcion,tc.tipo caja,tc.id id_caja,'Egreso' nombre_tabla,egr.efectivo_retirado efectivo,egr.valor_retirado consignado,egr.vigente,egr.dni_responsable,egr.id_responsable FROM egreso egr JOIN sede s ON egr.sede=s.id JOIN t_caja tc ON egr.t_caja_origen=tc.id 
                  UNION
                  SELECT n.total,n.fecha_trans,s.nombre sede,s.id id_sede,n.prefijo,n.id,n.observacion,tc.tipo caja,tc.id id_caja,'Nomina' nombre_tabla,n.efectivo_retirado efectivo,n.valor_retirado consignado,n.vigente,n.dni_responsable,n.id_responsable FROM nomina n JOIN sede s ON n.sede=s.id JOIN t_caja tc ON n.t_caja_origen=tc.id 
                  UNION
                  SELECT pp.total,pp.fecha_trans,s.nombre sede,s.id id_sede,pp.prefijo,pp.id,pp.observacion,tc.tipo caja,tc.id id_caja,'Pago proveedor' nombre_tabla,pp.efectivo_retirado efectivo,pp.valor_retirado consignado,pp.vigente,pp.dni_responsable,pp.id_responsable FROM pago_proveedor pp JOIN sede s ON pp.sede=s.id JOIN t_caja tc ON pp.t_caja_origen=tc.id 
                  UNION
                  SELECT rc.total,rc.fecha_trans,s.nombre sede,s.id id_sede,rc.prefijo,rc.id,rc.observacion,tc.tipo caja,tc.id id_caja,'Recibo caja' nombre_tabla,rc.efectivo_ingresado efectivo,rc.valor_consignado consignado,rc.vigente,rc.dni_responsable,rc.id_responsable FROM recibo_caja rc JOIN sede s ON rc.sede=s.id JOIN t_caja tc ON rc.t_caja_destino=tc.id 
                  UNION
                  SELECT rc.total,rc.fecha_trans,s.nombre sede,s.id id_sede,rc.prefijo,rc.id,rc.observacion,tc.tipo caja,tc.id id_caja,'Recibo caja' nombre_tabla,rc.efectivo_ingresado efectivo,rc.valor_consignado consignado,rc.vigente,rc.dni_responsable,rc.id_responsable FROM recibo_caja rc JOIN sede s ON rc.sede=s.id JOIN t_caja tc ON rc.t_caja_destino=tc.id 
                  UNION
                  SELECT ti.total,ti.fecha_trans,s.nombre sede,s.id id_sede,ti.prefijo,ti.id,ti.observacion,tc.tipo caja,tc.id id_caja,'Transferencia intersede' nombre_tabla,ti.efectivo_ingresado efectivo,ti.valor_consignado consignado,ti.est_traslado,ti.dni_responsable,ti.id_responsable FROM  transferencia_intersede ti JOIN sede s ON ti.sede_caja_destino=s.id JOIN t_caja tc ON ti.t_caja_destino=tc.id 
                  UNION
                  SELECT ti.total,ti.fecha_trans,s.nombre sede,s.id id_sede,ti.prefijo,ti.id,ti.observacion,tc.tipo caja,tc.id id_caja,'Transferencia intersede' nombre_tabla,ti.efectivo_retirado efectivo,ti.valor_retirado consignado,ti.est_traslado,ti.dni_responsable,ti.id_responsable FROM  transferencia_intersede ti JOIN sede s ON ti.sede_caja_origen=s.id JOIN t_caja tc ON ti.t_caja_origen=tc.id) u WHERE true ";

        $query.=(!empty($criterios['desde'])) ? "AND u.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
        $query.=(!empty($criterios['hasta'])) ? "AND u.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
        $query.=(isset($criterios['sede'])) ? "AND u.id_sede= '{$criterios['sede']}' " : "";
        $query.=(isset($criterios['id'])) ? "AND u.id= '{$criterios['id']}' " : "";
        $query.=(isset($criterios['caja'])) ? "AND u.id_caja= '{$criterios['caja']}' " : "";
        $query.=(isset($criterios['tipo_documento'])) ? "AND u.dni_responsable= '{$criterios['tipo_documento']}' " : "";
        $query.=(isset($criterios['documento'])) ? "AND u.id_responsable= '{$criterios['documento']}' " : "";

        return $this->db->query($query)->result();
    }

    public function listar_transacciones($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT *  FROM (SELECT ab.total,ab.fecha_trans,s.nombre sede,s.id id_sede,ab.prefijo,ab.id,ab.observacion,tc.tipo caja,tc.id id_caja,'Abono adelanto' nombre_tabla,ab.efectivo_ingresado efectivo,ab.valor_consignado consignado,ab.vigente,ab.dni_responsable,ab.id_responsable FROM abono_adelanto ab JOIN sede s ON ab.sede=s.id JOIN t_caja tc ON ab.t_caja_destino=tc.id 
                  UNION
                  SELECT (ap.subtotal+ap.int_mora) total,ap.fecha_trans,s.nombre sede,s.id id_sede,ap.prefijo,ap.id,ap.observacion,tc.tipo caja,tc.id id_caja,'Abono prestamo' nombre_tabla,ap.efectivo_ingresado efectivo,ap.valor_consignado consignado,ap.vigente,ap.dni_responsable,ap.id_responsable FROM abono_prestamo ap JOIN sede s ON ap.sede=s.id JOIN t_caja tc ON ap.t_caja_destino=tc.id 
                  UNION
                  SELECT ing.total,ing.fecha_trans,s.nombre sede,s.id id_sede,ing.prefijo,ing.id,ing.descripcion,tc.tipo caja,tc.id id_caja,'Ingreso' nombre_tabla,ing.efectivo_ingresado efectivo,ing.valor_consignado consignado,ing.vigente,ing.dni_responsable,ing.id_responsable FROM ingreso ing JOIN sede s ON ing.sede=s.id JOIN t_caja tc ON ing.t_caja_destino=tc.id 
                  UNION
                  SELECT f.total,f.fecha_trans,s.nombre sede,s.id id_sede,f.prefijo,f.id,f.observacion,tc.tipo caja,tc.id id_caja,'Factura' nombre_tabla,f.efectivo_ingresado efectivo,f.valor_consignado consignado,f.vigente,f.dni_responsable,f.id_responsable FROM factura f JOIN sede s ON f.sede=s.id JOIN t_caja tc ON f.t_caja_destino=tc.id 
                  UNION
                  SELECT nc.total,nc.fecha_trans,s.nombre sede,s.id id_sede,nc.prefijo,nc.id,nc.observacion,tc.tipo caja,tc.id id_caja,'Nota credito' nombre_tabla,nc.efectivo_retirado efectivo ,nc.valor_retirado consignado,nc.vigente,nc.dni_responsable,nc.id_responsable FROM nota_credito nc JOIN sede s ON nc.sede=s.id JOIN t_caja tc ON nc.t_caja_origen=tc.id 
                  UNION
                  SELECT rf.total,rf.fecha_trans,s.nombre sede,s.id id_sede,rf.prefijo,rf.id,rf.observacion,tc.tipo caja,tc.id id_caja,'Rete. fuente' nombre_tabla,rf.efectivo_ingresado efectivo,rf.valor_consignado consignado,rf.vigente,rf.dni_responsable,rf.id_responsable FROM retefuente rf JOIN sede s ON rf.sede=s.id JOIN t_caja tc ON rf.t_caja_destino=tc.id 
                  UNION
                  SELECT ad.total,ad.fecha_trans,s.nombre sede,s.id id_sede,ad.prefijo,ad.id,ad.observacion,tc.tipo caja,tc.id id_caja,'Adelanto' nombre_tabla,ad.efectivo_retirado efectivo,ad.valor_retirado consignado,ad.estado vigente,ad.dni_responsable,ad.id_responsable FROM adelanto ad JOIN sede s ON ad.sede=s.id JOIN t_caja tc ON ad.t_caja_origen=tc.id 
                  UNION
                  SELECT pre.total,pre.fecha_trans,s.nombre sede,s.id id_sede,pre.prefijo,pre.id,pre.observacion,tc.tipo caja,tc.id id_caja,'Prestamo' nombre_tabla,pre.efectivo_retirado efectivo,pre.valor_retirado consignado,pre.estado vigente,pre.dni_responsable,pre.id_responsable FROM prestamo pre JOIN sede s ON pre.sede=s.id JOIN t_caja tc ON pre.t_caja_origen=tc.id 
                  UNION
                  SELECT egr.total,egr.fecha_trans,s.nombre sede,s.id id_sede,egr.prefijo,egr.id,egr.descripcion,tc.tipo caja,tc.id id_caja,'Egreso' nombre_tabla,egr.efectivo_retirado efectivo,egr.valor_retirado consignado,egr.vigente,egr.dni_responsable,egr.id_responsable FROM egreso egr JOIN sede s ON egr.sede=s.id JOIN t_caja tc ON egr.t_caja_origen=tc.id 
                  UNION
                  SELECT n.total,n.fecha_trans,s.nombre sede,s.id id_sede,n.prefijo,n.id,n.observacion,tc.tipo caja,tc.id id_caja,'Nomina' nombre_tabla,n.efectivo_retirado efectivo,n.valor_retirado consignado,n.vigente,n.dni_responsable,n.id_responsable FROM nomina n JOIN sede s ON n.sede=s.id JOIN t_caja tc ON n.t_caja_origen=tc.id 
                  UNION
                  SELECT pp.total,pp.fecha_trans,s.nombre sede,s.id id_sede,pp.prefijo,pp.id,pp.observacion,tc.tipo caja,tc.id id_caja,'Pago proveedor' nombre_tabla,pp.efectivo_retirado efectivo,pp.valor_retirado consignado,pp.vigente,pp.dni_responsable,pp.id_responsable FROM pago_proveedor pp JOIN sede s ON pp.sede=s.id JOIN t_caja tc ON pp.t_caja_origen=tc.id 
                  UNION
                  SELECT rc.total,rc.fecha_trans,s.nombre sede,s.id id_sede,rc.prefijo,rc.id,rc.observacion,tc.tipo caja,tc.id id_caja,'Recibo caja' nombre_tabla,rc.efectivo_ingresado efectivo,rc.valor_consignado consignado,rc.vigente,rc.dni_responsable,rc.id_responsable FROM recibo_caja rc JOIN sede s ON rc.sede=s.id JOIN t_caja tc ON rc.t_caja_destino=tc.id 
                  UNION
                  SELECT rc.total,rc.fecha_trans,s.nombre sede,s.id id_sede,rc.prefijo,rc.id,rc.observacion,tc.tipo caja,tc.id id_caja,'Recibo caja' nombre_tabla,rc.efectivo_ingresado efectivo,rc.valor_consignado consignado,rc.vigente,rc.dni_responsable,rc.id_responsable FROM recibo_caja rc JOIN sede s ON rc.sede=s.id JOIN t_caja tc ON rc.t_caja_destino=tc.id 
                  UNION
                  SELECT ti.total,ti.fecha_trans,s.nombre sede,s.id id_sede,ti.prefijo,ti.id,ti.observacion,tc.tipo caja,tc.id id_caja,'Transferencia intersede' nombre_tabla,ti.efectivo_ingresado efectivo,ti.valor_consignado consignado,ti.est_traslado,ti.dni_responsable,ti.id_responsable FROM  transferencia_intersede ti JOIN sede s ON ti.sede_caja_destino=s.id JOIN t_caja tc ON ti.t_caja_destino=tc.id 
                  UNION
                  SELECT ti.total,ti.fecha_trans,s.nombre sede,s.id id_sede,ti.prefijo,ti.id,ti.observacion,tc.tipo caja,tc.id id_caja,'Transferencia intersede' nombre_tabla,ti.efectivo_retirado efectivo,ti.valor_retirado consignado,ti.est_traslado,ti.dni_responsable,ti.id_responsable FROM  transferencia_intersede ti JOIN sede s ON ti.sede_caja_origen=s.id JOIN t_caja tc ON ti.t_caja_origen=tc.id) u WHERE true ";

        $query.=(!empty($criterios['desde'])) ? "AND u.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
        $query.=(!empty($criterios['hasta'])) ? "AND u.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
        $query.=(isset($criterios['sede'])) ? "AND u.id_sede= '{$criterios['sede']}' " : "";
        $query.=(isset($criterios['id'])) ? "AND u.id= '{$criterios['id']}' " : "";
        $query.=(isset($criterios['caja'])) ? "AND u.id_caja= '{$criterios['caja']}' " : "";
        $query.=(isset($criterios['tipo_documento'])) ? "AND u.dni_responsable= '{$criterios['tipo_documento']}' " : "";
        $query.=(isset($criterios['documento'])) ? "AND u.id_responsable= '{$criterios['documento']}' " : "";


        $query.=" ORDER BY u.fecha_trans DESC LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

}
