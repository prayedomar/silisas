<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <form role="form" method="post" action="{action_crear}" id="formulario">
                <div class="row">
                    <legend>Consultar adelanto de nómina</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                    <div class="row">
                        <div class="col-xs-4 col-xs-offset-4">
                            <legend>Consecutivo del adelanto</legend>
                            <label><B>> </B>Prefijo + Espacio + Consecutivo: (FLST 3765)</label>    
                            <div class="form-group">
                                <input name="prefijo_id_adelanto" id="prefijo_id_adelanto" type="text" class="form-control letras_numeros" placeholder="Código del adelanto de nómina" maxlength="18" onkeyup="aMays(event, this)" onblur="aMays(event, this)" autofocus="autofocus">
                            </div>
                            <div id="validacion_inicial">
                                <?php if (isset($error_consulta)) { ?>
                                    <div class="alert alert-warning" id="div_warning"><p><strong><?php echo $error_consulta?></strong></p></div>
                                <?php } ?>
                            </div>                            
                            <div class="row text-center separar_submit">
                                <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Consultar </button>
                                <a href='{action_recargar}' class="btn btn-default" role="button"><span class="glyphicon glyphicon-user"></span> Modificar Adelanto de Nómina </a>
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
    });

    //Convertir a mayuscula
    function aMays(e, elemento) {
        tecla = (document.all) ? e.keyCode : e.which;
        elemento.value = elemento.value.toUpperCase();
    }

</script>