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
                            <div class="row text-center separar_submit">
                                <button type="button" class="btn btn-default" id="consultar_titular"><span class="glyphicon glyphicon-search"></span> Consultar </button>
                                <a href='{action_recargar}' class="btn btn-default" role="button"><span class="glyphicon glyphicon-user"></span> Modificar Titular </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="div_matriculas" style="display: none">
                    <!--<div id="div_matriculas">-->
                    <div class="row" id="nombre_titular">
                    </div>                 
                    <div class="overflow_tabla">
                        <label>Matrículas vígentes<em class="required_asterisco">*</em></label>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">Escojer</th>
                                    <th class="text-center">Contrato</th>
                                    <th class="text-center">Plan</th>
                                    <th class="text-center">Valor Inicial</th>
                                    <th class="text-center">Saldo</th>
                                    <th class="text-center">Sede Origen</th>
                                    <th class="text-center">Fecha del Préstamo</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_matricula_vigente">
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
                                    <th class="text-center">Abono Mínimo</th>
                                    <th class="text-center">Abono Máximo</th>
                                    <th class="text-center">Abono Realizado</th>                                           
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
                        <!--calculamos la cantidad de dias de la nomina y la cantidad de dias laborados (dias_nomina - ausencias)-->
                        <input type="hidden" name="dias_nomina" id="dias_nomina"/>
                        <input type="hidden" name="dias_remunerados" id="dias_remunerados"/>
                        <!--Aqui almacenamos el total devengado de la nomina-->
                        <input type="hidden" name="total_devengado" class="miles decimal2" id="total_devengado"/>
                        <input type="hidden" name="total_deducido" class="miles decimal2" id="total_deducido"/>
                        <input type="hidden" name="total_nomina" class="miles decimal2" id="total_nomina"/>
                        <!--para controlar el id de los nuevos conceptos agregados-->
                        <input type="hidden" name="contador_new_concepto" class="miles decimal2" id="contador_new_concepto"/>
                        <center>
                            <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                            <button id="btn_validar" class="btn btn-success">Crear Nómina</button>  
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
<script type="text/javascript">
    //Colocamos el contador de nuevos conceptos en 1
    $('#contador_new_concepto').attr('value', '1');

    //Calculamos total devengado, deducido y total nomina
    function calcular_total() {
        var total_devengado = 0;
        var total_deducido = 0;
        var cantidad = 0;
        var valor_unitario = 0;
        $(".renglon_concepto").each(function() {
            cantidad = new Number($(this).find("#cantidad").val());
            valor_unitario = new Number($(this).find("#valor_unitario").val().split(",").join(""));
            debito_credito = new Number($(this).find("#debito_credito").val());
            if (debito_credito == 1) {
                total_devengado = total_devengado + (cantidad * valor_unitario);
                $(this).find("#total_concepto").attr('value', (cantidad * valor_unitario).toFixed(2));
                $(this).find("#total_concepto").change();
            } else {
                total_deducido = total_deducido + (cantidad * valor_unitario);
                $(this).find("#total_concepto").attr('value', (cantidad * valor_unitario).toFixed(2));
                $(this).find("#total_concepto").change();
            }

        });
        $('#total_devengado').attr('value', ((total_devengado).toFixed(2)));
        $('#total_devengado').change();
        $("#div_total_devengado").html("<h4>$ " + $('#total_devengado').val() + "</h4>");
        $('#total_deducido').attr('value', ((total_deducido).toFixed(2)));
        $('#total_deducido').change();
        $("#div_total_deducido").html("<h4>$ " + $('#total_deducido').val() + "</h4>");
        $('#total_nomina').attr('value', ((total_devengado - total_deducido).toFixed(2)));
        $('#total_nomina').change();
        $("#div_total_nomina").html("<h3>$ " + $('#total_nomina').val() + "</h3>");
    }

    //Cargar div de valor abono y cuotas  de matricula escogida
    $("table").delegate("#matricula", "change", function() {
        matricula = $("input[name='matricula']:checked").val();
        $.post('{action_llena_cuotas_matricula}', {
            matricula: matricula
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
                    $("#tbody_matricula_vigente").html(obj.filasTabla);
                    $("#div_matriculas").css("display", "block");
                    $('#dni').attr('disabled', 'disabled');
                    $('#id').attr('readonly', 'readonly');
                    $('#consultar_titular').attr('disabled', 'disabled');
                } else {
                    $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
                    $("#div_warning").html(obj.mensaje);
                    $("#div_warning").delay(5000).fadeOut(1000);
                }
            });
        } else {
            $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
            $("#div_warning").html("<p><strong>Antes de consultar, ingrese el tipo y número de identificación del titular.</strong></p>");
            $("#div_warning").delay(8000).fadeOut(1000);
        }
    });

    $("#div_nomina").delegate("#cantidad", "keyup", function() {
        calcular_total();
    });
    $("#div_nomina").delegate("#valor_unitario", "keyup", function() {
        calcular_total();
    });

    //Agregamos nuevos conceptos desde el boton Agregar Concepto.
    $("#div_nomina").delegate("#agregar_concepto", "click", function() {
        //Agregamos un concepto de nomina
        var idUltimoConcepto = $('#contador_new_concepto').val();
        var empleado = $('#empleado').val();
        $.post('{action_llena_agregar_concepto}', {
            empleado: empleado,
            idUltimoConcepto: idUltimoConcepto
        }, function(data) {
            if (data != "") {
                $("#conceptos_nuevos").append(data);
                //Aumentamos el contador de nuevos conceptos.
                var aumentarId = (new Number($('#contador_new_concepto').val()) + 1);
                $('#contador_new_concepto').attr('value', aumentarId);
            }
        });
    });

    //Eliminamos los conceptos y preguntamos antes
    $("#div_nomina").delegate(".drop_concepto_pdte", "click", function() {
        if (confirm('¿Está seguro que desea eliminar el Concepto de la Nómina? \n \n Sólo cuando creé una nueva Nómina, podrá recuperarlo.')) {
            $("#div_concepto_pdte_" + $(this).attr('id')).remove();
            calcular_total();
        }
    });

    //Eliminamos los conceptos y preguntamos antes
    $("#div_nomina").delegate(".drop_concepto_new", "click", function() {
        if (confirm('¿Está seguro que desea eliminar el Concepto de la Nómina?')) {
            $("#div_concepto_new_" + $(this).attr('id')).remove();
            calcular_total();
        }
    });

    //Cargamos la informacion en el concepto segun el t_concepto
    $("#div_nomina").delegate("#t_concepto_nomina", "change", function() {
        var empleado = $('#empleado').val();
        var tConceptoNomina = $(this).val();
        var idDivConcepto = $(this).parent().parent().parent().parent().attr('id');
        $.post('{action_llena_info_t_concepto}', {
            empleado: empleado,
            tConceptoNomina: tConceptoNomina
        }, function(data) {
            var obj = JSON.parse(data);
            if (obj.respuesta == "OK")
            {
                if (obj.detalle_requerido == '1') {
                    $("#" + idDivConcepto).find("#label_detalle").html('<label>Detalles adicionales<em class="required_asterisco">*</em></label>');
                } else {
                    if (obj.detalle_requerido == '0') {
                        $("#" + idDivConcepto).find("#label_detalle").html('<label>Detalles adicionales</label>');
                    }
                }
                $("#" + idDivConcepto).find("#detalle").attr('placeholder', obj.placeholder_detalle);
                if (new Number(obj.valor_unitario) == '0') {
                    $("#" + idDivConcepto).find("#valor_unitario").attr('value', 0);
                    $("#" + idDivConcepto).find("#valor_unitario").removeAttr('readonly');
                } else {
                    $("#" + idDivConcepto).find("#valor_unitario").attr('readonly', 'readonly');
                    $("#" + idDivConcepto).find("#valor_unitario").attr('value', obj.valor_unitario);
                    $("#" + idDivConcepto).find("#valor_unitario").change();
                }
                if (obj.debito_credito == '1') {
                    $("#" + idDivConcepto).find("#label_total_concepto").html('<label>Devengado</label>');
                    $("#" + idDivConcepto).find("#debito_credito").attr('value', 1);
                } else {
                    if (obj.debito_credito == '0') {
                        $("#" + idDivConcepto).find("#label_total_concepto").html('<label>Deducido</label>');
                        $("#" + idDivConcepto).find("#debito_credito").attr('value', 0);
                    }
                }
                if (obj.t_cantidad_dias == '1') {
                    $("#" + idDivConcepto).find("#cantidad").attr('value', 1);
                    $("#" + idDivConcepto).find("#cantidad").attr('readonly', 'readonly');
                } else {
                    if (obj.t_cantidad_dias == '2') {
                        $("#" + idDivConcepto).find("#cantidad").attr('value', $('#dias_nomina').val());
                        $("#" + idDivConcepto).find("#cantidad").attr('readonly', 'readonly');
                    } else {
                        if (obj.t_cantidad_dias == '3') {
                            $("#" + idDivConcepto).find("#cantidad").attr('value', $('#dias_remunerados').val());
                            $("#" + idDivConcepto).find("#cantidad").attr('readonly', 'readonly');
                        } else {
                            if (obj.t_cantidad_dias == '4') {
                                $("#" + idDivConcepto).find("#cantidad").attr('value', 0);
                                $("#" + idDivConcepto).find("#cantidad").removeAttr('readonly');
                            }
                        }
                    }
                }
                $("#" + idDivConcepto).find("#detalle").removeAttr('readonly');
                calcular_total();
            }
        });
    });



    //Cargamos la info de las cuentas para el pago
    $.post('{action_llena_cuenta_responsable}', {
        idResposable: '{id_responsable}',
        dniResposable: '{dni_responsable}'
    }, function(data) {
        $("#tbody_cuenta_bancaria").html(data);
    });

    //Habilita las cajas y las cuentas
    $("table").delegate("#cuenta", "change", function() {
        var total = new Number($('#total_nomina').val().split(",").join(""));
        var efectivo_retirado = new Number($('#efectivo_retirado').val().split(",").join(""));
        $('#valor_retirado').attr('value', ((total - efectivo_retirado).toFixed(2)));
        $('#valor_retirado').change();
        $("#valor_retirado").removeAttr("readonly");
    });

    $(".form-group").delegate("#valor_retirado", "blur", function() {
        var total = new Number($('#total_nomina').val().split(",").join(""));
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
        var total = new Number($('#total_nomina').val().split(",").join(""));
        var valor_retirado = new Number($('#valor_retirado').val().split(",").join(""));
        $('#efectivo_retirado').attr('value', ((total - valor_retirado).toFixed(2)));
        $('#efectivo_retirado').change();
        $("#efectivo_retirado").removeAttr("readonly");
    });

    $(".form-group").delegate("#efectivo_retirado", "blur", function() {
        var total = new Number($('#total_nomina').val().split(",").join(""));
        var efectivo_retirado = new Number($('#efectivo_retirado').val().split(",").join(""));
        $('#valor_retirado').attr('value', ((total - efectivo_retirado).toFixed(2)));
        $('#valor_retirado').change();
    }
    );


    //Validamos el formulario antes de enviarlo por submit
    //Enviar formulario por ajax
    $('#btn_validar').live('click', function() {
        $.ajax({
            type: "POST",
            url: $("#action_validar").attr("value"),
            cache: false,
            data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
            success: function(data)
            {
                if (data != "OK") {
                    $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                    $("#div_alert").html(data);
                    $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
                } else {
                    $(window).unbind('beforeunload');
                    $("#btn_submit").click();
                }
            },
            error: function(data) {
                $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                $('#div_alert').html('<p>Hubo un error en la peticion al servidor</p>');
                $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');

            }
        });
        return false; // Evitar ejecutar el submit del formulario
    });


</script>