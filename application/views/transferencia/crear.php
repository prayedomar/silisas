<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear transferencia intersede</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-12">
                        <form role="form" method="post" action="{action_crear}" id="formulario">
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-2"> 
                                    <p style="text-align:justify;"><B>Nota: </b>Ésta opción la utiliza el empleado que realiza una transferencia de dinero a cualquiera de las sedes. <br><b>> </b>Sólo tendrá validez cuando el destinatario certifique que recibió dicha transferencia.</B></p><br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4 col-xs-offset-4">                                   
                                    <div class="form-group">
                                        <label>Valor a transferir<em class="required_asterisco">*</em></label>                                        
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" name="total" id="total" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12">
                                        </div>
                                    </div>                                  
                                </div>
                            </div>
                            <legend><h3>Información del remitente:</h3></legend>
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
                            <br><legend><h3>Información del destinatario:</h3></legend>
                            <div class="row">
                                <div class="col-xs-6 col-xs-offset-3">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label>Sede de destino<em class="required_asterisco">*</em></label>
                                                <select name="sede_destino" id="sede_destino" class="form-control" value="flst">
                                                    <option value="default">Seleccione sede</option>
                                                    {sede_destino}
                                                    <option value="{id}">{nombre}</option>
                                                    {/sede_destino}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label>Tipo de destino<em class="required_asterisco">*</em></label>
                                                <select name="tipo_destino" id="tipo_destino" class="form-control" value="flst">
                                                    <option value="default">Seleccione tipo de destino</option>
                                                    <option value="caja">Caja de efectivo</option>
                                                    <option value="cuenta">Cuenta bancaria</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="validacion_inicial">
                                    </div>                             
                                    <div class="row text-center">
                                        <button type="button" class="btn btn-default" id="consultar_destino"><span class="glyphicon glyphicon-search"></span> Consultar información de destino </button>
                                        <button type="button" class="btn btn-default" id="modificar_destino"><span class="glyphicon glyphicon-list-alt"></span> Modificar información de destino </button>
                                    </div><br><br>                           
                                </div>
                            </div>
                            <div>
                                <div id="div_caja_destino" style="display: none">
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
                                            <tbody id="tbody_caja_destino">
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
                                </div>
                                <div id="div_cuenta_destino" style="display: none">
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
                                            <tbody id="tbody_cuenta_destino">
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
                                </div>
                            </div>
                            <hr>                            
                            <div class="form-group">
                                <label id="label_descripcion">Observación<em class="required_asterisco">*</em></label>
                                <label  style="display:none;" id="label_descripcion_required">Observación<em class="required_asterisco">*</em></label>
                                <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="250" placeholder="Observación: Procedencia del dinero que va a enviar, etc..."  style="max-width:100%;"></textarea>
                            </div>
                            <div id="validacion_alert">
                            </div>                            
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <input type="hidden" name="sede_destino_hidden" id="sede_destino_hidden" value="" />
                                <input type="hidden" name="tipo_destino_hidden" id="tipo_destino_hidden" value="" />
                                <input type="hidden" name="btn_consultar_destino" id="btn_consultar_destino" value="0" /><!--Sirve para saber si esta apretado o no-->
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                                    <button id="botonValidar" class="btn btn-success"> Crear transferencia </button>                                 
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
    //Habilitamos input de efectivo retirado de las cajas ORIGEN
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

    $("form").delegate("#consultar_destino", "click", function() {
        var sedeDestino = $('#sede_destino').val();
        var tipoDestino = $('#tipo_destino').val();
        if ((sedeDestino != "default") && (tipoDestino != "default")) {
            $('#sede_destino_hidden').attr('value', sedeDestino);
            $('#tipo_destino_hidden').attr('value', tipoDestino);
            $('#btn_consultar_destino').attr('value', '1');
            $("#div_warning").remove();
            if (tipoDestino == "caja") {
                $.post('{action_llena_caja_destino}', {
                    sedeDestino: sedeDestino
                }, function(data) {
                    if (data != "") {
                        $("#tbody_caja_destino").html(data);
                        $("#div_caja_destino").css("display", "block");
                        $('#sede_destino').attr('disabled', 'disabled');
                        $('#tipo_destino').attr('disabled', 'disabled');
                        $('#consultar_destino').attr('disabled', 'disabled');
                    } else {
                        $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
                        $("#div_warning").html("<p><strong><center>No se encontró ninguna caja autorizada para ésta sede.</center></strong></p>");
                        $("#div_warning").delay(8000).fadeOut(1000);
                        $("#tbody_caja_destino").html("");
                        $("#div_caja_destino").css("display", "none");
                    }
                });
            } else {
                $.post('{action_llena_cuenta_destino}', {
                    sedeDestino: sedeDestino
                }, function(data) {
                    if (data != "") {
                        $("#tbody_cuenta_destino").html(data);
                        $("#div_cuenta_destino").css("display", "block");
                        $('#sede_destino').attr('disabled', 'disabled');
                        $('#tipo_destino').attr('disabled', 'disabled');
                        $('#consultar_destino').attr('disabled', 'disabled');
                    } else {
                        $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
                        $("#div_warning").html("<p><strong><center>No se encontró ninguna cuenta autorizada para ésta sede.</center></strong></p>");
                        $("#div_warning").delay(8000).fadeOut(1000);
                        $("#tbody_cuenta_destino").html("");
                        $("#div_cuenta_destino").css("display", "none");
                    }
                });
            }
        } else {
            $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
            $("#div_warning").html("<p><strong><center>Antes de consultar, ingrese la sede y el tipo de destino.</center></strong></p>");
            $("#div_warning").delay(8000).fadeOut(1000);
        }
    });

    $("form").delegate("#modificar_destino", "click", function() {
        $('#btn_consultar_destino').attr('value', '0');
        $("#tbody_caja_destino").html("");
        $("#tbody_cuenta_destino").html("");
        $("#div_caja_destino").css("display", "none");
        $("#div_cuenta_destino").css("display", "none");
        $('#sede_destino').removeAttr('disabled', 'disabled');
        $('#tipo_destino').removeAttr('disabled', 'disabled');
        $('#consultar_destino').removeAttr('disabled', 'disabled');
        $('#sede_destino').val('default');
        $('#tipo_destino').val('default');
        $('#sede_destino_hidden').attr('value', '');
        $('#tipo_destino_hidden').attr('value', '');
        $('#efectivo_ingresado').attr('value', '0.00');
        $('#valor_consignado').attr('value', '0.00');
        $('#efectivo_ingresado').attr('readonly', 'readonly');
        $('#valor_consignado').attr('readonly', 'readonly');
    });


    //Habilitamos con los valores DESTINO
    $("table").delegate("#caja_destino", "click", function() {
        var total = new Number($('#total').val().split(",").join(""));
        $('#efectivo_ingresado').attr('value', ((total).toFixed(2)));
        $('#efectivo_ingresado').change();
        $("#efectivo_ingresado").removeAttr("readonly");
    });
    //Habilitamos inputde valor retirado de las cuentas
    $("table").delegate("#cuenta_destino", "click", function() {
        var total = new Number($('#total').val().split(",").join(""));
        $('#valor_consignado').attr('value', ((total).toFixed(2)));
        $('#valor_consignado').change();
        $("#valor_consignado").removeAttr("readonly");
    });

</script>