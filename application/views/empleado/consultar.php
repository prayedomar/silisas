<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar salones <span class="help-block pull-right">(<?= $cantidad_empleados ?> empleados encontrados)</span></legend>
                <div id="divCriterios" class="row">
                    <div class="col-xs-2">
                        <label>Tipo de documento</label>
                        <input type='text' id="nombre" class='form-control letras_numeros' placeholder="Nombre" value="<?= isset($_GET["nombre"]) ? $_GET["nombre"] : "" ?>">
                    </div>
                    <div class="col-xs-2">
                        <label>Num. de documento</label>
                        <select id="sede" class="form-control">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($listaSedes as $row) { ?>
                                <option value="<?= $row->id ?>" <?= isset($_GET["sede"]) && $_GET["sede"] == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <label>Primer nombre</label>
                        <input type='text' id="nombre" class='form-control letras_numeros' placeholder="Nombre" value="<?= isset($_GET["nombre"]) ? $_GET["nombre"] : "" ?>">
                    </div>
                    <div class="col-xs-2">
                        <label>Segundo nombre</label>
                        <input type='text' id="nombre" class='form-control letras_numeros' placeholder="Nombre" value="<?= isset($_GET["nombre"]) ? $_GET["nombre"] : "" ?>">
                    </div>
                    <div class="col-xs-2">
                        <label>Primer apellido</label>
                        <input type='text' id="nombre" class='form-control letras_numeros' placeholder="Nombre" value="<?= isset($_GET["nombre"]) ? $_GET["nombre"] : "" ?>">
                    </div>
              <div class="col-xs-1">
                        <br>
                        <button id="searchBtn" class='btn btn-primary'> <span class="glyphicon glyphicon-search"></span></button>
                    </div>
                    <div class="col-xs-1">
                        <br>
                        <a class='btn btn-primary' href="<?= base_url() ?>empleado/consultar"> <span class="glyphicon glyphicon-refresh"></span></a>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-xs-2">
                        <label>Segundo apellido</label>
                        <input type='text' id="nombre" class='form-control letras_numeros' placeholder="Nombre" value="<?= isset($_GET["nombre"]) ? $_GET["nombre"] : "" ?>">
                    </div>
                    <div class="col-xs-2">
                        <label>Estado</label>
                        <input type='text' id="nombre" class='form-control letras_numeros' placeholder="Nombre" value="<?= isset($_GET["nombre"]) ? $_GET["nombre"] : "" ?>">
                    </div>
                    <div class="col-xs-2">
                        <label>Sede principal</label>
                        <input type='text' id="nombre" class='form-control letras_numeros' placeholder="Nombre" value="<?= isset($_GET["nombre"]) ? $_GET["nombre"] : "" ?>">
                    </div>
                    <div class="col-xs-2">
                        <label>Depto. empresarial</label>
                        <input type='text' id="nombre" class='form-control letras_numeros' placeholder="Nombre" value="<?= isset($_GET["nombre"]) ? $_GET["nombre"] : "" ?>">
                    </div>
                    <div class="col-xs-2">
                        <label>Cargo</label>
                        <input type='text' id="nombre" class='form-control letras_numeros' placeholder="Nombre" value="<?= isset($_GET["nombre"]) ? $_GET["nombre"] : "" ?>">
                    </div>

                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12">
                        <table class='table table-hover'>
                            <thead>
                                <tr>
                                    <th>Tipo documento</th>
                                    <th>Nombre</th>
                                    <th>Fecha de nacimiento</th>
                                    <th>Domicilio</th>
                                    <th>Teléfonos</th>
                                    <th>Email</th>
                                      <th>Sede</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lista_empleados as $row) { ?>
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
                    <div class="col-xs-12">
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
