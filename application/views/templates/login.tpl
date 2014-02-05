<html>
    <head>
        <title> SiliSAS </title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=9" />
        <meta name="description" content="Login siliSAS">
        <meta name="author" content="Omar Stevenson Rivera Correa">
        <link rel=StyleSheet href={$css} TYPE="text/css">
    </head>
    <body>
<center>
<form method="post" action="login_html/new_user" id="login">
    <h1>Sili SAS</h1>
    <fieldset id="inputs">
<ul>
        {foreach from=$dni item=loc}
        <li>{$loc.id}{$loc.tipo}</li>
//etc.
        {/foreach}
    </ul>
        <input name="id" id="id" type="text" maxlength="15" placeholder="Id de Usuario" autofocus required>
        <p  class="error"></p>
        <input name="password" id="password" type="password" maxlength="30" placeholder="Contrase&ntilde;a" required>
        <p  class="error"></p>
    </fieldset>
    <fieldset id="actions">
        <input type="submit" id="submit" name="logarse" value="Iniciar sesión">
    </fieldset>
</form>


</center>
</body>
</html>