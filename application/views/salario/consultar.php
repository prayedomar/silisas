<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar salarios <span class="help-block pull-right">(<?= $cantidad_salarios ?> salarios encontrados)</span></legend>
                <div id="divCriterios" class="row">
                    <div class="col-xs-2 col-xs-offset-2">
                        <label> Nombre</label>
                        <input type='text' id="nombre" class='form-control letras_numeros' placeholder="Nombre" value="<?= isset($_GET["nombre"]) ? $_GET["nombre"] : "" ?>">
                    </div>
                    <div class="col-xs-2">
                        <label>Tipo de salario</label>
                        <select id="tipo_salario" class="form-control">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($tipos_salarios as $row) { ?>
                                <option value="<?= $row->id ?>" <?= isset($_GET["tipo_salario"]) && $_GET["tipo_salario"] == $row->id ? "selected" : "" ?>><?= $row->tipo ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <label>Vigente </label>
                        <select id="vigente" class="form-control">
                            <option value="">Seleccionar...</option>
                            <option value="1" <?= isset($_GET["vigente"]) && $_GET["vigente"] == 1 ? "selected" : "" ?>>Vigente</option>
                            <option value="0" <?= isset($_GET["vigente"]) && $_GET["vigente"] == 0 ? "selected" : "" ?>>No vigente</option>
                        </select>
                    </div>
                    <div class="col-xs-1">
                        <br>
                        <button id="searchBtn" class='btn btn-primary'> <span class="glyphicon glyphicon-search"></span></button>
                    </div>
                    <div class="col-xs-1">
                        <br>
                        <a class='btn btn-primary' href="<?= base_url() ?>salario/consultar"> <span class="glyphicon glyphicon-refresh"></span></a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12">
                        <table class='table table-hover'>
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Tipo de salario</th>
                                    <th>Vigente</th>
                                    <th>Onservación</th>
                                    <th>Fecha de creación</th>
                                    <th>Detalles</th>
                                </tr>
                            </thead>
                            <tbody id="bodyTabla">
                                <?php foreach ($lista_salarios as $row) { ?>
                                    <tr>
                                        <td><?= $row->nombre ?></td>
                                        <td><?= $row->t_salario ?></td>
                                        <td><?= $row->vigente == 1 ? "Vigente" : "No vigente" ?></td>
                                        <td><?= $row->observacion ?></td>
                                        <td><?= $row->fecha_trans ?></td>
                                        <td><button class="btn btn-primary btn-sm" data-idsalario="<?= $row->id_salario ?>">Ver detalles</button></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div id="paginacion" class=" pull-right"
                             data-nombre="<?= isset($_GET["nombre"]) ? $_GET["nombre"] : "" ?>"
                             data-tipo_salario="<?= isset($_GET["tipo_salario"]) ? $_GET["tipo_salario"] : "" ?>"
                             data-vigente="<?= isset($_GET["vigente"]) ? $_GET["vigente"] : "" ?>">
                            <ul class="pagination">
                                <li class="<?= $paginaActiva == 1 ? "active" : "noActive"; ?>">
                                    <a data-page="1">1</a></li>
                                <?php for ($i = 2; $i <= $cantidadPaginas; $i++) { ?>
                                    <li class="<?= $paginaActiva == $i ? "active" : "noActive" ?>">
                                        <a data-page="<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content modal-content-minimo">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Detalles</h3>
            </div>
            <div id="bodyModalDetalles" class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>