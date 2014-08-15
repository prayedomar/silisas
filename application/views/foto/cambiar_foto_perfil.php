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
                <img src="<?= $_SESSION["rutaImg"] ?>" id="target">
                <div id="subir_foto">
                    <?= form_open_multipart('foto/subir_foto'); ?><br>
                    <label>X1 <input type="text" size="4" id="x1" name="x1" /></label>
                    <label>Y1 <input type="text" size="4" id="y1" name="y1" /></label>
                    <label>X2 <input type="text" size="4" id="x2" name="x2" /></label>
                    <label>Y2 <input type="text" size="4" id="y2" name="y2" /></label>
                    <label>W <input type="text" size="4" id="w" name="w" /></label>
                    <label>H <input type="text" size="4" id="h" name="h" /></label>
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
    // Simple event handler, called from onChange and onSelect
    // event handlers, as per the Jcrop invocation above
    function showCoords(c)
    {
        $('#x1').val(c.x);
        $('#y1').val(c.y);
        $('#x2').val(c.x2);
        $('#y2').val(c.y2);
        $('#w').val(c.w);
        $('#h').val(c.h);
    };
    function clearCoords()
    {
        $('#x1').val('');
        $('#y1').val('');
        $('#x2').val('');
        $('#y2').val('');
        $('#w').val('');
        $('#h').val('');
    };    
    jQuery(function($) {
        var jcrop_api;
        $('#target').Jcrop({
            onChange: showCoords,
            onSelect: showCoords,
            onRelease: clearCoords
        }, function() {
            jcrop_api = this;
        });
        $('#coords').on('change', 'input', function(e) {
            var x1 = $('#x1').val(),
                    x2 = $('#x2').val(),
                    y1 = $('#y1').val(),
                    y2 = $('#y2').val();
            jcrop_api.setSelect([x1, y1, x2, y2]);
        });
    });



    //Cargar div de valor abono y cuotas  de matricula escogida     
    $("#link_subir_foto").live("click", function() {
        $("#foto_perfil").click();
    });
    //Cargamos la vista previa de la carga    
    $("#foto_perfil").live("change", function() {
        alert("cargamos la vista previa de la imgane para recortarla");
    });
</script>