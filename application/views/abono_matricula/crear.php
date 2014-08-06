<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <form role="form" method="post" action="{action_crear}" id="formulario">
                <div class="row">
                    <legend>Crear abono a matrícula</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-3">
                            <legend>Titular</legend>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Tipo de Identificación<em class="required_asterisco">*</em></label>
                                        <select name="dni" id="dni" class="form-control exit_caution">
                                            <option value="default">Seleccione...</option>
                                            {dni}
                                            <option value="{id}">{tipo}</option>
                                            {/dni}
                                        </select>
                                    </div>   
                                </div>
                                <div class="col-xs-6">  
                                    <div class="form-group">
                                        <label>Número de Identificación<em class="required_asterisco">*</em></label>
                                        <input name="id" id="id" type="text" class="form-control exit_caution numerico" placeholder="Número de Identificación" maxlength="13">
                                    </div>
                                </div>
                            </div>
                            <div id="validacion_inicial">
                            </div>                            
                            <div class="row text-center">
                                <button type="button" class="btn btn-default" id="consultar_titular"><span class="glyphicon glyphicon-search"></span> Consultar </button>
                                <a href='{action_recargar}' class="btn btn-default" role="button"><span class="glyphicon glyphicon-user"></span> Seleccionar otro Titular </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="div_matriculas" style="display: none">
                    <div class="row separar_submit" id="nombre_titular">
                    </div>       
                    <div class="row"  style="display: none">
                        <div class="col-xs-6 col-xs-offset-3 separar_div">
                            <legend>Recibo de caja a nombre de:</legend>
                            <p class="help-block"><B>> </B>Si el beneficiario que aparecerá en el recibo de caja es diferente al titular (Por ejemplo: una empresa, un familiar, etc.), modifíquelo a continuacion.</p>
                            <div class="form-group">
                                <label>Tipo de Identificación<em class="required_asterisco">*</em></label>
                                <select name="dni_a_nombre_de" id="dni_a_nombre_de" class="form-control exit_caution">
                                    <option value="default">Seleccione...</option>
                                    {dni_a_nombre_de}
                                    <option value="{id}">{tipo}</option>
                                    {/dni_a_nombre_de}
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Número de Identificación<em class="required_asterisco">*</em></label>
                                        <input name="id_a_nombre_de" id="id_a_nombre_de" type="text" class="form-control exit_caution numerico" placeholder="Número de Identificación" maxlength="13">
                                    </div>
                                </div>
                                <div class="col-xs-6"  id="div_dv" style="display:none;">
                                    <div class="form-group">
                                        <label>Dígito de Verificación</label>
                                        <input name="d_v_a_nombre_de" id="d_v_a_nombre_de" class="form-control exit_caution soloclick" size="1" maxlength="1" type="text" value="0" readonly="readonly">
                                    </div>    
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Nombre completo / Razón Social<em class="required_asterisco">*</em></label>
                                <input name="a_nombre_de" id="a_nombre_de" type="text" class="form-control exit_caution letras_numeros" placeholder="Razón Social" maxlength="100">
                            </div>
                            <div class="form-group">
                                <label>Dirección<em class="required_asterisco">*</em></label>
                                <input name="direccion_a_nombre_de" id="direccion_a_nombre_de" type="text" class="form-control exit_caution alfanumerico" placeholder="Dirección" maxlength="80">
                            </div>                             
                        </div>
                    </div>
                    <div class="row">
                        <legend>Información de matrícula y cuotas</legend>
                        <div class="overflow_tabla">
                            <label>Matrícula a cancelar<em class="required_asterisco">*</em></label>
                            <table class="table table-hover tabla-matriculas">
                                <thead>
                                    <tr>
                                        <th class="text-center">Escojer</th>
                                        <th class="text-center">Contrato</th>
                                        <th class="text-center">Plan</th>
                                        <th class="text-center">Valor total</th>
                                        <th class="text-center">Saldo</th>
                                        <th class="text-center">Sede Origen</th>
                                        <th class="text-center">Fecha inicial</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_matricula_vigente">
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-xs-offset-4">
                                <div class="form-group">
                                    <label>Valor del abono</label>                            
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" name="total" id="total" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12" readonly="readonly">
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
                            <input type="hidden" name="titular_name" id="titular_name" value="" />
                            <!--aqui hiran los datos ocultos-->
                            <input type="hidden" name="saldo" id="saldo" value="0" />
                            <center>
                                <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                                <button id="btn_validar" class="btn btn-success">Crear abono a matrícula</button>  
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
    //Cargar div de valor abono y cuotas  de matricula escogida     
    $(".tabla-matriculas").delegate("#matricula", "click", function() {
        $("#saldo").attr("value", $(this).data('saldo'));
        $("#total").attr("value", '');
        $("#total").removeAttr("readonly", "readonly");
    });

    //Llenamos la informacion de las matriculas y los pagos.
    $("form").delegate("#consultar_titular", "click", function() {
        var dni = $('#dni').val();
        var id = $('#id').val();
        if ((dni != "default") && (id != "")) {
            $.post('{action_validar_titular_llena_matriculas}', {
                dni: dni,
                id: id
            }, function(data) {
                var obj = JSON.parse(data);
                if (obj.respuesta == "OK")
                {
                    $("#nombre_titular").html('<center><table><th><td><h4>Nombre del titular: </h4></td><td><h4 class="h_negrita"> ' + obj.nombreTitular + '</h4></td></th></table></center>');
                    $("#titular_name").val(obj.nombreTitular);
                    $("#tbody_matricula_vigente").html(obj.filasTabla);
                    $("#div_matriculas").css("display", "block");
                    $('#dni').attr('disabled', 'disabled');
                    $('#id').attr('readonly', 'readonly');
                    $('#consultar_titular').attr('disabled', 'disabled');
                    //Actualizamo la informacion del titular en a nombre de 
                    $("#dni_a_nombre_de option[value=" + $('#dni').val() + "]").attr("selected", true);
                    $("#id_a_nombre_de").attr("value", $('#id').val());
                    $("#a_nombre_de").attr("value", obj.nombreTitular);
                    $("#direccion_a_nombre_de").attr("value", obj.direccion);
                    $("#div_warning").remove();
                } else {
                    $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
                    $("#div_warning").html(obj.mensaje);
                    $("#div_warning").delay(8000).fadeOut(1000);
                }
            });
        } else {
            $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
            $("#div_warning").html("<p><strong>Antes de consultar, ingrese el tipo y número de identificación del titular.</strong></p>");
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


    //Validamos el formulario antes de enviarlo por submit
    //Enviar formulario por ajax
    $('#btn_validar').live('click', function() {
        $('#dni').removeAttr("disabled");
        $.ajax({
            type: "POST",
            url: $("#action_validar").attr("value"),
            cache: false,
            data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
            success: function(data)
            {
                if (data != "OK") {
                    $('#dni').attr('disabled', 'disabled');
                    $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                    $("#div_alert").html(data);
                    $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
                } else {
                    $(window).unbind('beforeunload');
                    $("#btn_submit").click();
                }
            },
            error: function(data) {
                $('#dni').attr('disabled', 'disabled');
                $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                $('#div_alert').html('<p>Hubo un error en la peticion al servidor</p>');
                $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');

            }
        });
        return false; // Evitar ejecutar el submit del formulario
    });


</script>