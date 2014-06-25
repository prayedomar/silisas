<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear préstamo</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-12">
                        <form role="form" method="post" action="{action_crear}" id="formulario">
                            <div class="row">
                                <div class="col-xs-6 col-xs-offset-3 ">
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
                                        <p class="help-block"><B>> </B>Sólo aparecerán los empleados activos que pertenecen a cualquiera de sus sedes encargadas.</p>                                        
                                        <select name="empleado" id="empleado" class="form-control exit_caution">
                                            <option value="default">Seleccione Empleado Beneficiario</option>
                                            {empleado}
                                            <option value="{id}-{dni}">{nombre1} {nombre2} {apellido1} {apellido2}</option>
                                            {/empleado}
                                        </select>
                                    </div>
                                    <div class="form-group"  id="div_cliente" style="display:none;">
                                        <label>Cliente Beneficiario<em class="required_asterisco">*</em></label>
                                        <select name="cliente" id="cliente" class="form-control exit_caution">
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Valor del Préstamo<em class="required_asterisco">*</em></label>                                        
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" name="total" id="total" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Tasa de Interés Corriente<em class="required_asterisco">*</em></label>                                        
                                        <div class="input-group">
                                            <span class="input-group-addon">%</span>
                                            <input type="text" name="tasa_interes" id="tasa_interes" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="5">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Cantidad de Cuotas (Mensuales)<em class="required_asterisco">*</em></label>
                                        <input name="cant_cuotas" id="cant_cuotas" type="text" class="form-control exit_caution numerico" placeholder="Cantidad de Cuotas" maxlength="3">
                                    </div>
                                    <div class="form-group">
                                        <label>Fecha Desembolso Dinero<em class="required_asterisco">*</em></label>
                                        <div class="input-group">
                                            <input name="fecha_desembolso" id="fecha_desembolso" type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" value={fecha_actual}>
                                            <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div> 
                                    </div>                                    
                                </div>
                            </div>
                            <hr>
                            <div class="overflow_tabla">
                                <label>Caja de Efectivo Origen (Punto de venta)</label>
                                <p class="help-block"><B>> </B>Seleccione una caja en el caso en que halla utilizado dinero de ella para realizar el adelanto (Sólo aparecerán las cajas previamente autorizadas para usted).</p>
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
                            <div class="form-group">
                                <label>Valor retirado de la Caja de Efectivo</label>                            
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" name="efectivo_retirado" id="efectivo_retirado" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12" readonly="readonly">
                                </div>
                            </div>                            
                            <hr>                            
                            <div class="overflow_tabla">
                                <label>Cuenta Bancaria Origen</label>
                                <p class="help-block"><B>> </B>Seleccione una cuenta en el caso en que halla utilizado dinero de ella para realizar el adelanto (Sólo aparecerán las cuentas previamente autorizadas para usted).</p>
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
                            <div class="form-group">
                                <label>Valor retirado de la Cuenta Bancaria</label>                            
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" name="valor_retirado" id="valor_retirado" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12" readonly="readonly">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="250" placeholder="Observación..."  style="max-width:100%;"></textarea>
                            </div>
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                                    <button id="botonValidar" class="btn btn-success">Crear Préstamo</button>                                 
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
    //Cargamos los clientes, ya que con parser es un problema si la consulta es vacia
    $.post('{action_llena_clientes}', {},
            function(data) {
                $("#cliente").html(data);
                $("#cliente").prepend('<option value="default" selected>Seleccione Cliente Beneficiario</option>');
            });


    //Mostramos los divs de empleado y cliente dependiendo de t_beneficiario
    $(".form-group").delegate("#t_beneficiario", "change", function() {
        t_beneficiario = $('#t_beneficiario').val();
        if (t_beneficiario == '1') {
            $("#div_empleado").css("display", "block");
            $("#div_cliente").css("display", "none");
        } else {
            if (t_beneficiario == '4') {
                $("#div_empleado").css("display", "none");
                $("#div_cliente").css("display", "block");
            } else {
                $("#div_empleado").css("display", "none");
                $("#div_cliente").css("display", "none");
            }
        }
        //Reseteamos los clientes
        $('#empleado').find('option:first').attr('selected', 'selected').parent('select');
        $('#cliente').find('option:first').attr('selected', 'selected').parent('select');
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
        if ($('#valor_retirado').is('[readonly]')) {
            $('#efectivo_retirado').attr('value', ((total).toFixed(2)));
        } else {
            var valor_retirado = new Number($('#valor_retirado').val().split(",").join(""));
            $('#efectivo_retirado').attr('value', ((total - valor_retirado).toFixed(2)));
        }
        $('#efectivo_retirado').change();
        $("#efectivo_retirado").removeAttr("readonly");
    });
    //Habilitamos inputde valor retirado de las cuentas
    $("table").delegate("#cuenta", "click", function() {
        var total = new Number($('#total').val().split(",").join(""));
        if ($('#efectivo_retirado').is('[readonly]')) {
            $('#valor_retirado').attr('value', ((total).toFixed(2)));
        } else {
            var efectivo_retirado = new Number($('#efectivo_retirado').val().split(",").join(""));
            $('#valor_retirado').attr('value', ((total - efectivo_retirado).toFixed(2)));
        }
        $('#valor_retirado').change();
        $("#valor_retirado").removeAttr("readonly");
    });
    //Calcula el valor contrario al modificar el valor retirado.
    $(".form-group").delegate("#valor_retirado", "blur", function() {
        //Preguntamos si el valor retirado es readonly
        if (!($('#efectivo_retirado').is('[readonly]'))) {
            var total = new Number($('#total').val().split(",").join(""));
            var valor_retirado = new Number($('#valor_retirado').val().split(",").join(""));
            $('#efectivo_retirado').attr('value', ((total - valor_retirado).toFixed(2)));
        }
        $('#efectivo_retirado').change();        
    });
    //Calcula el valor contrario al modificar el efectivo retirado.
    $(".form-group").delegate("#efectivo_retirado", "blur", function() {
        if (!($('#valor_retirado').is('[readonly]'))) {
            var total = new Number($('#total').val().split(",").join(""));
            var efectivo_retirado = new Number($('#efectivo_retirado').val().split(",").join(""));
            $('#valor_retirado').attr('value', ((total - efectivo_retirado).toFixed(2)));
        }
        $('#valor_retirado').change();        
    });

</script>