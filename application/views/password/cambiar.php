<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Cambiar contraseña de ingreso al sistema</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-4 col-xs-offset-4">
                        <form role="form" method="post" action="{action_crear}" id="formulario">
                            <div class="form-group">
                                <label>Contraseña actual<em class="required_asterisco">*</em></label>
                                <input name="password_old" id="password_old" class="form-control exit_caution" type="password" maxlength="30" placeholder="Contraseña" required>
                            </div>
                            <div class="form-group">
                                <label>Nueva contraseña<em class="required_asterisco">*</em></label>
                                <input name="password_new_1" id="password_new_1" class="form-control exit_caution" type="password" maxlength="30" placeholder="Contraseña" required>
                            </div>
                            <div class="form-group">
                                <label>Confirmar nueva contraseña<em class="required_asterisco">*</em></label>
                                <input name="password_new_2" id="password_new_2" class="form-control exit_caution" type="password" maxlength="30" placeholder="Contraseña" required onpaste="return false">
                            </div>                            
                            <div id="validacion_alert">
                            </div>
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                                    <button id="botonValidar" class="btn btn-success">Cambiar contraseña</button> 
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