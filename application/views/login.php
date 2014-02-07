<!DOCTYPE html>
<html lang="es">
    <head>
        <title> SILI S.A.S </title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=9" />
        <meta name="description" content="Login siliSAS" />
        <meta name="author" content="Omar Stevenson Rivera Correa" />
        <!-- Favicon -->        
        <link rel="shortcut icon" href="{base_url}images/favicon3.ico" />        
        <!-- CSS -->        
        <link rel=StyleSheet href="{base_url}libraries/bootstrap_3.0.2/css_cerulean/bootstrap.min.css" TYPE="text/css" />
        <link rel=StyleSheet href="{base_url}css/login.css" TYPE="text/css" />
        <!-- Js -->
        <script src="{base_url}libraries/html5shim/html5.js"></script>
        <script src="{base_url}libraries/respond/respond.min.js"></script>
        <script src="{base_url}libraries/jquery/jquery-1.9.0.js"></script>
        <script src="{base_url}libraries/bootstrap_3.0.2/js/bootstrap.min.js"></script>  
        <script type="text/javascript">
            $(document).ready(function() {
                //validar con ajax
                $('#botonValidar').click(function() {
                    $.ajax({
                        type: "POST",
                        url: $("#action_validar").attr("value"),
                        cache: false,
                        data: $("#login").serialize(), // Adjuntar los campos del formulario enviado.
                        success: function(data)
                        {
                            if (data != "OK") {
                                $("#validacion_alert").attr('class', 'alert alert-danger');
                                $("#validacion_alert").html(data);
                            } else {
                                $("#btn_submit").click();
                            }
                        },
                        error: function(data) {
                            $("#validacion_alert").attr('class', 'alert alert-danger');
                            var rHtml = 'Hubo un error en la peticion al servidor'
                            $('#validacion_alert').html(rHtml);
                        }
                    });
                    return false; // Evitar ejecutar el submit del formulario
                });
            });
        </script>        
    </head>
    <body>
        <form method="post" action="login/new_user" id="login">
            <!--            <h1>SILI SAS</h1>-->
            <div id="div_img">
                <img src="{base_url}images/logo_login_2.png" style="max-width:100%;" class="sun">
            </div>
            <div class="div_inputs">
                <fieldset id="inputs">
                    <label>Tipo de Usuario</label>
                    <select name="t_usuario" class="select">
                        <option value="default">Seleccione T.U</option>
                        {t_usuario}
                        <option value={id}>{tipo}</option>
                        {/t_usuario}      
                    </select>                     
                    <label>Tipo de Identificación</label>
                    <select name="dni" class="select">
                        <option value="default">Seleccione T.I</option>
                        {dni}
                        <option value={id}>{tipo}</option>
                        {/dni}      
                    </select>
                    <label>Identificación de usuario</label>   
                    <input name="id" id="id" type="text" maxlength="13" required>
                    <label>Contraseña</label>  
                    <input name="password" id="password" type="password" maxlength="30" required>
                    <input type="hidden" name="token" value={token} />
                    <input type="hidden" id="action_validar" value={action_validar} />
                </fieldset>
                <fieldset id="actions">
                    <center>
                        <!--                        El submit oculto tiene que estar de segunda, porq sino, cada vez que que den enter en el formulario (en vez de click) activara el submit oculto y sería un caos. Caso puntual: firefox-->
                        <button id="botonValidar" class="btn btn-primary btn-lg active">Iniciar Sesión</button>
                        <button type="submit" name="submit" value="submit" class="btn btn-primary btn-lg active" style="display:none;" id="btn_submit"></button>
                        <div id="validacion_alert">
                        </div>                  
                    </center>
                </fieldset>
            </div>
        </form>
    </body>
</html>