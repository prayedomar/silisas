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
        if ($("#desde").val() != "") {
            url += "desde=" + $("#desde").val() + "&";
        }
        if ($("#hasta").val() != "") {
            url += "hasta=" + $("#hasta").val() + "&";
        }
        if ($("#tipo_ausencia").val() != "") {
            url += "tipo_ausencia=" + $("#tipo_ausencia").val() + "&";
        }
        if ($("#vigente").val() != "") {
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
        if ($("#paginacion").data("desde") != "") {
            url += "desde=" + $("#paginacion").data("desde") + "&";
        }
        if ($("#paginacion").data("hasta") != "") {
            url += "hasta=" + $("#paginacion").data("hasta") + "&";
        }
        if ($("#paginacion").data("tipoausencia") != "") {
            url += "tipo_ausencia=" + $("#paginacion").data("tipoausencia") + "&";
        }
       if ($("#paginacion").data("vigente") != "" || $("#paginacion").data("vigente") == "0" ) {
            url += "vigente=" + $("#paginacion").data("vigente") + "&";
        }

        url = url.substr(0, url.length - 1);
        window.location.href = url;

    });
});