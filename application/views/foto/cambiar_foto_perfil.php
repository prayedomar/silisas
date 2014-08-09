<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Cambiar foto de perfil</legend>
                <div class="row" style="margin-top:80px;">
                    <div class="col-xs-5 col-xs-offset-1">
                        <button id="link_subir_foto" class="thumbnail" style="text-decoration:none;">
                            <h1><span class="glyphicon glyphicon-arrow-up"></span> Subir foto de perfil</h1>
                        </button>
                    </div>
                    <div class="col-xs-5">
                        <button class="thumbnail" style="text-decoration:none;">
                            <h1><span class="glyphicon glyphicon-camera"></span> Tomar foto de perfil</h1>
                        </button>
                    </div>
                </div> 
                <div id="subir_foto">
                    <?= form_open_multipart('foto/subir_foto'); ?>
                    <div class="row">
                        <input type="file" name="foto_perfil" id="foto_perfil" size="20" class="form-control" style="display:none"  placeholder="Seleccione foto de perfil"/>                 
                        <div class="form-group separar_submit">
                            <center>
                                <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success">Subir foto</button>
                                <a href="<?= base_url() ?>"class="btn btn-danger" role="button"> Cancelar </a>
                            </center>
                        </div> 
                        <div id="validacion_alert">
                        </div>  
                    </div> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //Cargar div de valor abono y cuotas  de matricula escogida     
    $("#link_subir_foto").live("click", function() {
        $("#foto_perfil").click();
    });
    //Cargamos la vista previa de la carga    
    $("#foto_perfil").live("change", function() {
        alert("cargamos la vista previa de la imgane para recortarla");
    });
</script>