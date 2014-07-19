<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <form role="form" method="post" action="{action_crear}" id="formulario">          
                <div class="row separar_div">
                    <legend>Crear empleado</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p>               
                    <div class="row">
                        <div class="col-xs-6">
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
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Primer Nombre<em class="required_asterisco">*</em></label>
                                        <input name="nombre1" id="nombre1" type="text" class="form-control exit_caution alfabeto" placeholder="Primer Nombre" maxlength="30">
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Segundo Nombre</label>
                                        <input name="nombre2" id="nombre2" type="text" class="form-control exit_caution alfabeto_espacios" placeholder="Segundo Nombre" maxlength="30">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Primer Apellido<em class="required_asterisco">*</em></label>
                                        <input name="apellido1" id="apellido1" type="text" class="form-control exit_caution alfabeto" placeholder="Primer Apellido" maxlength="30">
                                    </div> 
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Segundo Apellido</label>
                                        <input name="apellido2" id="apellido2" type="text" class="form-control exit_caution alfabeto" placeholder="Segundo Apellido" maxlength="30">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Fecha de Nacimiento<em class="required_asterisco">*</em></label>
                                        <div class="input-group">
                                            <input name="fecha_nacimiento" id="fecha_nacimiento" type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha de Nacimiento">
                                            <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Género<em class="required_asterisco">*</em></label>
                                        <select name="genero" id="genero" class="form-control exit_caution">
                                            <option value="default">Seleccione Género</option>
                                            <option value="F">Mujer</option>
                                            <option value="M">Hombre</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Estado Civil<em class="required_asterisco">*</em></label>
                                <select name="est_civil" id="est_civil" class="form-control exit_caution">
                                    <option value="default">Seleccione Estado Civil</option>
                                    {est_civil}
                                    <option value="{id}">{estado}</option>
                                    {/est_civil}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>País de domicilio<em class="required_asterisco">*</em></label>
                                <select name="pais" id="pais" class="form-control exit_caution">
                                    <option value="default">Seleccione País</option>
                                    {pais}
                                    <option value="{id}">{nombre}</option>
                                    {/pais}
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Departamento de domiclio<em class="required_asterisco">*</em></label>
                                        <select name="provincia" id="provincia" class="form-control exit_caution" disabled>
                                            <option value="default">Seleccione primero País</option>
                                        </select>                                
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Ciudad de domiclio<em class="required_asterisco">*</em></label>
                                        <select name="ciudad" id="ciudad" class="form-control exit_caution" disabled>
                                            <option value="default">Seleccione primero Depto</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tipo de Domicilio<em class="required_asterisco">*</em></label>
                                <select name="t_domicilio" id="t_domicilio" class="form-control exit_caution">
                                    <option value="default">Seleccione T. de Domicilio</option>
                                    {t_domicilio}
                                    <option value="{id}">{tipo}</option>
                                    {/t_domicilio}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Dirección<em class="required_asterisco">*</em></label>
                                <input name="direccion" id="direccion" type="text" class="form-control exit_caution alfanumerico" placeholder="Dirección" maxlength="80">
                            </div>
                            <div class="form-group">
                                <label>Barrio/Sector</label>
                                <input name="barrio" id="barrio" type="text" class="form-control exit_caution letras_numeros" placeholder="Barrio o Sector" maxlength="40">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Telefonos fijos de contacto<em class="required_asterisco">*</em></label>                                    
                                        <input name="telefono" id="telefono" type="text" class="form-control exit_caution alfanumerico" placeholder="Anexar indicativo Ej:(034-4114107)" maxlength="40">
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Celular</label>
                                        <input name="celular" id="celular" type="text" class="form-control exit_caution numerico" placeholder="Celular" maxlength="10">
                                    </div>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Correo Electrónico<em class="required_asterisco">*</em></label>
                                <input name="email" id="email" type="text" class="form-control exit_caution email" placeholder="Correo Electrónico" maxlength="80">
                            </div>                            
                            <div class="form-group">
                                <label>Número de Cuenta Bancaria Nomina de Sili</label>
                                <input name="cuenta" id="cuenta" type="text" class="form-control exit_caution numerico" placeholder="Cuenta Bancaria de Nómina" maxlength="12">
                            </div>
                            <div class="form-group">
                                <label>Sede Principal<em class="required_asterisco">*</em></label>
                                <p class="help-block"><B>> </B>Sólo aparecerán las sedes autorizadas del responsable.</p>                                
                                <select name="sede_ppal" id="sede_ppal" class="form-control exit_caution">
                                    <option value="default">Seleccione Sede Principal</option>
                                    {sede_ppal}
                                    <option value="{id}">{nombre}</option>
                                    {/sede_ppal}
                                </select>
                            </div>                               
                            <div class="form-group">
                                <label>Departamento Empresarial<em class="required_asterisco">*</em></label>
                                <select name="depto" id="depto" class="form-control exit_caution">
                                    <option value="default">Seleccione Departamento</option>
                                    {t_depto}
                                    <option value="{id}">{tipo}</option>
                                    {/t_depto}                                    
                                </select>
                            </div>                            
                            <div class="form-group">
                                <label>Cargo<em class="required_asterisco">*</em></label>
                                <select name="cargo" id="cargo" class="form-control exit_caution" disabled>
                                    <option value="default">Seleccione primero Departamento</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Salario<em class="required_asterisco">*</em></label>
                                <p class="help-block"><B>> </B>Salario que corresponden al departamento escogido.</p>
                                <select name="salario" id="salario" class="form-control exit_caution" disabled>
                                    <option value="default" selected>Seleccione Primero Departamento</option>
                                </select>
                            </div>                             
                            <div class="form-group">
                                <label>Jefe Inmediato<em class="required_asterisco">*</em></label>
                                <p class="help-block"><B>> </B>Empleados del mismo departamento y sede, junto con los altos directivos que tengan un nivel jerárquico superior.</p>
                                <select name="jefe" id="jefe" data-placeholder="Seleccione Jefe del Empleado" class="form-control exit_caution" disabled>
                                    <option value="default" selected>Seleccione Primero Sede, Departamento y Cargo</option>
                                </select>
                            </div>                           
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-xs-offset-3">
                        <div class="form-group">
                            <label>Fecha ingreso a la empresa<em class="required_asterisco">*</em></label>
                            <div class="input-group">
                                <input name="fecha_ingreso" id="fecha_ingreso" type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha de ingreso a la empresa">
                                <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div> 
                        </div>
                    </div>                    
                </div>
                <div class="row">
                    <legend>Último contrato laboral vigente</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                    <div class="row">
                        <div class="col-xs-8 col-xs-offset-2">
                            <div class="form-group">
                                <label>Tipo de Contrato Laboral<em class="required_asterisco">*</em></label>
                                <select name="t_contrato" id="t_contrato" class="form-control exit_caution">
                                    <option value="default">Seleccione tipo de contrato laboral</option>
                                    {t_contrato}
                                    <option value="{id}">{contrato}</option>
                                    {/t_contrato}
                                </select>
                            </div>                            
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Fecha inicio último contrato laboral<em class="required_asterisco">*</em></label>
                                        <div class="input-group">
                                            <input name="fecha_inicio" id="fecha_inicio" type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha de Inicio">
                                            <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-xs-6" id="duracion_contrato" style="display:none;">
                                    <div class="form-group">
                                        <label>Fecha fin último contrato laboral<em class="required_asterisco">*</em></label>
                                        <div class="input-group">
                                            <input name="fecha_fin" id="fecha_fin" type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha de Inicio">
                                            <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
                <hr>    
                <div class="row">
                    <div class="col-xs-12">
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
                            <center>
                                <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                                <button id="botonValidar" class="btn btn-success">Crear Empleado</button> 
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

    //PAra cargar los selects dinamicos de pais y departamento
    $(".form-group").delegate("#pais", "change", function() {
        pais = $('#pais').val();
        $.post('{action_llena_provincia}', {
            pais: pais
        }, function(data) {
            $("#provincia").removeAttr("disabled");
            $("#provincia").html(data);
            $("#provincia").prepend('<option value="default" selected>Seleccione Departamento</option>');
            //Con esto activamos automaticamente el evento click como si lo hicieramos nosotros.
            $("#provincia").change();
        });
    });
    $(".form-group").delegate("#provincia", "change", function() {
        provincia = $('#provincia').val();
        $.post('{action_llena_ciudad}', {
            provincia: provincia
        }, function(data) {
            $("#ciudad").removeAttr("disabled")
            $("#ciudad").html(data);
            $("#ciudad").prepend('<option value="default" selected>Seleccione Ciudad</option>');
        });
    });
    //Cargar cargo segun departamento
    $(".form-group").delegate("#depto", "change", function() {
        if ($('#depto').val() == "default") {
            $("#cargo").attr('disabled', 'disabled');
            $("#salario").attr('disabled', 'disabled');
            $("#cargo").html('<option value="default" selected>Seleccione primero Departamento</option>');
            $("#salario").html('<option value="default" selected>Seleccione primero Departamento</option>');
        } else {
            depto = $('#depto').val();
            $.post("{action_llena_cargo_departamento}", {
                depto: depto
            }, function(data) {
                $("#cargo").removeAttr("disabled");
                $("#cargo").html(data);
                $("#cargo").prepend('<option value="default" selected>Seleccione Cargo</option>');
            });
            $.post("{action_llena_salario_departamento}", {
                depto: depto
            }, function(data) {
                $("#salario").removeAttr("disabled");
                $("#salario").html(data);
                $("#salario").prepend('<option value="default" selected>Seleccione Salario</option>');
            });
        }
    });
    //Si modificamos cargo cargamos los jefes
    $(".form-group").delegate("#cargo", "change", function() {
        if (($('#cargo').val() == "default") || ($('#sede_ppal').val() == "default") || ($('#depto').val() == "default")) {
            $("#jefe").attr('disabled', 'disabled');
            $("#jefe").html('<option value="default" selected>Seleccione Primero Sede, Departamento y Cargo</option>');
        } else {
            sedePpal = $('#sede_ppal').val();
            cargo = $('#cargo').val();
            depto = $('#depto').val();
            $.post("{action_llena_jefe_new_empleado}", {
                sedePpal: sedePpal,
                cargo: cargo,
                depto: depto
            }, function(data) {
                $("#jefe").html(data);
                $("#jefe").prepend('<option value="default" selected>Seleccione Nuevo Jefe</option>');
                $("#jefe").removeAttr("disabled");
            });
        }
    });
    //Si modificamos depto cargamos los jefes
    $(".form-group").delegate("#depto", "change", function() {
        if (($('#cargo').val() == "default") || ($('#sede_ppal').val() == "default") || ($('#depto').val() == "default")) {
            $("#jefe").attr('disabled', 'disabled');
            $("#jefe").html('<option value="default" selected>Seleccione Primero Sede, Departamento y Cargo</option>');
        } else {
            sedePpal = $('#sede_ppal').val();
            cargo = $('#cargo').val();
            depto = $('#depto').val();
            $.post("{action_llena_jefe_new_empleado}", {
                sedePpal: sedePpal,
                cargo: cargo,
                depto: depto
            }, function(data) {
                $("#jefe").html(data);
                $("#jefe").prepend('<option value="default" selected>Seleccione Nuevo Jefe</option>');
                $("#jefe").removeAttr("disabled");
            });
        }
    });
    //Si modificamos cargo cargamos los jefes
    $(".form-group").delegate("#sede_ppal", "change", function() {
        if (($('#cargo').val() == "default") || ($('#sede_ppal').val() == "default") || ($('#depto').val() == "default")) {
            $("#jefe").attr('disabled', 'disabled');
            $("#jefe").html('<option value="default" selected>Seleccione Primero Sede, Departamento y Cargo</option>');
        } else {
            sedePpal = $('#sede_ppal').val();
            cargo = $('#cargo').val();
            depto = $('#depto').val();
            $.post("{action_llena_jefe_new_empleado}", {
                sedePpal: sedePpal,
                cargo: cargo,
                depto: depto
            }, function(data) {
                $("#jefe").html(data);
                $("#jefe").prepend('<option value="default" selected>Seleccione Nuevo Jefe</option>');
                $("#jefe").removeAttr("disabled");
            });
        }
    });    
    //Si modificamos sede ppal cargamos los jefes
    $(".form-group").delegate("#sede_ppal", "change", function() {
        if (($('#cargo').val() == "default") || ($('#sede_ppal').val() == "default") || ($('#depto').val() == "default")) {
            $("#jefe").attr('disabled', 'disabled');
            $("#jefe").html('<option value="default" selected>Seleccione Primero Sede, Departamento y Cargo</option>');
        } else {
            sedePpal = $('#sede_ppal').val();
            cargo = $('#cargo').val();
            depto = $('#depto').val();
            $.post("{action_llena_jefe_new_empleado}", {
                sedePpal: sedePpal,
                cargo: cargo,
                depto: depto
            }, function(data) {
                $("#jefe").html(data);
                $("#jefe").prepend('<option value="default" selected>Seleccione Nuevo Jefe</option>');
                $("#jefe").removeAttr("disabled");
            });
        }
    });

    //Cargar div de sancion segun t_sancion
    $("#t_contrato").live("change", function() {
        t_contrato = $('#t_contrato').val();
        if ((t_contrato == '1') || (t_contrato == 'default')) {
            $("#duracion_contrato").css("display", "none");
        } else {
            $("#duracion_contrato").css("display", "block");
        }
    });


</script>
