<!-- Modal -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Transacción Exitosa</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success">
                    <p>La transacción se ha ejecutado correctamente en la base de datos.</p>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{base_url}" class="btn btn-success" role="button">Aceptar</a>
                <a href="{url_recrear}" class="btn btn-primary" role="button">{msn_recrear}</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--Cargar el modal-->
<script type="text/javascript" id="js">
    $(document).ready(function() {
        $("#myModal").modal('show')
    });
</script>