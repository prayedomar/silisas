<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <form role="form" method="post" action="{action_crear}" id="formulario">
                <div class="row">
                    <legend>Crear nota crédito (Devolución)</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                    <div class="row">
                        <div class="col-xs-8 col-xs-offset-2">
                            <p style="text-align:justify;"><B>Nota: </B>Utilice ésta opción para registrar la devolución de dinero a un titular, por razones muy excepcionales. <br><b>> </b>Recuerde que para realizar dichas devoluciones de dinero, es necesario estar autorizado por la sede principal.</p><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-3">
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-2">
                                    <div class="form-group">
                                        <label>Número de Matrícula<em class="required_asterisco">*</em></label>
                                        <input name="matricula" id="matricula" type="text" class="form-control exit_caution numerico" placeholder="Número de contrato físico" maxlength="13">
                                    </div>
                                </div>
                            </div> 
                            <div id="validacion_inicial">
                            </div>                             
                            <div class="row text-center">
                                <button type="button" class="btn btn-default" id="consultar_matricula"><span class="glyphicon glyphicon-search"></span> Consultar </button>
                                <a href='{action_recargar}' class="btn btn-default" role="button"><span class="glyphicon glyphicon-list-alt"></span> Modificar matrícula </a>
                            </div>                            
                        </div>
                    </div>
                </div>
                <div id="div_nota_credito" style="display: none">
                    <hr>
                    <div class="row separar_submit" id="nombre_titular">
                    </div>
                    <div class="row">
                        <div class="col-xs-8 col-xs-offset-2">
                            <div class="form-group">
                                <label>Quién autoriza<em class="required_asterisco">*</em></label>
                                <input name="autoriza" id="autoriza" type="text" class="form-control exit_caution alfanumerico" placeholder="Quién autoriza..." maxlength="50">
                            </div>
                            <div class="form-group">
                                <label>Motivo de la devolución<em class="required_asterisco">*</em></label>
                                <textarea name="motivo" id="motivo" class="form-control exit_caution alfanumerico" rows="2" maxlength="250" placeholder="Motivo de la devolución..."  style="max-width:100%;"></textarea>
                            </div>
                            <div class="col-xs-6 col-xs-offset-3">
                                <label>Valor de la devolución<em class="required_asterisco">*</em></label>                                        
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" name="total" id="total" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <hr>
                        <div class="overflow_tabla">
                            <label>Caja de Efectivo Origen (Punto de venta)</label>
                            <p class="help-block"><B>> </B>Seleccione una caja en el caso en que halla utilizado dinero de ella para realizar ésta transacción (Sólo aparecerán las cajas previamente autorizadas para usted).</p>
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
                            <p class="help-block"><B>> </B>Seleccione una cuenta en el caso en que halla utilizado dinero de ella para realizar ésta transacción (Sólo aparecerán las cuentas previamente autorizadas para usted).</p>
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
                            <label id="label_descripcion">Observación</label>
                            <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="250" placeholder="Observacion..."  style="max-width:100%;"></textarea>
                        </div>  
                        <div id="validacion_alert">
                        </div>                        
                        <div class="form-group separar_submit">
                            <input type="hidden" id="action_validar" value={action_validar} />
                            <input type="hidden" name="id_responsable" value={id_responsable} />
                            <input type="hidden" name="dni_responsable" value={dni_responsable} />  
                            <!--Datos adicionales para ocultar del formulario-->
                            <input type="hidden" name="total_abonos" id="total_abonos">
                            <center>
                                <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                                <button id="btn_validar" class="btn btn-success">Crear nota crédito</button>  
                                <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                <a href="<?= base_url() ?>"class="btn btn-danger" role="button"> Cancelar </a>
                            </center>
                        </div>   
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">

    //Llenamos la informacion de las matriculas y los pagos.
    $("form").delegate("#consultar_matricula", "click", function() {
        var matricula = $('#matricula').val();
        if (matricula != "") {
            $.post('{action_validar_matricula}', {
                matricula: matricula
            }, function(data) {
                var obj = JSON.parse(data);
                if (obj.respuesta == "OK")
                {
                    $("#nombre_titular").html('<center><table><tr><td><h4>Nombre del titular: </h4></td><td><h4 class="h_negrita">&nbsp;&nbsp;&nbsp;&nbsp;' + obj.nombreTitular + '</h4></td></tr>\n\
                       <tr><td><h4>Nombre de plan: </h4></td><td><h4 class="h_negrita">&nbsp;&nbsp;&nbsp;&nbsp;' + obj.nombrePlan + '</h4></td></tr>\n\
                       <tr><td><h4>Valor del plan: </h4></td><td><h4 class="h_negrita">&nbsp;&nbsp;&nbsp;&nbsp;$' + obj.valorPlan + '</h4></td></tr>\n\
                       <tr><td><h4>Total abonos realizados: </h4></td><td><h4 class="h_negrita">&nbsp;&nbsp;&nbsp;&nbsp;$' + obj.totalAbonos + '</h4></td></tr></table></center>');
                    $('#total_abonos').attr('value', obj.totalAbonos);
                    $("#div_nota_credito").css("display", "block");
                    $('#matricula').attr('disabled', 'disabled');
                    $('#consultar_matricula').attr('disabled', 'disabled');
                    $("#div_warning").remove();
                } else {
                    $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
                    $("#div_warning").html(obj.mensaje);
                    $("#div_warning").delay(8000).fadeOut(1000);
                }
            });
        } else {
            $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
            $("#div_warning").html("<p><strong><center>Antes de consultar, ingrese el número de matrícula.</center></strong></p>");
            $("#div_warning").delay(8000).fadeOut(1000);
        }
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

    //Validamos el formulario antes de enviarlo por submit
    //Enviar formulario por ajax
    $('#btn_validar').live('click', function() {
        //desabilitamos el boton de enviar para evitar que lo apreten varias veces y que hagan la peticion al servidor 2 veces, ya me pasó una vez.
        $('#btn_validar').attr('disabled', 'disabled');        
        $('#matricula').removeAttr("disabled");
        $.ajax({
            type: "POST",
            url: $("#action_validar").attr("value"),
            cache: false,
            data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
            success: function(data)
            {
                if (data != "OK") {
                    $('#btn_validar').removeAttr("disabled");
                    $('#matricula').attr('disabled', 'disabled');
                    $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                    $("#div_alert").html(data);
                    $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
                } else {
                    $(window).unbind('beforeunload');
                    $("#btn_submit").click();
                }
            },
            error: function(data) {
                $('#btn_validar').removeAttr("disabled");
                $('#matricula').attr('disabled', 'disabled');
                $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                $('#div_alert').html('<p><strong>Hubo un error en la peticion al servidor. Verifique su conexion a internet.</strong></p>');
                $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');

            }
        });
        return false; // Evitar ejecutar el submit del formulario
    });

</script>