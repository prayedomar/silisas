<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <div class="col-xs-3">
                    <img src="<?= base_url() ?>images/huevo.png" class="img-responsive">
                </div>
                <div class="col-xs-9">
                    <div class="row">
                        <h1><?= $_SESSION["msnBienvenida"] ?></h1>
                        <p><?= $_SESSION["textoBienvenida"] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Manual del Usuario</h4>
            </div>
            <div class="modal-body">
                <p>
                    El manual de usuario se encuentra en construcción.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal"> Aceptar </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->