<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear abono a adelanto de nómina</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-12">
                        <form role="form" method="post" action="{action_crear}" id="formulario">
                            <div class="row">
                                <div class="col-xs-4 col-xs-offset-4">
                                    <div class="form-group">
                                        <label>Empleado<em class="required_asterisco">*</em></label>
                                        <p class="help-block"><B>> </B>Sólo aparecerán los empleados activos que tienen adelantos vigentes y que pertenecen a cualquiera de sus sedes encargadas.</p>
                                        <select name="empleado" id="empleado" data-placeholder="Seleccione Empleado del adelanto" class="form-control exit_caution">
                                        </select>
                                    </div>
                                </div>   
                            </div>
                            <div class="overflow_tabla">
                                <label>Adelanto a Abonar<em class="required_asterisco">*</em></label>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Escojer</th>                                            
                                            <th class="text-center">Valor</th>
                                            <th class="text-center">Saldo</th>
                                            <th class="text-center">Sede</th>
                                            <th class="text-center">Observación</th>
                                            <th class="text-center">Fecha del Adelanto</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_adelanto_abono">
                                    </tbody>
                                </table>
                            </div>       
                            <div class="row">
                                <div class="col-xs-6 col-xs-offset-3 ">
                                    <div class="form-group">
                                        <label>Valor del Abono<em class="required_asterisco">*</em></label>                                        
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" name="total" id="total" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12"  readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="overflow_tabla">
                                <label>Caja de Efectivo Destino (Punto de Venta)</label>
                                <p class="help-block"><B>> </B>Seleccione una caja en el caso en que halla ingresado dinero en ella con el dinero recibido (Sólo aparecerán las cajas previamente autorizadas para usted).</p>                                    
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Escojer</th>  
                                            <th class="text-center">Sede</th>                                            
                                            <th class="text-center">Tipo de Caja</th>
                                            <th class="text-center">Observación</th>
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
                                <label>Observación</label>
                                <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="255" placeholder="Observación..."  style="max-width:100%;"></textarea>
                            </div>
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                                    <button id="botonValidar" class="btn btn-success">Crear Abono a Adelanto</button>                                 
                                    <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                    <a href="{base_url}" class="btn btn-danger" role="button"> Cancelar </a>
                                </center>
                            </div>   
                            <div id="validacion_alert">
                            </div>
                        </form>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    //CArgar el select de Empleadps
    $.post('{action_llena_empleado_adelanto}', {
        idResposable: '{id_responsable}',
        dniResposable: '{dni_responsable}'
    }, function(data) {
        $("#empleado").html(data);
        $("#empleado").prepend('<option value="default" selected>Seleccione Empleado Beneficiario</option>');
    });


    //Llena cuentas del responsable
    $(".form-group").delegate("#empleado", "change", function() {
        empleado = $('#empleado').val();
        $.post('{action_llena_adelanto_empleado}', {
            empleado: empleado
        }, function(data) {
            $("#tbody_adelanto_abono").html(data);
            $("#total").attr('readonly', 'readonly');
            $('#total').attr('value', '');
            $("#empleado option[value=default]").remove();
        });
    });

    //Cargar div de valor abono
    $("table").delegate("#adelanto", "change", function() {
        $("#total").removeAttr("readonly");
    });

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