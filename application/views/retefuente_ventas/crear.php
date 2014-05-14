<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear retención en la fuente por Ventas </legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-12">
                        <form role="form" method="post" action="{action_crear}" id="formulario">
                            <div class="row">
                                <div class="col-xs-10 col-xs-offset-1"> 
                                    <p style="text-align:justify;"><B>Nota 1: </B>Ésta opción se utiliza cuando un cliente jurídico (una empresa), exige la devolucion de la retención en la fuente, por haber comprado el programa (11% por servicios). </p>
                                    <p style="text-align:justify;"><B>Nota 2: </B>Recuerde que cuando es un pago a nombre de empresa, <b>No</b> lo puede hacer por la opción <b>crear->Recibo de Caja</b>, sino por la opción: <b>crear->factura</b>.</p><br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 col-xs-offset-3">
                                    <legend>Factura de venta</legend>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label>Prefijo de factura<em class="required_asterisco">*</em></label>
                                                <select name="prefijo_factura" id="prefijo_factura" class="form-control exit_caution" value="flst">
                                                    <option value="default">Seleccione prefijo</option>
                                                    {sede}
                                                    <option value="{prefijo_trans}">{prefijo_trans}</option>
                                                    {/sede}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label>Número de factura<em class="required_asterisco">*</em></label>
                                                <input name="id_factura" id="id_factura" type="text" class="form-control numerico exit_caution" placeholder="Consecutivo" maxlength="13">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="validacion_inicial">
                                    </div>                             
                                    <div class="row text-center">
                                        <button type="button" class="btn btn-default" id="consultar_factura"><span class="glyphicon glyphicon-search"></span> Consultar </button>
                                        <a href='{action_recargar}' class="btn btn-default" role="button"><span class="glyphicon glyphicon-list-alt"></span> Modificar factura </a>
                                    </div>                                 
                                </div>
                            </div>
                            <div id="div_retencion" style="display: none">
                                <hr>
                                <div class="row separar_submit" id="info_factura">
                                </div>                                
                                <div class="row">
                                    <div class="col-xs-4 col-xs-offset-4">
                                        <div class="form-group">
                                            <label>Valor de la retención en la fuente<em class="required_asterisco">*</em></label>                                        
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" name="total" id="total" class="form-control" placeholder="0.00" maxlength="12" readonly="readonly">
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
                                    <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="250" placeholder="Descripción..."  style="max-width:100%;"></textarea>
                                </div>

                                <div class="form-group separar_submit">
                                    <input type="hidden" id="action_validar" value={action_validar} />
                                    <input type="hidden" name="id_responsable" value={id_responsable} />
                                    <input type="hidden" name="dni_responsable" value={dni_responsable} />                         
                                    <center>
                                        <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                                        <button id="btn_validar" class="btn btn-success">Crear retención en la fuente</button>  
                                        <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                        <a href="{base_url}" class="btn btn-danger" role="button"> Cancelar </a>
                                    </center>
                                </div>   
                                <div id="validacion_alert">
                                </div>
                            </div>
                        </form>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    //Llenamos la informacion de las matriculas y los pagos.
    $("form").delegate("#consultar_factura", "click", function() {
        var prefijo = $('#prefijo_factura').val();
        var id = $('#id_factura').val();
        if ((prefijo != "default") && (id != "")) {
            $.post('{action_validar_factura}', {
                prefijo: prefijo,
                id: id
            }, function(data) {
                var obj = JSON.parse(data);
                if (obj.respuesta == "OK")
                {
                    $("#info_factura").html('<center><table><tr><td><h4>Factura a nombre de: </h4></td><td><h4 class="h_negrita">&nbsp;&nbsp;&nbsp;&nbsp;' + obj.aNombreDe + '</h4></td></tr>\n\
                       <tr><td><h4>Número de matrícula: </h4></td><td><h4 class="h_negrita">&nbsp;&nbsp;&nbsp;&nbsp;' + obj.matricula + '</h4></td></tr>\n\
                       <tr><td><h4>Subtotal factura: </h4></td><td><h4 class="h_negrita">&nbsp;&nbsp;&nbsp;&nbsp;$' + obj.subtotal + '</h4></td></tr>\n\
                       <tr><td><h4>Retención en la fuente (11%): </h4></td><td><h4 class="h_negrita">&nbsp;&nbsp;&nbsp;&nbsp;$' + obj.retefuente + '</h4></td></tr></table></center>');
                    $('#total').attr('value', obj.retefuente);
                    $("#div_retencion").css("display", "block");
                    $('#prefijo_factura').attr('disabled', 'disabled');
                    $('#id_factura').attr('disabled', 'disabled');
                    $('#consultar_factura').attr('disabled', 'disabled');
                    $("#div_warning").remove();
                } else {
                    $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
                    $("#div_warning").html(obj.mensaje);
                    $("#div_warning").delay(8000).fadeOut(1000);
                }
            });
        } else {
            $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
            $("#div_warning").html("<p><strong><center>Antes de consultar, ingrese el prefijo y el número de factura.</center></strong></p>");
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
        $('#prefijo_factura').removeAttr("disabled");
        $('#id_factura').removeAttr("disabled");
        $.ajax({
            type: "POST",
            url: $("#action_validar").attr("value"),
            cache: false,
            data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
            success: function(data)
            {
                if (data != "OK") {
                    $('#prefijo_factura').attr('disabled', 'disabled');
                    $('#id_factura').attr('disabled', 'disabled');
                    $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                    $("#div_alert").html(data);
                    $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
                } else {
                    $(window).unbind('beforeunload');
                    $("#btn_submit").click();
                }
            },
            error: function(data) {
                $('#prefijo_factura').attr('disabled', 'disabled');
                $('#id_factura').attr('disabled', 'disabled');
                $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                $('#div_alert').html('<p>Hubo un error en la peticion al servidor</p>');
                $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');

            }
        });
        return false; // Evitar ejecutar el submit del formulario
    });

</script>