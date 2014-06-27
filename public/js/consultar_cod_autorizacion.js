$(function() {

    $("#searchBtn").click(function() {
        $("#coverDisplay").css({
            "opacity": "1",
            "width": "100%",
            "height": "100%"
        });
        var url = "consultar?";
        if ($("#id").val() != "") {
            url += "id=" + $("#id").val() + "&";
        }
        if ($("#tabla_autorizada").val() != "") {
            url += "tabla_autorizada=" + $("#tabla_autorizada").val() + "&";
        }
        if ($("#id_empleado_autorizado").val() != "") {
            url += "id_empleado_autorizado=" + $("#id_empleado_autorizado").val() + "&";
        }
        if ($("#id_responsable").val() != "") {
            url += "id_responsable=" + $("#id_responsable").val() + "&";
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
        var paginacion = $("#paginacion");

        if ($(this).data("page") == 1) {
            url = "consultar?";
        } else {
            var url = "consultar?page=" + $(this).data("page") + "&";
        }
        if ($("#paginacion").data("id") != "") {
            url += "id=" + $("#paginacion").data("id") + "&";
        }
        if ($("#paginacion").data("tabla_autorizada") != "") {
            url += "tabla_autorizada=" + $("#paginacion").data("tabla_autorizada") + "&";
        }
        if ($("#paginacion").data("id_empleado_autorizado") != "") {
            url += "id_empleado_autorizado=" + $("#paginacion").data("id_empleado_autorizado") + "&";
        }
        if ($("#paginacion").data("id_responsable") != "") {
            url += "id_responsable=" + $("#paginacion").data("id_responsable") + "&";
        }
        if ($("#paginacion").data("vigente") != "" || $("#paginacion").data("vigente") == "0") {
            url += "vigente=" + $("#paginacion").data("vigente") + "&";
        }
        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });
});