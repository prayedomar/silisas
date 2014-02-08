$(function() {

    $("#searchBtn").click(function() {
        $("#coverDisplay").css({
            "opacity": "1",
            "width": "100%",
            "height": "100%"
        });
        var url = "consultar?";
        if ($("#numero_cuenta").val() != "") {
            url += "numero_cuenta=" + $("#numero_cuenta").val() + "&";
        }
        if ($("#cuenta").val() != "") {
            url += "cuenta=" + $("#cuenta").val() + "&";
        }
        if ($("#banco").val() != "") {
            url += "banco=" + $("#banco").val() + "&";
        }
        if ($("#nombre_cuenta").val() != "") {
            url += "nombre_cuenta=" + $("#nombre_cuenta").val() + "&";
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
        if ($("#paginacion").data("numerocuenta") != "") {
            url += "numero_cuenta=" + $("#paginacion").data("numerocuenta") + "&";
        }
        if ($("#paginacion").data("cuenta") != "") {
            url += "cuenta=" + $("#paginacion").data("cuenta") + "&";
        }
        if ($("#paginacion").data("banco") != "") {
            url += "banco=" + $("#paginacion").data("banco") + "&";
        }
        if ($("#paginacion").data("nombrecuenta") != "") {
            url += "nombre_cuenta=" + $("#paginacion").data("nombrecuenta") + "&";
        }
        if ($("#paginacion").data("vigente") != "" || $("#paginacion").data("vigente") == "0") {
            url += "vigente=" + $("#paginacion").data("vigente") + "&";
        }
        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });

});