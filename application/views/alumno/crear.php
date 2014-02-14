<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear alumno</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <form role="form" method="post" action="{action_crear}" id="formulario">
                        <div class="col-xs-6">
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
                                        <input name="nombre2" id="nombre2" type="text" class="form-control exit_caution alfabeto" placeholder="Segundo Nombre" maxlength="30">
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
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
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
                                <label>País<em class="required_asterisco">*</em></label>
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
                                        <label>Departamento<em class="required_asterisco">*</em></label>
                                        <select name="provincia" id="provincia" class="form-control exit_caution" disabled>
                                            <option value="default">Seleccione primero País</option>
                                        </select>                                
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Ciudad<em class="required_asterisco">*</em></label>
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
                                <label>Barrio/Sector<em class="required_asterisco">*</em></label>
                                <input name="barrio" id="barrio" type="text" class="form-control exit_caution letras_numeros" placeholder="Barrio o Sector" maxlength="40">
                            </div>
                            <div id="validacion_alert">
                            </div>                             
                        </div>
                        <div class="col-xs-6"> 
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Telefonos<em class="required_asterisco">*</em></label>
                                        <input name="telefono" id="telefono" type="text" class="form-control exit_caution alfanumerico" placeholder="Telefonos" maxlength="40">
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
                                <input name="matricula" id="matricula" type="text" class="form-control exit_caution numerico" placeholder="Número de Matrícula" maxlength="13">
                            </div>
                            <div class="form-group">
                                <label>Velocidad Inicial<em class="required_asterisco">*</em></label>                                
                                <div class="input-group">
                                    <span class="input-group-addon">p.p.m</span>
                                    <input type="text" name="velocidad_ini" id="velocidad_ini" class="form-control numerico miles" placeholder="0" maxlength="5">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Comprensión Inicial<em class="required_asterisco">*</em></label>                                
                                <div class="input-group">
                                    <span class="input-group-addon">%</span>
                                    <input type="text" name="comprension_ini" id="comprension_ini" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="5">
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
                                <label>Observación</label>
                                <textarea name="observacion" id="observacion" class="form-control exit_caution" rows="4" maxlength="255" placeholder="Observación..."style="max-width:100%;"></textarea>
                            </div>
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                                    <button id="botonValidar" class="btn btn-success">Crear Alumno</button> 
                                    <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                    <a href="{base_url}" class="btn btn-danger" role="button"> Cancelar </a>
                                </center>
                            </div>                           
                        </div>
                    </form>
                </div>
            </div>
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
    
    
</script>
