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
        if ($("#tipo_salario").val() != "") {
            url += "tipo_salario=" + $("#tipo_salario").val() + "&";
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

    $("#bodyTabla button").click(function() {
        var idSalario = $(this).data("idsalario");
        $.ajax({
            url: "detalles",
            data: {
                idSalario: idSalario,
            },
            type: 'GET',
            success: function(data) {
                if (data != "[]") {
                    data = JSON.parse(data);
                    var str = "";
                    $.each(data, function(k, l) {
                        console.log(l.tipo);
                        str += "<div class='row'> <div class='col-xs-6'> <div class='form-group'> <label class='margin_label'>" + l.tipo + "</label> </div> </div> <div class='col-xs-6'> <div class='form-group'> <div class='input-group'> <span class='input-group-addon'>$</span> <input type='hidden' value='20'> <input type='text' class='form-control decimal decimal2 miles' placeholder='0.00' maxlength='12' value='" + l.valor_unitario + "' readonly> </div> </div> </div> </div";
                    });
                    $("#bodyModalDetalles").html(str);
                } else {
                    $("#bodyModalDetalles").html("No se econtraron detalles");
                }
                $("#modalDetalles").modal("show");
            }
        });
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
        if ($("#paginacion").data("tipo_salario") != "") {
            url += "tipo_salario=" + $("#paginacion").data("tipo_salario") + "&";
        }
       if ($("#paginacion").data("vigente") != "" || $("#paginacion").data("vigente") == "0" ) {
            url += "vigente=" + $("#paginacion").data("vigente") + "&";
        }

        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });
});