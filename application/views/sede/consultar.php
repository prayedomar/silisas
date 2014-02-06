<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1 thumbnail">
            <div class="row">
                <legend>Consultar sedes <span class="help-block pull-right">(<?= $cantidadSedes ?> sedes encontradas)</span></legend>
                <div id="divCriterios" class="row">
                    <div class="col-xs-2">
                        <label> Nombre</label>
                        <input type='text' id="nombre" class='form-control letras_numeros' placeholder="Nombre" value="<?= isset($_GET["nombre"]) ? $_GET["nombre"] : "" ?>">
                    </div>
                    <div class="col-xs-2">
                        <label>Pais</label>
                        <select id="pais" class="form-control">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($paises as $row) { ?>
                                <option value="<?= $row->id ?>" <?= isset($_GET["pais"]) && $_GET["pais"] == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <label>Departamento </label>
                        <select id="departamento" class="form-control" <?= empty($_GET["departamento"]) && empty($_GET["pais"]) ? "disabled" : "" ?>>
                            <?php if (!empty($_GET["departamento"]) || !empty($_GET["pais"])) { ?>
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($departamentos as $row) {
                                    ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["departamento"]) && $_GET["departamento"] == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <label>Ciudad </label>
                        <select id="ciudad" class="form-control" <?= empty($_GET["ciudad"]) && empty($_GET["departamento"]) ? "disabled" : "" ?>>
                            <?php if (!empty($_GET["ciudad"]) || !empty($_GET["departamento"])) { ?>
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($ciudades as $row) {
                                    ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["ciudad"]) && $_GET["ciudad"] == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <label>Estado </label>
                        <select id="estado" class="form-control">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($estados as $row) { ?>
                                <option value="<?= $row->id ?>" <?= isset($_GET["estado"]) && $_GET["estado"] == $row->id ? "selected" : "" ?>><?= $row->estado ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-xs-1">
                        <br>
                        <button id="searchBtn" class='btn btn-primary'> <span class="glyphicon glyphicon-search"></span></button>
                    </div>
                    <div class="col-xs-1">
                        <br>
                        <a class='btn btn-primary' href="<?= base_url() ?>sede/consultar"> <span class="glyphicon glyphicon-refresh"></span></a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12">
                        <table class='table table-hover'>
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>País</th>
                                    <th>Departamento</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Prefijo transacción</th>
                                    <th>Estado</th>
                                    <th>Observación</th>
                                    <th>Fecha de cración</th>
                                    <th>Responsable</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lista_sedes as $row) { ?>
                                    <tr>
                                        <td><?= $row->nombre ?></td>
                                        <td><?= $row->pais ?></td>
                                        <td><?= $row->departamento ?></td>
                                        <td><?= $row->ciudad ?></td>
                                        <td><?= $row->tel1 . " - " . $row->tel2 ?></td>
                                        <td><?= $row->prefijo_trans ?></td>
                                        <td><?= $row->estado ?></td>
                                        <td><?= $row->observacion ?></td>
                                        <td><?= $row->fecha_trans ?></td>
                                        <td><?= $row->nombre1 . " " . $row->nombre2 . " " . $row->apellido1 . " " . $row->apellido2; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div id="paginacion" class=" pull-right">
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
