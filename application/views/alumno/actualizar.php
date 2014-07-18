<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <form role="form" method="post" action="{action_crear}" id="formulario">
                <div class="row">
                    <legend>Modificar alumno</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-3">
                            <legend>Alumno</legend>
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
                                <button type="button" class="btn btn-default" id="consultar_alumno"><span class="glyphicon glyphicon-search"></span> Consultar </button>
                                <a href='{action_recargar}' class="btn btn-default" role="button"><span class="glyphicon glyphicon-user"></span> Consultar otro alumno </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="div_alumno" style="display: none">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Tipo de Identificación<em class="required_asterisco">*</em></label>
                                        <select name="dni_new" id="dni_new" class="form-control exit_caution">
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
                                        <input name="id_new" id="id_new" type="text" class="form-control exit_caution numerico" placeholder="Número de Identificación" maxlength="13">
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
                                        <select name="provincia" id="provincia" class="form-control exit_caution">
                                            <option value="default">Seleccione primero País</option>
                                        </select>                                
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Ciudad de domiclio<em class="required_asterisco">*</em></label>
                                        <select name="ciudad" id="ciudad" class="form-control exit_caution">
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
                                <label>Número de Matrícula<em class="required_asterisco">*</em></label>
                                <input name="matricula" id="matricula" type="text" class="form-control exit_caution numerico" readonly>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">                            
                                    <div class="form-group">
                                        <label>Velocidad Inicial<em class="required_asterisco">*</em></label>                                
                                        <div class="input-group">
                                            <span class="input-group-addon">p.p.m</span>
                                            <input type="text" name="velocidad_ini" id="velocidad_ini" class="form-control numerico miles" placeholder="0" maxlength="5">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6">  
                                    <div class="form-group">
                                        <label>Comprensión Inicial<em class="required_asterisco">*</em></label>                                
                                        <div class="input-group">
                                            <span class="input-group-addon">%</span>
                                            <input type="text" name="comprension_ini" id="comprension_ini" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="5">
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tipo de Curso<em class="required_asterisco">*</em></label>
                                <select name="t_curso" id="t_curso" class="form-control exit_caution">
                                    <option value="default">Seleccione Tipo de Curso</option>
                                    {t_curso}
                                    <option value="{id}">{tipo}</option>
                                    {/t_curso}
                                </select>
                            </div> 
                            <div class="form-group">
                                <label>Clases por Semana<em class="required_asterisco">*</em></label>
                                <select name="cant_clases" id="cant_clases" class="form-control exit_caution">
                                    <option value="default">Seleccione cantidad de Clases</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                </select>
                            </div> 
                            <div class="form-group">
                                <label>Estado del alumno<em class="required_asterisco">*</em></label>
                                <select name="est_alumno" id="est_alumno" class="form-control exit_caution">
                                    <option value="default">Seleccione estado del alumno</option>
                                    {est_alumno}
                                    <option value="{id}">{estado}</option>
                                    {/est_alumno}
                                </select>
                            </div>                             
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea name="observacion" id="observacion" class="form-control exit_caution" rows="4" maxlength="250" placeholder="Observación..."style="max-width:100%;"></textarea>
                            </div>                            
                        </div>
                    </div>
                    <div id="validacion_alert">
                    </div>
                    <div class="form-group separar_submit">
                        <input type="hidden" id="action_validar" value={action_validar} />
                        <input type="hidden" name="id_responsable" value={id_responsable} />
                        <input type="hidden" name="dni_responsable" value={dni_responsable} />
                        <center>
                            <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                            <button id="btn_validar" class="btn btn-success">Modificar alumno</button> 
                            <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                            <a href="<?= base_url() ?>"class="btn btn-danger" role="button"> Cancelar </a>
                        </center>
                    </div>                      
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    //PAra cargar los selects dinamicos de pais y departamento
    $("#pais").live("change", function() {
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
    $("#provincia").live("change", function() {
        provincia = $('#provincia').val();
        $.post('{action_llena_ciudad}', {
            provincia: provincia
        }, function(data) {
            $("#ciudad").removeAttr("disabled")
            $("#ciudad").html(data);
            $("#ciudad").prepend('<option value="default" selected>Seleccione Ciudad</option>');
        });
    });

    //Llenamos la informacion de las matriculas y los pagos.
    $("form").delegate("#consultar_alumno", "click", function() {
        var dni = $('#dni').val();
        var id = $('#id').val();
        if ((dni != "default") && (id != "")) {
            $.post('{action_validar_alumno}', {
                dni: dni,
                id: id
            }, function(data) {
                var obj = JSON.parse(data);
                if (obj.respuesta == "OK")
                {
                    $("#div_alumno").css("display", "block");
                    $('#dni').attr('disabled', 'disabled');
                    $('#id').attr('readonly', 'readonly');
                    $('#consultar_alumno').attr('disabled', 'disabled');
                    //Actulizamos los input con valores existentes.
                    $("#id_new").attr("value", obj.id);
                    $("#dni_new").attr("value", obj.dni);
                    $("#nombre1").attr("value", obj.nombre1);
                    $("#nombre2").attr("value", obj.nombre2);
                    $("#apellido1").attr("value", obj.apellido1);
                    $("#apellido2").attr("value", obj.apellido2);
                    $("#genero").attr("value", obj.genero);
                    $("#fecha_nacimiento").attr("value", obj.fecha_nacimiento);
                    $("#pais").attr("value", obj.pais);
                    $.post('{action_llena_provincia}', {
                        pais: obj.pais
                    }, function(data) {
                        $("#provincia").removeAttr("disabled");
                        $("#provincia").html(data);
                        $("#provincia").prepend('<option value="default" selected>Seleccione Departamento</option>');
                        $("#provincia").attr("value", obj.provincia);
                        $.post('{action_llena_ciudad}', {
                            provincia: obj.provincia
                        }, function(data) {
                            $("#ciudad").removeAttr("disabled")
                            $("#ciudad").html(data);
                            $("#ciudad").prepend('<option value="default" selected>Seleccione Ciudad</option>');
                            $("#ciudad").attr("value", obj.ciudad);
                        });
                    });
                    $("#t_domicilio").attr("value", obj.t_domicilio);
                    $("#direccion").attr("value", obj.direccion);
                    $("#barrio").attr("value", obj.barrio);
                    $("#telefono").attr("value", obj.telefono);
                    $("#celular").attr("value", obj.celular);
                    $("#email").attr("value", obj.email);
                    $("#matricula").attr("value", obj.matricula);
                    $("#velocidad_ini").attr("value", obj.velocidad_ini);
                    $("#comprension_ini").attr("value", obj.comprension_ini);
                    $("#t_curso").attr("value", obj.t_curso);
                    $("#cant_clases").attr("value", obj.cant_clases);
                    $("#est_alumno").attr("value", obj.estado);
                    $("#observacion").attr("value", obj.observacion);
                    $("#div_warning").remove();
                } else {
                    $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
                    $("#div_warning").html(obj.mensaje);
                    $("#div_warning").delay(8000).fadeOut(1000);
                }
            });
        } else {
            $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
            $("#div_warning").html("<p><strong>Antes de consultar, ingrese el tipo y número de identificación del alumno.</strong></p>");
            $("#div_warning").delay(8000).fadeOut(1000);
        }
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
