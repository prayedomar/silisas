<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <form role="form" method="post" action="{action_crear}" id="formulario">
                <div class="row">
                    <legend>Consultar egreso</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-3">
                            <legend>Consecutivo del egreso</legend>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Prefijo de sede<em class="required_asterisco">*</em></label>
                                        <select name="prefijo" id="prefijo" class="form-control" value="flst">
                                            <option value="default">Seleccione prefijo</option>
                                            {sede}
                                            <option value="{prefijo_trans}">{prefijo_trans}</option>
                                            {/sede}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Número o consecutivo<em class="required_asterisco">*</em></label>
                                        <input name="id" id="id" type="text" class="form-control numerico" placeholder="Número o consecutivo" maxlength="13" <?php if (isset($id)) { ?> value="<?php echo $id ?>" <?php } ?>>
                                    </div>
                                </div>
                            </div>
                            <div id="validacion_inicial">
                                <?php if ($error_consulta != "") { ?>
                                    <div class="alert alert-warning" id="div_warning"><p><strong><?php echo $error_consulta?></strong></p></div>
                                <?php } ?>
                            </div>                            
                            <div class="row text-center separar_submit">
                                <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Consultar </button>
                                <a href='{action_recargar}' class="btn btn-default" role="button"><span class="glyphicon glyphicon-user"></span> Modificar egreso </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#div_warning").delay(4000).fadeOut(1000);
        $("#prefijo").val('{prefijo}');
    });

    //Convertir a mayuscula
    function aMays(e, elemento) {
        tecla = (document.all) ? e.keyCode : e.which;
        elemento.value = elemento.value.toUpperCase();
    }

</script>