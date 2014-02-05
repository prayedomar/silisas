<?php /* Smarty version Smarty-3.1.15, created on 2013-11-23 17:46:54
         compiled from "application\views\templates\login_view.tpl" */ ?>
<?php /*%%SmartyHeaderCode:311895290ea0ee0f872-87303643%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '875c6c97ea7f25505015a61b731e775b8b96e5d5' => 
    array (
      0 => 'application\\views\\templates\\login_view.tpl',
      1 => 1385228594,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '311895290ea0ee0f872-87303643',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'empleado' => 0,
    'object' => 0,
    'title' => 0,
    'description' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_5290ea0f1b7a53_95580556',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5290ea0f1b7a53_95580556')) {function content_5290ea0f1b7a53_95580556($_smarty_tpl) {?><html>
    <head>
        <title> Acceso SiliSAS </title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="description" content="Login siliSAS">
        <meta name="author" content="Omar Rivera">
        <link rel=StyleSheet href="<<?php ?>?php echo base_url() ?<?php ?>>css/login.css" TYPE="text/css">
        <!—[if lt IE 9]>
        <script src="<<?php ?>?php echo base_url() ?<?php ?>>libraries/html5shim/html5shim/html5.js"></script>
        <script src="<<?php ?>?php echo base_url() ?<?php ?>>libraries/respond/respond.min.js"></script>
        <![endif]—>
        <link rel="stylesheet" href="<<?php ?>?php echo base_url() ?<?php ?>>libraries/bootstrap_select1.3.5/bootstrap-select.min.css">
    </head>
    <body>
    <center>
        <<?php ?>?php
        $username = array('name' => 'username', 'placeholder' => 'Nombre de Usuario', 'id' => 'username');
        $password = array('name' => 'password', 'placeholder' => 'Contrase&ntilde;a', 'id' => 'password');
        $submit = array('name' => 'submit', 'value' => 'Iniciar sesión', 'title' => 'Iniciar sesión', 'id' => 'submit');
        $form = array('id' => 'login');
        $dni = array(
            '1' => 'Cedula de Ciudadania',
            '2' => 'Cedula de Extranjeria',
            '3' => 'Pasaporte',
            '4' => 'Tarjeta de Identidad',
        );
        echo form_open('login/new_user', $form)
        ?<?php ?>>
        <h1>Sili SAS</h1>
        <fieldset id="inputs">
            <!--<SELECT name="hola" class="select">
                <option>Tipo de Comumento</option>
                <option value="1">Cedula de Ciudadania</option>
                <option value="2">Cedula de Extranjeria</option>
                <option value="3">Pasaporte</option>
                <option value="4">Tarjeta de Identidad</option>
            </select>-->
            <SELECT name="hola" class="select">
                <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['i'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['i']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['empleado']->value) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['name'] = 'i';
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['total']);
?>
                if()
                <option <?php if (isset($_smarty_tpl->tpl_vars['object']->value->empleado)) {?>selected<?php }?> title="<?php echo $_smarty_tpl->tpl_vars['empleado']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]->get('nombre');?>
"><?php echo $_smarty_tpl->tpl_vars['empleado']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]->get('id');?>
</option> 
                <?php endfor; endif; ?>
            </select>
                <p>index.tpl page shows the variables here:</p>
<p>title: <?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</p>
<p>description: <?php echo $_smarty_tpl->tpl_vars['description']->value;?>
</p>
            <<?php ?>?= form_input($username) ?<?php ?>><p class="error"><<?php ?>?= strip_tags(form_error('username')) ?<?php ?>></p>
            <<?php ?>?= form_password($password) ?<?php ?>><p class="error"><<?php ?>?= strip_tags(form_error('password')) ?<?php ?>></p>
            <<?php ?>?= form_hidden('token', $token) ?<?php ?>>
        </fieldset>
        <<?php ?>?= form_submit($submit) ?<?php ?>>
        <<?php ?>?= form_close() ?<?php ?>>
        <<?php ?>?php
        if ($this->session->flashdata('usuario_incorrecto')) {
            ?<?php ?>>
            <p><<?php ?>?= $this->session->flashdata('usuario_incorrecto') ?<?php ?>></p>
            <<?php ?>?php
        }
        ?<?php ?>>
    </center>
    <script src="<<?php ?>?php echo base_url() ?<?php ?>>libraries/jquery/jquery-2.0.3.min.js" type="text/javascript"></script>
</body>
</html><?php }} ?>
