<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar transferencias pendientes por aprobar</legend>
                <div class="row">
                    <div class="col-xs-12">
                        <div id="html_transferencias">
                            <?= $html_transferencias ?>
                        </div>     
                        <div class="row separar_submit">
                            <div class="col-xs-6 col-xs-offset-3">
                                <center>
                                    <a href="<?= base_url() ?>"class="btn btn-info" role="button"> Volver a la pagina principal </a>
                                </center>
                            </div>
                        </div>                             
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Modal eliminar peticion de transferencias-->
<div class="modal" id="modal_anular_peticion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Anular petición de transferencias</h3>
            </div>
            <div class="modal-body">
                <center><p>¿Está seguro que desea anular la petición de ésta transferencias?</p></center>
                <input type="hidden" name="prefijo_id_transferencia" id="prefijo_id_transferencia"/>
                <div id="alert_modal_2">
                </div>
            </div>
            <div class="modal-footer">
                <center>
                    <button id="anular_peticion" class="btn btn-success">Desautorizar Empleado</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="cerrar_modal_2">Cerrar</button>
                </center>
            </div>
        </div>
    </div>
</div>
<!--Llenamos en la tabla las solicitudes de placas pendientes-->
<script type="text/javascript">
    $('.btn_anular_peticion').live('click', function() {
        $("#alert_modal_2").removeAttr('class');
        $("#alert_modal_2  > *").remove();
        $("#prefijo_id_transferencia").attr('value', $(this).attr('id'));
        $("#modal_anular_peticion").modal('show');
    });
    //Anular sede Secundaria
    $('#anular_peticion').live('click', function() {
        var transferencia = $('#prefijo_id_transferencia').val();
        $.post('{action_anular_peticion}', {
            transferencia: transferencia
        }, function(data) {
            var obj = JSON.parse(data);
            if (obj.respuesta == "OK")
            {
                $("#cerrar_modal_2").click();
                //recargamos la pagina
                location.reload();
            } else {
                $("#alert_modal_2").html('<div class="alert alert-warning" id="div_warning"></div>');
                $("#div_warning").html(obj.mensaje);
                $("#div_warning").delay(8000).fadeOut(1000);
            }
        });
    });
</script>