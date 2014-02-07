$(function() {

    $("#searchBtn").click(function() {
        $("#coverDisplay").css({
            "opacity": "1",
            "width": "100%",
            "height": "100%"
        });
        var url = "consultar?";
        if ($("#nombre").val() != "") {
            url += "nombre=" + $("#nombre").val() + "&";
        }
        if ($("#sede").val() != "") {
            url += "sede=" + $("#sede").val() + "&";
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
        if ($("#paginacion").data("nombre") != "") {
            url += "nombre=" + $("#paginacion").data("nombre") + "&";
        }
        if ($("#paginacion").data("sede") != "") {
            url += "sede=" + $("#paginacion").data("sede") + "&";
        }
       if ($("#paginacion").data("vigente") != "" || $("#paginacion").data("vigente") == "0" ) {
            url += "vigente=" + $("#paginacion").data("vigente") + "&";
        }

        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });
});