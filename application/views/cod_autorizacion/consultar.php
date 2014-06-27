<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar códigos de autorización <span class="help-block pull-right">(<?= $cantidadCodigos ?> códigos encontrados)</span></legend>
                <div id="divCriterios" class="row">
                    <div class="col-xs-2">
                        <center><label>Cód. autorizacion</label></center>
                        <input type='text' id="id" class='form-control numerico' placeholder="Cód. autorización" maxlength="13" value="<?= isset($_GET["id"]) ? $_GET["id"] : "" ?>">
                    </div>
                    <div class="col-xs-4">
                        <center><label>Tipo de permiso a autorizar</label></center>
                        <select id="tabla_autorizada" class="form-control">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($tabla_autorizacion as $row) { ?>
                                <option value="<?= $row->id ?>" <?= isset($_GET["tabla_autorizada"]) && $_GET["tabla_autorizada"] == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <center><label>ID Autorizado</label></center>
                        <input type='text' id="id_empleado_autorizado" class='form-control numerico' maxlength="13" placeholder="Empleado autorizado" value="<?= isset($_GET["id_empleado_autorizado"]) ? $_GET["id_empleado_autorizado"] : "" ?>">
                    </div>
                    <div class="col-xs-2">
                        <center><label>ID Responsable</label></center>
                        <input type='text' id="id_responsable" class='form-control numerico' maxlength="13" placeholder="Empleado responsable" value="<?= isset($_GET["id_responsable"]) ? $_GET["id_responsable"] : "" ?>">
                    </div>
                    <div class="col-xs-2">
                        <label>Pendiente </label>
                        <select id="vigente" class="form-control">
                            <option value="">Seleccionar...</option>
                            <option value="1" <?= isset($_GET["vigente"]) && $_GET["vigente"] == 1 ? "selected" : "" ?>>Pendiente</option>
                            <option value="0" <?= isset($_GET["vigente"]) && $_GET["vigente"] == 0 ? "selected" : "" ?>>No pendiente</option>
                        </select>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-xs-1 col-xs-offset-5">
                        <center>
                            <br>
                            <button id="searchBtn" class='btn btn-primary'> <span class="glyphicon glyphicon-search"></span></button>
                        </center>
                    </div>
                    <div class="col-xs-1">
                        <br>
                        <a class='btn btn-primary' href="<?= base_url() ?>cod_autorizacion/consultar"> <span class="glyphicon glyphicon-refresh"></span></a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12">
                        <table class='table table-hover'>
                            <thead>
                                <tr>
                                    <th><center>Id</center></th>
                            <th><center>Empleado autorizado</center></th>
                            <th><center>Tipo de permiso</center></th>
                            <th><center>Registro</center></th>
                            <th><center>Observación</center></th>
                            <th><center>Responsable</center></th>
                            <th><center>Pendiente</center></th>
                            <th><center>Fecha de creación</center></th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listaCodigos as $row) { ?>
                                    <tr>
                                        <td><?= $row->id ?></td>
                                        <td><?= $row->autorizado ?></td>
                                        <td><?= $row->nombre_permiso ?></td>
                                        <td><center><?= $row->registro_autorizado ?></center></td>
                                <td><?= $row->observacion ?></td>
                                <td><?= $row->responsable ?></td>
                                <td><center><?= $row->vigente == 1 ? "Pendiente" : "No pendiente" ?></center></td>                                        
                                <td><center><?= $row->fecha_trans ?></center></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div id="paginacion" class=" pull-right"
                             data-id="<?= isset($_GET["id"]) ? $_GET["id"] : "" ?>"
                             data-tabla_autorizada="<?= isset($_GET["tabla_autorizada"]) ? $_GET["tabla_autorizada"] : "" ?>"
                             data-id_empleado_autorizado="<?= isset($_GET["id_empleado_autorizado"]) ? $_GET["id_empleado_autorizado"] : "" ?>"
                             data-id_responsable="<?= isset($_GET["id_responsable"]) ? $_GET["id_responsable"] : "" ?>"
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
