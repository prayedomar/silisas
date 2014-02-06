<div class="contenidoperm">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 thumbnail">
            <div class="row">
                <legend>Consultar salones <span class="help-block pull-right">(<?= $cantidadSalones ?> salones encontradas)</span></legend>
                <div id="divCriterios" class="row">
                    <div class="col-md-2 col-md-offset-2">
                        <label> Nombre</label>
                        <input type='text' id="nombre" class='form-control letras_numeros' placeholder="Nombre" value="<?= isset($_GET["nombre"]) ? $_GET["nombre"] : "" ?>">
                    </div>
                    <div class="col-md-2">
                        <label>Sede</label>
                        <select id="sede" class="form-control">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($listaSedes as $row) { ?>
                                <option value="<?= $row->id ?>" <?= isset($_GET["sede"]) && $_GET["sede"] == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Vigente </label>
                        <select id="vigente" class="form-control">
                            <option value="">Seleccionar...</option>
                            <option value="1" <?= isset($_GET["vigente"]) && $_GET["vigente"] == 1 ? "selected" : "" ?>>Vigente</option>
                            <option value="0" <?= isset($_GET["vigente"]) && $_GET["vigente"] == 0 ? "selected" : "" ?>>No vigente</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <br>
                        <button id="searchBtn" class='btn btn-primary'> <span class="glyphicon glyphicon-search"></span></button>
                    </div>
                    <div class="col-md-1">
                        <br>
                        <a class='btn btn-primary' href="<?= base_url() ?>salon/consultar"> <span class="glyphicon glyphicon-refresh"></span></a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <table class='table table-hover'>
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Capacidad</th>
                                    <th>Sede</th>
                                    <th>Vigente</th>
                                    <th>Observación</th>
                                    <th>Fecha de creación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listaSalones as $row) { ?>
                                    <tr>
                                        <td><?= $row->nombre ?></td>
                                        <td><?= $row->capacidad ?></td>
                                        <td><?= $row->sede ?></td>
                                        <td><?= $row->vigente == 1 ? "Vigente" : "No vigente" ?></td>
                                        <td><?= $row->observacion ?></td>
                                        <td><?= $row->fecha_trans ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="paginacion" class=" pull-right"
                             data-nombre="<?= isset($_GET["nombre"]) ? $_GET["nombre"] : "" ?>"
                             data-sede="<?= isset($_GET["sede"]) ? $_GET["sede"] : "" ?>"
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
