<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear egreso</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1">
                        <form role="form" method="post" action="{action_crear}" id="formulario">
                            <p style="text-align:justify;"><B>Nota: </B>Utilice esta opción en el caso en que el egreso que desea registrar no pertenezca a ninguna de las otras opciones disponibles de egresos, tales como: Nómina, Adelanto de nómina, Pago a proveedor, etc.</p><br>
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-2">
                                    <div class="form-group">
                                        <label>Tipo de Egreso<em class="required_asterisco">*</em></label>
                                        <select name="t_egreso" id="t_egreso" class="form-control exit_caution">
                                            <option value="default">Seleccione Tipo de Egreso</option>
                                            {t_egreso}
                                            <option value="{id}">{tipo}</option>
                                            {/t_egreso}
                                        </select>
                                    </div>                                    
                                    <div class="form-group">
                                        <label>Tipo de Usuario Beneficiario<em class="required_asterisco">*</em></label>
                                        <select name="t_beneficiario" id="t_beneficiario" class="form-control exit_caution">
                                            <option value="default">Seleccione Tipo de Usuario Beneficiario</option>
                                            {t_beneficiario}
                                            <option value="{id}">{tipo}</option>
                                            {/t_beneficiario}
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Tipo de Id. del Beneficiario<em class="required_asterisco">*</em></label>
                                        <select name="dni_beneficiario" id="dni_beneficiario" class="form-control exit_caution">
                                            <option value="default">Seleccione Tipo de Id. del Beneficiario </option>
                                            {dni}
                                            <option value="{id}">{tipo}</option>
                                            {/dni}
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-7">
                                            <div class="form-group">
                                                <label>Número de Id. del Beneficiario<em class="required_asterisco">*</em></label>
                                                <input name="id_beneficiario" id="id_beneficiario" type="text" class="form-control exit_caution numerico" placeholder="Número de Id. del Beneficiario" maxlength="13">
                                            </div>
                                        </div>
                                        <div class="col-xs-5"  id="div_dv" style="display:none;">
                                            <div class="form-group">
                                                <label>Dígito de Verificación</label>
                                                <input name="d_v" id="d_v" class="form-control exit_caution soloclick" size="1" maxlength="1" type="text" value="0" readonly="readonly">
                                            </div>    
                                        </div>
                                    </div>
                                    <div class="form-group" id="div_nombre_beneficiario" style="display:none;">
                                        <label>Nombre del Beneficiario<em class="required_asterisco">*</em></label>
                                        <input name="nombre_beneficiario" id="nombre_beneficiario" type="text" class="form-control exit_caution letras_numeros" placeholder="Nombre del Beneficiario" maxlength="100">
                                    </div>                                    
                                    <div class="form-group">
                                        <label>Valor del Egreso<em class="required_asterisco">*</em></label>                                        
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
                                <label id="label_descripcion">Descripción</label>
                                <label  style="display:none;" id="label_descripcion_required">Descripción<em class="required_asterisco">*</em></label>
                                <textarea name="descripcion" id="descripcion" class="form-control exit_caution alfanumerico" rows="4" maxlength="250" placeholder="Descripción..."  style="max-width:100%;"></textarea>
                            </div>
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                                    <button id="botonValidar" class="btn btn-success">Crear Egreso</button>                                 
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
    $(".form-group").delegate("#id_beneficiario", "blur", function() {
        var vpri, x, y, z, i, nit1, dv1;
        nit1 = $(this).val();
        if (isNaN(nit1))
        {
            $("#d_v").attr('value', 'Error de Nit');
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
                x += (y * vpri[z - i]);
                //document.write(x+"<br>");		
            }
            y = x % 11
            //document.write(y+"<br>");
            if (y > 1)
            {
                dv1 = 11 - y;
            } else {
                dv1 = y;
            }
            $("#d_v").attr('value', dv1);
        }
    });

    //Cargar div de d.v segun t_sancion
    $(".form-group").delegate("#dni_beneficiario", "change", function() {
        dni = $('#dni_beneficiario').val();
        if (dni == '6') {
            $("#div_dv").css("display", "block");
        } else {
            $("#div_dv").css("display", "none");
        }
    });

    //Mostramos el obligatorio de descripcion si en t_egreso selecciona "otro" o "transaccion intersede de difereente pais"
    $(".form-group").delegate("#t_egreso", "change", function() {
        t_egreso = $('#t_egreso').val();
        if ((t_egreso == '8')||(t_egreso == '9')) {
            $("#label_descripcion_required").css("display", "block");
            $("#label_descripcion").css("display", "none");
        } else {
            $("#label_descripcion_required").css("display", "none");
            $("#label_descripcion").css("display", "block");
        }
    });

    //Mostramos el div de nombre de beneficiario si es otros
    $(".form-group").delegate("#t_beneficiario", "change", function() {
        t_beneficiario = $('#t_beneficiario').val();
        if (t_beneficiario == '6') {
            $("#div_nombre_beneficiario").css("display", "block");
        } else {
            $("#div_nombre_beneficiario").css("display", "none");
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

</script>