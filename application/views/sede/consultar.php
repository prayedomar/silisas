<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
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
                                    <th>Dirección</th>
                                    <th>Prefijo transacción</th>
                                    <th>Estado</th>
                                    <?php if ($_SESSION["perfil"] == "admon_sistema" || $_SESSION["perfil"] == "directivo") { ?>
                                        <th>Acciones</th>
                                    <?php } ?>
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
                                        <td><?= $row->direccion ?></td>
                                        <td><?= $row->prefijo_trans ?></td>
                                        <td><?= $row->estado ?></td>
                                        <?php if ($_SESSION["perfil"] == "admon_sistema" || $_SESSION["perfil"] == "directivo") { ?>
                                            <td><button class="editar btn btn-success btn-sm"
                                                        data-id="<?= $row->id ?>"
                                                        data-nombre="<?= $row->nombre ?>"
                                                        data-pais="<?= $row->id_pais ?>"
                                                        data-departamento="<?= $row->id_departamento ?>"
                                                        data-ciudad="<?= $row->id_ciudad ?>"
                                                        data-estado="<?= $row->id_estado ?>"
                                                        data-direccion="<?= $row->direccion ?>"
                                                        data-tel1="<?= $row->tel1 ?>"
                                                        data-tel2="<?= $row->tel2 ?>"
                                                        data-prefijo_trans="<?= $row->prefijo_trans ?>"
                                                        data-observacion="<?= $row->observacion ?>"
                                                        >Editar</button></td>
                                            <?php } ?>
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
<div class="modal" id="modal-editar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content modal-content-minimo">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Editar sede</h3>
            </div>
            <form role="form" method="post" action="actualizar" id="formulario">
                <div  class="modal-body">
                    <div class="row">

                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="required">Nombre de la Sede<em class="required_asterisco">*</em></label>
                                <input name="nombre" id="nombre-modal" type="text" class="form-control letras_numeros" placeholder="Nombre de la Sede" maxlength="40" autofocus="autofocus">
                            </div>
                            <div class="form-group">
                                <label>País de domicilio<em class="required_asterisco">*</em></label>
                                <select name="pais" id="pais-modal" class="form-control">
                                    <option value="default">Seleccione País</option>
                                    <?php foreach ($pais as $row) { ?>
                                        <option value="<?= $row->id ?>"><?= $row->nombre ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Departamento de domiclio<em class="required_asterisco">*</em></label>
                                <select name="provincia" id="provincia-modal" class="form-control" disabled>
                                    <option value="default">Seleccione primero País</option>
                                </select>                                
                            </div>
                            <div class="form-group">
                                <label>Ciudad de domiclio<em class="required_asterisco">*</em></label>
                                <select name="ciudad" id="ciudad-modal" class="form-control" disabled>
                                    <option value="default">Seleccione primero Depto</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Estado<em class="required_asterisco">*</em></label>
                                <select name="estado" id="estado-modal" class="form-control">
                                    <option value="default">Seleccione Estado</option>
                                    <?php foreach ($est_sede as $row) { ?>
                                        <option value="<?= $row->id ?>"><?= $row->estado ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Dirección<em class="required_asterisco">*</em></label>
                                <input name="direccion" id="direccion-modal" type="text" class="form-control alfanumerico" placeholder="Dirección de la Sede" maxlength="80">
                            </div>                            

                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Telefono 1</label>
                                <input name="tel1" id="tel1-modal" type="text" class="form-control alfanumerico" placeholder="Telefono 1" maxlength="40">
                            </div>
                            <div class="form-group">
                                <label>Telefono 2</label>
                                <input name="tel2" id="tel2-modal" type="text" class="form-control alfanumerico" placeholder="Telefono 2" maxlength="40">
                            </div>
                            <div class="form-group">
                                <label class="required">Prefijo para Transacciones<em class="required_asterisco">*</em></label>
                                <p class="help-block"><B>> </B>Prefijo de 4 letras para Facturas, Recibos de Caja, etc.</p>                                                                
                                <input name="prefijo_trans" id="prefijo_trans-modal" type="text" class="form-control alfabeto" placeholder="Prefijo para Transacciones" maxlength="4" autofocus="autofocus" readonly>
                            </div>                            
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea name="observacion" id="observacion-modal" class="form-control alfanumerico" rows="4" maxlength="250" placeholder="Observación..."  style="max-width:100%;"></textarea>
                            </div>
                            <div class="form-group separar_submit">
                                <input type="hidden" name="id_sede" id="id_sede" value=""/>
                            </div>

                        </div>

                    </div>
                    <div class="row">
                        <div id="validacion_alert">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                    <button id="botonValidarSede" class="btn btn-success">Actualizar Sede</button>                                 
                    <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>