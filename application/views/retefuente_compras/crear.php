<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear retención en la fuente por compras o servicios</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-12">
                        <form role="form" method="post" action="{action_crear}" id="formulario">
                            <div class="row">
                                <div class="col-xs-10 col-xs-offset-1"> 
                                    <p style="text-align:justify;"><B>Nota 1: </B>Ésta opción se utiliza para registrar la retención en la fuente que tiene que retener a los proveedores, cuando las compras de productos superen $742.000 o las compras de servicios superen $110.0000.</p>
                                    <p style="text-align:justify;"><B>Nota 2: </B>Utilice como base la tabla de retenciones del presente año, para saber que porcentaje debe retener, dependiendo del tipo de compra que realice (Consulte con el departamento de contabilidad).</p><br>                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 col-xs-offset-3">   
                                    <div class="form-group">
                                        <label>Proveedor<em class="required_asterisco">*</em></label>
                                        <select name="proveedor" id="proveedor" data-placeholder="Seleccione proveedor" class="chosen-select form-control exit_caution">
                                            <option value="default"></option>
                                            {proveedor}
                                            <option value="{id}_{dni}">{razon_social} - {id}</option>
                                            {/proveedor}
                                        </select>
                                    </div>  
                                    <div class="form-group">
                                        <label>Código de factura<em class="required_asterisco">*</em></label> 
                                        <input name="factura" id="factura" type="text" class="form-control letras_numeros" placeholder="Código de factura a pagar" maxlength="40">
                                    </div>                                    
                                    <div class="form-group">
                                        <label>Valor de la retención en la fuente<em class="required_asterisco">*</em></label>                                        
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" name="total" id="total" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12">
                                        </div>
                                    </div>                                  
                                </div>
                            </div>
                            <hr>                    
                            <div class="overflow_tabla">
                                <label>Caja de Efectivo Destino (Punto de venta)</label>
                                <p class="help-block"><B>> </B>Seleccione una caja en el caso en que halla ingresado dinero en ella con el dinero recibido (Sólo aparecerán las cajas previamente autorizadas para usted).</p>                                    
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Escojer</th>  
                                            <th class="text-center">Sede</th>                                            
                                            <th class="text-center">Tipo de Caja</th>
                                            <th class="text-center">Responsable</th>
                                            <th class="text-center">Fecha de Creación</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_caja_efectivo">
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 col-xs-offset-3 ">
                                    <div class="form-group">
                                        <label>Valor Ingresado a la Caja de Efectivo</label>                            
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" name="efectivo_ingresado" id="efectivo_ingresado" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12" readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                            </div>                         
                            <hr>                            
                            <div class="overflow_tabla">
                                <label>Cuenta Bancaria Destino</label>
                                <p class="help-block"><B>> </B>Seleccione una cuenta en el caso en que halla consignado dinero en ella con el dinero recibido (Sólo aparecerán las cuentas previamente autorizadas para usted).</p>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Escojer</th>                                            
                                            <th class="text-center"># Cuenta</th>
                                            <th class="text-center">Tipo Cuenta</th>
                                            <th class="text-center">Banco</th>
                                            <th class="text-center">Nombre</th>
                                            <th class="text-center">Observación</th>
                                            <th class="text-center">Fecha Creación</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_cuenta_bancaria">
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 col-xs-offset-3 ">
                                    <div class="form-group">
                                        <label>Valor Consignado a la Cuenta Bancaria</label>                            
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" name="valor_consignado" id="valor_consignado" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12" readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label>Descripción<em class="required_asterisco">*</em></label>
                                <p class="help-block"><B>> </B>Motivo o concepto de la retención, porcentaje %, etc...</p>
                                <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="250" placeholder="Descripción..."  style="max-width:100%;"></textarea>
                            </div>
                            <div id="validacion_alert">
                            </div>
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />                         
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                                    <button id="botonValidar" class="btn btn-success">Crear retención en la fuente</button>  
                                    <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                    <a href="{base_url}" class="btn btn-danger" role="button"> Cancelar </a>
                                </center>
                            </div>   
                        </form>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    //Llenamos la cajas del responsable
    $.post('{action_llena_caja_responsable}', {
        idResposable: '{id_responsable}',
        dniResposable: '{dni_responsable}'
    }, function(data) {
        $("#tbody_caja_efectivo").html(data);
    });

    //Llenamos las cajas del responsable
    $.post('{action_llena_cuenta_responsable}', {
        idResposable: '{id_responsable}',
        dniResposable: '{dni_responsable}'
    }, function(data) {
        $("#tbody_cuenta_bancaria").html(data);
    });

    //Habilitamos input de efectivo retirado de las cajas
    $("table").delegate("#caja", "click", function() {
        var total = new Number($('#total').val().split(",").join(""));
        if ($('#valor_consignado').is('[readonly]')) {
            $('#efectivo_ingresado').attr('value', ((total).toFixed(2)));
        } else {
            var valor_consignado = new Number($('#valor_consignado').val().split(",").join(""));
            $('#efectivo_ingresado').attr('value', ((total - valor_consignado).toFixed(2)));
        }
        $('#efectivo_ingresado').change();
        $("#efectivo_ingresado").removeAttr("readonly");
    });

    //Habilitamos inputde valor retirado de las cuentas
    $("table").delegate("#cuenta", "click", function() {
        var total = new Number($('#total').val().split(",").join(""));
        if ($('#efectivo_ingresado').is('[readonly]')) {
            $('#valor_consignado').attr('value', ((total).toFixed(2)));
        } else {
            var efectivo_ingresado = new Number($('#efectivo_ingresado').val().split(",").join(""));
            $('#valor_consignado').attr('value', ((total - efectivo_ingresado).toFixed(2)));
        }
        $('#valor_consignado').change();
        $("#valor_consignado").removeAttr("readonly");
    });

    //Calcula el valor contrario al modificar el valor retirado.
    $(".form-group").delegate("#valor_consignado", "blur", function() {
        //Preguntamos si el valor retirado es readonly
        if (!($('#efectivo_ingresado').is('[readonly]'))) {
            var total = new Number($('#total').val().split(",").join(""));
            var valor_consignado = new Number($('#valor_consignado').val().split(",").join(""));
            $('#efectivo_ingresado').attr('value', ((total - valor_consignado).toFixed(2)));
        }
        $('#efectivo_ingresado').change();
    });

    //Calcula el valor contrario al modificar el efectivo retirado.
    $(".form-group").delegate("#efectivo_ingresado", "blur", function() {
        if (!($('#valor_consignado').is('[readonly]'))) {
            var total = new Number($('#total').val().split(",").join(""));
            var efectivo_ingresado = new Number($('#efectivo_ingresado').val().split(",").join(""));
            $('#valor_consignado').attr('value', ((total - efectivo_ingresado).toFixed(2)));
        }
        $('#valor_consignado').change();
    });
</script>