$(function() {

    $("#searchBtn").click(function() {
        $("#coverDisplay").css({
            "opacity": "1",
            "width": "100%",
            "height": "100%"
        });
        var url = "consultar?";
        if ($("#contrato").val() != "") {
            url += "contrato=" + $("#contrato").val() + "&";
        }
        if ($("#fecha_matricula_desde").val() != "") {
            url += "fecha_matricula_desde=" + $("#fecha_matricula_desde").val() + "&";
        }
        if ($("#fecha_matricula_hasta").val() != "") {
            url += "fecha_matricula_hasta=" + $("#fecha_matricula_hasta").val() + "&";
        }
        if ($("#id_titular").val() != "") {
            url += "id_titular=" + $("#id_titular").val() + "&";
        }
        if ($("#id_ejecutivo").val() != "") {
            url += "id_ejecutivo=" + $("#id_ejecutivo").val() + "&";
        }
        if ($("#cargo_ejecutivo").val() != "") {
            url += "cargo_ejecutivo=" + $("#cargo_ejecutivo").val() + "&";
        }
        if ($("#plan").val() != "") {
            url += "plan=" + $("#plan").val() + "&";
        }
        if ($("#datacredito").val() != "") {
            url += "datacredito=" + $("#datacredito").val() + "&";
        }
        if ($("#juridico").val() != "") {
            url += "juridico=" + $("#juridico").val() + "&";
        }
        if ($("#sede").val() != "") {
            url += "sede=" + $("#sede").val() + "&";
        }
        if ($("#estado").val() != "") {
            url += "estado=" + $("#estado").val() + "&";
        }
        if ($("#id_alumno").val() != "") {
            url += "id_alumno=" + $("#id_alumno").val() + "&";
        }
        url = url.substr(0, url.length - 1);
        window.location.href = url;
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
        if ($("#paginacion").data("contrato") != "") {
            url += "contrato=" + $("#paginacion").data("contrato") + "&";
        }
        if ($("#paginacion").data("fecha_matricula_desde") != "") {
            url += "fecha_matricula_desde=" + $("#paginacion").data("fecha_matricula_desde") + "&";
        }
        if ($("#paginacion").data("fecha_matricula_hasta") != "") {
            url += "fecha_matricula_hasta=" + $("#paginacion").data("fecha_matricula_hasta") + "&";
        }
        if ($("#paginacion").data("id_titular") != "") {
            url += "id_titular=" + $("#paginacion").data("id_titular") + "&";
        }
        if ($("#paginacion").data("id_ejecutivo") != "") {
            url += "id_ejecutivo=" + $("#paginacion").data("id_ejecutivo") + "&";
        }
        if ($("#paginacion").data("cargo_ejecutivo") != "") {
            url += "cargo_ejecutivo=" + $("#paginacion").data("cargo_ejecutivo") + "&";
        }
        if ($("#paginacion").data("plan") != "") {
            url += "plan=" + $("#paginacion").data("plan") + "&";
        }
        if ($("#paginacion").data("datacredito") != "") {
            url += "datacredito=" + $("#paginacion").data("datacredito") + "&";
        }
        if ($("#paginacion").data("juridico") != "") {
            url += "juridico=" + $("#paginacion").data("juridico") + "&";
        }
        if ($("#paginacion").data("sede") != "") {
            url += "sede=" + $("#paginacion").data("sede") + "&";
        }
        if ($("#paginacion").data("estado") != "") {
            url += "estado=" + $("#paginacion").data("estado") + "&";
        }
        if ($("#paginacion").data("id_alumno") != "") {
            url += "id_alumno=" + $("#paginacion").data("id_alumno") + "&";
        }

        url = url.substr(0, url.length - 1);
        window.location.href = url;

    });
});

