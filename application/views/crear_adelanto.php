<div class="contenidoperm">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 thumbnail">
            <div class="row">
                <legend>Crear Adelanto de Nomina</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <form role="form" method="post" action="{action_crear}" id="formulario">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3 ">
                                    <div class="form-group">
                                        <label>Empleado Beneficiario<em class="required_asterisco">*</em></label>
                                        <p class="help-block"><B>> </B>Sólo aparecerán los empleados activos que pertenecen a cualquiera de sus sedes encargadas.</p>                                        
                                        <select name="empleado" id="empleado" data-placeholder="Seleccione Empleado Beneficiario" class="chosen-select form-control exit_caution">
                                            <option value="default"></option>
                                            {empleado}
                                            <option value="{id}-{dni}">{nombre1} {nombre2} {apellido1} {apellido2}</option>
                                            {/empleado}
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Valor del Adelanto<em class="required_asterisco">*</em></label>  
                                        <p class="help-block"><B>> </B>Recuerde que los adelantos de nomina no pueden superar la capacidad de pago del empleado (En base a su salario, las prestaciones sociales, etc.)</p>                                        
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" name="total" id="total" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="overflow_tabla">
                                <label>Caja de Efectivo Origen (Punto de Venta)</label>
                                <p class="help-block"><B>> </B>Seleccione una caja en el caso en que halla utilizado dinero de ella para realizar el adelanto (Sólo aparecerán las cajas previamente autorizadas para usted).</p>
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
                                <label>Observación<em class="required_asterisco">*</em></label>
                                <p class="help-block"><B>> </B>Motivo, Quién lo Autorizó, Forma de Pago, etc.</p>
                                <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="255" placeholder="Observación..."  style="max-width:100%;"></textarea>
                            </div>
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                                    <button id="botonValidar" class="btn btn-success">Crear Adelanto de Nomina</button>                                 
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
    $.post('{action_llena_cuenta_responsable}', {
        idResposable: '{id_responsable}',
        dniResposable: '{dni_responsable}'
    }, function(data) {
        $("#tbody_cuenta_bancaria").html(data);
    });

    //Habilita las cajas y las cuentas
    $("table").delegate("#cuenta", "change", function() {
        var total = new Number($('#total').val().split(",").join(""));
        var efectivo_retirado = new Number($('#efectivo_retirado').val().split(",").join(""));
        $('#valor_retirado').attr('value', ((total - efectivo_retirado).toFixed(2)));
        $('#valor_retirado').change();
        $("#valor_retirado").removeAttr("readonly");
    });

    $(".form-group").delegate("#valor_retirado", "blur", function() {
        var total = new Number($('#total').val().split(",").join(""));
        var valor_retirado = new Number($('#valor_retirado').val().split(",").join(""));
        $('#efectivo_retirado').attr('value', ((total - valor_retirado).toFixed(2)));
        $('#efectivo_retirado').change();
    });

    $.post('{action_llena_caja_responsable}', {
        idResposable: '{id_responsable}',
        dniResposable: '{dni_responsable}'
    }, function(data) {
        $("#tbody_caja_efectivo").html(data);
    });

    //Cargar div de valor retirado cuenta bancaria
    $("table").delegate("#caja", "change", function() {
        var total = new Number($('#total').val().split(",").join(""));
        var valor_retirado = new Number($('#valor_retirado').val().split(",").join(""));
        $('#efectivo_retirado').attr('value', ((total - valor_retirado).toFixed(2)));
        $('#efectivo_retirado').change();
        $("#efectivo_retirado").removeAttr("readonly");
    });

    $(".form-group").delegate("#efectivo_retirado", "blur", function() {
        var total = new Number($('#total').val().split(",").join(""));
        var efectivo_retirado = new Number($('#efectivo_retirado').val().split(",").join(""));
        $('#valor_retirado').attr('value', ((total - efectivo_retirado).toFixed(2)));
        $('#valor_retirado').change();
    });


</script>