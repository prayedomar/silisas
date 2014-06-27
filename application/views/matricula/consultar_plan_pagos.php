<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <form role="form" method="post" action="{action_crear}" id="formulario">
                <div class="row">
                    <legend>Consultar cronograma de pagos de matrícula</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-3">  
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-2">
                                    <label>Número de Matrícula<em class="required_asterisco">*</em></label>
                                    <input name="id" id="id" type="text" class="form-control numerico" placeholder="Número de Contrato Físico" maxlength="13" <?php if (isset($id)) { ?> value="<?php echo $id ?>" <?php } ?>>
                                </div>
                            </div><br>
                            <div id="validacion_inicial">
                                <?php if ($error_consulta != "") { ?>
                                <div class="alert alert-warning" id="div_warning"><p><strong><center><?php echo $error_consulta ?></center></strong></p></div>
                                <?php } ?>
                            </div>                            
                            <div class="row text-center separar_submit">
                                <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Consultar </button>
                                <a href='{action_recargar}' class="btn btn-default" role="button"><span class="glyphicon glyphicon-user"></span> Modificar matrícula </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>