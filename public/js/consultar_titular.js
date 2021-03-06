$(function() {

    $("#searchBtn").click(function() {
        $("#coverDisplay").css({
            "opacity": "1",
            "width": "100%",
            "height": "100%"
        });
        var url = "consultar?";
        if ($("#tipo_documento").val() != "") {
            url += "tipo_documento=" + $("#tipo_documento").val() + "&";
        }
        if ($("#numero_documento").val() != "") {
            url += "numero_documento=" + $("#numero_documento").val() + "&";
        }
        if ($("#primer_nombre").val() != "") {
            url += "primer_nombre=" + $("#primer_nombre").val() + "&";
        }
        if ($("#segundo_nombre").val() != "") {
            url += "segundo_nombre=" + $("#segundo_nombre").val() + "&";
        }
        if ($("#primer_apellido").val() != "") {
            url += "primer_apellido=" + $("#primer_apellido").val() + "&";
        }
        if ($("#segundo_apellido").val() != "") {
            url += "segundo_apellido=" + $("#segundo_apellido").val() + "&";
        }
        if ($("#fecha_nacimiento").val() != "") {
            url += "fecha_nacimiento=" + $("#fecha_nacimiento").val() + "&";
        }
        if ($("#fecha_nacimiento_hasta").val() != "") {
            url += "fecha_nacimiento_hasta=" + $("#fecha_nacimiento_hasta").val() + "&";
        }
        if ($("#vigente").val() != null && $("#vigente").val() != "") {
            url += "vigente=" + $("#vigente").val() + "&";
        }

        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });


    $("#divCriterios .form-control").keypress(function(e) {
        if (e.which == 13) {
            $("#searchBtn").trigger("click");
        }
    });
    $("#depto").change(function() {
        if ($(this).val() == "") {
            $("#cargo").html("").prop("disabled", true);
        } else {
            $.ajax({
                url: "listar_cargos",
                data: {
                    idDepto: $(this).val(),
                },
                type: 'GET',
                success: function(data) {
                    if (data != "[]") {
                        data = JSON.parse(data);
                        var str = "<option value=''>Seleccionar...</option>";
                        $.each(data, function(k, l) {
                            str += "<option value='" + l.id + "'>" + l.cargo_masculino + "</option>";
                        });
                        $("#cargo").html(str).prop("disabled", false)
                    }
                }
            });
        }
    });

    $("#bodyTabla .editar").click(function() {
        document.getElementById("formularioEditarTitular").reset();
        $("#dni_old-modal").val($(this).data("dni"));
        $("#id_old-modal").val($(this).data("id"));
        $("#dni-modal").val($(this).data("dni"));
        $("#id-modal").val($(this).data("id"));
        $("#nombre1-modal").val($(this).data("nombre1"));
        $("#nombre2-modal").val($(this).data("nombre2"));
        $("#apellido1-modal").val($(this).data("apellido1"));
        $("#apellido2-modal").val($(this).data("apellido2"));
        $("#fecha_nacimiento-modal").val($(this).data("fecha_nacimiento"));
        $("#genero_titular-modal").val($(this).data("genero"));

        var id_pais = $(this).data("id_pais");
        var id_departamento = $(this).data("id_departamento");
        var id_ciudad = $(this).data("id_ciudad");
        $("#pais-modal").val(id_pais);

        $.post('llena_provincia', {
            pais: id_pais
        }, function(data) {
            $("#provincia-modal").removeAttr("disabled");
            $("#provincia-modal").html(data);
            $("#provincia-modal").prepend('<option value="default" selected>Seleccione Departamento</option>');
            //Con esto activamos automaticamente el evento click como si lo hicieramos nosotros.
            $("#provincia-modal").val(id_departamento);
        });
        $.post('llena_ciudad', {
            provincia: id_departamento
        }, function(data) {
            $("#ciudad-modal").removeAttr("disabled")
            $("#ciudad-modal").html(data);
            $("#ciudad-modal").prepend('<option value="default" selected>Seleccione Ciudad</option>');
            $("#ciudad-modal").val(id_ciudad);
        });
        $("#t_domicilio-modal").val($(this).data("t_domicilio"));
        $("#direccion-modal").val($(this).data("direccion"));
        $("#barrio-modal").val($(this).data("barrio"));
        $("#telefono-modal").val($(this).data("telefono"));
        $("#celular-modal").val($(this).data("celular"));
        $("#email-modal").val($(this).data("email"));
        $("#observacion-modal").val($(this).data("observacion"));
//        $("#modal-editar-titular").modal("show");
        $("#validacion_alert").html("");
        $('#modal-editar-titular').modal({
            show: true,
            keyboard: false,
            backdrop: 'static'
        });
    });
    $("#paginacion li.noActive a").click(function() {
        $("#coverDisplay").css({
            "opacity": "1",
            "width": "100%",
            "height": "100%"
        });

        if ($(this).data("page") == 1) {
            url = "consultar?";
        } else {
            var url = "consultar?page=" + $(this).data("page") + "&";
        }
        if ($("#paginacion").data("tipodocumento") != "") {
            url += "tipo_documento=" + $("#paginacion").data("tipodocumento") + "&";
        }
        if ($("#paginacion").data("numerodocumento") != "") {
            url += "numero_documento=" + $("#paginacion").data("numerodocumento") + "&";
        }
        if ($("#paginacion").data("primernombre") != "") {
            url += "primer_nombre=" + $("#paginacion").data("primernombre") + "&";
        }
        if ($("#paginacion").data("segundonombre") != "") {
            url += "segundo_nombre=" + $("#paginacion").data("segundonombre") + "&";
        }
        if ($("#paginacion").data("primerapellido") != "") {
            url += "primer_apellido=" + $("#paginacion").data("primerapellido") + "&";
        }
        if ($("#paginacion").data("segundoapellido") != "") {
            url += "segundo_apellido=" + $("#paginacion").data("segundoapellido") + "&";
        }
        if ($("#paginacion").data("fechanacimiento") != "") {
            url += "fecha_nacimiento=" + $("#paginacion").data("fechanacimiento") + "&";
        }
        if ($("#paginacion").data("fechanacimientohasta") != "") {
            url += "fecha_nacimiento_hasta=" + $("#paginacion").data("fechanacimientohasta") + "&";
        }
        if ($("#paginacion").data("vigente") != "" || $("#paginacion").data("vigente") == "0") {
            url += "vigente=" + $("#paginacion").data("vigente") + "&";
        }

        url = url.substr(0, url.length - 1);
        window.location.href = url;

    });

    $("#toExcel").click(function() {
        var url = "excel?";
        if ($("#tipo_documento").val() != "") {
            url += "tipo_documento=" + $("#tipo_documento").val() + "&";
        }
        if ($("#numero_documento").val() != "") {
            url += "numero_documento=" + $("#numero_documento").val() + "&";
        }
        if ($("#primer_nombre").val() != "") {
            url += "primer_nombre=" + $("#primer_nombre").val() + "&";
        }
        if ($("#segundo_nombre").val() != "") {
            url += "segundo_nombre=" + $("#segundo_nombre").val() + "&";
        }
        if ($("#primer_apellido").val() != "") {
            url += "primer_apellido=" + $("#primer_apellido").val() + "&";
        }
        if ($("#segundo_apellido").val() != "") {
            url += "segundo_apellido=" + $("#segundo_apellido").val() + "&";
        }
        if ($("#fecha_nacimiento").val() != "") {
            url += "fecha_nacimiento=" + $("#fecha_nacimiento").val() + "&";
        }
        if ($("#fecha_nacimiento_hasta").val() != "") {
            url += "fecha_nacimiento_hasta=" + $("#fecha_nacimiento_hasta").val() + "&";
        }
        if ($("#vigente").val() != null && $("#vigente").val() != "") {
            url += "vigente=" + $("#vigente").val() + "&";
        }
        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });
    $("#pais-modal").live("change", function() {
        pais = $('#pais-modal').val();
        $.post('llena_provincia', {
            pais: pais
        }, function(data) {
            $("#provincia-modal").removeAttr("disabled");
            $("#provincia-modal").html(data);
            $("#provincia-modal").prepend('<option value="default" selected>Seleccione Departamento</option>');
            //Con esto activamos automaticamente el evento click como si lo hicieramos nosotros.
            $("#provincia-modal").change();
        });
    });
    $("#provincia-modal").live("change", function() {
        provincia = $('#provincia-modal').val();
        $.post('llena_ciudad', {
            provincia: provincia
        }, function(data) {
            $("#ciudad-modal").removeAttr("disabled")
            $("#ciudad-modal").html(data);
            $("#ciudad-modal").prepend('<option value="default" selected>Seleccione Ciudad</option>');
        });
    });
    $('#botonValidarEditarTitular').live('click', function() {
        //PAra desactivar el click al lado del modal para cerrarlo
        $(function() {
            $('#modal_loading').modal({
                show: true,
                keyboard: false,
                backdrop: 'static'
            });
        });
        $.ajax({
            type: "POST",
            url: "validarParaEditar",
            cache: false,
            data: $("#formularioEditarTitular").serialize(), // Adjuntar los campos del formulario enviado.
            success: function(data)
            {
                if (data != "OK") {
                    $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                    $("#div_alert").html(data);
                    $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
                    //Para cerrar el modal de loading
                    $('#modal_loading').modal('hide');
                } else {
                    $(window).unbind('beforeunload');
                    $("#btn_submit").click();
                }
            },
            error: function(data) {
                $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                $('#div_alert').html('<p><strong>Hubo un error en la peticion al servidor. Verifique su conexion a internet.</strong></p>');
                $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');

            }
        });
        return false; // Evitar ejecutar el submit del formulario
    });
});