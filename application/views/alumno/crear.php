<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear alumno</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <form role="form" method="post" action="{action_crear}" id="formulario">
                        <div class="col-xs-6 col-xs-offset-3">
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
                                        <label>Género<em class="required_asterisco">*</em></label>
                                        <select name="genero" id="genero" class="form-control exit_caution">
                                            <option value="default">Seleccione Género</option>
                                            <option value="F">Mujer</option>
                                            <option value="M">Hombre</option>
                                        </select>
                                    </div> 
                                </div>
                                <div class="col-xs-6">                                    
                                    <div class="form-group">
                                        <label>Número de Matrícula<em class="required_asterisco">*</em></label>
                                        <input name="matricula" id="matricula" type="text" class="form-control exit_caution numerico" placeholder="Número de Matrícula" maxlength="13">
                                    </div>  
                                </div>
                            </div>                                    
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea name="observacion" id="observacion" class="form-control exit_caution" rows="4" maxlength="255" placeholder="Observación..."style="max-width:100%;"></textarea>
                            </div>
                            <div id="validacion_alert">
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
