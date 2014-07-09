<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear abono a préstamo</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-12">
                        <form role="form" method="post" action="{action_crear}" id="formulario">
                            <div class="row">
                                <div class="col-xs-4 col-xs-offset-4">
                                    <div class="form-group">
                                        <label>Tipo de Usuario Beneficiario<em class="required_asterisco">*</em></label>
                                        <select name="t_beneficiario" id="t_beneficiario" class="form-control exit_caution">
                                            <option value="default">Seleccione T.U.B</option>
                                            {t_beneficiario}
                                            <option value="{id}">{tipo}</option>
                                            {/t_beneficiario}
                                        </select>
                                    </div>
                                    <div class="form-group" id="div_empleado" style="display:none;">
                                        <label>Empleado Beneficiario<em class="required_asterisco">*</em></label>
                                        <p class="help-block"><B>> </B>Sólo aparecerán los empleados activos que tienen préstamos vigentes y que pertenecen a cualquiera de sus sedes encargadas.</p>
                                        <select name="empleado" id="empleado" class="form-control exit_caution">
                                        </select>
                                    </div>
                                    <div class="form-group" id="div_cliente" style="display:none;">
                                        <label>Cliente Beneficiario<em class="required_asterisco">*</em></label>
                                        <p class="help-block"><B>> </B>Sólo aparecerán los clientes que tienen préstamos vigentes en cualquiera de sus sedes encargadas.</p>                                        
                                        <select name="cliente" id="cliente" class="form-control exit_caution">
                                        </select>
                                    </div>
                                </div>   
                            </div>
                            <div class="overflow_tabla">
                                <label>Préstamo a abonar<em class="required_asterisco">*</em></label>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Escojer</th>
                                            <th class="text-center">Valor</th>
                                            <th class="text-center"># Cuotas</th>
                                            <th class="text-center">Interés</th>                                            
                                            <th class="text-center">Cuota Fija</th>
                                            <th class="text-center">Sede Origen</th>
                                            <th class="text-center">Fecha del Préstamo</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_prestamo_abono">
                                    </tbody>
                                </table>
                            </div>
                            <div class="overflow_tabla">
                                <label>Cuota a Cancelar<em class="required_asterisco">*</em></label>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Escojer</th>
                                            <th class="text-center"># Cuota</th>
                                            <th class="text-center">Pago Mínimo</th>
                                            <th class="text-center">Pago Máximo</th>
                                            <th class="text-center">Pago Realizado</th>                                           
                                            <th class="text-center">Cantidad Mora</th>
                                            <th class="text-center">Int. Mora</th>
                                            <th class="text-center">Saldo Deuda</th>
                                            <th class="text-center">Fecha Límite</th>
                                            <th class="text-center">Fecha Pago</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_cuotas_pdtes">
                                    </tbody>
                                </table>
                            </div>  
                            <div class="row">
                                <div class="col-xs-6 col-xs-offset-3">
                                    <div class="form-group">
                                        <label>Valor del Abono<em class="required_asterisco">*</em></label>   
                                        <p class="help-block"><B>> </B>Con un abono superior al abono mínimo, disminuirán cuotas e intereses al final del préstamo.</p>
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" name="subtotal" id="subtotal" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12"  readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label>Intereses de Mora</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">$</span>
                                                    <input type="text" name="int_mora" id="int_mora" class="form-control decimal decimal2 miles" value="0.00" maxlength="12"  readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label>Total a Pagar</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">$</span>
                                                    <input type="text" name="total" id="total" class="form-control decimal decimal2 miles" value="0.00" maxlength="12"  readonly="readonly">
                                                </div>
                                            </div>    
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
                                <label>Observación</label>
                                <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="250" placeholder="Observación..."  style="max-width:100%;"></textarea>
                            </div>
                            <div id="validacion_alert">
                            </div>                            
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <input type="hidden" name="abono_minimo" id="abono_minimo"/>
                                <input type="hidden" name="abono_maximo" id="abono_maximo"/>
                                <input type="hidden" name="cant_dias_mora" id="cant_dias_mora"/>
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                                    <button id="botonValidar" class="btn btn-success">Crear Abono a Préstamo</button>                                 
                                    <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                    <a href="<?= base_url() ?>"class="btn btn-danger" role="button"> Cancelar </a>
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
    //Mostramos los divs de empleado y cliente dependiendo de t_beneficiario
    $(".form-group").delegate("#t_beneficiario", "change", function() {
        t_beneficiario = $('#t_beneficiario').val();
        if (t_beneficiario == '1') {
            //CArgar el select de Empleadps
            $.post('{action_llena_empleado_prestamo}', {
                idResposable: '{id_responsable}',
                dniResposable: '{dni_responsable}'
            }, function(data) {
                $("#empleado").html(data);
                $("#empleado").prepend('<option value="default" selected>Seleccione Empleado Beneficiario</option>');
            });
            $("#div_empleado").css("display", "block");
            $("#div_cliente").css("display", "none");
        } else {
            if (t_beneficiario == '4') {
                //CArgar el select de clientes
                $.post('{action_llena_cliente_prestamo}', {
                    idResposable: '{id_responsable}',
                    dniResposable: '{dni_responsable}'
                }, function(data) {
                    $("#cliente").html(data);
                    $("#cliente").prepend('<option value="default" selected>Seleccione Cliente Beneficiario</option>');
                });
                $("#div_empleado").css("display", "none");
                $("#div_cliente").css("display", "block");
            } else {
                $("#div_empleado").css("display", "none");
                $("#div_cliente").css("display", "none");
            }
        }
        //Borro los prestamos que pueda tener la tabla
        $("#tbody_prestamo_abono  > *").remove();
        $("#tbody_cuotas_pdtes  > *").remove();
        $("#subtotal").attr('readonly', 'readonly');
        $('#subtotal').attr('value', '');
        $('#int_mora').attr('value', '0.00');
        $('#total').attr('value', '0.00');
    });

    //Calculamos el total
    $(".form-group").delegate("#subtotal", "blur", function() {
        var subtotal = new Number($('#subtotal').val().split(",").join(""));
        var intMora = new Number($('#int_mora').val().split(",").join(""));
        $('#total').attr('value', subtotal + intMora);
        $('#total').change();
    });

    //Llena prestamo del empleado
    $(".form-group").delegate("#empleado", "change", function() {
        beneficiario = $('#empleado').val();
        $.post('{action_llena_prestamo_beneficiario}', {
            beneficiario: beneficiario
        }, function(data) {
            $("#tbody_prestamo_abono").html(data);
            $("#tbody_cuotas_pdtes  > *").remove();
            $("#subtotal").attr('readonly', 'readonly');
            $('#subtotal').attr('value', '');
            $('#int_mora').attr('value', '0.00');
            $('#total').attr('value', '0.00');
        });
    });

    //Llena prestamo del cliente
    $(".form-group").delegate("#cliente", "change", function() {
        beneficiario = $('#cliente').val();
        $.post('{action_llena_prestamo_beneficiario}', {
            beneficiario: beneficiario
        }, function(data) {
            $("#tbody_prestamo_abono").html(data);
            $("#tbody_cuotas_pdtes  > *").remove();
            $("#subtotal").attr('readonly', 'readonly');
            $('#subtotal').attr('value', '');
            $('#int_mora').attr('value', '0.00');
            $('#total').attr('value', '0.00');
        });
    });

    //Cargar div de valor abono y cuotas pendientes
    $("table").delegate("#prestamo", "change", function() {
        prestamo = $("input[name='prestamo']:checked").val();
        $.post('{action_llena_cuotas_prestamo_pdtes}', {
            prestamo: prestamo
        }, function(data) {
            var obj = JSON.parse(data);
            if (obj.respuesta == "OK")
            {
                $("#tbody_cuotas_pdtes").html(obj.filasTabla);
                //LLenamos 3 campos ocultos para validar valores por ajax
                $('#abono_minimo').attr('value', obj.abonoMinimo);
                $('#abono_maximo').attr('value', obj.abonoMaximo);
                $('#cant_dias_mora').attr('value', obj.cantMora);
                //Llenamos los campos subtotal, int. mora y total
                var subtotal = new Number(obj.abonoMinimo);
                var intMora = new Number(obj.intMora);
                $('#subtotal').attr('value', subtotal.toFixed(2));
                $('#int_mora').attr('value', intMora.toFixed(2));
                $('#total').attr('value', subtotal + intMora);
                $('#subtotal').change();
                $('#int_mora').change();
                $('#total').change();                
                $("#subtotal").removeAttr("readonly");
            }
        });
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