<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <form role="form" method="post" action="{action_crear}" id="formulario">
                <div class="row">
                    <legend>Crear factura de venta</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-3">
                            <legend>Titular</legend>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Tipo de Identificación<em class="required_asterisco">*</em></label>
                                        <select name="dni" id="dni" class="form-control exit_caution">
                                            <option value="default">Seleccione T.I.</option>
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
                                <a href='{action_recargar}' class="btn btn-default" role="button"><span class="glyphicon glyphicon-user"></span> Seleccionar otro titular </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="div_matriculas" style="display: none">
                    <div class="row separar_submit" id="nombre_titular">
                    </div>       
                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-3 separar_div">
                            <legend>Factura a nombre de:</legend>
                            <p class="help-block"><B>> </B>Si el beneficiario que aparecerá en la factura es diferente al titular (Por ejemplo: una empresa, un familiar, etc.), modifíquelo a continuacion.</p>
                            <div class="form-group">
                                <label>Tipo de Identificación<em class="required_asterisco">*</em></label>
                                <select name="dni_a_nombre_de" id="dni_a_nombre_de" class="form-control exit_caution">
                                    <option value="default">Seleccione T.I.</option>
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
                            <div class="col-xs-8 col-xs-offset-2">
                                <div id="validacion_saldo">
                                </div>
                            </div>
                        </div>
                        <div id="div_tipo_pago" class="separar_div" style="display:none;"> 
                            <div class="row">
                                <div class="col-xs-6 col-xs-offset-3">
                                    <div class="form-group">
                                        <label>Tipo de pago<em class="required_asterisco">*</em></label>
                                        <select name="tipo_pago" id="tipo_pago" class="form-control exit_caution">
                                            <option value="default">Seleccione tipo de pago</option>
                                            <option value="1">Pago de cuotas exactas del plan de la matrícula (Generalmente)</option>
                                            <option value="2">Abono distinto a las cuotas del plan de la matrícula (Casos especiales)</option>
                                        </select>
                                    </div>                        
                                </div>  
                            </div>
                            <div id="div_cuota_abono" style="display:none;">
                                <div class="row">
                                    <div class="col-xs-4 col-xs-offset-4">
                                        <div class="form-group">
                                            <label>Valor del abono</label>                            
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" name="total" id="total" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12">
                                            </div>
                                        </div>
                                    </div>
                                </div>                        
                            </div>  
                            <div id="div_cuotas_fijas" style="display:none;">
                                <div class="overflow_tabla">
                                    <label>Cuotas a cancelar<em class="required_asterisco">*</em></label>
                                    <table class="table table-hover tabla-cuotas">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Escojer</th>
                                                <th class="text-center"># Cuota</th>
                                                <th class="text-center">Detalle</th>
                                                <th class="text-center">Abono pendiente</th>
                                                <th class="text-center">Fecha esperada</th>
                                                <th class="text-center">Cantidad Mora</th>
                                                <th class="text-center">Int. Mora</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_cuotas">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-xs-4 col-xs-offset-4">
                                        <div class="form-group">
                                            <label>Descuento</label>    
                                            <p class="help-block"><B>> </B>Para descontar sólo intereses de mora.</p>                                    
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" name="descuento" id="descuento" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12" readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                </div>                        
                                <div class="row">
                                    <div class="col-xs-6 col-xs-offset-4">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <p><h4>Total abonos (+)</h4></p>
                                                <p><h4>Total intereses (+)</h4></p>
                                                <p><h4>Total descuento (-)</h4></p>
                                                <p><h3>Pago total</h3></p>
                                            </div>
                                            <div class="col-xs-8">
                                                <div id="div_subtotal"><h4>$ 0.00</h4></div>
                                                <div id="div_intereses"><h4>$ 0.00</h4></div>
                                                <div id="div_descuento"><h4>$ 0.00</h4></div>
                                                <div id="div_total"><h3>$ 0.00</h3></div>
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
                            <!--Datos adicionales para ocultar del formulario-->
                            <input type="hidden" name="subtotal" id="subtotal" class="decimal decimal2 miles"/>
                            <input type="hidden" name="int_mora" id="int_mora" class="decimal decimal2 miles"/>
                            <input type="hidden" name="descuento_hidden" id="descuento_hidden" class="decimal decimal2 miles"/>
                            <input type="hidden" name="saldo" id="saldo" value="0" />                            
                            <center>
                                <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                                <button id="btn_validar" class="btn btn-success">Crear factura</button>  
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
    $("form").delegate("#consultar_titular", "click", function() {
        var dni = $('#dni').val();
        var id = $('#id').val();
        if ((dni != "default") && (id != "")) {
            $.post('{action_validar_titular_llena_matriculas}', {dni: dni,
                id: id
            }, function(data) {
                var obj = JSON.parse(data);
                if (obj.respuesta == "OK")
                {
                    $("#nombre_titular").html('<center><table><th><td><h4>Nombre del titular: </h4></td><td><h4 class="h_negrita"> ' + obj.nombreTitular + '</h4></td></th></table></center>');
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


    //Cargar div de valor abono y cuotas  de matricula escogida     
    $(".tabla-matriculas").delegate("#matricula", "click", function() {
        saldo = $("input[name='matricula']:checked").data('saldo');
        $("#saldo").attr("value", saldo);
        if (saldo > '0') {
            $("#div_warning").remove();
            $("#div_tipo_pago").css("display", "block");
        } else {
            $("#div_tipo_pago").css("display", "none");
            $("#validacion_saldo").html('<div class="alert alert-info" id="div_warning"></div>');
            $("#div_warning").html("<p><strong><center>La matrícula seleccionada, se encuentra a paz y salvo.</center></strong></p>");
        }
    });

    //Cargar div de valor abono y cuotas  de matricula escogida     
    $(".form-group").delegate("#tipo_pago", "change", function() {
        tipo_pago = $("#tipo_pago").val();
        if (tipo_pago == "1") {
            $("#div_cuota_abono").css("display", "none");
            $("#div_cuotas_fijas").css("display", "block");
            matricula = $("input[name='matricula']:checked").val();
            $.post('{action_llena_cuotas_matricula}', {
                matricula: matricula
            }, function(data) {
                var obj = JSON.parse(data);
                if (obj.respuesta == 'OK') {
                    $("#tbody_cuotas").html(obj.filasTabla);
                    calcular_total();
                }
            });
        } else {
            if (tipo_pago == "2") {
                $("#total").attr("value", '');
                $("#div_cuota_abono").css("display", "block");
                $("#div_cuotas_fijas").css("display", "none");
            } else {
                $("#div_cuota_abono").css("display", "none");
                $("#div_cuotas_fijas").css("display", "none");
            }
        }
    });


    $(".form-group").delegate("#id_a_nombre_de", "blur", function() {
        var vpri, x, y, z, i, nit1, dv1;
        nit1 = $(this).val();
        if (isNaN(nit1))
        {
            $("#d_v_a_nombre_de").attr('value', 'Error de Nit');
        } else {
            vpri = new Array(16);
            x = 0;
            y = 0;
            z = nit1.length;
            vpri[1] = 3;
            vpri[2] = 7;
            vpri[3] = 13;
            vpri[4] = 17;
            vpri[5] = 19;
            vpri[6] = 23;
            vpri[7] = 29;
            vpri[8] = 37;
            vpri[9] = 41;
            vpri[10] = 43;
            vpri[11] = 47;
            vpri[12] = 53;
            vpri[13] = 59;
            vpri[14] = 67;
            vpri[15] = 71;
            for (i = 0; i < z; i++)
            {
                y = (nit1.substr(i, 1));
                //document.write(y+"x"+ vpri[z-i] +":");
                x += (y * vpri[z - i]);                 //document.write(x+"<br>");		
            }
            y = x % 11
            //document.write(y+"<br>");
            if (y > 1)
            {
                dv1 = 11 - y;
            } else {
                dv1 = y;
            }
            $("#d_v_a_nombre_de").attr('value', dv1);
        }
    });
    //Cargar div de d.v segun t_dni
    $(".form-group").delegate("#dni_a_nombre_de", "change", function() {
        dni = $(this).val();
        if (dni == '6') {
            $("#div_dv").css("display", "block");
        } else {
            $("#div_dv").css("display", "none");
        }
    });


    $(".input-group").delegate("#descuento", "focus", function() {
        if ($('#descuento').val() == '0.00') {
            $('#descuento').attr('value', '');
        }
    });

    $(".input-group").delegate("#descuento", "blur", function() {
        var descuento = new Number($('#descuento').val().split(",").join(""));
        $('#descuento_hidden').attr('value', ((descuento).toFixed(2)));
        $('#descuento_hidden').change();
        $("#div_descuento").html("<h4>$ " + $('#descuento_hidden').val() + "</h4>");
        if ($('#descuento').val() == '') {
            $('#descuento').attr('value', '0.00');
        }
        calcular_total();
    });

    //Calculamos total devengado, deducido y total nomina
    function calcular_total() {
        var valor_pendiente = 0;
        var int_mora = 0;
        var total_valor_pendiente = 0;
        var total_int_mora = 0;
        var total = 0;
        $("input:checkbox:checked").each(function() {
            valor_pendiente = new Number($(this).data('valor_pendiente'));
            int_mora = new Number($(this).data('int_mora'));
            total_valor_pendiente = total_valor_pendiente + valor_pendiente;
            total_int_mora = total_int_mora + int_mora;
            total = total_valor_pendiente + total_int_mora;
        });
        var descuento = new Number($('#descuento').val().split(",").join(""));
        total = total - descuento;
        $('#subtotal').attr('value', ((total_valor_pendiente).toFixed(2)));
        $('#subtotal').change();
        $('#int_mora').attr('value', ((total_int_mora).toFixed(2)));
        $('#int_mora').change();
        $('#total').attr('value', ((total).toFixed(2)));
        $('#total').change();//        //        
        $("#div_subtotal").html("<h4>$ " + $('#subtotal').val() + "</h4>");
        $("#div_intereses").html("<h4>$ " + $('#int_mora').val() + "</h4>");
        $("#div_total").html("<h3>$ " + $('#total').val() + "</h3>");
    }

    //Cargar div de valor abono y cuotas  de matricula escogida 
    //Cuando seleccione una cuota debo verificar que lo haga en orden de arriba hacia abajo. No permite que salte cuotas.     
    $(".tabla-cuotas").delegate("#cuotas", "change", function() {
        $("input:checkbox:checked").each(function() {
            if ($(this).parent().parent().index() != 0) {
                if (!($(this).parent().parent().prev().find("#cuotas").is(':checked'))) {
                    $(this).attr('checked', false);
                }
            }
        });
        calcular_total();
        $("#descuento").removeAttr("readonly", "readonly");
        $('#descuento').attr('value', '0.00');
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