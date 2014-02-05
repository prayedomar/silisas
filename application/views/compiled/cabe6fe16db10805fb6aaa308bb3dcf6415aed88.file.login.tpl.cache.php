<?php /* Smarty version Smarty-3.1.15, created on 2013-11-29 21:22:11
         compiled from "application\views\templates\login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:250945299058376c077-49748785%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cabe6fe16db10805fb6aaa308bb3dcf6415aed88' => 
    array (
      0 => 'application\\views\\templates\\login.tpl',
      1 => 1385760124,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '250945299058376c077-49748785',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'css' => 0,
    'dni' => 0,
    'loc' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_529905838d92b3_70410469',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_529905838d92b3_70410469')) {function content_529905838d92b3_70410469($_smarty_tpl) {?><html>
    <head>
        <title> SiliSAS </title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=9" />
        <meta name="description" content="Login siliSAS">
        <meta name="author" content="Omar Stevenson Rivera Correa">
        <link rel=StyleSheet href=<?php echo $_smarty_tpl->tpl_vars['css']->value;?>
 TYPE="text/css">
    </head>
    <body>
<center>
<form method="post" action="login_html/new_user" id="login">
    <h1>Sili SAS</h1>
    <fieldset id="inputs">
<ul>
        <?php  $_smarty_tpl->tpl_vars['loc'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['loc']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['dni']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['loc']->key => $_smarty_tpl->tpl_vars['loc']->value) {
$_smarty_tpl->tpl_vars['loc']->_loop = true;
?>
        <li><?php echo $_smarty_tpl->tpl_vars['loc']->value['id'];?>
<?php echo $_smarty_tpl->tpl_vars['loc']->value['tipo'];?>
</li>
//etc.
        <?php } ?>
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
</html><?php }} ?>
