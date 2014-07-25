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
        if ($("#matricula").val() != "") {
            url += "matricula=" + $("#matricula").val() + "&";
        }
        if ($("#curso").val() != "") {
            url += "curso=" + $("#curso").val() + "&";
        }
        if ($("#estado").val() != "") {
            url += "estado=" + $("#estado").val() + "&";
        }
        if ($("#sede_ppal").val() != "") {
            url += "sede_ppal=" + $("#sede_ppal").val() + "&";
        }

        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });


    $("#divCriterios .form-control").keypress(function(e) {
        if (e.which == 13) {
            $("#searchBtn").trigger("click");
        }
    });

    $("#bodyTabla button").click(function() {
        $("#divMatricula").html($(this).data("matricula"));
        $("#divVelocidadInicial").html($(this).data("velocidadini") + " p.p.m");
        $("#divComprensionInicial").html($(this).data("comprensionini") + " %");
        $("#divCurso").html($(this).data("curso"));
        $("#divEstado").html($(this).data("estado"));

        if ($(this).data("fechagrados") == "") {
            $("#divFechaGrados").html("Pendiente");
        } else {
            $("#divFechaGrados").html($(this).data("fechagrados"));
        }
        $("#divObservacion").html($(this).data("observacion"));
//        $("#modalDetalles").modal("show");
        $('#modalDetalles').modal({
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
        if ($("#paginacion").data("matricula") != "") {
            url += "matricula=" + $("#paginacion").data("matricula") + "&";
        }
        if ($("#paginacion").data("curso") != "") {
            url += "curso=" + $("#paginacion").data("curso") + "&";
        }
        if ($("#paginacion").data("estado") != "") {
            url += "estado=" + $("#paginacion").data("estado") + "&";
        }
        if ($("#paginacion").data("sedeppal") != "") {
            url += "sede_ppal=" + $("#paginacion").data("sedeppal") + "&";
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
        if ($("#matricula").val() != "") {
            url += "matricula=" + $("#matricula").val() + "&";
        }
        if ($("#curso").val() != "") {
            url += "curso=" + $("#curso").val() + "&";
        }
        if ($("#estado").val() != "") {
            url += "estado=" + $("#estado").val() + "&";
        }
        if ($("#sede_ppal").val() != "") {
            url += "sede_ppal=" + $("#sede_ppal").val() + "&";
        }

        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });
});