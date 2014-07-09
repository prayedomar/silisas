<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear proveedor</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <form role="form" method="post" action="{action_crear}" id="formulario">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Tipo de Identificación<em class="required_asterisco">*</em></label>
                                <select name="dni" id="dni" class="form-control exit_caution">
                                    <option value="default">Seleccione T.I.</option>
                                    {dni}
                                    <option value="{id}">{tipo}</option>
                                    {/dni}
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Número de Identificación<em class="required_asterisco">*</em></label>
                                        <input name="id" id="id" type="text" class="form-control exit_caution numerico" placeholder="Número de Identificación" maxlength="13">
                                    </div>
                                </div>
                                <div class="col-xs-6"  id="div_dv" style="display:none;">
                                    <div class="form-group">
                                        <label>Dígito de Verificación</label>
                                        <input name="d_v" id="d_v" class="form-control exit_caution soloclick" size="1" maxlength="1" type="text" value="0" readonly="readonly">
                                    </div>    
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Razón Social<em class="required_asterisco">*</em></label>
                                <input name="razon_social" id="razon_social" type="text" class="form-control exit_caution letras_numeros" placeholder="Razón Social" maxlength="100">
                            </div>
                            <div class="form-group">
                                <label>País de domicilio<em class="required_asterisco">*</em></label>
                                <select name="pais" id="pais" class="form-control exit_caution">
                                    <option value="default">Seleccione País</option>
                                    {pais}
                                    <option value="{id}">{nombre}</option>
                                    {/pais}
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Departamento</label>
                                        <select name="provincia" id="provincia" class="form-control exit_caution" disabled>
                                            <option value="default">Seleccione primero País</option>
                                        </select>                                
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Ciudad</label>
                                        <select name="ciudad" id="ciudad" class="form-control exit_caution" disabled>
                                            <option value="default">Seleccione primero Depto</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Tipo de Domicilio</label>
                                <select name="t_domicilio" id="t_domicilio" class="form-control exit_caution">
                                    <option value="default">Seleccione País</option>
                                    {t_domicilio}
                                    <option value="{id}">{tipo}</option>
                                    {/t_domicilio}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Dirección</label>
                                <input name="direccion" id="direccion" type="text" class="form-control exit_caution alfanumerico" placeholder="Dirección" maxlength="80">
                            </div>
                            <div class="form-group">
                                <label>Telefonos</label>
                                <input name="telefono" id="telefono" type="text" class="form-control exit_caution alfanumerico" placeholder="Telefonos" maxlength="40">
                            </div>                    
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea name="observacion" id="observacion" class="form-control exit_caution" rows="4" maxlength="250" placeholder="Observación..."style="max-width:100%;"></textarea>
                            </div>
                            <div id="validacion_alert">
                            </div>                            
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                                    <button id="botonValidar" class="btn btn-success">Crear Proveedor</button> 
                                    <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                    <a href="<?= base_url() ?>"class="btn btn-danger" role="button"> Cancelar </a>
                                </center>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Llenamos el select de empleados-->
<script type="text/javascript">
    $(".form-group").delegate("#id", "blur", function() {
        var vpri, x, y, z, i, nit1, dv1;
        nit1 = $(this).val();
        if (isNaN(nit1))
        {
            $("#d_v").attr('value', 'Error de Nit');
        } else {
            vpri = new Array(16);
            x = 0;
            y = 0;
            z = nit1.length;
            vpri[1] = 3;
            vpri[2] = 7;
            vpri[3] = 13;
            vpri[4] = 17;
            vpri[5] = 19;
            vpri[6] = 23;
            vpri[7] = 29;
            vpri[8] = 37;
            vpri[9] = 41;
            vpri[10] = 43;
            vpri[11] = 47;
            vpri[12] = 53;
            vpri[13] = 59;
            vpri[14] = 67;
            vpri[15] = 71;
            for (i = 0; i < z; i++)
            {
                y = (nit1.substr(i, 1));
                //document.write(y+"x"+ vpri[z-i] +":");
                x += (y * vpri[z - i]);
                //document.write(x+"<br>");		
            }
            y = x % 11
            //document.write(y+"<br>");
            if (y > 1)
            {
                dv1 = 11 - y;
            } else {
                dv1 = y;
            }
            $("#d_v").attr('value', dv1);
        }
    });

    //Cargar div de d.v segun t_dni
    $(".form-group").delegate("#dni", "change", function() {
        dni = $('#dni').val();
        if (dni == '6') {
            $("#div_dv").css("display", "block");
        } else {
            $("#div_dv").css("display", "none");
        }
    });
    
    //PAra cargar los selects dinamicos de pais y departamento
    $("#pais").live("change", function() {
        pais = $('#pais').val();
        $.post('{action_llena_provincia}', {
            pais: pais
        }, function(data) {
            $("#provincia").removeAttr("disabled");
            $("#provincia").html(data);
            $("#provincia").prepend('<option value="default" selected>Seleccione Departamento</option>');
            //Con esto activamos automaticamente el evento click como si lo hicieramos nosotros.
            $("#provincia").change();
        });
    });
    $("#provincia").live("change", function() {
        provincia = $('#provincia').val();
        $.post('{action_llena_ciudad}', {
            provincia: provincia
        }, function(data) {
            $("#ciudad").removeAttr("disabled")
            $("#ciudad").html(data);
            $("#ciudad").prepend('<option value="default" selected>Seleccione Ciudad</option>');
        });
    });

</script>