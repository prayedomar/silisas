<!-- Modal -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Transacción Exitosa</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success">
                    <p>La transacción se ha ejecutado correctamente en la base de datos.</p>
                </div>
            </div>
            <div class="modal-footer">
                <center>
                    <a href="<?= base_url() ?>" class="btn btn-default" role="button"><span class="glyphicon glyphicon-ok"></span> Aceptar</a>
                    <a href="{url_imprimir}" class="btn btn-default" role="button" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir comprobante</a>                
                    <a href="{url_recrear}" class="btn btn-default" role="button"><span class="glyphicon glyphicon-repeat"></span> {msn_recrear}</a>
                </center>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--Cargar el modal-->
<script type="text/javascript" id="js">
    $(function() {
        $("#myModal").modal({
            show: true,
            keyboard: false,
            backdrop: 'static'
        });
    });
</script>