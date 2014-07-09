<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link  rel="stylesheet" href="<?= base_url() ?>libraries/bootstrap_3.0.2/css_cerulean/bootstrap.min.css" />
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
        <script src="<?= base_url() ?>libraries/bootstrap_3.0.2/js/bootstrap.min.js"></script>
        <script>
            $(function() {
                $('#botonCalcular').click(function() {
                    $.ajax({
                        url: ""<?= base_url() ?>"/prueba/prueba_ajax",
                        type: 'POST',
                        cache: false,
                        data: {
                            variable1: $('#text1').val(),
                            variable2: $('#text2').val()
                        },
                        success: function(r) {
                            var rHtml;
                            if (r.suma == 4) {
                                rHtml = "puto";
                            } else {
                                rHtml = "no puto";
                            }

//                        var rHtml = 'El resultado de la suma es: ' + r.suma + '<br/>';
//                        rHtml += 'El resultado del producto es: ' + r.producto;
                            $('#respuesta').html(rHtml); // Mostrar la respuesta del servidor en el div con el id "respuesta"
                        },
                        error: function(r) {
                            var rHtml = 'Hubo un error en el servidor'
                            $('#respuesta').html(rHtml);
                        },
                        dataType: "json"
                    });
                });
            });
        </script>
        <style>
            button{position:relative;}
        </style>
        <!--para que pueda funcionar bootstrap select-->
    </head>
    <body>
        <div>
            Valor 1: <input type="text" id="text1">
            Valor 2: <input type="text" id="text2">
            <button id="botonCalcular">Calcular</button>
        </div>
        <div id="respuesta">
            Aquí se imprimirá la respuesta
        </div>
    </body>
</html>